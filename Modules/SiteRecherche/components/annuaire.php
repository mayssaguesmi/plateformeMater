<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biochimie et Biotechnologie - Université de Tunis El Manar</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        /* General body styling */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }

        /* Hero section styling */
        .hero-bg {
            background-image: url('/wp-content/plugins/plateforme-master/images/SiteRechercheImages/Groupe 3330 (1).png');
            background-size: cover;
            background-position: center;
            padding: 8rem 0;
            color: white;
        }

        .hero-bg h1 {
            font-size: 50px;
            width: 340px;
            font-weight: 500;
            /* text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5); */
        }

        .breadcrumb-custom {
            background-color: rgb(83 81 81 / 40%);
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .breadcrumb-custom a {
            color: white;
            text-decoration: none;
        }

        .breadcrumb-custom a:hover {
            text-decoration: underline;
        }

        .breadcrumb-custom span {
            color: #e9ecef;
            margin: 0 0.5rem;
        }

        /* Custom Component: Title Divider */

        .titre-ligne-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 40px 0;
            gap: 10px;
            padding: 0 10%;
        }

        .ligne-gauche,
        .ligne-droite {
            flex: 1;
            height: 2px;
            background-color: #b60303;
            position: relative;
        }

        .ligne-gauche::after,
        .ligne-droite::before {
            content: "";
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 10px;
            height: 10px;
            background-color: #b60303;
            border-radius: 50%;
        }

        .ligne-gauche::after {
            right: 0;
        }

        .ligne-droite::before {
            left: 0;
        }

        .titre-ligne {
            padding: 8px 25px;
            border: 2px solid #b60303;
            border-radius: 999px;
            font-size: 16px;
            color: #b60303;
            font-weight: 500;
            background-color: white;
            white-space: nowrap;
        }


        .titre-voir-plus-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 60px 0 40px;
        }

        .titre-voir-plus-ligne {
            padding: 8px 35px;
            border: 2px solid #b60303;
            border-radius: 10px;
            font-size: 16px;
            color: #b60303;
            font-weight: 500;
            background-color: transparent;
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .titre-voir-plus-ligne a {
            text-decoration: none;
            color: #b60303;
        }

        .titre-voir-plus-ligne:hover {
            background-color: #b60303;
        }

        .titre-voir-plus-ligne:hover a {
            color: white;
        }


        /* Utility classes */
        .text-custom-red {
            color: #b60303;
        }

        /* Hero section styling */
        .hero-bg {
            background-image: url('/wp-content/plugins/plateforme-master/images/SiteRechercheImages/Groupe 3329.png');
            background-size: cover;
            background-position: center;
        }

        .display-3 {
            font-weight: 600 !important;
            font-size: 2.8rem;
        }

        /* Search and Filter Section Styling */
        .search-icon {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: #6c757d;
            pointer-events: none;
        }

        .form-control,
        .form-select {
            border-radius: 0.5rem;
            height: 50px;
            border: 1px solid #A6A485;
        }

        .form-select:focus {
            border-color: #b60303;
            box-shadow: 0 0 0 0.25rem rgba(182, 3, 3, 0.25);
        }

        #applyBtn,
        #resetBtn {
            border-radius: 0.5rem;
            padding: 0.5rem 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 50px;
            width: 50px;
            border: 1px solid #dee2e6;
            box-shadow: 1px 1px 14px 1px rgba(0, 0, 0, 0.15);
        }

        #applyBtn {
            color: #b60303;
            background-color: transparent;
        }

        #applyBtn:hover {
            background-color: #b60303;
            color: #fff;
        }

        #resetBtn {
            /* border-color: #6c757d; */
            color: #b60303;
            background-color: transparent;
        }

        #resetBtn:hover {
            background-color: #6c757d;
            color: #fff;
        }

        /* New Profile Card Design */
        .card-profile-new {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: white;
            /* overflow: hidden; */
            cursor: pointer;
            position: relative;
            height: 350px;
        }

        .card-profile-new:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.15);
        }

        .card-profile-new .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            transition: transform 0.3s ease;
        }



        .card-profile-new .card-body {
            position: absolute;
            bottom: -12px;
            left: 0px;
            right: 0px;
            background: rgba(10, 10, 10, 0.4);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 0px 55px 0px 0px;
            width: 350px;
            height: 90px;
            z-index: 2;
        }

        .card-profile-new .card-title {
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.1rem;
            font-size: 1.1rem;
        }

        .card-profile-new .card-text {
            color: #e9ecef;
            font-size: 0.85rem;
        }

        .linkedin-icon-new {
            background-color: #0077b5;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            text-decoration: none;
            transition: background-color 0.2s ease;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .linkedin-icon-new:hover {
            background-color: #005582;
        }

        /* Chef de Structure Section */
        .chef-structure {
            margin-top: 2rem;
            padding-top: 1.5rem;
            /* border-top: 1px solid #eee; */
        }

        .chef-structure img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Footer */
        .footer {
            background-color: white;
            padding: 40px 0;
            border-top: 1px solid #e0e0e0;
        }

        .footer-logo {
            height: 60px;
        }

        .presentation-content-section {
            box-shadow: 1px 1px 14px 1px rgba(0, 0, 0, 0.15);
        }
/* Main Content Styling */
.main-content {
    margin-top: -80px;
    position: relative;
    z-index: 10;
    margin-bottom: 80px;

}
        .search-card {
    background-color: #ffffff;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 1px 1px 14px 1px rgba(0, 0, 0, 0.15);
    /* border: 1px solid #dee2e6; */
}

.search-card .form-select {
    border-color: #d3c8bc;
    border-radius: 0.5rem;
}

.search-card .form-select:focus {
    border-color: #c9b9a6;
    box-shadow: 0 0 0 0.2rem rgba(182, 3, 3, 0.1);
}

.search-card .btn-icon {
    background-color: #fff;
    border: 1px solid #e0e0e0;
    width: 44px;
    height: 44px;
    border-radius: 0.5rem;
    color: #b60303;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: all 0.2s ease-in-out;
}

.search-card .btn-icon:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.07);
}
    </style>
