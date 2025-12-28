<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <style>
        body {
            background-color: #f0f2f5;
            /* font-family: 'Segoe UI', sans-serif; */
            /* padding: 20px; */
        }

        .accordion-container {
            border-radius: 12px;
            /* overflow: hidden; */
            /* This line was causing the issue */
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #ddd;
        }

        .accordion-content {
            padding: 25px;
            background: #fff;
        }

        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 25px;
        }

        .filter-selectgb {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-box {
            display: flex;
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

        .date-input-container {
            display: flex;
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

        .filter-select {
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #d8d4b7;
            font-size: 14px;
            background: #fff;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg fill='%232d2a12' height='14' viewBox='0 0 24 24' width='14' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-position: right 10px center;
            background-repeat: no-repeat;
            background-size: 12px;
            padding-right: 30px;
            width: 200px;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn-statut {
            background-color: #BF0404;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
        }

        .icon-btn {
            width: 44px;
            height: 44px;
            background: #fff;
            border-radius: 12px;
            border: 1px solid #ddd;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #BF0404;
            font-size: 18px;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Segoe UI', sans-serif;
        }

        .styled-table thead {
            background-color: #f3f1e9;
        }

        .styled-table th,
        .styled-table td {
            padding: 14px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .styled-table tbody tr:hover {
            background-color: #f9f9f9;
        }

        .styled-table th {
            font-weight: 600;
            color: #333;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 20px;
        }

        .badge-success {
            color: #198754;
            background-color: #e6f7ee;
        }

        .badge-danger {
            color: #d71920;
            background-color: #fff0f0;
        }

        .badge-warning {
            color: #d89e00;
            background-color: #fff9e6;
        }

        .actions {
            position: relative;
            display: inline-block;
        }

        .action-btn {
            background-color: transparent;
            border: none;
            cursor: pointer;
            padding: 5px;
            width: 36px;
            height: 36px;
            font-size: 24px;
            font-weight: bolder;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 200px;
            background-color: #ffffff;
            border: 1px solid #d8d4b7;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 5px 0;
        }

        .dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            text-decoration: none;
            font-size: 14px;
            color: #2d2a12;
        }

        .dropdown-menu a:hover {
            background-color: #f5f5f5;
        }

        .dropdown-menu i {
            width: 16px;
            text-align: center;
        }

        .dataTables_wrapper .dataTables_paginate {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: 2px solid #c60000;
            color: #c60000 !important;
            padding: 8px 14px;
            border-radius: 8px;
            background: white !important;
            font-weight: bold;
            cursor: pointer;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
            border: 2px solid #c60000;
            color: #c60000 !important;
            padding: 8px 14px;
            border-radius: 8px;
            background: white !important;
            font-weight: bold;
            cursor: pointer;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            color: black !important;
            border: none;
        }

        /* ------------------------ */
        .btn-close-x {
            background: transparent;
            border: none;
            font-size: 20px;
            font-weight: bold;
            color: #fff;
            background-color: #c62828;
            border-radius: 5px;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-close-x:hover {
            background-color: #a02020;
        }

        .modal-overlay {
            position: fixed;
            top: 0px;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: flex-end;
            z-index: 999999;
        }

        .popup-container {
            background-color: white;
            width: 400px;
            height: 100%;
            padding: 20px 0px;
            box-shadow: -4px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            padding-top: 0px;
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
            margin-bottom: 20px;
            padding-left: 25px;
            padding-right: 25px;
            box-shadow: 0px 5px 16px #00000029;
            padding-top: 20px;
        }

        form.popup-form {
            padding-left: 25px;
            padding-right: 25px;
        }

        .popup-header h2,
        .popup-form h2 {
            font-size: 16px;
            margin: 0;
            color: #2A2916;
        }

        .btn-enregistrer {
            background-color: #c62828;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .popup-form .form-group {
            margin-bottom: 15px;
        }

        .popup-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .popup-form input,
        .popup-form select,
        .popup-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #b5af8e;
            border-radius: 7px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .popup-form .date-time-group {
            display: flex;
            gap: 10px;
        }

        .popup-form .date-time-group .form-group {
            flex: 1;
        }

        .popup-form .input-with-icon {
            position: relative;
        }

        .popup-form .input-with-icon input {
            padding-right: 30px;
        }

        .popup-form .input-with-icon img {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
        }

        .popup-form .input-with-icon i {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            color: #666;
        }

        .popup-form textarea {
            resize: vertical;
            min-height: 80px;
        }

        #reservationsTable {
            border: none !important;
            border-collapse: collapse;
            box-shadow: none !important;
        }

        #reservationsTable th {
            border: 0px solid #EBE9D7;
        }

        #reservationsTable td {
            border: 1px solid #EBE9D7;
        }

        #reservationsTable thead {
            border: none !important;
            position: static;
            transform: translateY(-15px);
        }

        #reservationsTable tbody tr:first-child td {
            border-top: 1px solid #EBE9D7 !important;
        }

        #reservationsTable {
            border-collapse: separate;
            border-spacing: 0;
        }

        #reservationsTable thead tr:first-child th:first-child {
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        #reservationsTable thead tr:first-child th:last-child {
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        #reservationsTable tbody tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }

        #reservationsTable tbody tr:last-child td:last-child {
            border-bottom-right-radius: 12px;
        }

        #reservationsTable tbody tr:first-child td:first-child {
            border-top-left-radius: 12px;
        }

        #reservationsTable tbody tr:first-child td:last-child {
            border-top-right-radius: 12px;
        }

        .reservations-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:16px;
  margin-bottom:16px; /* espace avant la zone de filtres */
}
.reservations-title{
  font-weight:700;
  font-size:20px;
  color:#2A2916;
  display:flex;
  align-items:center;
  gap:10px;
}
.reservations-title i{
  font-size:22px;
  color:#A6A485;
}


