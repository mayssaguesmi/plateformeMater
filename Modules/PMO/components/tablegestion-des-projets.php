<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste Des Projets</title>
    <style>
        .dashboard-sub-title {
            font-weight: bold;
        }

        .filter-inputs {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon .icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            pointer-events: none;
            font-size: 14px;
        }

        .input-with-icon .left-icon {
            left: 0.85rem;
        }

        .input-with-icon .right-icon {
            right: 0.85rem;
        }

        .filter-bar .filter-input,
        .filter-bar .filter-select {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 0.6rem 0.75rem;
            background-color: #fdfdfd;
            font-size: 14px;
            height: 42px;
            box-sizing: border-box;
            transition: border-color 0.2s;
            min-width: 180px;
        }

        .filter-bar .filter-input {
            width: 220px;
        }

        .filter-bar .filter-input:focus,
        .filter-bar .filter-select:focus {
            outline: none;
            /* border-color: #c60000; */
        }

        .input-with-icon .date-input {
            padding-left: 0.75rem;
            padding-right: 2.5rem;
        }

        .filter-bar .filter-select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            padding-right: 2.5rem;
            cursor: pointer;
        }

        .filter-bar .icon-btn {
            width: 42px;
            height: 42px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            background-color: #fdfdfd;
            color: #BF0404;
            cursor: pointer;
            transition: background-color 0.2s;
            font-size: 16px;
        }

        .filter-bar .icon-btn:hover {
            background-color: #f5f5f5;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
        }

        .content-block {
            background: #fff;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .add-project-btn {
            background-color: #c60000;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .add-project-btn:hover {
            background-color: #a50000;
        }

        /* ---------- Table ---------- */
        .styled-table {
            width: 100%;
            border-collapse: collapse
        }

        .styled-table thead {
            background: #f3f1e9
        }

        .styled-table th,
        .styled-table td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #eee
        }

        .styled-table tbody tr:hover {
            background: #fafafa
        }

        #projectsTable {
            border: none !important;
            box-shadow: none !important;
            border-collapse: separate;
            border-spacing: 0
        }

        #projectsTable th {
            border: 0
        }

        #projectsTable td {
            border: 1px solid #A6A4853D;
        }

        #projectsTable thead {
            position: static;
            transform: translateY(-15px)
        }

        #projectsTable tbody tr:first-child td {
            border-top: 1px solid #A6A4853D !important;
        }

        /* arrondis */
        #projectsTable thead tr:first-child th:first-child {
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px
        }

        #projectsTable thead tr:first-child th:last-child {
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px
        }

        #projectsTable tbody tr:last-child td:first-child {
            border-bottom-left-radius: 12px
        }

        #projectsTable tbody tr:last-child td:last-child {
            border-bottom-right-radius: 12px
        }

        #projectsTable tbody tr:first-child td:first-child {
            border-top-left-radius: 12px
        }

        #projectsTable tbody tr:first-child td:last-child {
            border-top-right-radius: 12px
        }

        .actions {
            position: relative;
            display: inline-block;
        }

        .action-btn {
            background-color: transparent;
            color: #2d2a12;
            border: 1px solid transparent;
            border-radius: 8px;
            width: 36px;
            height: 36px;
            font-size: 24px;
            font-weight: bolder;
            cursor: pointer;
            transition: background-color 0.2s, box-shadow 0.2s;
            line-height: 1;
            padding: 0;
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn:hover {
            background-color: #e6e6de;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 160px;
            background-color: #ffffff;
            border: 1px solid #d8d4b7;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 6px 0;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-menu a {
            display: block;
            padding: 8px 14px;
            text-decoration: none;
            font-size: 14px;
            color: #2d2a12;
            transition: background-color 0.2s;
        }

        .dropdown-menu a:hover {
            background-color: #f4f4f4;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            /* Flex by default now handled by JS */
            justify-content: flex-end;
            z-index: 9999;
        }

        .popup-container {
            background-color: white;
            width: 450px;
            height: 100%;
            padding: 0;
            box-shadow: -4px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 25px;
            box-shadow: 0px 5px 16px #0000001a;
            flex-shrink: 0;
        }

        .popup-header h2 {
            font-size: 18px;
            margin: 0;
            color: #2A2916;
        }

        .btn-enregistrer {
            background-color: #c62828;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .popup-form {
            padding: 25px;
            overflow-y: auto;
            flex-grow: 1;
        }

        .popup-form .form-group {
            margin-bottom: 15px;
        }

        .popup-form .form-group label {
            display: block;
            font-weight: 600;
            color: #6E6D55;
            font-size: 14px;
            /* margin-bottom: 5px; */
        }

        .popup-form .form-group input,
        .popup-form .form-group select,
        .popup-form .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #b5af8e;
            border-radius: 7px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .popup-form .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .popup-form .form-group input:focus,
        .popup-form .form-group select:focus,
        .popup-form .form-group textarea:focus {
            outline: none;
            /* border-color: #c60000; */
            /* box-shadow: 0 0 0 2px rgba(198, 0, 0, 0.2); */
        }

        .popup-form .input-with-icon select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            padding-right: 30px;
            background-color: #fff;
        }

        .popup-form .input-file-wrapper {
            display: flex;
            align-items: center;
            /* border: 1px solid #b5af8e; */
            border-radius: 7px;
            background-color: white;
            overflow: hidden;
        }

        .popup-form .input-file-text {
            flex-grow: 1;
            border: none;
            padding: 10px 12px;
            background-color: transparent;
            color: #888;
            cursor: default;
            border-radius: 7px 0 0 7px !important;
        }

        .popup-form .input-file-text:focus {
            outline: none;
        }

        .popup-form .btn-importer {
            background-color: #A6A485;
            color: #fff !important;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #b5af8e;
            white-space: nowrap;
        }

        .popup-form .form-row {
            display: flex;
            gap: 15px;
        }

        .popup-form .form-row .form-group {
            flex: 1;
        }

        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: #C60000;
            border-color: #C60000;
        }

        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding-bottom: 30px;
            position: relative;
            flex-wrap: wrap;
        }

        /* Hide default DataTables pagination */
        .dataTables_paginate {
            display: none !important;
        }

        .paginate_button {
            display: none !important;
        }
    </style>
