<div class="statistiques-wrapper">
    <div class="header-bar">
        <h2 class="dashboard-sub-title">
            <img src="/wp-content/plugins/plateforme-master/images/ed/16406436.png" alt="Icon"
                style="width: 38px; margin-right: 8px; vertical-align: middle;">
            Statistiques Générales
        </h2>
        <button class="btn-report"><i class="fa fa-file-alt"></i> Générer un rapport global</button>
    </div>

    <hr class="section-divider">

    <div class="stats-grid">
        <!-- Gauche -->
        <div class="left-stats">
            <!-- dans .left-stats -->
            <div class="stat-box">
                <span class="label">Toltal des équipements </span>
                <span class="value" id="stat-publiees">0</span>
            </div>
            <div class="stat-box">
                <span class="label">Prochain entretien</span>
                <span class="value" id="stat-total">0</span>
            </div>

        </div>

        <!-- Droite -->
        <div class="right-graph">
            <div class="graph-header">
                <h4>Etat des équipement</h4>
                <select class="graph-select" id="anneeSelect"></select>

                </select>
            </div>
            <div class="blocChart">
                <div class="canvas-container">
                    <canvas id="pieChart"></canvas>
                </div>
                <div class="legend" id="chartLegend"></div>
            </div>
        </div>
    </div>
</div>

<style>
.header-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dashboard-sub-title {
    font-weight: bold;
    font-size: 24px;
}

.statistiques-wrapper {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
    padding: 20px;
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
}

.btn-report {
    border: 1px solid #c60000;
    color: #c60000;
    background: #fff;
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    font-weight: 500;
}

.stats-grid {
    display: flex;
    align-items: stretch;
    gap: 20px;
}

.left-stats {
    display: flex;
    flex-direction: column;
    gap: 42px;
    flex: 1;
}

.stat-box {
    background: #f8f9fa;
    padding: 36px 20px;
    /* margin-bottom: 15px; */
    border-radius: 10px;
    box-shadow: 0px 0px 16px #0000001C;
    background: #FFFFFF 0% 0% no-repeat padding-box;
    border-left: 4px solid #c60000;
    padding-left: 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stat-box .label {
    font-weight: 700;
    font-size: 19px;
    color: #2A2916;
}

.stat-box .value {
    background: #ECEBE3;
    border-radius: 6px;
    padding: 9px 8px;
    font-weight: bold;
    font-size: 21px;
    min-width: 51px;
    text-align: center;
}

.right-graph {
    flex: 2;
    background: #fdfdfd;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0px 0px 16px #0000001C;
}

.graph-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.graph-header h4 {
    font-size: 18px;
    margin: 0;
    font-weight: bold;
}

.graph-select {
    padding: 5px 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
    font-size: 14px;
}

.canvas-container {
    width: 100%;
    max-width: 180px;
    margin: 0 auto 20px;
}

#pieChart {
    width: 100% !important;
    height: auto !important;
}

.legend {
    font-size: 14px;
    color: #444;
    margin-top: 10px;
    display: flex;
    flex-direction: column; /* ✅ les items deviennent verticaux */
    align-items: flex-start; /* ✅ alignés à gauche (ou center si tu veux centrer) */
    gap: 8px; /* ✅ petit espacement entre chaque ligne */
    padding-top: 20px;
}


.legend-item {
    display: flex;
    align-items: center;
}

.legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 6px;
}

.dot-green {
    background-color: #808066;
}

.dot-red {
    background-color: #b1342f;
}

.dot-beige {
    background-color: #dabebe;
}

.blocChart {
    display: flex;
    width: max-content;
    margin: 0 auto;
    gap: 25px;
}


hr {
    border: 1px solid #ECEBE3 !important;
}
</style>