.reservations-header img {
    width: 28px;
    height: 28px;
}
    </style>
</head>

<body>

<?php
  // URL de la page "mon-labo" (adapter le slug si besoin)
  $mon_labo_url = esc_url( site_url('/mon-labo') );
?>

<div class="content-block">
  <div class="accordion-container">
    <div class="accordion-content">
      <!-- Reservations -->
      <div id="reservations-section">
        <div class="reservations-header">
  <h2 class="reservations-title">
<img src="/wp-content/plugins/plateforme-master/imagesED/7050930.png" alt="">
  Tableau des réservations
  </h2>
</div>

        <div class="table-controls">
          <div class="filter-selectgb">
            <div class="search-box">
              <i class="fa fa-search"></i>
              <input type="text" class="filter-input" id="reservationsSearch" placeholder="Recherchez...">
            </div>
            <select class="filter-select" id="statusFilter">
              <option value="">Statut</option>
              <option value="Validée">Validée</option>
              <option value="Refusée">Refusée</option>
              <option value="En attente">En attente</option>
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

        <table class="styled-table" id="reservationsTable">
          <thead>
            <tr>
              <th><input type="checkbox" id="checkAllReservations"></th>
              <th>Catégorie</th>
              <th>Nom</th>
              <th>Réservé par</th>
              <th>Date</th>
              <th>Heure</th>
              <th>Statut</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="checkbox"></td>
              <td>Salle</td>
              <td>Salle Réunion 1</td>
              <td>Dr. A. Mejri</td>
              <td>20/06/2025</td>
              <td>10:00 - 12:00</td>
              <td><span class="badge badge-success"><i class="fa-regular fa-circle-check" style="color: #198754;"></i>Validée</span></td>
              <td>
                <div class="actions">
                  <button class="action-btn">...</button>
                  <div class="dropdown-menu">
                    <a href="#" class="openModifierModal">Valider</a>
                    <a href="#">Rejeter</a>
                    <a href="#">Voir</a>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td><input type="checkbox"></td>
              <td>Équipement</td>
              <td>Microscope Électronique</td>
              <td>Y. Ben Salem</td>
              <td>15/07/2025</td>
              <td>13:30 - 14:00</td>
              <td><span class="badge badge-danger"><i class="fa-regular fa-circle-stop" style="color: #d71920;"></i>Refusée</span></td>
              <td>
                <div class="actions">
                  <button class="action-btn">...</button>
                  <div class="dropdown-menu">
                    <a href="#" class="openModifierModal">Modifier</a>
                    <a href="#">Voir</a>
                    <a href="#">Annuler</a>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td><input type="checkbox"></td>
              <td>Équipement</td>
              <td>Spectrophotomètre UV</td>
              <td>Dr. Leila Romdhane</td>
              <td>01/05/2025</td>
              <td>13:30 - 14:00</td>
              <td><span class="badge badge-warning"><i class="fa-regular fa-clock" style="color: #d89e00;"></i>En attente</span></td>
              <td>
                <div class="actions">
                  <button class="action-btn">...</button>
                  <div class="dropdown-menu">
                    <a href="#" class="openModifierModal">Modifier</a>
                    <a href="#">Voir</a>
                    <a href="#">Annuler</a>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal for Nouvelle Reservation -->
