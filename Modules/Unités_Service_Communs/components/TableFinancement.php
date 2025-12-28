<!-- Icônes + Flatpickr (pour date du formulaire) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
:root{
  --ink:#2A2916; --line:#EBE9D7; --muted:#6E6D55; --chip:#E9E7D7; --chip-active:#A6A485;
  --danger:#D71920; --accent:#C60000; --success:#198754; --warn:#d89e00;
}
body{font-family:'Segoe UI',sans-serif;background:#f4f4f9}

/* ====== Container & Tabs ====== */
.card{background:#fff;border-radius:12px;border: none;box-shadow: 0 2px 6px rgba(0, 0, 0, .05);}
.tabs{display:flex;gap:10px;}
.tab-btn{flex:1;padding:14px 18px;border:none;border-radius:12px 12px 0 0;background:#A6A485;color:#fff;font-weight:700;display:flex;gap:10px;align-items:center;justify-content:center;cursor:pointer}
.tab-btn.active{background:#fff;color:var(--ink)}
.panel{display:none;padding:20px}
.panel.active{display:block}

/* ====== Toolbar / Filtres ====== */
.controls{display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:16px}
.filters{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.search{display:flex;align-items:center;border:1px solid #d8d4b7;border-radius:8px;background:#fff;padding:0 10px;min-width:240px}
.search i{color:#666;margin-right:6px}
.search input{border:none;background:transparent;padding:10px 6px;outline:none;font-size:14px;width:100%}
.sel{position:relative}
.sel select{padding:10px 36px 10px 12px;border:1px solid #d8d4b7;border-radius:8px;background:#fff;appearance:none;min-width:200px}
.sel .chev{position:absolute;right:10px;top:50%;transform:translateY(-50%);color:#6b7280}
.actions{display:flex;gap:10px}
.icon-btn{width:40px;height:40px;background:#fff;border:1px solid #ddd;border-radius:10px;color:var(--accent);display:flex;align-items:center;justify-content:center;box-shadow:0 0 5px rgba(0,0,0,.06);cursor:pointer}
.add-btn{background:var(--danger);color:#fff;border:none;border-radius:8px;padding:10px 16px;font-weight:700;display:flex;gap:8px;align-items:center}
.add-btn:hover{filter:brightness(.95)}

/* ====== Table (head/body séparés) ====== */
.table-wrap{background:#fff;border-radius:12px;padding:6px}
.table{width:100%;border-collapse:separate;border-spacing:0}
.table thead{background:#f3f1e9;transform:translateY(-12px)}
.table thead th{background:#f3f1e9 !important;position:relative;z-index:1}
.table th,.table td{padding:14px;text-align:left}
.table th{border:0}
.table td{border:1px solid var(--line)}
.table tbody tr:first-child td:first-child{border-top-left-radius:12px}
.table tbody tr:first-child td:last-child{border-top-right-radius:12px}
.table tbody tr:last-child td:first-child{border-bottom-left-radius:12px}
.table tbody tr:last-child td:last-child{border-bottom-right-radius:12px}

/* Badges statut */
.badge{display:inline-block;padding:4px 10px;border-radius:20px;font-weight:700;font-size:13px}
.badge-warn{background:#fff9e6;color:var(--warn)}
.badge-ok{background:#e6f7ee;color:var(--success)}
.badge-ko{background:#fff0f0;color:#D71920}

/* Actions (menu …) */
.menu-wrap{position:relative;display:inline-block}
.menu-btn{background:transparent;border:none;font-size:20px;cursor:pointer;width:36px;height:36px;border-radius:8px}
.menu-btn:hover{background:#e6e6de}
.dropdown{display:none;position:absolute;top:100%;right:0;background:#fff;border:1px solid #d8d4b7;border-radius:8px;min-width:180px;box-shadow:0 4px 8px rgba(0,0,0,.1);z-index:1000;padding:6px 0}
.dropdown a{display:block;padding:9px 14px;text-decoration:none;color:var(--ink);font-size:14px}
.dropdown a:hover{background:#f5f5f5}
.dropdown.show{display:block}

/* Pagination */
.pager{display:flex;justify-content:flex-end;gap:6px;margin:12px 6px 0}
.pbtn{border:2px solid var(--accent);background:#fff;color:var(--accent);border-radius:8px;padding:6px 10px;min-width:34px;font-weight:600;cursor:pointer}
.pbtn.active{background:var(--accent);color:#fff}
.pbtn.disabled{opacity:.5;cursor:default}

/* ====== Drawer (sidebar) ====== */
.overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);display:none;justify-content:flex-end;z-index:9999}
.drawer{background:#fff;width:480px;height:100%;display:flex;flex-direction:column;box-shadow:-4px 0 10px rgba(0,0,0,.1)}
.drawer-head{display:flex;justify-content:space-between;align-items:center;padding:18px 22px;box-shadow:0 5px 16px #0001}
.drawer-head h2{margin:0;font-size:18px;color:#2A2916}
.drawer-save{background:#c62828;color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer}
.drawer-body{padding:22px;overflow:auto}
.form-group{margin-bottom:14px}
.form-group label{display:block;font-weight:600;color:#6E6D55;font-size:14px;margin-bottom:6px}
.form-group input,.form-group select,.form-group textarea{
  width:100%;padding:10px 12px;border:1px solid #b5af8e;border-radius:7px;font-size:14px
}
.input-file{display:flex;align-items:center;border-radius:7px;background:#fff;overflow:hidden}
.input-file .name{flex:1;background:#f9f9f9;border:1px solid #b5af8e;border-right:none;padding:10px 12px;border-radius:7px 0 0 7px;color:#666}
.input-file .btn{background:#A6A485;color:#fff;padding:10px 16px;border:1px solid #b5af8e;border-left:none;border-radius:0 7px 7px 0;display:flex;gap:6px;align-items:center;white-space:nowrap}
.drawer input[type="radio"]{accent-color:#C60000}
</style>

<div class="card">
  <!-- Onglets -->
  <div class="tabs">
    <button class="tab-btn active" data-tab="b1">
      <img src="/wp-content/plugins/plateforme-master/images/icons/781831.png" style="width:22px" alt="" onerror="this.style.display='none'">
      Budget Par Plateforme / Projet
    </button>
    <button class="tab-btn" data-tab="b2">
      <img src="/wp-content/plugins/plateforme-master/images/icons/13010433.png" style="width:22px" alt="" onerror="this.style.display='none'">
      Suivi des dépenses
    </button>
  </div>

  <!-- ======= TAB 1 : Budget ======= -->
  <div class="panel active" id="b1">
    <div class="controls">
      <div class="filters">
        <div class="search">
          <i class="fa fa-search"></i>
          <input id="b_search" placeholder="Recherchez..." type="text">
        </div>
        <div class="sel">
          <select id="b_projet">
            <option value="">Projet</option>
            <option>Plateforme Biotech</option>
            <option>Plateforme Informatique</option>
            <option>Projet Erasmus+</option>
          </select>
          <i class="fa-solid fa-chevron-down chev"></i>
        </div>
        <div class="sel">
          <select id="b_periode">
            <option value="">Période</option>
            <option>2025</option>
            <option>2024</option>
          </select>
          <i class="fa-solid fa-chevron-down chev"></i>
        </div>
      </div>
      <div class="actions">
        <button class="icon-btn" title="Filtrer"><i class="fa-solid fa-filter"></i></button>
        <button class="icon-btn" title="Exporter"><i class="fa-solid fa-download"></i></button>
      </div>
    </div>

    <div class="table-wrap">
      <table class="table" id="b_table">
        <thead>
          <tr>
            <th style="width:40px"><input type="checkbox" id="b_check"></th>
            <th>Plateforme</th>
            <th>Budget alloué</th>
            <th>Dépenses</th>
            <th>Reste dispo.</th>
            <th>% consommé</th>
            <th style="width:90px">Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <div class="pager" id="b_pager"></div>
    </div>
  </div>

  <!-- ======= TAB 2 : Dépenses ======= -->
  <div class="panel" id="b2">
    <div class="controls">
      <div class="filters">
        <div class="search">
          <i class="fa fa-search"></i>
          <input id="d_search" placeholder="Recherchez..." type="text">
        </div>
        <div class="sel">
          <select id="d_projet">
            <option value="">Projet</option>
            <option>Plateforme Biotech</option>
            <option>Plateforme Informatique</option>
            <option>Projet Erasmus+</option>
          </select>
          <i class="fa-solid fa-chevron-down chev"></i>
        </div>
        <div class="sel">
          <select id="d_periode">
            <option value="">Période</option>
            <option>2025</option>
            <option>2024</option>
          </select>
          <i class="fa-solid fa-chevron-down chev"></i>
        </div>
      </div>
      <div class="actions">
        <button class="add-btn" id="d_add"><i class="fa-solid fa-plus"></i>Nouvelle dépense</button>
        <button class="icon-btn" title="Filtrer"><i class="fa-solid fa-filter"></i></button>
        <button class="icon-btn" title="Exporter"><i class="fa-solid fa-download"></i></button>
      </div>
    </div>

    <div class="table-wrap">
      <table class="table" id="d_table">
        <thead>
          <tr>
            <th style="width:40px"><input type="checkbox" id="d_check"></th>
            <th>Date</th>
            <th>Nature dépense</th>
            <th>Montant</th>
            <th>Responsable</th>
            <th>Statut</th>
            <th>Facture</th>
            <th style="width:90px">Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <div class="pager" id="d_pager"></div>
    </div>
  </div>
</div>

<!-- ===== Sidebar “Nouvelle dépense” ===== -->
<div class="overlay" id="drawer">
  <div class="drawer">
    <div class="drawer-head">
      <h2 id="dr_title">Ajouter une dépense</h2>
      <button class="drawer-save" id="dr_save">Enregistrer</button>
    </div>
    <div class="drawer-body">
      <div class="form-group">
        <label for="f_date">Date dépense</label>
        <input type="text" id="f_date" placeholder="jj/mm/aaaa">
      </div>
      <div class="form-group">
        <label for="f_plateforme">Plateforme concernée</label>
        <select id="f_plateforme">
          <option value="">Sélection..</option>
          <option>Plateforme Biotech</option>
          <option>Plateforme Informatique</option>
          <option>Projet Erasmus+</option>
        </select>
      </div>
      <div class="form-group">
        <label for="f_type">Type de dépense</label>
        <select id="f_type">
          <option value="">Sélection..</option>
          <option>Maintenance RMN</option>
          <option>Achat Serveurs HPC</option>
          <option>Formation Doctorants</option>
        </select>
      </div>
      <div class="form-group">
        <label for="f_montant">Montant</label>
        <input type="number" id="f_montant" min="0" step="100">
      </div>
      <div class="form-group">
        <label>Pièce justificative (upload PDF, facture, devis)</label>
        <div class="input-file">
          <input id="f_file_name" class="name" value="importer..." readonly>
          <label class="btn">
            <i class="fa-solid fa-upload"></i>Importer
            <input id="f_file" type="file" accept=".pdf,.jpg,.png" style="display:none">
          </label>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>
<script>
(()=>{
  /* ===== Données statiques ===== */
  const BUDGETS = [
    {id:1, projet:'Plateforme Biotech',      annee:2025, budget:800000, depenses:550000},
    {id:2, projet:'Plateforme Informatique', annee:2025, budget:600000, depenses:450000},
    {id:3, projet:'Projet Erasmus+',         annee:2025, budget:400000, depenses:220000},
    {id:4, projet:'Plateforme Biotech',      annee:2024, budget:720000, depenses:650000}
  ];
  const DEPENSES = [
    {id:1, date:'2025-08-05', nature:'Maintenance RMN',    montant:35000,  resp:'Dr. Ali',   statut:'En attente', facture:false, projet:'Plateforme Biotech',      annee:2025},
    {id:2, date:'2025-07-28', nature:'Achat Serveurs HPC', montant:120000, resp:'Ing. Zouari',statut:'Payée',      facture:true,  projet:'Plateforme Informatique', annee:2025},
    {id:3, date:'2025-07-15', nature:'Formation Doctorants', montant:8500, resp:'Mme Salem', statut:'Refusée',     facture:false, projet:'Projet Erasmus+',         annee:2025},
  ];

  /* ===== Helpers ===== */
  const $=s=>document.querySelector(s), $$=s=>Array.from(document.querySelectorAll(s));
  const esc=s=>String(s??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
  const fmtMoney=n=> new Intl.NumberFormat('fr-TN',{maximumFractionDigits:0}).format(n)+' DT';
  const fmtDateFR=iso=>{const [y,m,d]=iso.split('-');return `${d}/${m}/${y}`};

  /* ===== Tabs ===== */
  $$('.tab-btn').forEach(b=>{
    b.addEventListener('click',()=>{
      $$('.tab-btn').forEach(x=>x.classList.remove('active')); b.classList.add('active');
      $$('.panel').forEach(p=>p.classList.remove('active')); $('#'+b.dataset.tab).classList.add('active');
    });
  });

  /* ===== TAB 1 : Budget ===== */
  let bFiltered=[...BUDGETS], bPage=1, per=5;

  const renderBudget=()=>{
    const tb=$('#b_table tbody'); tb.innerHTML='';
    const rows=bFiltered.slice((bPage-1)*per,bPage*per);
    if(!rows.length){
      tb.innerHTML=`<tr><td colspan="7" style="text-align:center;color:#888;">Aucun enregistrement</td></tr>`;
    }else{
      rows.forEach(r=>{
        const reste = Math.max(0, r.budget - r.depenses);
        const pc = Math.round((r.depenses / r.budget)*100);
        const tr=document.createElement('tr');
        tr.innerHTML=`
          <td><input type="checkbox"></td>
          <td>${esc(r.projet)}</td>
          <td>${fmtMoney(r.budget)}</td>
          <td>${fmtMoney(r.depenses)}</td>
          <td>${fmtMoney(reste)}</td>
          <td>${pc} %</td>
          <td>
            <div class="menu-wrap">
              <button class="menu-btn">⋯</button>
              <div class="dropdown">
                <a href="/fiche-budget">Détails</a>
              </div>
            </div>
          </td>`;
        tb.appendChild(tr);
      });
    }
    renderPager('#b_pager', bFiltered.length, bPage, p=>{bPage=p; renderBudget();});
  };

  const applyBudgetFilters=()=>{
    const q=($('#b_search').value||'').toLowerCase();
    const proj=$('#b_projet').value||'';
    const per=$('#b_periode').value||'';
    bFiltered=BUDGETS.filter(r=>{
      const hay=[r.projet,r.annee].join(' ').toLowerCase();
      if(q && !hay.includes(q)) return false;
      if(proj && r.projet!==proj) return false;
      if(per && String(r.annee)!==per) return false;
      return true;
    });
    bPage=1; renderBudget();
  };

  $('#b_search').addEventListener('input',applyBudgetFilters);
  $('#b_projet').addEventListener('change',applyBudgetFilters);
  $('#b_periode').addEventListener('change',applyBudgetFilters);

  /* ===== TAB 2 : Dépenses ===== */
  let dFiltered=[...DEPENSES], dPage=1;

  const badge=(st)=>{
    const v=st.toLowerCase();
    if(v.startsWith('pay')) return `<span class="badge badge-ok">Payée</span>`;
    if(v.startsWith('ref')) return `<span class="badge badge-ko">Refusée</span>`;
    return `<span class="badge badge-warn">En attente</span>`;
  };

  const renderDepenses=()=>{
    const tb=$('#d_table tbody'); tb.innerHTML='';
    const rows=dFiltered.slice((dPage-1)*per,dPage*per);
    if(!rows.length){
      tb.innerHTML=`<tr><td colspan="8" style="text-align:center;color:#888;">Aucun enregistrement</td></tr>`;
    }else{
      rows.forEach(r=>{
        const tr=document.createElement('tr'); tr.dataset.id=r.id;
        tr.innerHTML=`
          <td><input type="checkbox" class="row-check"></td>
          <td>${fmtDateFR(r.date)}</td>
          <td>${esc(r.nature)}</td>
          <td>${fmtMoney(r.montant)}</td>
          <td>${esc(r.resp)}</td>
          <td>${badge(r.statut)}</td>
          <td>${r.facture ? '<img src="/wp-content/plugins/plateforme-master/images/icons/27) Icon-attach-2.png" alt="" style: width:10px;>' : '-'}</td>
          <td>
            <div class="menu-wrap">
              <button class="menu-btn">⋯</button>
              <div class="dropdown">
                <a href="#" class="d-facture">Facture</a>
              </div>
            </div>
          </td>`;
        tb.appendChild(tr);
      });
    }
    renderPager('#d_pager', dFiltered.length, dPage, p=>{dPage=p; renderDepenses();});
  };

  const applyDepensesFilters=()=>{
    const q=($('#d_search').value||'').toLowerCase();
    const proj=$('#d_projet').value||'';
    const per=$('#d_periode').value||'';
    dFiltered=DEPENSES.filter(r=>{
      const hay=[r.nature,r.resp,r.projet,r.statut].join(' ').toLowerCase();
      if(q && !hay.includes(q)) return false;
      if(proj && r.projet!==proj) return false;
      if(per && String(r.annee)!==per) return false;
      return true;
    });
    dPage=1; renderDepenses();
  };

  $('#d_search').addEventListener('input',applyDepensesFilters);
  $('#d_projet').addEventListener('change',applyDepensesFilters);
  $('#d_periode').addEventListener('change',applyDepensesFilters);

  /* ===== Pagination util ===== */
  function renderPager(selector,totalRows,page,go){
    const total=Math.max(1, Math.ceil(totalRows/5));
    const el=$(selector); el.innerHTML='';
    const mk=(label,p,dis=false,act=false)=>{
      const b=document.createElement('button');
      b.className=`pbtn ${dis?'disabled':''} ${act?'active':''}`.trim();
      b.innerHTML=label; b.disabled=!!dis; b.addEventListener('click',()=>!dis&&go(p));
      el.appendChild(b);
    };
    mk('&laquo;',1,page===1); mk('&lsaquo;',Math.max(1,page-1),page===1);
    for(let i=1;i<=total;i++) mk(String(i),i,false,i===page);
    mk('&rsaquo;',Math.min(total,page+1),page===total); mk('&raquo;',total,page===total);
  }

  /* ===== Menus “…” (les deux tables) ===== */
  document.body.addEventListener('click',e=>{
    const btn=e.target.closest('.menu-btn');
    if(btn){
      const dd=btn.nextElementSibling;
      $$('.dropdown').forEach(x=>x.classList.remove('show'));
      dd.classList.add('show'); e.preventDefault(); return;
    }
    if(!e.target.closest('.menu-wrap')) $$('.dropdown').forEach(x=>x.classList.remove('show'));

    // Action “Facture” (tab2)
    const link=e.target.closest('#d_table .dropdown a.d-facture');
    if(link){
      e.preventDefault();
      const id=Number(link.closest('tr').dataset.id);
      const row=DEPENSES.find(x=>x.id===id);
      if(row?.facture){ alert('Ouverture de la facture (démo statique).'); }
      else{ alert('Aucune facture attachée.'); }
    }
  });

  /* ===== Drawer (Nouvelle dépense) ===== */
  flatpickr.localize(flatpickr.l10ns.fr);
  flatpickr('#f_date',{dateFormat:'d/m/Y'});

  const openDr=()=>{ $('#dr_title').textContent='Ajouter une dépense'; $('#drawer').style.display='flex'; document.body.style.overflow='hidden'; resetForm(); };
  const closeDr=()=>{ $('#drawer').style.display='none'; document.body.style.overflow=''; };
  const resetForm=()=>{ $('#f_date').value=''; $('#f_plateforme').value=''; $('#f_type').value=''; $('#f_montant').value=''; $('#f_source').value=''; $('#f_resp').value=''; $('#f_file').value=''; $('#f_file_name').value='importer...'; };
  $('#d_add').addEventListener('click',openDr);
  $('#drawer').addEventListener('click',e=>{ if(e.target.id==='drawer') closeDr(); });
  document.addEventListener('keydown',e=>{ if(e.key==='Escape') closeDr(); });
  $('#f_file').addEventListener('change',e=>{ $('#f_file_name').value = e.target.files[0] ? e.target.files[0].name : 'importer...'; });

  $('#dr_save').addEventListener('click',()=>{
    // lecture rapide des champs
    const dateStr=$('#f_date').value.trim();
    const [d,m,y]=dateStr.split('/'); const iso=y && m && d ? `${y}-${m}-${d}` : '';
    const proj=$('#f_plateforme').value.trim();
    const nature=$('#f_type').value.trim();
    const montant=+($('#f_montant').value||0);
    const resp=$('#f_resp').value.trim();
    if(!iso || !proj || !nature || !montant){ alert('Champs requis manquants.'); return; }

    const id = DEPENSES.length ? Math.max(...DEPENSES.map(x=>x.id))+1 : 1;
    DEPENSES.push({id, date:iso, nature, montant, resp, statut:'En attente', facture: !!$('#f_file').files.length, projet:proj, annee:+y});
    applyDepensesFilters();
    closeDr();
  });

  /* ===== Init ===== */
  // click global pour fermer les menus
  document.addEventListener('click',e=>{ if(!e.target.closest('.menu-wrap')) $$('.dropdown').forEach(x=>x.classList.remove('show')); });
  applyBudgetFilters();
  applyDepensesFilters();
})();
</script>