</head>

<body>

    <!-- Hero Section -->
    <section class="hero-bg">
        <div class="container">
            <div class="breadcrumb-custom">
                <a href="#">Université de Tunis El Manar</a><span>›</span> <a
                    href="/structures-de-recherche-utm">Structures de recherche</a>
                <span>›</span> Structures de recherche
            </div>
                <h1 id="labTitle" class="text-start">Annuaire</h1>
        </div>
    </section>

    <main class="container  main-content">
      

      

     

          <!-- Search Section -->
        <div class="search-card col-lg-12 mx-auto mb-5">
            <h5 class="fw-bold mb-3">Recherche</h5>
            <div class="row g-3 align-items-center">
                <div class="col-lg-4 position-relative">
                    <input type="text" id="searchInput" placeholder="Nom et prénom"
                        class="form-control form-control-lg pe-5">
                    <img width="20px" class="search-icon"
                        src="/wp-content/plugins/plateforme-master/images/SiteRechercheImages/27) Icon-search.png"
                        alt="Icon-search.png">
                </div>
               <div class="col-lg-3">
                    <select id="domainSelect" class="form-select form-select-lg">
                        <option value="" selected>Domaine</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <select id="gradeSelect" class="form-select form-select-lg">
                        <option value="" selected>Grade</option>
                    </select>
                </div>

                <div class="col-lg-2 d-flex justify-content-end">
                    <button id="applyBtn" class="btn btn-icon me-2"> <img width="15px"
                            src="/wp-content/plugins/plateforme-master/images/SiteRechercheImages/27) Icon-checkmark.png"
                            alt="Icon-checkmark"></button>
                    <button id="resetBtn" class="btn btn-icon">
                        <img width="15px"
                            src="/wp-content/plugins/plateforme-master/images/SiteRechercheImages/27) Icon-refresh.png"
                            alt="Icon-refresh">
                    </button>
                </div>
            </div>
        </div>


        <!-- stat -->

         <?php include 'stat_annuaire.php'; ?>

        <!-- Team Section -->
        <section class="mt-5">
            <div id="teamGrid" class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
             
            </div>
            <div id="noResultsMessage" class="text-center fs-4 mt-5" style="display: none;">
                Aucun profil ne correspond à votre recherche.
            </div>
        </section>

    
    </main>

  
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  

    <?php
    $current_user = wp_get_current_user();
    $roles = (array) $current_user->roles;
    $role = $roles[0] ?? '';
    $user_id = get_current_user_id();