<div class="modal-overlay" id="modalObjectifs" style="display: none;">
  <div class="popup-container" id="popupContainerObjectifs">
    <div class="popup-header">
      <h2>Nouvelle réservation</h2>
      <button class="btn-enregistrer" id="btnSaveObjectifs">Enregistrer</button>
    </div>
    <form class="popup-form">
      <div class="form-group">
        <label for="nom-equipement">Nom De L'équipement/Salle</label>
        <select id="nom-equipement">
          <option>Spectrophotomètre UV</option>
          <option>Microscope X</option>
          <option>Salle Réunion 1</option>
        </select>
      </div>
      <div class="date-time-group">
        <div class="form-group">
          <label for="date-reservation">Date</label>
          <div class="input-with-icon">
            <input type="text" id="date-reservation" value="" placeholder="11/01/2025">
            <img width="20px" src="/wp-content/plugins/plateforme-master/images/icons/27) Icon-calendar.png" alt="Icon-calendar">
          </div>
        </div>
        <div class="form-group">
          <label for="heure-reservation">Heure De Début / Fin</label>
          <div class="input-with-icon">
            <input type="text" id="heure-reservation" placeholder="10:00 - 11:00">
            <i class="fa fa-clock"></i>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="objectif-reservation">Objectif / motif de la réservation</label>
        <textarea id="objectif-reservation" placeholder="Objectif"></textarea>
      </div>
    </form>
  </div>
</div>

<!-- MODAL FOR MODIFIER (EDIT) RESERVATION -->
<div class="modal-overlay" id="modalModifierReservation" style="display: none;">
  <div class="popup-container">
    <div class="popup-header">
      <h2>Modifier la Réservation</h2>
      <button id="btnSaveReservationEdit" type="button" class="btn-enregistrer">Enregistrer</button>
    </div>
    <form class="popup-form">
      <div class="form-group">
        <label for="modifier-nom-equipement">Nom De L'équipement/Salle</label>
        <input type="text" id="modifier-nom-equipement" readonly style="background-color: #f0f0f0;">
      </div>
      <div class="date-time-group">
        <div class="form-group">
          <label for="modifier-date-reservation">Date</label>
          <div class="input-with-icon">
            <input type="text" id="modifier-date-reservation" placeholder="jj/mm/aaaa">
            <img width="20" src="/wp-content/plugins/plateforme-master/images/icons/27) Icon-calendar.png" alt="Icon-calendar">
          </div>
        </div>
        <div class="form-group">
          <label for="modifier-heure-reservation">Heure De Début / Fin</label>
          <div class="input-with-icon">
            <input type="text" id="modifier-heure-reservation" placeholder="10:00 - 11:00">
            <i class="fa fa-clock"></i>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="modifier-objectif-reservation">Objectif / motif de la réservation</label>
        <textarea id="modifier-objectif-reservation" placeholder="Objectif"></textarea>
      </div>
    </form>
  </div>
</div>

    <!-- jQuery + DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable for the reservations table
            var reservationsTable = $('#reservationsTable').DataTable({
                paging: true,
                searching: true,
                ordering: false,
                info: false,
                pageLength: 5,
                dom: 't<"bottom"p>',
                language: {
                    paginate: {
                        previous: "<i class='fa fa-chevron-left'></i>",
                        next: "<i class='fa fa-chevron-right'></i>"
                    },
                    emptyTable: "Aucune donnée disponible",
                    zeroRecords: "Aucun enregistrement correspondant trouvé"
                },
                columnDefs: [{
                    "orderable": false,
                    "targets": [0, 7]
                }]
            });

            // Custom search for reservations table
            $('#reservationsSearch').on('keyup', function () {
                reservationsTable.search(this.value).draw();
            });

            /*************** CHARGEMENT PAR DÉFAUT — reservationsTable ***************/
function _inferTypeFromLabel(label='') {
  const s = String(label).toLowerCase();
  // heuristique simple : "salle", "amphi", "auditorium" => Salle, sinon Équipement
  return (s.startsWith('salle') || s.includes('amphi') || s.includes('auditorium')) ? 'Salle' : 'Équipement';
}
function _statusBadge(statut='') {
  const s = String(statut).toLowerCase();
  if (s === 'validee')  return `<span class="badge badge-success"><i class="fa-regular fa-circle-check"></i> Validée</span>`;
  if (s === 'refusee')  return `<span class="badge badge-danger"><i class="fa-regular fa-circle-stop"></i> Refusée</span>`;
  if (s === 'annulee')  return `<span class="badge badge-danger"><i class="fa-regular fa-circle-stop"></i> Annulée</span>`;
  return `<span class="badge badge-warning"><i class="fa-regular fa-clock"></i> En attente</span>`;
}
function _fmtDateFR(yyyy_mm_dd='') {
  if (!yyyy_mm_dd) return '—';
  const [y,m,d] = yyyy_mm_dd.split('-'); return `${d}/${m}/${y}`;
}
function _fmtRange(h1='', h2='') {
  const a = (h1||'').slice(0,5), b = (h2||'').slice(0,5);
  return (a && b) ? `${a} - ${b}` : '—';
}
async function _fetchReservationsDefault() {
  const url = `${REST_BASE}/plateforme-directeurderecherche/v1/reservation?mine=1&per_page=200`;
  return getJSON(url); // tu as déjà getJSON au-dessus
}

