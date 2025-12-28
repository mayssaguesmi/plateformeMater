<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tableau Des Demandes De Maintenances</title>

  <!-- Font Awesome (icônes) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"/>

  <!-- ====================== TES STYLES EXISTANTS (inchangés) ====================== -->
  <style>
    /* General Body Styles */
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f5f7;
      color: #333;
    }
    /* Main Content Block */
    .content-block {
      background: #fff;
      border-radius: 10px;
      padding: 24px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      margin: 20px 0;
    }
    /* Header */
    .header-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; }
    .dashboard-sub-title { font-weight:bold; font-size:22px; display:flex; align-items:center; }
    .section-divider { border:none; border-top:1px solid #e0e0e0; margin:10px 0; }

    /* Filter Bar */
    .filter-bar { display:flex; justify-content:space-between; align-items:center; gap:1rem; padding-bottom:30px; position:relative; flex-wrap:wrap; }
    .filter-inputs { display:flex; align-items:center; gap:.75rem; flex-wrap:wrap; }
    .input-with-icon { position:relative; }
    .input-with-icon .icon { position:absolute; top:50%; transform:translateY(-50%); color:#6b7280; pointer-events:none; font-size:14px; }
    .input-with-icon .right-icon { right:.85rem; }

    .filter-input, .filter-select {
      border:1px solid #e0e0e0; border-radius:6px; padding:.6rem .75rem; background:#fdfdfd;
      font-size:14px; height:42px; box-sizing:border-box; transition:border-color .2s; min-width:180px;
    }
    .filter-input { padding-right:2.5rem; }
    .filter-input:focus, .filter-select:focus { outline:none; border-color:#c60000; }
    .filter-select { -webkit-appearance:none; -moz-appearance:none; appearance:none; padding-right:2.5rem; cursor:pointer; }

    .filter-actions { display:flex; gap:10px; }
    .icon-btn {
      width:42px; height:42px; border:1px solid #e0e0e0; border-radius:6px; background:#fdfdfd; color:#BF0404;
      cursor:pointer; transition:background-color .2s; font-size:16px; display:flex; align-items:center; justify-content:center;
    }
    .icon-btn:hover { background:#f5f5f5; }

    /* Table Styles */
    .styled-table { width:100%; border-collapse:separate; border-spacing:0; }
    .styled-table thead th {
      background:#f3f1e9; padding:14px; text-align:center; border-bottom:1px solid #EBE9D7;
      font-size:14px; white-space:nowrap;
    }
    .styled-table tbody td {
      padding:14px; text-align:center; border:1px solid #EBE9D7; border-top:none; font-size:14px; white-space:nowrap;
    }
    .styled-table th:first-child, .styled-table td:first-child { border-left:1px solid #EBE9D7; }
    .styled-table th:first-child { border-top-left-radius:12px; }
    .styled-table th:last-child { border-top-right-radius:12px; }
    .styled-table tbody tr:last-child td:first-child { border-bottom-left-radius:12px; }
    .styled-table tbody tr:last-child td:last-child { border-bottom-right-radius:12px; }
    .styled-table .left { text-align:left; }

    /* Badge Styles */
    .badge { display:inline-flex; align-items:center; padding:4px 10px; font-size:13px; font-weight:600; border-radius:20px; border:2px solid transparent; }
    .badge-success { color:#198754; background:#e6f7ee; border-color:#198754; }
    .badge-warning { color:#d89e00; background:#fff9e6; border-color:#d89e00; }

    /* Icon with Badge */
    .icon-with-badge{ position:relative; display:inline-block; }
    .icon-with-badge .main-icon{ font-size:24px; color:#4a4a4a; }
    .icon-badge{
      position:absolute; top:-10px; right:-13px; background:#c8c8b8; color:#fff; border-radius:50%; width:16px; height:16px;
      display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:600;
    }

    /* Actions Dropdown */
    .actions{ position:relative; display:inline-block; }
    .action-btn{
      background:transparent; border:1px solid transparent; border-radius:8px; width:36px; height:36px; font-size:24px; font-weight:bolder;
      cursor:pointer; transition:background-color .2s; line-height:1; padding:0; display:flex; align-items:center; justify-content:center;
    }
    .action-btn:hover{ background:#e6e6de; }
    .dropdown-menu{
      display:none; position:absolute; top:100%; right:0; min-width:180px; background:#fff; border:1px solid #d8d4b7; border-radius:8px;
      box-shadow:0 4px 8px rgba(0,0,0,.1); z-index:1000; padding:5px;
    }
    .dropdown-menu.show{ display:block; }
    .dropdown-menu a{
      display:block; padding:9px; text-decoration:none; font-size:14px; color:#2d2a12; transition:background-color .2s; border-radius:4px;
    }
    .dropdown-menu a:hover{ background:#f4f4f4; }

    /* DataTables Customizations — pagination (rouge bordé + arrondi) */
    .dataTables_wrapper .dataTables_paginate{
      display:flex; justify-content:center; gap:10px; margin-top:20px;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button{
      border:2px solid #c60000 !important; color:#c60000 !important; padding:8px 14px; border-radius:8px; background:#fff !important;
      font-weight:bold; cursor:pointer; font-size:13px; box-shadow:none !important; display:flex; justify-content:center; align-items:center;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover{
      background:#f8eaea !important; color:#c60000 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current{
      background:#fff !important; color:#3333 !important; border-color:#c60000;
    }
    .dataTables_wrapper .dataTables_paginate .ellipsis{ display:none; }

    #candidaturesTable{ border:none !important; border-collapse:collapse; box-shadow:none !important; }
    #candidaturesTable th{ border:0px solid #EBE9D7; }
    #candidaturesTable td{ border:1px solid #EBE9D7; }
    #candidaturesTable thead{ border:none !important; position:static; transform:translateY(-15px); }
    #candidaturesTable tbody tr:first-child td{ border-top:1px solid #EBE9D7 !important; }
    #candidaturesTable{ border-collapse:separate; border-spacing:0; }
    #candidaturesTable thead tr:first-child th:first-child{ border-top-left-radius:12px; border-bottom-left-radius:12px; }
    #candidaturesTable thead tr:first-child th:last-child{ border-top-right-radius:12px; border-bottom-right-radius:12px; }
    #candidaturesTable tbody tr:last-child td:first-child{ border-bottom-left-radius:12px; }
    #candidaturesTable tbody tr:last-child td:last-child{ border-bottom-right-radius:12px; }
    #candidaturesTable tbody tr:first-child td:first-child{ border-top-left-radius:12px; }
    #candidaturesTable tbody tr:first-child td:last-child{ border-top-right-radius:12px; }

    /* ===== Sidebar “Voir” (AJOUT – n’empiète pas sur tes styles) ===== */
    .ms-overlay{position:fixed;inset:0;background:rgba(0,0,0,.25);display:none;justify-content:flex-end;z-index:9999}
    .ms-panel{width:420px;max-width:100%;height:100%;background:#fff;display:flex;flex-direction:column;box-shadow:-4px 0 12px rgba(0,0,0,.12);animation:msSlideIn .25s ease-out}
    @keyframes msSlideIn{from{transform:translateX(20px);opacity:.6}to{transform:none;opacity:1}}
    .ms-header{display:flex;justify-content:space-between;align-items:center;padding:14px 16px;box-shadow:0 5px 16px #00000029}
    .ms-header h2{font-size:16px;margin:0;color:#2A2916}
    .ms-save{background:#c62828;color:#fff;border:none;padding:6px 14px;border-radius:6px;cursor:pointer;font-size:14px}
    .ms-body{padding:0 16px 18px}
    .ms-field{padding:12px 0;border-bottom:1px solid #e0ded3}
    .ms-field:last-child{border-bottom:none}
    .ms-label{font-weight:600;color:#6b6a57;margin-bottom:6px}
    .ms-value{color:#2A2916}
    .ms-gallery{display:flex;gap:10px;margin-top:6px;flex-wrap:wrap}
    .ms-gallery img{width:96px;height:64px;object-fit:cover;border-radius:8px;border:1px solid #e0ded3}
    .ms-select{position:relative;display:flex;align-items:center}
    .ms-select select{width:100%;height:42px;border:1px solid #b5af8e;border-radius:7px;padding:0 36px 0 12px;appearance:none;background:#fff}
    .ms-select i{position:absolute;right:12px;pointer-events:none;color:#6b6a57}
    .table-controls {
    display: flex
;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 25px;
}
.filter-selectgb {
    display: flex
;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
}
.search-box {
    display: flex
;
    align-items: center;
    border: 1px solid #d8d4b7;
    border-radius: 6px;
    padding: 0 10px;
    background-color: #fff;
}
.search-box i {
    color: #666;
}
.filter-input {
    padding: 10px 5px;
    border-radius: 6px;
    border: none;
    outline: none;
    font-size: 14px;
    background: #fff;
    width: 200px;
}
.filter-select {
    padding: 10px 12px;
    border-radius: 6px;
    border: 1px solid #d8d4b7;
    font-size: 14px;
    background: #fff;
    appearance: none;
    background-image: url(data:image/svg+xml,%3Csvg fill='%232d2a12' height='14' viewBox='0 0 24 24' width='14' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E);
    background-position: right 10px center;
    background-repeat: no-repeat;
    background-size: 12px;
    padding-right: 30px;
    width: 200px;
}
.date-input-container {
    display: flex
;
    align-items: center;
    border: 1px solid #d8d4b7;
    border-radius: 6px;
    padding: 0 10px;
    background-color: #fff;
}
.date-input {
    padding: 10px 5px;
    border: none;
    outline: none;
    font-size: 14px;
    border-radius: 6px;
}

  </style>
</head>
<body>

  <div class="content-block">
    <div class="header-bar">
      <h2 class="dashboard-sub-title">
        <img src="/wp-content/plugins/plateforme-master/imagesED/7050930.png" alt="Icon"
             style="width: 38px; margin-right: 8px; vertical-align: middle; border-radius: 5px;">
        Tableau Des Demandes De Maintenances
      </h2>
    </div>
    <hr class="section-divider">

   <div class="table-controls">
          <div class="filter-selectgb">
            <div class="search-box">
              <i class="fa fa-search"></i>
              <input type="text" class="filter-input" id="reservationsSearch" placeholder="Recherchez...">
            </div>
            <select class="filter-select" id="statusFilter">
              <option value="">Statut</option>
              <option value="Validée">Terminée</option>
              <option value="Refusée">En attente</option>
              <option value="En attente">En cours</option>
            </select>
            <div class="date-input-container">
              <input type="text" id="dateFilter" class="date-input" placeholder="date">
              <img width="20px" src="/wp-content/plugins/plateforme-master/images/icons/27) Icon-calendar.png" alt="Icon-calendar">
            </div>
          </div>
          <div class="filter-actions">
            <button class="icon-btn">
              <img width="20px" src="/wp-content/plugins/plateforme-master/images/icons/27) Icon-funnel.png" alt="Icon-funnel">
            </button>
            <button class="icon-btn">
              <img width="20px" src="/wp-content/plugins/plateforme-master/images/icons/Groupe 152.png" alt="upload-red.png">
            </button>
          </div>
        </div>

    <table id="candidaturesTable" class="styled-table display" style="width:100%">
      <thead>
        <tr>
          <th><input type="checkbox" id="checkAll"></th>
          <th>Equipements</th>
          <th>Prochaine maintenance</th>
          <th>Demander par</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Exemple minimal pour voir le sidebar (supprime si tu remplis côté PHP) -->
        <tr data-model="UV-Vis Thermo Scientific" data-type="Corrective"
            data-motif="Le Lorem Ipsum est, en imprimerie, une suite de mots sans signification…"
            data-piece="test.pdf"
            data-photos="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=400,https://images.unsplash.com/photo-1581594693700-5c2af88a6a97?q=80&w=400,https://images.unsplash.com/photo-1580982334280-22d8b0f5c0fe?q=80&w=400">
          <td><input type="checkbox" class="row-checkbox"></td>
          <td class="left">Spectrophotomètre</td>
          <td>01/05/2025</td>
          <td>Dr. Leila Romdhane</td>
          <td><span class="badge badge-warning"><i class="fa-regular fa-clock" style="color:#d89e00;"></i>En cours</span></td>
          <td>
            <div class="actions">
              <button class="action-btn" type="button">...</button>
              <div class="dropdown-menu">
                <a href="#" class="action-view">Terminée</a>
                <a href="#" class="action-view">En cours</a>
                <a href="#">Voir</a>
                <!-- “En attente” est volontairement absent -->
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- ====================== SIDEBAR “VOIR” (AJOUT) ====================== -->
  <div id="msSidebarOverlay" class="ms-overlay" aria-hidden="true">
    <aside class="ms-panel" role="dialog" aria-modal="true" aria-labelledby="ms-title">
      <header class="ms-header">
        <h2 id="ms-title">Demande de Maintenance</h2>
        <button type="button" class="ms-save" id="ms-save-btn">Enregistrer</button>
      </header>
      <div class="ms-body">
        <div class="ms-field"><div class="ms-label">Equipement :</div><div class="ms-value" id="ms-eq">—</div></div>
        <div class="ms-field"><div class="ms-label">Modèle :</div><div class="ms-value" id="ms-model">—</div></div>
        <div class="ms-field"><div class="ms-label">Demander Par :</div><div class="ms-value" id="ms-who">—</div></div>
        <div class="ms-field"><div class="ms-label">Date Et Heure :</div><div class="ms-value" id="ms-date">—</div></div>
        <div class="ms-field"><div class="ms-label">Type De Maintenance :</div><div class="ms-value" id="ms-type">—</div></div>
        <div class="ms-field"><div class="ms-label">Motif</div><div class="ms-value" id="ms-motif">—</div></div>
        <div class="ms-field"><div class="ms-label">Pièces Jointe</div><div class="ms-value" id="ms-piece">—</div></div>
        <div class="ms-field">
          <div class="ms-label">Photo De L'équipement</div>
          <div class="ms-gallery" id="ms-photos"></div>
        </div>
        <div class="ms-field">
          <div class="ms-label">Statut</div>
          <div class="ms-select">
            <select id="ms-status">
              <option value="En cours">En Cours</option>
              <option value="Terminée">Terminée</option>
            </select>
            <i class="fa-solid fa-chevron-down"></i>
          </div>
        </div>
      </div>
    </aside>
  </div>

  <!-- ====================== PHP (WP vars) ====================== -->
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

  <!-- ====================== jQuery + DataTables ====================== -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

  <!-- ====================== JS : DataTables + Sidebar Voir ====================== -->
  <script>
    // Init DataTables (respecte ton style/dom)
    $(function(){
      $('#candidaturesTable').DataTable({
        paging:true,
        searching:false, // tu as déjà un champ de recherche custom si besoin
        ordering:false,
        info:false,
        pageLength:3,
        dom:'t<"bottom"p>',
        language:{
          paginate:{ previous:"<i class='fa fa-chevron-left'></i>", next:"<i class='fa fa-chevron-right'></i>" },
          emptyTable:"Aucune donnée disponible",
          zeroRecords:"Aucun enregistrement correspondant trouvé"
        },
        columnDefs:[ {orderable:false, targets:[0,5]} ]
      });
    });

    // Dropdown simple
    document.addEventListener('click', (e)=>{
      if (e.target.closest('.action-btn')) {
        const actions = e.target.closest('.actions');
        const menu = actions.querySelector('.dropdown-menu');
        document.querySelectorAll('.dropdown-menu').forEach(m=> m!==menu && (m.style.display='none'));
        menu.style.display = (menu.style.display==='block' ? 'none' : 'block');
        return;
      }
      // clic hors menu => fermer
      if (!e.target.closest('.actions')) {
        document.querySelectorAll('.dropdown-menu').forEach(m=> m.style.display='none');
      }
    });

    // Ouvrir le sidebar “Voir”
    function ms_extractRowData(row){
      const tds = row.querySelectorAll('td');
      const d = row.dataset || {};
      const data = {
        eq:   (d.eq   || (tds[1]?.textContent||'')).trim(),
        date: (d.date || (tds[2]?.textContent||'')).trim(),
        who:  (d.who  || (tds[3]?.textContent||'')).trim(),
        status:(d.status || (tds[4]?.innerText||'')).trim(),
        model: d.model || '—',
        type:  d.type  || '—',
        motif: d.motif || '—',
        piece: d.piece || '—',
        photos: (d.photos ? d.photos.split(',') : [])
      };
      return data;
    }
    function ms_openSidebar(data){
      const ov = document.getElementById('msSidebarOverlay');
      ov.style.display='flex'; ov.setAttribute('aria-hidden','false');
      document.getElementById('ms-eq').textContent = data.eq || '—';
      document.getElementById('ms-model').textContent = data.model || '—';
      document.getElementById('ms-who').textContent = data.who || '—';
      document.getElementById('ms-date').textContent = data.date || '—';
      document.getElementById('ms-type').textContent = data.type || '—';
      document.getElementById('ms-motif').textContent = data.motif || '—';
      document.getElementById('ms-piece').textContent = data.piece || '—';
      const g = document.getElementById('ms-photos'); g.innerHTML='';
      (data.photos||[]).forEach(src=>{ const img = new Image(); img.src = src.trim(); img.alt='photo'; g.appendChild(img); });
      document.getElementById('ms-status').value = (/terminée/i.test(data.status||'')) ? 'Terminée' : 'En cours';

      // fermer en cliquant sur l’overlay
      ov.onclick = (e)=>{ if(e.target===ov) ms_closeSidebar(); };
    }
    function ms_closeSidebar(){
      const ov = document.getElementById('msSidebarOverlay');
      ov.style.display='none'; ov.setAttribute('aria-hidden','true');
    }

    // clic “Voir” (par classe ou par libellé)
    document.addEventListener('click', (e)=>{
      const a = e.target.closest('.dropdown-menu a');
      if (!a) return;
      const isVoir = a.classList.contains('action-view') || /voir/i.test(a.textContent);
      if (!isVoir) return;
      e.preventDefault();
      const tr = a.closest('tr');
      const data = ms_extractRowData(tr);
      ms_openSidebar(data);
      a.closest('.dropdown-menu').style.display = 'none';
    });

    // bouton Enregistrer du sidebar (à brancher sur ton API si besoin)
    document.getElementById('ms-save-btn').addEventListener('click', ()=>{
      // TODO: fetch(...) pour persister d’éventuelles modifs (statut, etc.)
      ms_closeSidebar();
    });
  </script>
</body>
</html>