?>
<script>
        window.PMSettings = {
            restUrl: "<?= esc_url(rest_url()) ?>",
            nonce: "<?= wp_create_nonce('wp_rest') ?>",
            role: "<?= esc_js($role) ?>",
            userId: <?= (int) $user_id ?>
        };
</script>


<script>
document.addEventListener('DOMContentLoaded', async () => {
  const $ = (s,ctx=document)=>ctx.querySelector(s);
  const qp = new URLSearchParams(location.search);
  const labId = parseInt(qp.get('laboratoireid') || qp.get('laboratoire_id') || '0',10);
  if(!labId){ console.warn('Param ?laboratoireid manquant'); return; }

  const restBase = (window.PMSettings?.restUrl || '/wp-json/').replace(/\/+$/,'') + '/';
  const headers  = { 'X-WP-Nonce': (window.PMSettings?.nonce||'') };

  async function fetchJSON(url, opts={}) {
    const res = await fetch(url, { credentials:'same-origin', headers, ...opts });
    const txt = await res.text();
    let data; try{ data = JSON.parse(txt); }catch{ data = { raw: txt }; }
    if (!res.ok) {
      console.error(`[REST ${res.status}] ${url}`, data);
      throw new Error(`${res.status} ${res.statusText}`);
    }
    return data;
  }

  // --- essais de routes pour le labo (path param, puis query ?id=)
  async function loadLab(id){
    const tries = [
      `${restBase}plateforme-recherche/v1/laboratoire/${id}`,
      `${restBase}plateforme-recherche/v1/laboratoire?id=${id}`
    ];
    let lastErr;
    for (const u of tries){
      try { return await fetchJSON(u); } catch(e){ lastErr = e; }
    }
    throw lastErr || new Error('No route responded');
  }

  // membres (si ta route diffère, on loguera l’URL en erreur)
  async function loadMembers(id){
    const u = `${restBase}plateforme-recherche/v1/membre?laboratoire_id=${id}&with_user=1&per_page=200`;
    return await fetchJSON(u).then(j => Array.isArray(j) ? j : (j.data||[]));
  }

  function renderLab(lab){
    // hero
    const title = lab.denomination || lab.nom || 'Laboratoire';
    const objective = (lab.objectif_general||'').trim() || '—';
    const axes = Array.isArray(lab.axes_recherche) ? lab.axes_recherche
               : (lab.meta_json ? (JSON.parse(lab.meta_json).axes_recherche||[]) : []);
    $('#labTitle').textContent = title;
    $('#labObjective').textContent = objective;
    const ul = $('#labAxes'); ul.innerHTML = axes?.length ? axes.map(a=>`<li>${a}</li>`).join('') : '<li>—</li>';

    // directeur
        $('#directorName').textContent = lab.directeur_nom || '—';

        // helper: URL valide ?
        const hasUrl = v => typeof v === 'string'
        && (v = v.trim()) !== '' && v.toLowerCase() !== 'null' && v.toLowerCase() !== 'undefined';

        const img = document.getElementById('directorAvatar');
        const avatar = lab.directeur_avatar || lab.avatar_url || lab.logo_url || '';

        if (hasUrl(avatar)) {
        img.src = avatar;
        img.style.display = 'inline-block'; // rendre visible seulement si on a une image
        // si l’URL est cassée → cacher l'image
        img.onerror = function () { this.onerror = null; this.style.display = 'none'; };
        } else {
        // pas d’image → on laisse caché
        img.style.display = 'none';
        }

  }
const DEFAULT_MEMBER_IMG = "/wp-content/plugins/plateforme-master/images/SiteRechercheImages/Groupe%20de%20masques%20367%20(1).png";

    /*function memberCardHTML(m){
    const avatar = (m.avatar_url && m.avatar_url.trim()) ? m.avatar_url : DEFAULT_MEMBER_IMG;
    const name   = m.user_display_name || m.display_name || '—';
    const spec   = m.specialite || m.specialite_label || '—';

    return `
        <div class="col">
       <!-- <div class="card card-profile-new" data-profile-url="/Coordonnees?user=${m.user_id||''}"> -->
            <div class="card card-profile-new" data-profile-url="#">
       <img
            src="${avatar}"
            class="card-img-top"
            alt="${name}"
            style="background-color:#e9ecef;"
            onerror="this.onerror=null;this.src='${DEFAULT_MEMBER_IMG}';"
            >
            <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                <h5 class="card-title">${name}</h5>
                <p class="card-text small mb-0">${spec}</p>
                </div>
                <a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer" class="linkedin-icon-new">
                <i class="fa-brands fa-linkedin-in"></i>
                </a>
            </div>
            </div>
        </div>
        </div>`;
    }
        */

    function memberCardHTML(m){
        const avatar = (m.avatar_url && m.avatar_url.trim()) ? m.avatar_url : DEFAULT_MEMBER_IMG;
        const name   = m.user_display_name || m.display_name || '—';
        const grade  = m.grade || '—';
        const doms   = m.domaines || ''; // string concat depuis API

        return `
            <div class="col" data-domaines="${doms}">
            <div class="card card-profile-new" data-profile-url="#">
                <img src="${avatar}" class="card-img-top" alt="${name}"
                    style="background-color:#e9ecef;"
                    onerror="this.onerror=null;this.src='${DEFAULT_MEMBER_IMG}';">
                <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                    <h5 class="card-title">${name}</h5>
                    <p class="card-text small mb-0">${grade}</p>
                    </div>
                    <a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer" class="linkedin-icon-new">
                    <i class="fa-brands fa-linkedin-in"></i>
                    </a>
                </div>
                </div>
            </div>
            </div>`;
    }


/*
  function renderMembers(members){
    const grid = $('#teamGrid');
    grid.innerHTML = members.map(memberCardHTML).join('');
    // (ré)attache clic cartes (hors icône LinkedIn)
    grid.querySelectorAll('.card-profile-new').forEach(card=>{
      card.addEventListener('click', e=>{
        if (e.target.closest('.linkedin-icon-new')) return;
        const url = card.getAttribute('data-profile-url'); if (url) location.href = url;
      });
    });
    // remplit le select des spécialités
    const sel = $('#domainSelect');
    sel.innerHTML = '<option value="" selected>Spécialité</option>';
    [...new Set(members.map(m => (m.specialite||'').trim()).filter(Boolean))]
      .sort().forEach(s=>{ const o=document.createElement('option'); o.value=s; o.textContent=s; sel.appendChild(o); });
  }
*/


function renderMembers(members){
  const grid = $('#teamGrid');
  grid.innerHTML = members.map(memberCardHTML).join('');

  grid.querySelectorAll('.card-profile-new').forEach(card=>{
    card.addEventListener('click', e=>{
      if (e.target.closest('.linkedin-icon-new')) return;
      const url = card.getAttribute('data-profile-url'); if (url) location.href = url;
    });
  });

  // Remplir le select des domaines
  const selDomain = $('#domainSelect');
  selDomain.innerHTML = '<option value="" selected>Domaine</option>';
  [...new Set(members.flatMap(m => 
      (m.domaines ? m.domaines.split(',').map(d=>d.trim()) : [])
  ).filter(Boolean))].sort().forEach(d=>{
    const o=document.createElement('option'); o.value=d; o.textContent=d; selDomain.appendChild(o);
  });

  // Remplir le select des grades
  const selGrade = $('#gradeSelect');
  selGrade.innerHTML = '<option value="" selected>Grade</option>';
  [...new Set(members.map(m => (m.grade||'').trim()).filter(Boolean))]
    .sort().forEach(g=>{
      const o=document.createElement('option'); o.value=g; o.textContent=g; selGrade.appendChild(o);
    });
}


/*
  function attachFilters(){
    const searchInput = $('#searchInput'), domainSelect = $('#domainSelect');
    const applyBtn = $('#applyBtn'), resetBtn = $('#resetBtn'), noRes = $('#noResultsMessage');
    function filter(){
      const term=(searchInput.value||'').toLowerCase(), spec=domainSelect.value||'';
      let n=0;
      document.querySelectorAll('#teamGrid .col').forEach(col=>{
        const name=(col.querySelector('.card-title')?.textContent||'').toLowerCase();
        const s=(col.querySelector('.card-text')?.textContent||'').trim();
        const show = (!term || name.includes(term)) && (!spec || s===spec);
        col.style.display = show ? '' : 'none'; if (show) n++;
      });
      noRes.style.display = n? 'none':'block';
    }
    applyBtn.addEventListener('click', filter);
    searchInput.addEventListener('keyup', filter);
    domainSelect.addEventListener('change', filter);
    resetBtn.addEventListener('click', ()=>{ searchInput.value=''; domainSelect.selectedIndex=0; filter(); });
  }
    */

  function attachFilters(){
    const searchInput = $('#searchInput'),
            domainSelect = $('#domainSelect'),
            gradeSelect = $('#gradeSelect'),
            applyBtn = $('#applyBtn'),
            resetBtn = $('#resetBtn'),
            noRes = $('#noResultsMessage');

    function filter(){
        const term=(searchInput.value||'').toLowerCase();
        const dom=domainSelect.value||'';
        const grade=gradeSelect.value||'';
        let n=0;

        document.querySelectorAll('#teamGrid .col').forEach(col=>{
        const name=(col.querySelector('.card-title')?.textContent||'').toLowerCase();
        const gradeTxt=(col.querySelector('.card-text')?.textContent||'').trim();
        const domAttr=(col.getAttribute('data-domaines')||'').split(',').map(d=>d.trim());

        const show = (!term || name.includes(term))
                    && (!grade || gradeTxt===grade)
                    && (!dom || domAttr.includes(dom));

        col.style.display = show ? '' : 'none'; 
        if (show) n++;
        });

        noRes.style.display = n? 'none':'block';
    }

        applyBtn.addEventListener('click', filter);
        searchInput.addEventListener('keyup', filter);
        domainSelect.addEventListener('change', filter);
        gradeSelect.addEventListener('change', filter);
        resetBtn.addEventListener('click', ()=>{
            searchInput.value='';
            domainSelect.selectedIndex=0;
            gradeSelect.selectedIndex=0;
            filter();
        });
    }

     // Charger les stats du labo
  async function loadStats(labId){
    const url = `${restBase}plateforme-recherche/v1/membre?laboratoire_id=${labId}&per_page=1`;
    const data = await fetchJSON(url);

    // --- 1. MAJ chiffres enseignants / doctorants ---
    const enseignants = data.repartition_profil.find(p => parseInt(p.profil_id) === 1);
    const doctorants  = data.repartition_profil.find(p => parseInt(p.profil_id) === 2);

    document.querySelector('.stat-box:nth-child(1) .value').textContent = enseignants ? enseignants.total : '0';
    document.querySelector('.stat-box:nth-child(2) .value').textContent = doctorants ? doctorants.total : '0';

    // --- 2. Préparer graphique chercheurs par année ---
    const labels = data.repartition_annee.map(r => r.annee);
    const values = data.repartition_annee.map(r => r.total);

    renderChart(labels, values);
  }

  // Fonction pour dessiner le graphique
  function renderChart(labels, values){
    const ctx = document.getElementById('barChartProjets');
    if (!ctx) return;

    const existing = Chart.getChart(ctx);
    if (existing) existing.destroy();

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Chercheurs',
          data: values,
          backgroundColor: '#b1342f',
          borderColor: '#b1342f',
          borderWidth: 1,
          borderRadius: 6,
          borderSkipped: false,
          barPercentage: 0.5,
          categoryPercentage: 0.7
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              title: () => '',
              label: ctx => `Total: ${ctx.parsed.y}`
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: 'rgba(0,0,0,0.08)' },
            ticks: { stepSize: 10, padding: 10 }
          },
          x: {
            grid: { display: false },
            ticks: { padding: 10 }
          }
        }
      }
    });
  }



  try {
    const [lab, members] = await Promise.all([loadLab(labId), loadMembers(labId)]);
 //  renderLab(lab);
    renderMembers(members);
    attachFilters();
    await loadStats(labId);
  } catch (e) {
    console.error('Load error:', e);
    alert("Impossible de charger les données du laboratoire.\nVoir console pour le détail.");
  }
});
</script>