/* ====== Helpers d'affichage (réservations) ====== */
function frType(categorie) {
  return String(categorie);
}
function frStatut(s) {
  const v = String(s || '').toLowerCase();
  if (v === 'validee')  return 'Validée';
  if (v === 'refusee')  return 'Refusée';
  if (v === 'annulee')  return 'Annulée';
  return 'En attente';
}
function statusBadgeFR(s) {
  const lbl = frStatut(s);
  const v = String(s || '').toLowerCase();
  if (v === 'validee')  return `<span class="badge badge-success"><i class="fa-regular fa-circle-check"></i> ${lbl}</span>`;
  if (v === 'refusee')  return `<span class="badge badge-danger"><i class="fa-regular fa-circle-stop"></i> ${lbl}</span>`;
  if (v === 'annulee')  return `<span class="badge badge-danger"><i class="fa-regular fa-circle-stop"></i> ${lbl}</span>`;
  return `<span class="badge badge-warning"><i class="fa-regular fa-clock"></i> ${lbl}</span>`;
}
function fmtFRdate(d){ if(!d) return '—'; const [y,m,dd]=(d||'').split('-'); return `${dd}/${m}/${y}`; }
function fmtRange(h1,h2){ const a=(h1||'').slice(0,5), b=(h2||'').slice(0,5); return (a&&b)?`${a} - ${b}`:'—'; }
function isOwnerReservation(r){
  return Number(r.equip_owner_id) === Number(window.PMSettings?.userId || 0);
}
function isPendingReservation(r){
  return String(r.statut || '').toLowerCase() === 'en_attente';
}
function renderReservationActions(r){
  const id = escAttr(r.id);
  const ownerId = r.equip_owner_id;
  const isOwner = isOwnerReservation(r);
  const isPending = isPendingReservation(r);

  // Lien "Modifier" disponible pour tous (tu peux le restreindre si besoin)
  

  // Menu owner-only
  if (isOwner) {
    // Valider/Refuser uniquement si "en_attente"
    const disAttr = isPending ? '' : ' data-disabled="1"';
    items.unshift(
      `<a href="#" class="res-approve" data-id="${id}" data-owner="${ownerId}"${disAttr}><i class="fa fa-check"></i> Valider</a>`,
      `<a href="#" class="res-reject"  data-id="${id}" data-owner="${ownerId}"${disAttr}><i class="fa fa-ban"></i> Refuser</a>`
    );
    // Annuler : autorisé pour owner (même si validée), adapte si besoin
    items.push(
      `<a href="#" class="res-cancel"  data-id="${id}" data-owner="${ownerId}" style="color:#BF0404;"><i class="fa fa-times"></i> Annuler</a>`
    );
  }

  return `
    <div class="actions">
      <button class="action-btn" type="button" aria-haspopup="true" aria-expanded="false">...</button>
      <div class="dropdown-menu">
        ${items.join('')}
      </div>
    </div>
  `;
}

/* ====== CHARGEMENT PAR DÉFAUT — reservationsTable ====== */
async function reloadReservations() {
  try {
    // Mes réservations (créées par l'utilisateur connecté)
    const rows = await fetchReservations({ mine: 1, per_page: 200 });

    const dt = $('#reservationsTable').DataTable();
    dt.clear();

    (rows || []).forEach(r => {
      // Type : à partir de r.categorie (équipement/salle)
      const typeTxt = frType(r.equip_categorie_label);

      // Nom : utiliser les colonnes jointes (equip_nom / equip_modele), sinon fallback resource_label / id
      const nomTxt =
        (r.equip_nom ? `${r.equip_nom}${r.equip_modele ? ' – ' + r.equip_modele : ''}` :
         (r.resource_label && r.resource_label !== '0' ? r.resource_label : `#${r.resource_id}`));

      // Réservé par : first_name + last_name > display_name > #created_by
      const whoTxt =
        (r.reserver_first_name || r.reserver_last_name)
          ? `${r.reserver_first_name || ''} ${r.reserver_last_name || ''}`.trim()
          : (r.reserver_display_name || `#${r.created_by}`);

      // Date / Heure / Statut
      const dateTxt  = fmtFRdate(r.date_reservation);
      const heureTxt = fmtRange(r.heure_debut, r.heure_fin);
      const statutTd = statusBadgeFR(r.statut);

      dt.row.add([
        `<input type="checkbox" class="row-check" data-id="${r.id}">`,
        typeTxt,
        esc(nomTxt),
        esc(whoTxt),
        dateTxt,
        heureTxt,
        statutTd,
       renderReservationActions(r) // ← ICI le menu conditionnel
      ]);
    });

    dt.draw(false);

  } catch (e) {
    console.error('[reloadReservations]', e);
    // window.toast?.('Erreur chargement des réservations', true) || alert('Erreur chargement des réservations');
  }
}