<?php if (is_user_logged_in()): ?>
<script>
// REST settings exposées au JS
window.pmsettings = {
    rest_root: <?php echo json_encode(esc_url_raw(rest_url())); ?>,
    nonce: <?php echo json_encode(wp_create_nonce('wp_rest')); ?>
};
// Rôles utilisateur courant
window.pmuser = {
    roles: <?php echo json_encode($roles); ?>
};
</script>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
<script>
(function () {
  // ===== REST config =====
  const REST_ROOT =
    (window.pmsettings && pmsettings.rest_root) ||
    (window.wpApiSettings && wpApiSettings.root) ||
    '/wp-json/';
  const NONCE =
    (window.pmsettings && pmsettings.nonce) ||
    (window.wpApiSettings && wpApiSettings.nonce) ||
    '';
  const API = REST_ROOT.replace(/\/$/, '') + '/plateforme-directeurderecherche/v1';

  // ===== DOM =====
  // total équipements → on réutilise #stat-publiees (juste un id) et prochain entretien → #stat-total
  const elTotalEquip = document.getElementById('stat-publiees');
  const elProchainEnt = document.getElementById('stat-total');
  const elYear = document.getElementById('anneeSelect');
  const elLegend = document.getElementById('chartLegend');
  const ctx = document.getElementById('pieChart').getContext('2d');

  // ===== Années universitaires dynamiques =====
  function academicYearLabelFor(date) {
    const d = date instanceof Date ? date : new Date();
    const y = d.getFullYear();
    const m = d.getMonth() + 1;
    const y1 = (m >= 9) ? y : (y - 1);
    return `${y1} - ${y1 + 1}`;
  }
  function fillYearsSelect(selectEl, count = 7) {
    if (!selectEl) return;
    selectEl.innerHTML = '';
    const currentLabel = academicYearLabelFor(new Date());
    const startY1 = parseInt(currentLabel.slice(0, 4), 10);
    for (let i = 0; i < count; i++) {
      const y1 = startY1 - i;
      const opt = document.createElement('option');
      opt.value = `${y1} - ${y1 + 1}`;
      opt.textContent = opt.value;
      if (i === 0) opt.selected = true;
      selectEl.appendChild(opt);
    }
  }

  // ===== Chart setup (statuts équipements) =====
  const LABELS = ['Fonctionnel', 'En panne', 'En maintenance', 'Hors service'];
  const COLORS = ['#808066', '#b1342f', '#dabebe', '#A6A485']; // palette proche de ton design
  let chart = null;

  function renderLegend() {
    elLegend.innerHTML = '';
    LABELS.forEach((label, i) => {
      const item = document.createElement('div');
      item.className = 'legend-item';
      item.innerHTML =
        `<span class="legend-dot" style="background-color:${COLORS[i]}"></span>${label}`;
      elLegend.appendChild(item);
    });
  }

  function getSelectedYearLabel() {
    const v = (elYear && elYear.value) ? elYear.value.trim() : '';
    return v || (elYear && elYear.options[elYear.selectedIndex]?.text.trim()) || '';
  }

  // ===== Helpers fetch =====
  async function fetchJSON(url) {
    const resp = await fetch(url, {
      headers: { 'X-WP-Nonce': NONCE, 'Accept': 'application/json' },
      credentials: 'same-origin'
    });
    if (!resp.ok) {
      const t = await resp.text().catch(() => '');
      throw new Error(`${url} → ${resp.status} ${t}`);
    }
    return resp.json();
  }

  // ===== 1) Appel principal (préféré) =====
  // Attendu: {
  //   total_equipements: number,
  //   prochain_entretien_count: number,
  //   statuts: { fonctionnel: number, en_panne: number, en_maintenance: number, hors_service: number }
  // }
  async function fetchEquipStatsPreferred(yearLabel) {
    const url = new URL(API + '/equipements/stats', window.location.origin);
    if (yearLabel) url.searchParams.set('year', yearLabel);
    return fetchJSON(url.toString());
  }

  // ===== 2) Fallback si l’endpoint n’existe pas =====
  // On agrège depuis /equipement (paginé) et on tente /maintenances/prochaines (optionnel)
  async function fetchAllEquipementsPaged(perPage = 100) {
    let page = 1, all = [];
    while (true) {
      const url = new URL(API + '/equipement', window.location.origin);
      url.searchParams.set('per_page', perPage);
      url.searchParams.set('page', page);
      const rows = await fetchJSON(url.toString());
      if (!Array.isArray(rows) || rows.length === 0) break;
      all = all.concat(rows);
      if (rows.length < perPage) break;
      page++;
      if (page > 50) break; // garde-fou
    }
    return all;
  }

  async function fetchProchainsEntretiensCountFallback(yearLabel) {
    // Si tu exposes un endpoint dédié, dé-commente ceci:
    // const url = new URL(API + '/maintenances/prochaines', window.location.origin);
    // if (yearLabel) url.searchParams.set('year', yearLabel);
    // const data = await fetchJSON(url.toString()); // { count: n }
    // return Number(data?.count || 0);
    return 0; // fallback neutre si non disponible
  }

  async function fetchEquipStatsFallback(yearLabel) {
    const items = await fetchAllEquipementsPaged(100);

    // Comptage des statuts
    const statuts = { fonctionnel: 0, en_panne: 0, en_maintenance: 0, hors_service: 0 };
    items.forEach(e => {
      const s = String(e.statut || '').toLowerCase();
      if (s === 'fonctionnel') statuts.fonctionnel++;
      else if (s === 'en_panne') statuts.en_panne++;
      else if (s === 'en_maintenance') statuts.en_maintenance++;
      else if (s === 'hors_service') statuts.hors_service++;
    });

    const total_equipements = items.length;
    const prochain_entretien_count = await fetchProchainsEntretiensCountFallback(yearLabel);

    return { total_equipements, prochain_entretien_count, statuts };
  }

  async function fetchEquipStats() {
    const yearLabel = getSelectedYearLabel();
    try {
      // essaie l’endpoint dédié
      return await fetchEquipStatsPreferred(yearLabel);
    } catch (e) {
      // si 404/501 etc. → fallback d’agrégation
      return await fetchEquipStatsFallback(yearLabel);
    }
  }

  // ===== UI update =====
  function updateBoxes(stats) {
    elTotalEquip.textContent = Number(stats.total_equipements || 0);
    elProchainEnt.textContent = Number(stats.prochain_entretien_count || 0);
  }

  function updateChart(stats) {
    const dataValues = [
      Number(stats?.statuts?.fonctionnel || 0),
      Number(stats?.statuts?.en_panne || 0),
      Number(stats?.statuts?.en_maintenance || 0),
      Number(stats?.statuts?.hors_service || 0)
    ];

    if (!chart) {
      chart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: LABELS,
          datasets: [{ data: dataValues, backgroundColor: COLORS }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            datalabels: {
              color: '#fff',
              font: { weight: 'bold', size: 13 },
              formatter: (value, ctx) => {
                const total = (ctx.chart.data.datasets[0].data || [])
                  .reduce((a, b) => a + b, 0) || 1;
                const pct = Math.round(100 * value / total);
                return pct ? pct + '%' : '';
              }
            }
          }
        },
        plugins: [ChartDataLabels]
      });
    } else {
      chart.data.datasets[0].data = dataValues;
      chart.update();
    }
  }

  // ===== Init =====
  fillYearsSelect(elYear, 7);
  renderLegend();

  async function refresh() {
    try {
      const stats = await fetchEquipStats();
      updateBoxes(stats);
      updateChart(stats);
    } catch (e) {
      console.error(e);
      // valeurs neutres
      const zero = {
        total_equipements: 0,
        prochain_entretien_count: 0,
        statuts: { fonctionnel: 0, en_panne: 0, en_maintenance: 0, hors_service: 0 }
      };
      updateBoxes(zero);
      updateChart(zero);
    }
  }

  if (elYear) elYear.addEventListener('change', refresh);
  refresh();
})();
</script>