<script>




document.addEventListener('DOMContentLoaded', async () => {
  const restBase = (window.PMSettings?.restUrl || '/wp-json/').replace(/\/+$/,'') + '/';
  const headers  = { 'X-WP-Nonce': (window.PMSettings?.nonce||'') };

  async function fetchJSON(url) {
    const res = await fetch(url, { credentials:'same-origin', headers });
    if (!res.ok) throw new Error(`${res.status} ${res.statusText}`);
    return await res.json();
  }

  /*

  // Charger les stats du labo
  async function loadStats(labId){
    const url = `${restBase}plateforme-recherche/v1/membre?laboratoire_id=${labId}&per_page=1`;
    const data = await fetchJSON(url);

    // --- 1. MAJ chiffres enseignants / doctorants ---
    const enseignants = data.repartition_profil.find(p => parseInt(p.profil_id) === 1);
    const doctorants  = data.repartition_profil.find(p => parseInt(p.profil_id) === 2);

    document.querySelector('.stat-box:nth-child(1) .value').textContent = enseignants ? enseignants.total : '0';
    document.querySelector('.stat-box:nth-child(2) .value').textContent = doctorants ? doctorants.total : '0';

    // --- 2. Préparer graphique chercheurs par année ---
    const labels = data.repartition_annee.map(r => r.annee);
    const values = data.repartition_annee.map(r => r.total);

    renderChart(labels, values);
  }

  // Fonction pour dessiner le graphique
  function renderChart(labels, values){
    const ctx = document.getElementById('barChartProjets');
    if (!ctx) return;

    const existing = Chart.getChart(ctx);
    if (existing) existing.destroy();

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels,
        datasets: [{
          label: 'Chercheurs',
          data: values,
          backgroundColor: '#b1342f',
          borderColor: '#b1342f',
          borderWidth: 1,
          borderRadius: 6,
          borderSkipped: false,
          barPercentage: 0.5,
          categoryPercentage: 0.7
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              title: () => '',
              label: ctx => `Total: ${ctx.parsed.y}`
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: 'rgba(0,0,0,0.08)' },
            ticks: { stepSize: 10, padding: 10 }
          },
          x: {
            grid: { display: false },
            ticks: { padding: 10 }
          }
        }
      }
    });
  }

  */

  // Initialisation
 // const labId = document.getElementById('bloc-stats-combine')?.dataset.labId;
  /*
 if (labId) {
    try {
      await loadStats(labId);
    } catch (e) {
      console.error('Erreur chargement stats:', e);
    }
  }
*/

});
</script>


</body>

</html>