/* Bind “Annuler” (statut → annulee) */
$(document)
  .off('click.res.cancelRow','a.res-cancel')
  .on('click.res.cancelRow','a.res-cancel', async function(e){
    e.preventDefault();
    const id = $(this).data('id');
    if (!id) return;
    if (!confirm('Annuler cette réservation ?')) return;
    try {
      await fetch(`${REST_BASE}/plateforme-directeurderecherche/v1/reservation/${id}`,{
        method:'PATCH',
        headers: HEADERS_JSON,
        credentials:'include',
        body: JSON.stringify({ statut:'annulee' })
      }).then(r=>{ if(!r.ok) throw new Error('PATCH '+r.status); });
      window.toast?.('Réservation annulée') || alert('Réservation annulée');
      reloadReservations();
    } catch(err) {
      console.error(err);
      window.toast?.('Échec annulation', true) || alert('Échec annulation');
    }
  });

    /* Charger par défaut au démarrage */
    reloadReservations();


            // Status filter for reservations table
            $('#statusFilter').on('change', function () {
                var status = $(this).val();
                if (status) {
                    // The search will look for the text inside the span
                    reservationsTable.column(6).search(status).draw();
                } else {
                    reservationsTable.column(6).search('').draw();
                }
            });

            // Date filter for reservations table (more flexible search)
            $('#dateFilter').on('keyup', function () {
                reservationsTable.column(4).search(this.value).draw();
            });


            // "Check all" functionality for reservations table
            $("#checkAllReservations").on("click", function () {
                var rows = reservationsTable.rows({
                    'search': 'applied'
                }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            // Handle individual checkbox clicks to uncheck "check all"
            $('#reservationsTable tbody').on('change', 'input[type="checkbox"]', function () {
                if (!this.checked) {
                    var el = $('#checkAllReservations').get(0);
                    if (el && el.checked && ('indeterminate' in el)) {
                        el.indeterminate = true;
                    }
                }
            });

            // Dropdown menu logic for both tables
            $(document).on('click', '.action-btn', function (e) {
                e.stopPropagation();
                let dropdown = $(this).closest('.actions').find('.dropdown-menu');
                $('.dropdown-menu').not(dropdown).hide(); // Hide other menus
                dropdown.toggle(); // Toggle current menu
            });

            // Close dropdowns when clicking outside
            $(document).on('click', function () {
                $('.dropdown-menu').hide();
            });

            // Open reservation modal
            $('#openReservationModal').on('click', function () {
                openmodalObjectifs();
            });

            // Open and populate Modifier Reservation modal
            
            // Generic modal close logic
            $('.modal-overlay').on('click', function (e) {
                if ($(e.target).is('.modal-overlay')) {
                    $(this).hide();
                }
            });
            $('.btn-close-x').on('click', function () {
                $(this).closest('.modal-overlay').hide();
            });

        });

        function openmodalObjectifs() {
            $('#modalObjectifs').css('display', 'flex');
        }

        function openModalModifierReservation() {
            $('#modalModifierReservation').css('display', 'flex');
        }
    </script>

<?php
$current_user = wp_get_current_user();
$roles = (array) $current_user->roles;
$role  = $roles[0] ?? '';
$user_id = get_current_user_id();

?>
<script>
  window.PMSettings = {
    restUrl: "<?= esc_url( rest_url() ) ?>",
    nonce: "<?= wp_create_nonce('wp_rest') ?>",
    role: "<?= esc_js( $role ) ?>",
    userId: <?= (int) $user_id ?>
  };
</script>

<script>
    // ========== CONFIG ==========
    const REST_BASE = (window.PMSettings?.restUrl || '/wp-json').replace(/\/$/, '');
    const HEADERS_JSON = {
      'X-WP-Nonce': window.PMSettings?.nonce || '',
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    };

    // ========== HELPERS ==========
    async function getJSON(url) {
      const res = await fetch(url, { headers: HEADERS_JSON, credentials:'include' });
      if (!res.ok) throw new Error(`${url} → ${res.status}`);
      return res.json();
    }
    function esc(s=''){ return String(s).replace(/[&<>"']/g, m=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;', "'":'&#39;' }[m])); }
    function escAttr(s=''){ return String(s).replace(/"/g,'&quot;'); }

    /************* API *************/
    async function fetchReservations(params = {}){
      const q = new URLSearchParams(params).toString();
      return getJSON(`${REST_BASE}/plateforme-directeurderecherche/v1/reservation?${q}`);
    }
    async function updateReservationStatus(id, statut){
      return fetch(`${REST_BASE}/plateforme-directeurderecherche/v1/reservation/${id}`,{
        method:'PATCH', headers: HEADERS_JSON, credentials:'include',
        body: JSON.stringify({ statut })
      }).then(r=>{ if(!r.ok) throw new Error('PATCH status '+r.status); return r.json(); });
    }
    async function cancelReservation(id){
      // soit endpoint cancel, soit PATCH statut
      return updateReservationStatus(id, 'annulee');
    }

    /************* RENDER LIST (ex: #reservationsTable déjà DataTables) *************/
    window.reloadReservations = async function(){
      try{
        // deux vues utiles :
        // 1) Mes réservations
        const mine = await fetchReservations({ mine:1, per_page:200 });
        // 2) Réservations sur MES équipements
        const owner = await fetchReservations({ owner:1, per_page:200 });

        // Ici, choisis ce que tu veux afficher dans #reservationsTable (mine, owner, ou fusion)
        const rows = mine; // ou [...owner, ...mine] avec déduplication sur id

        // Si tu utilises DataTables (comme dans ton code) :
        const dt = $('#reservationsTable').DataTable();
        dt.clear();

        (rows || []).forEach(r => {
          // Type : à partir de r.categorie (équipement/salle)

          // Nom : utiliser les colonnes jointes (equip_nom / equip_modele), sinon fallback resource_label / id
          const nomTxt =
            (r.equip_nom ? `${r.equip_nom}${r.equip_modele ? ' – ' + r.equip_modele : ''}` :
             (r.resource_label && r.resource_label !== '0' ? r.resource_label : `#${r.resource_id}`));

          // Réservé par : first_name + last_name > display_name > #created_by
          const whoTxt =
            (r.reserver_first_name || r.reserver_last_name)
              ? `${r.reserver_first_name || ''} ${r.reserver_last_name || ''}`.trim()
              : (r.reserver_display_name || `#${r.created_by}`);

          // Date / Heure / Statut
          const dateTxt  = fmtFRdate(r.date_reservation);
          const heureTxt = fmtRange(r.heure_debut, r.heure_fin);
          const statutTd = statusBadgeFR(r.statut);

          dt.row.add([
            `<input type="checkbox" class="row-check" data-id="${r.id}">`,
            r.equip_categorie_label,
            esc(nomTxt),
            esc(whoTxt),
            dateTxt,
            heureTxt,
            statutTd,

             renderReservationActions(r) // ← ICI le menu conditionnel

          ]);
        });
        dt.draw(false);

      }catch(e){
        console.error('[reloadReservations]', e);
        window.toast?.('Erreur chargement réservations', true) || alert('Erreur chargement réservations');
      }
    };

    // Bind actions statut dans le menu
    $(document)
      .off('click.res.appr','a.res-approve')
      .on('click.res.appr','a.res-approve', async function(e){ e.preventDefault();
        const id = $(this).data('id'); try { await updateReservationStatus(id,'validee'); reloadReservations?.(); } catch(err){ alert('Échec validation'); }
      })
      .off('click.res.rej','a.res-reject')
      .on('click.res.rej','a.res-reject', async function(e){ e.preventDefault();
        const id = $(this).data('id'); try { await updateReservationStatus(id,'refusee'); reloadReservations?.(); } catch(err){ alert('Échec refus'); }
      })
      .off('click.res.can','a.res-cancel')
      .on('click.res.can','a.res-cancel', async function(e){ e.preventDefault();
        const id = $(this).data('id'); if(!confirm('Annuler cette réservation ?')) return;
        try { await cancelReservation(id); reloadReservations?.(); } catch(err){ alert('Échec annulation'); }
      });

    // Ouvrir / fermer le menu "..."
    $(document)
      .off('click.res.menu', '.actions .action-btn')
      .on('click.res.menu', '.actions .action-btn', function(e){
        e.stopPropagation();
        const $menu = $(this).siblings('.dropdown-menu');
        $('.dropdown-menu').not($menu).hide();
        $menu.toggle();
      });
    $(document).off('click.res.closemenus').on('click.res.closemenus', function(){ $('.dropdown-menu').hide(); });

    // Garde-fou owner + désactivation visuelle
    function guardOwner($a){
      const ownerId = Number($a.data('owner') || 0);
      const me      = Number(window.PMSettings?.userId || 0);
      if (ownerId !== me) {
        window.toast?.("Action réservée au propriétaire de l'équipement", true) || alert("Action non autorisée");
        return false;
      }
      if ($a.is('[data-disabled]')) {
      //window.toast?.("Action indisponible pour ce statut", true) || alert("Action indisponible");
      //return false;
      }
      return true;
    }

    // Valider
    $(document)
      .off('click.res.approve', 'a.res-approve')
      .on('click.res.approve', 'a.res-approve', async function(e){
        e.preventDefault();
        const $a = $(this);
        if (!guardOwner($a)) return;
        const id = $a.data('id');
        try {
          await updateReservationStatus(id, 'validee');
          window.reloadReservations?.();
        } catch(err) {
          console.error(err);
          alert('Échec validation');
        }
      });

    // Refuser
    $(document)
      .off('click.res.reject', 'a.res-reject')
      .on('click.res.reject', 'a.res-reject', async function(e){
        e.preventDefault();
        const $a = $(this);
        if (!guardOwner($a)) return;
        const id = $a.data('id');
        try {
          await updateReservationStatus(id, 'refusee');
          window.reloadReservations?.();
        } catch(err) {
          console.error(err);
          alert('Échec refus');
        }
      });

    // Annuler
    $(document)
      .off('click.res.cancel', 'a.res-cancel')
      .on('click.res.cancel', 'a.res-cancel', async function(e){
        e.preventDefault();
        const $a = $(this);
        if (!guardOwner($a)) return;
        const id = $a.data('id');
        if (!confirm('Annuler cette réservation ?')) return;
        try {
          await cancelReservation(id); // utilise ta fonction existante
          window.reloadReservations?.();
        } catch(err) {
          console.error(err);
          alert('Échec annulation');
        }
      });

    // Remplit le <select id="nom-equipement"> avec tous les équipements
    function fillNomEquipementSelect(sel, items) {
      if (!sel) return;
      sel.innerHTML = '';
      sel.append(new Option('Sélectionnez un équipement', ''));
      (items || []).forEach(e => {
        const label = `${e.nom_appareil || '—'}${e.modele ? ' – ' + e.modele : ''}`;
        const opt = new Option(label, e.id);
        sel.append(opt);
      });
    }

    // Charge TOUS les équipements (sans filtre user)
    async function loadAllEquipementsIntoSelect() {
      const base = (window.PMSettings?.restUrl || '/wp-json').replace(/\/$/, '');
      const url  = `${base}/plateforme-directeurderecherche/v1/equipement?all=1&per_page=1000`;
      const sel  = document.getElementById('nom-equipement');

      try {
        // si tu as déjà wpFetchJson(); sinon remplace par fetch(...).then(r=>r.json())
        const items = await getJSON(url);
        fillNomEquipementSelect(sel, items);
      } catch (e) {
        console.error('[loadAllEquipementsIntoSelect]', e);
        if (sel) {
          sel.innerHTML = '';
          sel.append(new Option('Erreur de chargement', ''));
        }
        window.toast?.('Erreur de chargement des équipements', true);
      }
    }

    // Juste charger la liste au chargement de la page
    document.addEventListener('DOMContentLoaded', () => {
      loadAllEquipementsIntoSelect();
    });

    function frToApiDate(s){ // "dd/mm/yyyy" -> "yyyy-mm-dd"
      if(!s) return '';
      const [dd,mm,yyyy] = s.split('/'); 
      return (yyyy && mm && dd) ? `${yyyy}-${mm}-${dd}` : '';
    }
    function apiToFrDate(s){ // "yyyy-mm-dd" -> "dd/mm/yyyy"
      if(!s) return '';
      const [yyyy,mm,dd] = s.split('-'); 
      return (yyyy && mm && dd) ? `${dd}/${mm}/${yyyy}` : s;
    }
    // Déjà dans ton code ? alors ne le duplique pas :
    function parseHoraireRange(str){
      if (!str) return { start:'', end:'' };
      const m = str.split('-').map(s=>s.trim());
      const hhmm = s => (s && s.length===5 ? s+':00' : s);
      return { start: hhmm(m[0]||''), end: hhmm(m[1]||'') };
    }


    /* ---------- API helper: GET reservation by id ---------- */
    async function fetchReservationById(id){
      return getJSON(`${REST_BASE}/plateforme-directeurderecherche/v1/reservation/${encodeURIComponent(id)}`);
    }

    /* ---------- Ouvrir + préremplir la modale d’édition ---------- */
    async function openReservationEditModal(id){
      try{
        const r  = await fetchReservationById(id);
        const $m = $('#modalModifierReservation').data('res-id', id);

        // Nom affiché (joint ou fallback)
        const nom = r.equip_nom
          ? `${r.equip_nom}${r.equip_modele ? ' – ' + r.equip_modele : ''}`
          : (r.resource_label && r.resource_label !== '0' ? r.resource_label : `#${r.resource_id}`);

        $('#modifier-nom-equipement').val(nom);
        $('#modifier-date-reservation').val(apiToFrDate(r.date_reservation));
        $('#modifier-heure-reservation').val(`${(r.heure_debut||'').slice(0,5)} - ${(r.heure_fin||'').slice(0,5)}`);
        $('#modifier-objectif-reservation').val(r.objectif || '');

        // Ouvre avec ta fonction existante (cohérence UI)
        openModalModifierReservation();
      }catch(e){
        console.error('[openReservationEditModal]', e);
        window.toast?.('Impossible de charger la réservation', true) || alert('Impossible de charger la réservation');
      }
    }

    /* ---------- Binding propre pour le lien "Modifier" du tableau ---------- */
    $(document)
      .off('click.resOpenEdit', '.openModifierModal')
      .on('click.resOpenEdit', '.openModifierModal', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();      // évite les anciens handlers
        const id = $(this).data('id');
        if (id) openReservationEditModal(id);
      });

    /* ---------- Sauvegarde (PATCH) ---------- */
    async function updateReservationFromEditModal(){
      const $m   = $('#modalModifierReservation');
      const id   = $m.data('res-id');
      if (!id) return;

      const dateFR = ($('#modifier-date-reservation').val()||'').trim();
      const heures = ($('#modifier-heure-reservation').val()||'').trim();
      const obj    = ($('#modifier-objectif-reservation').val()||'').trim();

      const dateAPI      = frToApiDate(dateFR);
      const {start, end} = parseHoraireRange(heures);

      if (!dateAPI || !start || !end) {
        window.toast?.('Date et heures sont obligatoires', true) || alert('Date et heures sont obligatoires');
        return;
      }

      const $btn = $('#btnSaveReservationEdit');
      const txt  = $btn.text();
      $btn.prop('disabled', true).text('Enregistrement…');

      try{
        const res = await fetch(`${REST_BASE}/plateforme-directeurderecherche/v1/reservation/${encodeURIComponent(id)}`, {
          method: 'PATCH',
          headers: HEADERS_JSON,
          credentials: 'include',
          body: JSON.stringify({
            date_reservation: dateAPI,
            heure_debut:      start,
            heure_fin:        end,
            objectif:         obj
          })
        });

        if (!res.ok) {
          const msg = await res.text().catch(()=>res.statusText);
          if (res.status === 409) throw new Error('CONFLICT');   // slot déjà pris
          throw new Error(msg || ('HTTP '+res.status));
        }

        window.toast?.('Réservation mise à jour') || alert('Réservation mise à jour');
        $m.hide();
        window.reloadReservations?.();
      }catch(e){
        if (String(e.message).includes('CONFLICT')) {
          window.toast?.('Créneau non disponible pour cet équipement', true) || alert('Créneau non disponible');
        } else {
          console.error('[updateReservationFromEditModal]', e);
          window.toast?.('Échec mise à jour', true) || alert('Échec mise à jour');
        }
      }finally{
        $btn.prop('disabled', false).text(txt);
      }
    }

    /* ---------- Bouton Enregistrer (modale edit) ---------- */
    $(document)
      .off('click.resSaveEdit', '#btnSaveReservationEdit')
      .on('click.resSaveEdit', '#btnSaveReservationEdit', function(e){
        e.preventDefault(); e.stopPropagation();
        updateReservationFromEditModal();
      });

    /************* UI — MODALE “Nouvelle réservation” *************/
    async function saveReservationFromModal(){
      const $modal = $('#modalObjectifs');
      // IMPORTANT : la valeur du select "nom-equipement" doit contenir l'ID en value
      const rid    = parseInt($('#nom-equipement').val() || 0, 10);
      const rlabel = $('#nom-equipement option:selected').text().trim();
      const date   = ($('#date-reservation').val()||'').split('/').reverse().join('-'); // 11/01/2025 -> 2025-01-11
      const h      = parseHoraireRange($('#heure-reservation').val());
      const obj    = ($('#objectif-reservation').val()||'').trim();

      if (!rid || !date || !h.start || !h.end) {
        window.toast?.('Veuillez remplir tous les champs obligatoires', true) || alert('Champs manquants');
        return;
      }

      const payload = {
        resource_id: rid,
        resource_label: rlabel,
        date_reservation: date,
        heure_debut: h.start,
        heure_fin: h.end,
        objectif: obj
      };

      const $btn = $('#btnSaveObjectifs'); const txt = $btn.text();
      $btn.prop('disabled', true).text('Enregistrement…');

      try {
        await postJSON('/plateforme-directeurderecherche/v1/reservation', payload);
        window.toast?.('Réservation enregistrée', false) || alert('Réservation enregistrée');
        $modal.hide();
        // rafraîchis ta table des réservations si tu as une fonction
        if (typeof window.reloadReservations === 'function') reloadReservations();
      } catch(e){
        console.error('[createReservation]', e);
        if (String(e).includes('409')) {
          window.toast?.('Créneau non disponible', true) || alert('Créneau non disponible');
        } else {
          window.toast?.('Échec enregistrement', true) || alert('Échec enregistrement');
        }
      } finally {
        $btn.prop('disabled', false).text(txt);
      }
    }

    // bind bouton Enregistrer de la modale
    $('#btnSaveObjectifs').off('click.resv').on('click.resv', function(e){
      e.preventDefault(); saveReservationFromModal();
    });

    async function postJSON(endpoint, data) {
      const res = await fetch(`${REST_BASE}${endpoint}`, {
        method: 'POST',
        headers: HEADERS_JSON,
        credentials: 'include',
        body: JSON.stringify(data)
      });
      if (!res.ok) {
        const msg = await res.text().catch(()=>res.statusText);
        throw new Error(`POST ${endpoint} → ${res.status} ${msg}`);
      }
      return res.json();
    }

</script>

</body>

</html>