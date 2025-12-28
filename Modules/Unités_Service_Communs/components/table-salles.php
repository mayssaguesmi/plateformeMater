<!-- External CSS Libraries -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
  body{font-family:'Segoe UI',sans-serif;background-color:#f4f4f9}
  .dashboard-sub-title{font-weight:bold}

  .content-block{background:#fff;border-radius:10px;padding:24px;box-shadow:0 2px 6px rgba(0,0,0,.05)}
  .header-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px}
  .section-divider{border:none;border-top:1px solid #e0e0e0;margin:10px 0}

  .add-project-btn{background:#c60000;color:#fff;border:none;border-radius:6px;padding:10px 20px;font-weight:bold;cursor:pointer;transition:.2s}
  .add-project-btn:hover{background:#a50000}

  .filter-bar{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding-bottom:30px;flex-wrap:wrap}
  .filter-inputs{display:flex;align-items:center;gap:.75rem;flex-wrap:wrap}
  .filter-actions{display:flex;gap:10px}

  .input-with-icon{position:relative}
  .input-with-icon .icon{position:absolute;top:50%;transform:translateY(-50%);color:#6b7280;pointer-events:none;font-size:14px}
  .input-with-icon .right-icon{right:.85rem}

  .filter-bar .filter-input,.filter-bar .filter-select{
    border:1px solid #e0e0e0;border-radius:6px;padding:.6rem .75rem;background:#fdfdfd;font-size:14px;height:42px;min-width:180px;transition:border-color .2s;box-sizing:border-box
  }
  .filter-bar .filter-input{width:220px}
  .filter-bar .filter-input:focus,.filter-bar .filter-select:focus{outline:none;border-color:#c60000}
  .filter-bar .filter-select{appearance:none;padding-right:2.5rem;cursor:pointer}

  .filter-bar .icon-btn{width:42px;height:42px;border:1px solid #e0e0e0;border-radius:6px;background:#fdfdfd;color:#BF0404;cursor:pointer;transition:.2s;font-size:16px}
  .filter-bar .icon-btn:hover{background:#f5f5f5}

  /* ===== TABLE : head/body séparés comme la maquette ===== */
  #roomsTable{border:none !important;border-collapse:separate;box-shadow:none !important;border-spacing:0;width:100%}
  #roomsTable thead{background:#f3f1e9;border:none !important;position:static;transform:translateY(-15px)}
  #roomsTable th,#roomsTable td{padding:14px;text-align:left}
  /* lignes body avec fines bordures */
  #roomsTable td{border:1px solid #EBE9D7}
  #roomsTable th{border:0px solid #EBE9D7}
  /* coins arrondis */
  #roomsTable thead tr:first-child th:first-child{border-top-left-radius:12px;border-bottom-left-radius:12px}
  #roomsTable thead tr:first-child th:last-child{border-top-right-radius:12px;border-bottom-right-radius:12px}
  #roomsTable tbody tr:first-child td{border-top:1px solid #EBE9D7 !important}
  #roomsTable tbody tr:first-child td:first-child{border-top-left-radius:12px}
  #roomsTable tbody tr:first-child td:last-child{border-top-right-radius:12px}
  #roomsTable tbody tr:last-child td:first-child{border-bottom-left-radius:12px}
  #roomsTable tbody tr:last-child td:last-child{border-bottom-right-radius:12px}

  /* Statuts (pills) */
  .badge{display:inline-block;padding:4px 10px;font-size:13px;font-weight:600;border-radius:20px;text-transform:capitalize;border:2px solid transparent;background:#fff}
  .badge-disponible{color:#198754;border-color:#198754;background:#e6f7ee}
  .badge-reservee{color:#8a6d3b;border-color:#d8c7a0;background:#fff9e6}

  /* Actions */
  .actions{position:relative;display:inline-block}
  .action-btn{background:transparent;color:#2d2a12;border:1px solid transparent;border-radius:8px;width:36px;height:36px;font-size:24px;font-weight:bolder;cursor:pointer;transition:.2s;line-height:1;display:flex;align-items:center;justify-content:center}
  .action-btn:hover{background:#e6e6de;box-shadow:0 1px 3px rgba(0,0,0,.1)}
  .dropdown-menu{display:none;position:absolute;top:100%;right:0;min-width:160px;background:#fff;border:1px solid #d8d4b7;border-radius:8px;box-shadow:0 4px 8px rgba(0,0,0,.1);z-index:1000;padding:6px 0}
  .dropdown-menu a{display:block;padding:8px 14px;text-decoration:none;font-size:14px;color:#2d2a12;transition:background-color .2s}
  .dropdown-menu a:hover{background:#f4f4f4}
  .dropdown-menu.show{display:block}

  /* Pagination personnalisée */
  .pagination-controls{display:flex;justify-content:flex-end;align-items:center;gap:6px;margin-top:20px}
  .pagination-button{border-radius:8px;border:2px solid #c60000!important;background:#fff!important;color:#c60000!important;font-weight:600;cursor:pointer;transition:all .2s ease;padding:8px 12px;display:inline-flex;align-items:center;justify-content:center;min-width:36px}
  .pagination-button.active{background:#c60000!important;color:#fff!important}
  .pagination-button:hover:not(.active):not(.disabled){background:#fde0e0!important}
  .pagination-button.disabled{opacity:.5;cursor:default;background:#fff!important}

  /* Sidebar (modal) */
  .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);display:none;justify-content:flex-end;z-index:9999}
  .popup-container{background:#fff;width:450px;height:100%;padding:0;box-shadow:-4px 0 10px rgba(0,0,0,.1);overflow-y:auto;display:flex;flex-direction:column}
  .popup-header{display:flex;justify-content:space-between;align-items:center;padding:20px 25px;box-shadow:0 5px 16px #0000001a;flex-shrink:0}
  .popup-header h2{font-size:18px;margin:0;color:#2A2916}
  .btn-enregistrer{background:#c62828;color:#fff;border:none;padding:8px 16px;border-radius:5px;cursor:pointer;font-size:14px;font-weight:500}
  .popup-form{padding:25px;overflow-y:auto;flex-grow:1}
  .popup-form .form-group{margin-bottom:15px}
  .popup-form .form-group label{display:block;font-weight:600;color:#6E6D55;font-size:14px}
  .popup-form .form-group input,.popup-form .form-group select{width:100%;padding:10px 12px;border:1px solid #b5af8e;border-radius:7px;font-size:14px;box-sizing:border-box}
  .popup-form .form-group input:focus,.popup-form .form-group select:focus{outline:none;border-color:#c60000;box-shadow:0 0 0 2px rgba(198,0,0,.2)}
</style>

<div class="content-block">
  <div class="header-bar">
    <h2 class="dashboard-sub-title">
      <img src="/wp-content/plugins/plateforme-master/images/icons/4540152.png" alt="Icon" style="width:38px;margin-right:8px;vertical-align:middle;">
      Liste des salles
    </h2>
    <button class="add-project-btn" id="addRoomBtn">Ajouter une salle</button>
  </div>

  <hr class="section-divider">

  <!-- Filtres -->
  <div class="filter-bar">
    <div class="filter-inputs">
      <!-- Localisation -->
      <div class="input-with-icon">
        <select class="filter-select" id="filterLocalisation">
          <option value="">Localisation</option>
          <option>FDST – Bloc A</option>
          <option>FDST – Bloc B</option>
          <option>FST – Informatique</option>
          <option>Faculté Médecine</option>
        </select>
        <i class="fas fa-chevron-down icon right-icon"></i>
      </div>

      <!-- Capacité -->
      <div class="input-with-icon">
        <select class="filter-select" id="filterCapacite">
          <option value="">Capacité</option>
          <option value="<=20">≤ 20</option>
          <option value="21-50">21–50</option>
          <option value="51-100">51–100</option>
          <option value=">100">> 100</option>
        </select>
        <i class="fas fa-chevron-down icon right-icon"></i>
      </div>

      <!-- Période -->
      <div class="input-with-icon">
        <input class="filter-input" id="filterPeriode" type="text" placeholder="Période">
        <i class="fas fa-calendar-days icon right-icon"></i>
      </div>

      <!-- Disponibilité -->
      <div class="input-with-icon">
        <select class="filter-select" id="filterDisponibilite">
          <option value="">Disponibilité</option>
          <option value="Disponible">Disponible</option>
          <option value="Réservée">Réservée</option>
        </select>
        <i class="fas fa-chevron-down icon right-icon"></i>
      </div>
    </div>

    <div class="filter-actions">
      <button class="icon-btn" title="Filter"><i class="fa-solid fa-filter"></i></button>
      <button class="icon-btn" title="Download" id="exportBtn"><i class="fa-solid fa-file-arrow-down"></i></button>
    </div>
  </div>

  <!-- Tableau -->
  <table id="roomsTable">
    <thead>
      <tr>
        <th style="width:48px"><input type="checkbox" id="checkAll"></th>
        <th>Nom salle</th>
        <th>Localisation</th>
        <th>Capacité</th>
        <th>Statut</th>
        <th style="width:90px">Actions</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <div class="pagination-controls"></div>
</div>

<!-- Sidebar: Ajouter / Modifier une salle -->
<div class="modal-overlay" id="roomModal">
  <div class="popup-container">
    <div class="popup-header">
      <h2 id="modalTitle">Ajouter une salle</h2>
      <button type="button" class="btn-enregistrer" id="saveRoomBtn">Enregistrer</button>
    </div>
    <form class="popup-form" id="roomForm">
      <div class="form-group">
        <label for="nomSalle">Nom salle</label>
        <input type="text" id="nomSalle" placeholder="Ex: Salle TP Bio 01">
      </div>
      <div class="form-group">
        <label for="localisationSalle">Localisation</label>
        <select id="localisationSalle">
          <option value="">Sélection..</option>
          <option>FDST – Bloc A</option>
          <option>FDST – Bloc B</option>
          <option>FST – Informatique</option>
          <option>Faculté Médecine</option>
        </select>
      </div>
      <div class="form-group">
        <label for="capaciteSalle">Capacité</label>
        <input type="number" id="capaciteSalle" min="1" placeholder="Ex: 25">
      </div>
      <div class="form-group">
        <label for="statutSalle">Statut</label>
        <select id="statutSalle">
          <option>Disponible</option>
          <option>Réservée</option>
        </select>
      </div>
    </form>
  </div>
</div>

<!-- JS libs -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>

<script>
(function(){
  // ==== Données statiques ====
  let rooms = [
    { id: 1, nom: "Salle TP Bio 01", localisation: "FDST – Bloc A", capacite: 25,  statut: "Disponible" },
    { id: 2, nom: "Salle Conférence", localisation: "Faculté Médecine", capacite: 100, statut: "Réservée" },
    { id: 3, nom: "Amphi 03", localisation: "FST – Informatique", capacite: 40, statut: "Réservée" },
    { id: 4, nom: "Salle Info B", localisation: "FDST – Bloc B", capacite: 28, statut: "Disponible" },
    { id: 5, nom: "Polyvalente 1", localisation: "FDST – Bloc A", capacite: 60, statut: "Disponible" },
    { id: 6, nom: "Salle Réunion A", localisation: "FDST – Bloc B", capacite: 18, statut: "Réservée" }
  ];

  // ==== État UI ====
  let filtered = [...rooms];
  let currentPage = 1;
  const itemsPerPage = 5;
  let editingId = null;

  // ==== Refs DOM ====
  const tbody = document.querySelector('#roomsTable tbody');
  const pagination = document.querySelector('.pagination-controls');

  const checkAll = document.getElementById('checkAll');
  const addBtn = document.getElementById('addRoomBtn');
  const exportBtn = document.getElementById('exportBtn');

  const filterLocalisation = document.getElementById('filterLocalisation');
  const filterCapacite = document.getElementById('filterCapacite');
  const filterPeriode = document.getElementById('filterPeriode');
  const filterDisponibilite = document.getElementById('filterDisponibilite');

  const modal = document.getElementById('roomModal');
  const modalTitle = document.getElementById('modalTitle');
  const saveBtn = document.getElementById('saveRoomBtn');

  const inputNom = document.getElementById('nomSalle');
  const inputLoc = document.getElementById('localisationSalle');
  const inputCap = document.getElementById('capaciteSalle');
  const inputStatut = document.getElementById('statutSalle');

  // ==== Helpers ====
  const badgeClass = (s) => (String(s).toLowerCase()==='disponible')
    ? 'badge badge-disponible'
    : 'badge badge-reservee';

  const openModal = (edit=false, id=null) => {
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    modalTitle.textContent = edit ? 'Modifier une salle' : 'Ajouter une salle';
    editingId = null;
    document.getElementById('roomForm').reset();

    if(edit && id!=null){
      const room = rooms.find(r => r.id === id);
      if(room){
        editingId = id;
        inputNom.value = room.nom;
        inputLoc.value = room.localisation;
        inputCap.value = room.capacite;
        inputStatut.value = room.statut;
      }
    }
  };
  const closeModal = () => { modal.style.display='none'; document.body.style.overflow=''; editingId=null; };

  const buildRow = (r) => {
    const tr = document.createElement('tr');
    tr.dataset.id = r.id;
    tr.innerHTML = `
      <td><input type="checkbox" class="row-check"></td>
      <td>${r.nom}</td>
      <td>${r.localisation}</td>
      <td>${r.capacite}</td>
      <td><span class="${badgeClass(r.statut)}">${r.statut}</span></td>
      <td>
        <div class="actions">
          <button class="action-btn" aria-haspopup="true" aria-expanded="false">⋯</button>
          <div class="dropdown-menu">
            <a href="#" class="btn-modifier">Modifier</a>
            <a href="#" class="btn-reserver">Réserver</a>
            <a href="#" class="btn-disponible">Disponible</a>
          </div>
        </div>
      </td>
    `;
    return tr;
  };

  const renderPagination = () => {
    pagination.innerHTML = '';
    const totalPages = Math.max(1, Math.ceil(filtered.length / itemsPerPage));

    const makeBtn = (label, page, disabled=false, active=false) => {
      const b = document.createElement('button');
      b.className = `pagination-button ${disabled ? 'disabled' : ''} ${active ? 'active' : ''}`.trim();
      b.dataset.page = page;
      b.innerHTML = label;
      b.disabled = !!disabled;
      return b;
    };

    // toujours visibles
    pagination.appendChild(makeBtn('&laquo;', 1, currentPage === 1));
    pagination.appendChild(makeBtn('&lsaquo;', Math.max(1, currentPage - 1), currentPage === 1));

    for (let p = 1; p <= totalPages; p++) {
      pagination.appendChild(makeBtn(String(p), p, false, p === currentPage));
    }

    pagination.appendChild(makeBtn('&rsaquo;', Math.min(totalPages, currentPage + 1), currentPage === totalPages));
    pagination.appendChild(makeBtn('&raquo;', totalPages, currentPage === totalPages));
  };

  const render = () => {
    tbody.innerHTML = '';
    if(!filtered.length){
      const tr = document.createElement('tr');
      tr.innerHTML = `<td colspan="6" style="text-align:center;color:#888;">Aucune salle trouvée</td>`;
      tbody.appendChild(tr);
      renderPagination();
      return;
    }

    const totalPages = Math.max(1, Math.ceil(filtered.length / itemsPerPage));
    if(currentPage > totalPages) currentPage = totalPages;

    const start = (currentPage-1)*itemsPerPage;
    const pageRows = filtered.slice(start, start+itemsPerPage);
    pageRows.forEach(r => tbody.appendChild(buildRow(r)));

    renderPagination();
  };

  const applyFilters = () => {
    const loc = (filterLocalisation.value || '').toLowerCase();
    const cap = filterCapacite.value;
    const disp = (filterDisponibilite.value || '');

    filtered = rooms.filter(r => {
      if(loc && r.localisation.toLowerCase() !== loc) return false;

      if(cap){
        if(cap==='<=20' && !(r.capacite<=20)) return false;
        if(cap==='21-50' && !(r.capacite>=21 && r.capacite<=50)) return false;
        if(cap==='51-100' && !(r.capacite>=51 && r.capacite<=100)) return false;
        if(cap==='>100' && !(r.capacite>100)) return false;
      }

      if(disp && r.statut !== disp) return false;
      return true;
    });

    currentPage = 1;
    render();
  };

  const exportCsv = () => {
    const rows = filtered.length ? filtered : rooms;
    const headers = ['Nom salle','Localisation','Capacité','Statut'];
    const lines = [headers.join(';')];
    rows.forEach(r=>{
      lines.push([r.nom,r.localisation,r.capacite,r.statut].map(v=>`"${String(v).replace(/"/g,'""')}"`).join(';'));
    });
    const blob = new Blob([lines.join('\n')],{type:'text/csv;charset=utf-8;'});
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'salles.csv';
    document.body.appendChild(a);
    a.click(); a.remove();
  };

  // ==== Events ====
  flatpickr("#filterPeriode",{mode:"range",dateFormat:"d/m/Y",locale:"fr"});

  // open/close modal
  addBtn.addEventListener('click',()=>openModal(false,null));
  modal.addEventListener('click',(e)=>{ if(e.target===modal) closeModal(); });
  document.addEventListener('keydown',(e)=>{ if(e.key==='Escape') closeModal(); });

  // save (statique)
  saveBtn.addEventListener('click',()=>{
    const nom = inputNom.value.trim();
    const loc = inputLoc.value.trim();
    const cap = parseInt(inputCap.value || '0',10);
    const st  = inputStatut.value;

    if(!nom){ alert('Nom salle requis'); return; }
    if(!loc){ alert('Localisation requise'); return; }
    if(!cap || cap<1){ alert('Capacité invalide'); return; }

    if(editingId!=null){
      const idx = rooms.findIndex(r => r.id === editingId);
      if(idx>=0) rooms[idx] = { ...rooms[idx], nom, localisation:loc, capacite:cap, statut:st };
    }else{
      const newId = rooms.length ? Math.max(...rooms.map(r=>r.id))+1 : 1;
      rooms.push({ id:newId, nom, localisation:loc, capacite:cap, statut:st });
    }
    applyFilters();
    closeModal();
  });

  // actions + pagination
  document.body.addEventListener('click',(e)=>{
    // menu ⋯
    const btn = e.target.closest('.action-btn');
    if(btn){
      const wrap = btn.closest('.actions');
      const menu = wrap.querySelector('.dropdown-menu');
      const expanded = btn.getAttribute('aria-expanded')==='true';
      document.querySelectorAll('.dropdown-menu.show').forEach(m=>m.classList.remove('show'));
      document.querySelectorAll('.action-btn[aria-expanded="true"]').forEach(b=>b.setAttribute('aria-expanded','false'));
      menu.classList.toggle('show', !expanded);
      btn.setAttribute('aria-expanded', String(!expanded));
      e.preventDefault();
      return;
    }

    if(!e.target.closest('.actions') && !e.target.closest('.pagination-button')){
      document.querySelectorAll('.dropdown-menu.show').forEach(m=>m.classList.remove('show'));
      document.querySelectorAll('.action-btn[aria-expanded="true"]').forEach(b=>b.setAttribute('aria-expanded','false'));
    }

    const a = e.target.closest('.dropdown-menu a');
    if(a){
      e.preventDefault();
      const tr = a.closest('tr');
      const id = Number(tr?.dataset?.id);
      const idx = rooms.findIndex(r=>r.id===id);
      if(idx<0) return;

      if(a.classList.contains('btn-modifier')){
        openModal(true, id);
        return;
      }
      if(a.classList.contains('btn-reserver')){
        alert('Réservation enregistrée (démo statique).');
        return;
      }
      if(a.classList.contains('btn-disponible')){
        rooms[idx].statut = (rooms[idx].statut==='Disponible') ? 'Réservée' : 'Disponible';
        applyFilters();
        return;
      }
    }

    const pbtn = e.target.closest('.pagination-button');
    if(pbtn && !pbtn.disabled){
      const newPage = parseInt(pbtn.dataset.page,10);
      if(!Number.isNaN(newPage) && newPage!==currentPage){
        currentPage = newPage;
        render();
      }
    }
  });

  // Filtres & export
  [filterLocalisation, filterCapacite, filterDisponibilite].forEach(el=>el.addEventListener('change', applyFilters));
  document.querySelector('.filter-actions .icon-btn[title="Filter"]').addEventListener('click', applyFilters);
  exportBtn.addEventListener('click', exportCsv);

  // CheckAll
  checkAll.addEventListener('change', (e)=>{
    document.querySelectorAll('.row-check').forEach(ch => ch.checked = e.target.checked);
  });
  tbody.addEventListener('change', (e)=>{
    if(e.target.classList.contains('row-check') && !e.target.checked) checkAll.checked = false;
  });

  // Init
  flatpickr.localize(flatpickr.l10ns.fr);
  applyFilters();
})();
</script>