</head>

<body>
  <!-- Nonce WP pour REST -->
  <script>
    window.wpApiSettings = window.wpApiSettings || {};
  </script>
  <?php if (function_exists('wp_create_nonce')): ?>
  <script>window.wpApiSettings.nonce = '<?php echo wp_create_nonce('wp_rest'); ?>';</script>
  <?php endif; ?>

  <div class="content-block">
    <div class="header-bar">
      <h2 class="dashboard-sub-title">Liste Des Projets</h2>
      <button class="add-project-btn" id="openAdd">Ajouter un projet</button>
    </div>

    <div class="filter-bar">
      <div class="filter-inputs">
        <div class="input-with-icon">
          <input class="filter-input" id="generalSearch" type="text" placeholder="Recherchez...">
          <i class="fas fa-search icon right-icon"></i>
        </div>

        <!-- Type (issu de utm_recherche_type_projet) -->
        <div class="input-with-icon">
          <select class="filter-select" id="typeFilter">
            <option value="">Type (Tous)</option>
          </select>
          <i class="fas fa-chevron-down icon right-icon"></i>
        </div>

        <!-- Filtre date (point dans l'intervalle) -->
        <div class="input-with-icon">
          <input class="filter-input" id="dateRangeFilter" type="text" placeholder="Sélectionner une date ">
          <i class="fas fa-calendar-alt icon right-icon"></i>
        </div>
      </div>
    </div>

    <table id="projectsTable" class="styled-table">
      <thead>
        <tr>
          <th><input type="checkbox" id="checkAll"></th>
          <th>Intitulé du projet</th>
          <th>Type</th>
          <th>Date début</th>
          <th>Date fin</th>
          <th>Financement</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <!-- Drawer: Ajouter -->
  <div class="modal-overlay" id="addProjectModal">
    <div class="popup-container">
      <div class="popup-header">
        <h2>Ajouter un projet</h2>
        <button type="button" class="btn-enregistrer" id="saveProjectBtn">Enregistrer</button>
      </div>
      <form class="popup-form" id="addProjectForm">
        <div class="form-group">
          <label for="titreProjet">Titre du projet</label>
          <input type="text" id="titreProjet" required>
        </div>

        <div class="form-group">
          <label for="typeProjet">Type</label>
          <div class="input-with-icon">
            <select id="typeProjet" required></select>
            <span class="icon right-icon">▼</span>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="financement">Financement prévisionnel</label>
            <input type="text" id="financement" placeholder="ex: 80 000 TND">
          </div>
          <div class="form-group">
            <label for="sourceFinancement">Source Financement</label>
            <div class="input-with-icon">
              <select id="sourceFinancement"></select>
              <span class="icon right-icon">▼</span>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="siteWebSource">Site web du source</label>
          <input type="url" id="siteWebSource" placeholder="https://example.com">
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="dateDebut">Date Début</label>
            <input type="date" id="dateDebut">
          </div>
          <div class="form-group">
            <label for="dateFin">Date Fin</label>
            <input type="date" id="dateFin">
          </div>
        </div>

        <div class="form-group">
          <label for="objectifs">Objectifs et description</label>
          <textarea id="objectifs" placeholder="description"></textarea>
        </div>
      </form>
    </div>
  </div>

  <!-- Drawer: Modifier -->
  <div class="modal-overlay" id="editProjectModal">
    <div class="popup-container">
      <div class="popup-header">
        <h2>Modifier le projet</h2>
        <button type="button" class="btn-enregistrer" id="updateProjectBtn">Enregistrer</button>
      </div>
      <form class="popup-form" id="editProjectForm">
        <input type="hidden" id="editProjectId">
        <div class="form-group">
          <label for="editTitreProjet">Titre du projet</label>
          <input type="text" id="editTitreProjet" required>
        </div>

        <div class="form-group">
          <label for="editTypeProjet">Type</label>
          <div class="input-with-icon">
            <select id="editTypeProjet" required></select>
            <span class="icon right-icon">▼</span>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="editFinancement">Financement prévisionnel</label>
            <input type="text" id="editFinancement" placeholder="ex: 80 000 TND">
          </div>
          <div class="form-group">
            <label for="editSourceFinancement">Source Financement</label>
            <div class="input-with-icon">
              <select id="editSourceFinancement"></select>
              <span class="icon right-icon">▼</span>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label for="editSiteWebSource">Site web du source</label>
          <input type="url" id="editSiteWebSource" placeholder="https://example.com">
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="editDateDebut">Date Début</label>
            <input type="date" id="editDateDebut">
          </div>
          <div class="form-group">
            <label for="editDateFin">Date Fin</label>
            <input type="date" id="editDateFin">
          </div>
        </div>

        <div class="form-group">
          <label for="editObjectifs">Objectifs et description</label>
          <textarea id="editObjectifs" placeholder="description"></textarea>
        </div>
      </form>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', () => {
    const REST = '/wp-json/plateforme-pmo/v1';
    const NONCE = (window.wpApiSettings && window.wpApiSettings.nonce) ? window.wpApiSettings.nonce : '';

    const tbody = document.querySelector('#projectsTable tbody');
    const addModal = document.getElementById('addProjectModal');
    const editModal = document.getElementById('editProjectModal');

    const openAddBtn = document.getElementById('openAdd');
    const saveProjectBtn = document.getElementById('saveProjectBtn');
    const updateProjectBtn = document.getElementById('updateProjectBtn');

    const filterSearch = document.getElementById('generalSearch');
    const filterType = document.getElementById('typeFilter');
    const filterDate = document.getElementById('dateRangeFilter');

    // selects
    const typeAdd = document.getElementById('typeProjet');
    const typeEdit = document.getElementById('editTypeProjet');
    const srcAdd  = document.getElementById('sourceFinancement');
    const srcEdit = document.getElementById('editSourceFinancement');

    // forms
    const addForm = {
      titre: document.getElementById('titreProjet'),
      type:  typeAdd,
      financement: document.getElementById('financement'),
      source: srcAdd,
      site: document.getElementById('siteWebSource'),
      d1: document.getElementById('dateDebut'),
      d2: document.getElementById('dateFin'),
      obj: document.getElementById('objectifs')
    };
    const editForm = {
      id: document.getElementById('editProjectId'),
      titre: document.getElementById('editTitreProjet'),
      type: typeEdit,
      financement: document.getElementById('editFinancement'),
      source: srcEdit,
      site: document.getElementById('editSiteWebSource'),
      d1: document.getElementById('editDateDebut'),
      d2: document.getElementById('editDateFin'),
      obj: document.getElementById('editObjectifs')
    };

    let ALL = [];      // projets (depuis API)
    let TYPES = [];    // [{id,label}]
    let SOURCES = [];  // [{id,label}]

    /* ---------- helpers dates ---------- */
    const toDDMMYYYY = (iso) => {
      if(!iso) return '';
      const [y,m,d] = iso.split('-'); return `${d}/${m}/${y}`;
    };
    const parseDDMMYYYY = (dmy) => {
      if(!/^\d{2}\/\d{2}\/\d{4}$/.test(dmy)) return null;
      const [d,m,y] = dmy.split('/'); return new Date(+y, +m-1, +d);
    };

    /* ---------- fetch utils ---------- */
    const jsonHeaders = { 'Content-Type':'application/json' };
    if (NONCE) jsonHeaders['X-WP-Nonce'] = NONCE;

    const apiGet  = (url) => fetch(url, {headers: jsonHeaders}).then(r=>r.json());
    const apiPost = (url, body) => fetch(url, {method:'POST', headers: jsonHeaders, body: JSON.stringify(body)}).then(r=>r.json());
    const apiPut  = (url, body) => fetch(url, {method:'PUT',  headers: jsonHeaders, body: JSON.stringify(body)}).then(r=>r.json());

    /* ---------- charge listes déroulantes ---------- */
    async function loadLists(){
      TYPES   = await apiGet(`${REST}/projet-types`);
      SOURCES = await apiGet(`${REST}/projet-sources`);

      const fill = (sel, list, placeholder) => {
        sel.innerHTML = '';
        const opt0 = document.createElement('option'); opt0.value=''; opt0.textContent = placeholder; sel.appendChild(opt0);
        list.forEach(it => {
          const o = document.createElement('option');
          o.value = it.id; o.textContent = it.label; sel.appendChild(o);
        });
      };
      fill(typeAdd, TYPES, 'Sélection..');
      fill(typeEdit, TYPES, 'Sélection..');
      fill(srcAdd,  SOURCES, 'Sélection..');
      fill(srcEdit, SOURCES, 'Sélection..');

      // filtre "Type"
      const typeFilterSel = filterType;
      typeFilterSel.innerHTML = '<option value="">Type (Tous)</option>';
      TYPES.forEach(t => {
        const o = document.createElement('option');
        o.value = t.id; o.textContent = t.label; typeFilterSel.appendChild(o);
      });
    }

    /* ---------- charge projets ---------- */
    async function loadProjects(){
      const rows = await apiGet(`${REST}/projets?per_page=100`);
      ALL = Array.isArray(rows) ? rows : [];
      applyFilters();
    }

    /* ---------- rendu table ---------- */
    function render(list){
      const makeRow = (p) => `
        <tr data-id="${p.id}">
          <td><input type="checkbox" class="row-check"></td>
          <td>${p.intitule || ''}</td>
          <td>${p.type_label || ''}</td>
          <td>${p.date_debut_fr || toDDMMYYYY(p.date_debut) || ''}</td>
          <td>${p.date_fin_fr   || toDDMMYYYY(p.date_fin)   || ''}</td>
          <td>${p.financement || ''}</td>
          <td>
            <div class="actions">
              <button class="action-btn" aria-haspopup="true" aria-expanded="false">⋯</button>
              <div class="dropdown-menu">
                <a href="#" class="btn-modifier">Modifier</a>
<a href="/gestion-des-projets-details-projets?id=${p.id}" class="btn-voir">Voir</a>

              </div>
            </div>
          </td>
        </tr>`;
      if(!list.length){
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:20px;">Aucun projet trouvé.</td></tr>`;
      } else {
        tbody.innerHTML = list.map(makeRow).join('');
      }
    }

    /* ---------- filtres ---------- */
    function applyFilters(){
      const q = (filterSearch.value || '').toLowerCase();
      const typeId = filterType.value || '';
      const dateStr = filterDate.value || '';
      const pointDate = dateStr ? parseDDMMYYYY(dateStr) : null;

      const out = ALL.filter(p => {
        const matchesQ =
          !q ||
          (p.intitule && p.intitule.toLowerCase().includes(q)) ||
          (p.type_label && p.type_label.toLowerCase().includes(q));

        const matchesType = !typeId || String(p.type_id) === String(typeId);

        let matchesDate = true;
        if(pointDate){
          const sd = p.date_debut_fr ? p.date_debut_fr : toDDMMYYYY(p.date_debut);
          const ed = p.date_fin_fr   ? p.date_fin_fr   : toDDMMYYYY(p.date_fin);
          const d1 = sd ? parseDDMMYYYY(sd) : null;
          const d2 = ed ? parseDDMMYYYY(ed) : null;
          matchesDate = d1 && d2 && pointDate >= d1 && pointDate <= d2;
        }
        return matchesQ && matchesType && matchesDate;
      });

      render(out);
    }

    /* ---------- modales ---------- */
    function openAdd(){ 
      document.getElementById('addProjectForm').reset();
      addModal.style.display = 'flex';
      const today = new Date().toISOString().split('T')[0];
      addForm.d1.value = today; addForm.d2.value = today;
    }
    function closeAdd(){ addModal.style.display = 'none'; }
    function openEdit(p){
      document.getElementById('editProjectForm').reset();
      editForm.id.value     = p.id;
      editForm.titre.value  = p.intitule || '';
      editForm.type.value   = p.type_id || '';
      editForm.financement.value = p.financement || '';
      editForm.source.value = p.source_financement_id || '';
      editForm.site.value   = p.site_web_source || '';
      editForm.d1.value     = (p.date_debut && p.date_debut.length===10) ? p.date_debut : ''; // YYYY-MM-DD
      editForm.d2.value     = (p.date_fin   && p.date_fin.length===10)   ? p.date_fin   : '';
      editForm.obj.value    = p.objectifs || '';
      editModal.style.display = 'flex';
    }
    function closeEdit(){ editModal.style.display = 'none'; }

    addModal.addEventListener('click', e => { if(e.target===addModal) closeAdd(); });
    editModal.addEventListener('click', e => { if(e.target===editModal) closeEdit(); });
    openAddBtn.addEventListener('click', openAdd);

    /* ---------- actions CRUD ---------- */
    saveProjectBtn.addEventListener('click', async (e) => {
      e.preventDefault();
      const payload = {
        intitule: (addForm.titre.value || '').trim(),
        type_id: addForm.type.value ? parseInt(addForm.type.value,10) : null,
        date_debut: addForm.d1.value || null, // YYYY-MM-DD
        date_fin:   addForm.d2.value || null,
        financement: (addForm.financement.value || '').trim(),
        source_financement_id: addForm.source.value ? parseInt(addForm.source.value,10) : null,
        site_web_source: (addForm.site.value || '').trim(),
        objectifs: (addForm.obj.value || '').trim()
      };
      if(!payload.intitule){ alert('Titre du projet requis.'); return; }

      const res = await apiPost(`${REST}/projets`, payload);
      if(res && !res.code){
        await loadProjects();
        closeAdd();
        alert('Projet ajouté.');
      } else {
        alert('Erreur: ' + (res.message || 'POST'));
      }
    });

    updateProjectBtn.addEventListener('click', async (e) => {
      e.preventDefault();
      const id = parseInt(editForm.id.value,10);
      const payload = {
        intitule: (editForm.titre.value || '').trim(),
        type_id: editForm.type.value ? parseInt(editForm.type.value,10) : null,
        date_debut: editForm.d1.value || null,
        date_fin:   editForm.d2.value || null,
        financement: (editForm.financement.value || '').trim(),
        source_financement_id: editForm.source.value ? parseInt(editForm.source.value,10) : null,
        site_web_source: (editForm.site.value || '').trim(),
        objectifs: (editForm.obj.value || '').trim()
      };
      const res = await apiPut(`${REST}/projets/${id}`, payload);
      if(res && !res.code){
        await loadProjects();
        closeEdit();
        alert('Projet mis à jour.');
      } else {
        alert('Erreur: ' + (res.message || 'PUT'));
      }
    });

    // open menu + ouvrir modale edit
    document.body.addEventListener('click', (e) => {
      const actionBtn = e.target.closest('.action-btn');
      if (actionBtn) {
        const dropdown = actionBtn.nextElementSibling;
        const open = dropdown.classList.contains('show');
        document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
        if(!open) dropdown.classList.add('show');
      } else if (!e.target.closest('.actions')) {
        document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
      }
     const editLink = e.target.closest('.btn-modifier');
if (editLink) {
  e.preventDefault();
  const tr = editLink.closest('tr');
  const rowId = tr.dataset.id;                         // string
  const p = ALL.find(x => String(x.id) === String(rowId));
  if (p) openEdit(p);
}
 });

    // filtres
    filterSearch.addEventListener('input', applyFilters);
    filterType.addEventListener('change', applyFilters);
    filterDate.addEventListener('input', applyFilters);

    // bootstrap
    (async () => {
      await loadLists();
      await loadProjects();
    })();
  });
  </script>
</body>

</html>