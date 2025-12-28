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


        .accordion-tabs {
            display: flex;
            background: #f3f3f3;
        }

        .tab-btn {
            flex: 1;
            padding: 15px 20px;
            font-weight: 600;
            border: none;
            background: #A6A485;
            cursor: pointer;
            font-size: 18px;
            color: #fff;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .tab-btn:not(:last-child) {
            border-right: 1px solid #ddd;
            margin-right: 10px;
        }

        .tab-btn.active {
            background-color: #fff;
            color: #2A2916;
        }

        .tab-btn img {
            width: 28px;
            height: 28px;
        }

        .accordion-content {
            padding: 25px;
            background: #fff;
        }


        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
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
            border: 1px solid #e0e0e0;
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

        .filter-buttons {
            display: flex;
            border: 1px solid #d8d4b7;
            border-radius: 5px;
            overflow: hidden;
            padding: 3px 5px;
        }

        .filter-btn {
            padding: 5px 25px;
            border: none;
            background: transparent;
            color: #2d2a12;
            font-weight: 500;
            cursor: pointer;
            /* border-right: 1px solid #d8d4b7; */
        }

        .filter-btn:last-child {
            border-right: none;
        }

        .filter-btn.active {
            background-color: #b2ae90;
            color: #fff;
            border-radius: 3px;
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

        .status-icon {
            font-size: 20px;
            text-align: center;
        }

        .status-icon.fa-exclamation-triangle {
            color: #d89e00;
        }

        .status-icon.fa-check-circle {
            color: #198754;
        }

        .status-icon.fa-times-circle {
            color: #d71920;
        }

        .details-icon {
            font-size: 20px;
            color: #555;
            cursor: pointer;
        }

        .actions {
            position: relative;
            display: inline-block;
        }

        .action-btn {
            background-color: transparent;
            border: none;
            /* font-size: 20px; */
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
            /* color: #A6A485; */
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

        .details-modal-content {
            padding: 25px;
        }

        .details-modal-content .detail-item {
            margin-bottom: 15px;
            border-bottom: 1px solid #6e6d5533;
        }

        .details-modal-content .detail-item label {
            font-weight: 600;
            color: #A6A485;
            display: block;
            font-size: 14px;
        }

        .details-modal-content .detail-item p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #2A2916;
            font-weight: 500;
        }

        .details-modal-content .file-download-link {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 6px;
        }

        .details-modal-content .file-download-link span {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .details-modal-content .file-download-link i {
            color: #c62828;
        }

        .details-modal-content .download-icon {
            color: #c62828;
            border: 1px solid #00000030;
            padding: 5px 8px;
            border-radius: 5px;
            cursor: pointer;
        }

        .details-modal-footer {
            text-align: center;
            padding: 20px 25px 5px;
            box-shadow: 0px -8px 16px #00000029;
        }

        .btn-history {
            background-color: #fff;
            color: #c62828;
            border: 1px solid #c62828;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .maintenance-history-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .maintenance-history-item:last-child {
            border-bottom: none;
        }

        .maintenance-history-item .btn-download-report {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .input-file-wrapper {
            position: relative;
        }

        .btn-importer {
            border-radius: 0px 7px 7px 0px;
            position: absolute;
            right: 15px;
            bottom: 0px;
            transform: translate(15px, 5px);
            background-color: #A6A485;
            padding: 9px;
            color: white;
        }

        #equipementsTable,
        #reservationsTable {
            border: none !important;
            border-collapse: collapse;
            box-shadow: none !important;
        }

        #equipementsTable th,
        #reservationsTable th {
            border: 0px solid #EBE9D7;
        }

        #equipementsTable td,
        #reservationsTable td {
            border: 1px solid #EBE9D7;
        }

        #equipementsTable thead,
        #reservationsTable thead {
            border: none !important;
            position: static;
            transform: translateY(-15px);
        }

        #equipementsTable tbody tr:first-child td,
        #reservationsTable tbody tr:first-child td {
            border-top: 1px solid #EBE9D7 !important;
        }

        #equipementsTable,
        #reservationsTable {
            border-collapse: separate;
            border-spacing: 0;
        }

        #equipementsTable thead tr:first-child th:first-child,
        #reservationsTable thead tr:first-child th:first-child {
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
        }

        #equipementsTable thead tr:first-child th:last-child,
        #reservationsTable thead tr:first-child th:last-child {
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        #equipementsTable tbody tr:last-child td:first-child,
        #reservationsTable tbody tr:last-child td:first-child {
            border-bottom-left-radius: 12px;
        }

        #equipementsTable tbody tr:last-child td:last-child,
        #reservationsTable tbody tr:last-child td:last-child {
            border-bottom-right-radius: 12px;
        }

        #equipementsTable tbody tr:first-child td:first-child,
        #reservationsTable tbody tr:first-child td:first-child {
            border-top-left-radius: 12px;
        }

        #equipementsTable tbody tr:first-child td:last-child,
        #reservationsTable tbody tr:first-child td:last-child {
            border-top-right-radius: 12px;
        }

        button.btn-view-photo {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        .equipments-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
            /* léger espace avant la zone de recherche/filtres */
        }

        .equipments-title {
            font-weight: 700;
            font-size: 20px;
            color: #2A2916;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .equipments-title i {
            font-size: 22px;
            color: #A6A485;
        }

        /* Bouton dans l’entête (même style que le tien) */
        .btn-statut.header {
            margin: 0;
            /* pas d’espace supplémentaire */
            align-self: center;
            /* bien centré verticalement */
        }

        .equipments-header img {
            width: 28px;
            height: 28px;
        }

        .filter-inputs {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .input-with-icon {
            position: relative;
        }

        .filter-bar .filter-input {
            width: 220px;
        }

        .filter-bar .filter-input,
        .filter-bar .filter-select {
            border-radius: 6px;
            padding: 0.6rem 0.75rem;
            background-color: #fdfdfd;
            font-size: 14px;
            height: 42px;
            box-sizing: border-box;
            transition: border-color 0.2s;
            min-width: 180px;
            border: 1px solid #e0e0e0;
        }

        .input-with-icon .right-icon {
            right: 0.85rem;
        }

        .input-with-icon .icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            pointer-events: none;
            font-size: 14px;
        }
    </style>
</head>

<body>

<?php
  // URL de la page "mon-labo" (adapter le slug si besoin)
  $mon_labo_url = esc_url( site_url('/uscr_details_plateforme') );
?>

<div class="content-block">
  <div class="accordion-container">
    <div class="accordion-content">
      <!-- Equipments -->
      <div id="equipments-section">

        <!-- Header au-dessus des filtres -->
        <div class="equipments-header">
          <h2 class="equipments-title">
            <img src="/wp-content/plugins/plateforme-master/imagesED/10550857.png" alt="">
            Liste des plateforms
          </h2>

          <?php if (!in_array('um_chercheur', $roles)) { ?>
            <button class="btn-statut header" id="openEquipementModal">Ajouter plateforme</button>
          <?php } ?>
        </div>

        <!-- (la zone de filtres reste telle quelle, sans le bouton Ajouter équipement) -->
        <div class="table-controls">
          <div class="filter-inputs">
            <div class="input-with-icon">
              <input class="filter-input" type="text" placeholder="Recherchez...">
              <i class="fas fa-search icon right-icon search-field"></i>
            </div>
            <div class="input-with-icon">
              <!-- Added ID 'gradeFilter' -->
              <select class="filter-select" id="gradeFilter">
                <option value="">Etablissement</option>
                <option></option>
                <option></option>
                <option></option>
                <option></option>
              </select>
              <i class="fas fa-chevron-down icon right-icon"></i>
            </div>
          </div>
          <div class="filter-actions">
            <!-- SUPPRIMER ICI l'ancien bouton Ajouter équipement -->
            <button class="icon-btn">
              <img width="20px" src="/wp-content/plugins/plateforme-master/images/icons/27) Icon-funnel.png" alt="Icon-funnel">
            </button>
            <button class="icon-btn">
              <img width="20px" src="/wp-content/plugins/plateforme-master/images/icons/Groupe 152.png" alt="upload-red.png">
            </button>
          </div>
        </div>

        <!--
          NOTE IMPORTANTE :
          On ajoute l'attribut data-details-url pour donner au JS l'URL de base de redirection.
          Le JS remplacera l'ancienne icône (qui ouvrait une modale) par un lien <a> vers mon-labo.
        -->
        <table class="styled-table" id="equipementsTable" data-details-url="<?= $mon_labo_url ?>">
          <thead>
            <tr>
              <th><input type="checkbox" id="checkAllEquipements"></th>
              <th>Nom</th>
              <th>Etablissement</th>
              <th>Unités</th>
              <th>Date de création</th>
              <th>Details</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="checkbox" class="row-check" data-id="1"></td>
              <td>Plateforme A</td>
              <td>Université X</td>
              <td>5</td>
              <td>2023-01-15</td>
              <td>
                <a class="details-icon" href="<?= $mon_labo_url ?>?equipement_id=1" title="Voir les détails">
                  <i class="fa fa-eye"></i>
                </a>
              </td>
              <td>
                <div class="actions">
                  <button class="action-btn" type="button" aria-haspopup="true" aria-expanded="false">...</button>
                  <div class="dropdown-menu">
                    <a href="#" class="openModifierEquipementModal" data-id="1"><i class="fa fa-edit"></i> Modifier</a>
                    <a href="#" class="action-supprimer" data-id="1" style="color:#BF0404;"><i class="fa fa-trash"></i> Supprimer</a>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td><input type="checkbox" class="row-check" data-id="2"></td>
              <td>Plateforme B</td>
              <td>Université Y</td>
              <td>3</td>
              <td>2022-06-10</td>
              <td>
                <a class="details-icon" href="<?= $mon_labo_url ?>?equipement_id=2" title="Voir les détails">
                  <i class="fa fa-eye"></i>
                </a>
              </td>
              <td>
                <div class="actions">
                  <button class="action-btn" type="button" aria-haspopup="true" aria-expanded="false">...</button>
                  <div class="dropdown-menu">
                    <a href="#" class="openModifierEquipementModal" data-id="2"><i class="fa fa-edit"></i> Modifier</a>
                    <a href="#" class="action-supprimer" data-id="2" style="color:#BF0404;"><i class="fa fa-trash"></i> Supprimer</a>
                  </div>
                </div>
              </td>
            </tr>
            <tr>
              <td><input type="checkbox" class="row-check" data-id="3"></td>
              <td>Plateforme C</td>
              <td>Université Z</td>
              <td>7</td>
              <td>2024-03-20</td>
              <td>
                <a class="details-icon" href="<?= $mon_labo_url ?>?equipement_id=3" title="Voir les détails">
                  <i class="fa fa-eye"></i>
                </a>
              </td>
              <td>
                <div class="actions">
                  <button class="action-btn" type="button" aria-haspopup="true" aria-expanded="false">...</button>
                  <div class="dropdown-menu">
                    <a href="#" class="openModifierEquipementModal" data-id="3"><i class="fa fa-edit"></i> Modifier</a>
                    <a href="#" class="action-supprimer" data-id="3" style="color:#BF0404;"><i class="fa fa-trash"></i> Supprimer</a>
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

<!-- MODAL FOR AJOUTER EQUIPEMENT -->
<div class="modal-overlay" id="modalAjouterEquipement" style="display: none;">
  <div class="popup-container" id="popupContainerEquipement">
    <div class="popup-header">
      <h2>Ajouter appareil</h2>
      <button class="btn-enregistrer" id="AddAppareil">Enregistrer</button>
    </div>
    <form class="popup-form">
      <div class="form-group">
        <label>Catégorie Des Equipements</label>
        <select id="categorie_id" name="categorie_id">
          <option value="">Catégorie Des Equipements</option>
        </select>
      </div>
      <div class="form-group">
        <label>Nom de l'appareil</label>
        <input type="text" placeholder="Nom de l'appareil">
      </div>
      <div class="form-group">
        <label>Lieu</label>
        <input type="text" id="lieu" name="lieu" placeholder="Lieu de l'appareil">
      </div>
      <div class="form-group">
        <label>Images</label>
        <div class="input-file-wrapper">
          <input type="text" class="input-file-text" placeholder="Importer des images..." readonly>
          <label class="btn-importer"><i class="fa fa-upload"></i> Importer <input type="file" multiple accept="image/*" style="display: none;"></label>
        </div>
      </div>
      <div class="form-group">
        <label>Modèle</label>
        <input type="text" placeholder="Modèle">
      </div>
      <div class="form-group">
        <label>Spécification technique</label>
        <textarea></textarea>
      </div>
      <div class="form-group">
        <label>Statut</label>
        <select id="statut" name="statut">
          <option value="">Statut</option>
          <option value="fonctionnel">Fonctionnel</option>
          <option value="en_panne">En panne</option>
          <option value="en_maintenance">En maintenance</option>
          <option value="hors_service">Hors service</option>
        </select>
      </div>
      <div class="form-group">
        <label>Disponibilité</label>
        <select id="disponibilite_id" name="disponibilite_id">
          <option value="">Disponibilité</option>
        </select>
      </div>
      <div class="form-group">
        <label>Protocole d'utilisation</label>
        <div class="input-file-wrapper">
          <input type="text" class="input-file-text" placeholder="Protocole d'utilisation" readonly>
          <label class="btn-importer"><i class="fa fa-upload"></i> Importer <input type="file" style="display: none;"></label>
        </div>
      </div>
      <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
      <div class="form-group">
        <label style="font-weight: bold; color: #333;">Conditions d'entretien :</label>
      </div>
      <div class="form-group">
        <label>Contrat</label>
        <div class="input-file-wrapper">
          <input type="text" class="input-file-text" placeholder="Contrat" readonly>
          <label class="btn-importer"><i class="fa fa-upload"></i> Importer <input type="file" style="display: none;"></label>
        </div>
      </div>
      <div class="form-group">
        <label>Périodicité</label>
        <select id="periodicite" name="periodicite">
          <option value="">Périodicité</option>
          <option value="mensuelle">Mensuelle</option>
          <option value="trimestrielle">Trimestrielle</option>
          <option value="semestrielle">Semestrielle</option>
          <option value="annuelle">Annuelle</option>
          <option value="a_la_demande">À la demande</option>
        </select>
      </div>
      <div class="form-group">
        <label>Consignes</label>
        <input type="text" placeholder="Consignes">
      </div>
    </form>
  </div>
</div>

<!-- MODAL FOR MODIFIER (EDIT) EQUIPEMENT -->
<div class="modal-overlay" id="modalModifierEquipement" style="display: none;">
  <div class="popup-container" id="popupContainerModifierEquipement">
    <div class="popup-header">
      <h2>Modifier l'équipement</h2>
      <button class="btn-enregistrer">Enregistrer</button>
    </div>
    <form class="popup-form">
      <div class="form-group">
        <label for="modifier-categorie-equipement">Catégorie Des Equipements</label>
        <select id="modifier-categorie-equipement"></select>
      </div>
      <div class="form-group">
        <label for="modifier-nom-appareil">Nom de l'appareil</label>
        <input type="text" id="modifier-nom-appareil" placeholder="Nom de l'appareil">
      </div>
      <div class="form-group">
        <label for="modifier-lieu">Lieu</label>
        <input type="text" id="modifier-lieu" placeholder="Lieu de l'appareil">
      </div>
      <div class="form-group">
        <label>Images</label>
        <div class="input-file-wrapper">
          <input type="text" class="input-file-text" placeholder="Importer des images..." readonly>
          <label class="btn-importer"><i class="fa fa-upload"></i> Importer <input type="file" multiple accept="image/*" style="display: none;"></label>
        </div>
        <div class="existing-images" style="margin-top: 10px; display: flex; flex-wrap: wrap; gap: 10px;"></div>
      </div>
      <div class="form-group">
        <label for="modifier-modele-appareil">Modèle</label>
        <input type="text" id="modifier-modele-appareil" placeholder="Modèle">
      </div>
      <div class="form-group">
        <label for="modifier-spec-tech">Spécification technique</label>
        <textarea id="modifier-spec-tech"></textarea>
      </div>
      <div class="form-group">
        <label for="modifier-statut-appareil">Statut</label>
        <select id="modifier-statut-appareil">
          <option value="">Statut</option>
          <option value="fonctionnel">Fonctionnel</option>
          <option value="en_panne">En panne</option>
          <option value="en_maintenance">En maintenance</option>
          <option value="hors_service">Hors service</option>
        </select>
      </div>
      <div class="form-group">
        <label for="modifier-disponibilite-appareil">Disponibilité</label>
        <select id="modifier-disponibilite-appareil"></select>
      </div>
      <div class="form-group">
        <label>Protocole d'utilisation</label>
        <div class="input-file-wrapper">
          <input type="text" class="input-file-text" placeholder="Protocole d'utilisation" readonly>
          <label class="btn-importer"><i class="fa fa-upload"></i> Importer <input type="file" style="display: none;"></label>
        </div>
      </div>
      <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
      <div class="form-group">
        <label style="font-weight: bold; color: #333;">Conditions d'entretien :</label>
      </div>
      <div class="form-group">
        <label>Contrat</label>
        <div class="input-file-wrapper">
          <input type="text" class="input-file-text" placeholder="Contrat" readonly>
          <label class="btn-importer"><i class="fa fa-upload"></i> Importer <input type="file" style="display: none;"></label>
        </div>
      </div>
      <div class="form-group">
        <label for="modifier-periodicite">Périodicité</label>
        <select id="modifier-periodicite">
          <option value="">Périodicité</option>
          <option value="mensuelle">Mensuelle</option>
          <option value="trimestrielle">Trimestrielle</option>
          <option value="semestrielle">Semestrielle</option>
          <option value="annuelle">Annuelle</option>
          <option value="a_la_demande">À la demande</option>
        </select>
      </div>
      <div class="form-group">
        <label for="modifier-consignes">Consignes</label>
        <input type="text" id="modifier-consignes" placeholder="Consignes">
      </div>
    </form>
  </div>
</div>

<!-- MODAL FOR MAINTENANCE -->
<div class="modal-overlay" id="modalMaintenance" style="display: none;">
  <div class="popup-container" id="popupContainerMaintenance">
    <div class="popup-header">
      <h2>Demande de maintenance</h2>
      <button class="btn-enregistrer">Enregistrer</button>
    </div>
    <form class="popup-form">
      <div class="form-group">
        <label>Équipement</label>
        <input type="text" placeholder="Spectrophotomètre UV" readonly style="background-color: #f0f0f0;">
      </div>
      <div class="form-group">
        <label>Type De Maintenance</label>
        <select>
          <option>Type</option>
        </select>
      </div>
      <div class="form-group">
        <label>Motif</label>
        <textarea></textarea>
      </div>
      <div class="form-group">
        <label>Motif</label>
        <div class="input-file-wrapper">
          <input type="text" class="input-file-text" placeholder="Fiche attaché" readonly>
          <label class="btn-importer"><i class="fa fa-upload"></i> Importer <input type="file" style="display: none;"></label>
        </div>
      </div>
      <div class="form-group">
        <label>Photo de l'équipement</label>
        <div class="input-file-wrapper">
          <input type="text" class="input-file-text" placeholder="Importer les photos..." readonly>
          <label class="btn-importer"><i class="fa fa-upload"></i> Importer <input type="file" style="display: none;"></label>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- MODAL FOR DETAILS APPAREIL -->
<div class="modal-overlay" id="modalDetailsAppareil" style="display: none;">
  <div class="popup-container" id="popupContainerDetailsAppareil">
    <div class="popup-header">
      <h2>Details appareil</h2>
      <button class="btn-close-x">X</button>
    </div>
    <div class="details-modal-content">
      <div class="detail-item">
        <label>Categorie :</label>
      </div>
      <div class="detail-item">
        <label>Nom de l'appareil :</label>
      </div>
      <div class="detail-item">
        <label>Modèle :</label>
      </div>
      <div class="detail-item">
        <label>Spécification technique :</label>
      </div>
      <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
      <div class="detail-item">
        <label>Conditions d'entretien :</label>
      </div>
      <div class="detail-item">
        <label>Statut :</label>
      </div>
      <div class="detail-item">
        <label>Disponibilité :</label>
      </div>
      <div class="detail-item">
        <label>Protocole d'utilisation :</label>
        <div class="file-download-link">
          <span><i class="fa fa-file-pdf"></i> Protocole.Pdf</span>
          <img width="20px" class="download-icon" src="/wp-content/plugins/plateforme-master/images/icons/Groupe 152.png" alt="upload-red.png">
        </div>
      </div>
      <div class="detail-item">
        <label>Contrat :</label>
        <div class="file-download-link">
          <span><i class="fa fa-file-pdf"></i> Contrat.Pdf</span>
          <img width="20px" class="download-icon" src="/wp-content/plugins/plateforme-master/images/icons/Groupe 152.png" alt="upload-red.png">
        </div>
      </div>
      <div class="detail-item">
        <label>Périodicité :</label>
      </div>
      <div class="detail-item">
        <label>Consignes :</label>
      </div>
    </div>
    <div class="details-modal-footer">
      <button class="btn-history openHistoryModal"><i class="fa fa-wrench"></i> Historique des maintenances</button>
    </div>
  </div>
</div>

<!-- MODAL FOR HISTORIQUE -->
<div class="modal-overlay" id="modalHistorique" style="display: none;">
  <div class="popup-container" id="popupContainerHistorique">
    <div class="popup-header">
      <h2><i class="fa fa-arrow-left" style="cursor: pointer; margin-right: 15px;"></i>Historique des maintenances</h2>
      <button class="btn-close-x">X</button>
    </div>
    <div class="details-modal-content">
      <!-- Maintenance history items to be populated dynamically -->
    </div>
  </div>
</div>

    <!-- jQuery + DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable for the equipments table
            var equipementsTable = $('#equipementsTable').DataTable({
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
                    "targets": [0, 5, 6]
                }]
            });

            // Category filter for equipments table
            $('#categoryFilter').on('change', function () {
                var category = $(this).val();
                equipementsTable.column(2).search(category).draw();
            });

            // Filter button logic for Equipments table
            $('.filter-buttons .filter-btn').on('click', function () {
                $('.filter-buttons .filter-btn').removeClass('active');
                $(this).addClass('active');

                var filterValue = $(this).text().trim(); // "Tous", "Disponibles", "Réservés"

                if (filterValue === "Tous") {
                    equipementsTable.column(4).search('').draw();
                } else if (filterValue === "Disponible") {
                    equipementsTable.column(4).search("Disponible").draw();
                } else if (filterValue === "Non Disponible") {
                    equipementsTable.column(4).search("Non Disponible").draw();
                }
            });

            // "Check all" functionality for equipments table
            $("#checkAllEquipements").on("click", function () {
                var rows = equipementsTable.rows({
                    'search': 'applied'
                }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            // Handle individual checkbox clicks to uncheck "check all" for equipments
            $('#equipementsTable tbody').on('change', 'input[type="checkbox"]', function () {
                if (!this.checked) {
                    var el = $('#checkAllEquipements').get(0);
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

            // Open equipement modal
            $('#openEquipementModal').on('click', function () {
                openModalAjouterEquipement();
            });

            // --- NEW --- Open and populate Modifier Equipement modal
            $(document)
                .off('click.openEditEquip')
                .on('click.openEditEquip', '.openModifierEquipementModal', async function (e) {
                    e.preventDefault();

                    // 1) Récupérer l'ID de l'équipement depuis le bouton ou la ligne
                    const id =
                        $(this).data('id') ||
                        $(this).closest('tr').find('.row-check').data('id') ||
                        $(this).closest('tr').find('.openDetailsModal').data('id');

                    if (!id) {
                        console.error('[openModifierEquipementModal] id manquant');
                        window.toast?.("ID d'équipement introuvable", true) || alert("ID d'équipement introuvable");
                        return;
                    }

                    try {
                        // 2) Ouvrir la modale d'édition AVEC préréglage de TOUS les champs
                        await openModifierEquipementModal(id);
                    } catch (err) {
                        console.error(err);
                        window.toast?.('Impossible de charger les données', true) || alert('Impossible de charger les données');
                    }
                });

            async function openModifierEquipementModal(id) {
                // 1) Assurer les listes (catégories + disponibilités) puis injecter dans les selects de la modale
                await loadEquipementLookups();

                // 2) Charger toutes les données de l'équipement
                const equip = await fetchEquipementById(id);
                const [protoUrl, entretien] = await Promise.all([
                    fetchProtocoleByEquip(id).catch(() => ''),     // URL dernier protocole
                    fetchEntretienByEquip(id).catch(() => null)    // { periodicite, consignes, fichier_contrat }
                ]);

                const $m = $('#modalModifierEquipement').data('equip-id', id);

                // 3) Préremplir TOUS les champs (par ID, pas par texte)
                $('#modifier-categorie-equipement').val(String(equip.categorie_id || ''));
                $('#modifier-nom-appareil').val(equip.nom_appareil || '');
                $('#modifier-modele-appareil').val(equip.modele || '');
                $('#modifier-spec-tech').val(equip.spcification_technique || '');
                $('#modifier-statut-appareil').val(equip.statut || '');
                $('#modifier-disponibilite-appareil').val(String(equip.disponibilite_id || ''));

                // 4) Miroirs des fichiers existants (afficher le nom)
                const basename = (url) => { try { return decodeURIComponent(new URL(url, location.origin).pathname.split('/').pop() || ''); } catch { return (url || '').split('/').pop() || ''; } };
                const $protoText = $m.find('.input-file-text[placeholder="Protocole d\'utilisation"]');
                const $contrText = $m.find('.input-file-text[placeholder="Contrat"]');
                $protoText.val(protoUrl ? basename(protoUrl) : '');
                $contrText.val(entretien?.fichier_contrat ? basename(entretien.fichier_contrat) : '');

                // Préremplir lieu
                $('#modifier-lieu').val(equip.lieu || ''); // Si lieu existe dans equip (après ajout backend)

                // Images existantes (commenté car pas de table images encore ; assume equip.images = ["url1", "url2"])
                /*
                const $existing = $('#modalModifierEquipement .existing-images');
                $existing.empty();
                (equip.images || []).forEach(url => {
                    const img = `<img src="${escAttr(url)}" alt="Image existante" style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;" onclick="window.open('${escAttr(url)}', '_blank')">`;
                    $existing.append(img);
                });
                */

                // 5) Miroir en cas de nouveau fichier choisi
                $m.find('.input-file-wrapper input[type="file"]').off('change.mirror').on('change.mirror', function () {
                    const name = this.files?.[0]?.name || '';
                    $(this).closest('.input-file-wrapper').find('.input-file-text').val(name);
                });

                // 6) Ouvrir la modale
                $m.css('display', 'flex');
            }

            // Open maintenance modal
            $(document).on('click', '.openMaintenanceModal', function (e) {
                e.preventDefault();
                openModalMaintenance();
            });

            // Open details modal
            $(document).on('click', '.openDetailsModal', function () {
                openModalDetailsAppareil();
            });

            // Open history modal from details modal
            $(document).on('click', '.openHistoryModal', function () {
                $('#modalDetailsAppareil').hide();
                openModalHistorique();
            });

            // Back arrow in history modal to return to details modal
            $('#modalHistorique .fa-arrow-left').on('click', function () {
                $('#modalHistorique').hide();
                openModalDetailsAppareil();
            });

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

        function openModalAjouterEquipement() {
            $('#modalAjouterEquipement').css('display', 'flex');
        }

        // --- NEW --- Function to open the modifier equipement modal
        function openModalModifierEquipement() {
            $('#modalModifierEquipement').css('display', 'flex');
        }

        function openModalMaintenance() {
            $('#modalMaintenance').css('display', 'flex');
        }

        function openModalDetailsAppareil() {
            $('#modalDetailsAppareil').css('display', 'flex');
        }

        function openModalHistorique() {
            $('#modalHistorique').css('display', 'flex');
        }
    </script>

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
    // Helpers
    async function wpFetchJson(url) {
        const res = await fetch(url, {
            headers: {
                'X-WP-Nonce': PMSettings?.nonce || '',
                'Accept': 'application/json'
            },
            credentials: 'include'
        });
        if (!res.ok) throw new Error('API ' + url + ' → ' + res.status);
        return await res.json();
    }

    function fillSelect(sel, items, placeholder) {
        if (!sel) return;
        sel.innerHTML = ''; // reset
        const opt0 = document.createElement('option');
        opt0.value = '';
        opt0.textContent = placeholder || '—';
        sel.appendChild(opt0);
        (items || []).forEach(it => {
            const opt = document.createElement('option');
            opt.value = it.id;                // on stocke l'id
            opt.dataset.code = it.code || ''; // utile si besoin du code
            opt.textContent = it.intitule || it.code || ('#' + it.id);
            sel.appendChild(opt);
        });
    }

    // Helper: conserve la sélection courante si possible
    function fillSelectWithPreserve(sel, items, placeholder, preserve = true) {
        if (!sel) return;
        const current = preserve ? sel.value : '';
        fillSelect(sel, items, placeholder);
        if (preserve && current) {
            const has = Array.from(sel.options).some(o => String(o.value) === String(current));
            if (has) sel.value = current;
        }
    }

    // Helper pour le select de filtre (valeur = libellé, pratique pour la recherche DataTables)
    function fillCategoryFilterWithPreserve(sel, items, placeholder = 'Catégorie', preserve = true) {
        if (!sel) return;
        const current = preserve ? sel.value : '';
        sel.innerHTML = '';
        sel.append(new Option(placeholder, ''));
        (items || []).forEach(it => {
            // value = intitule (pour que ton code de filtre existant continue à marcher)
            sel.append(new Option(it.intitule || it.code || ('#' + it.id), it.intitule || ''));
        });
        if (preserve && current) {
            const has = Array.from(sel.options).some(o => String(o.value) === String(current));
            if (has) sel.value = current;
        }
    }

    // Charge Catégories & Disponibilités (add + edit + filtre)
    async function loadEquipementLookups() {
        try {
            const base = (PMSettings?.restUrl || '/wp-json').replace(/\/$/, '');
            const [cats, dispos] = await Promise.all([
                wpFetchJson(`${base}/plateforme-directeurderecherche/v1/categories-equipements`),
                wpFetchJson(`${base}/plateforme-directeurderecherche/v1/disponibilites-equipements`)
            ]);

            // cache global
            window._EQUIP_CATS = cats;
            window._EQUIP_DISPOS = dispos;

            // --- Formulaire d'ajout ---
            fillSelect(document.getElementById('categorie_id'), cats, 'Catégorie Des Equipements');
            fillSelect(document.getElementById('disponibilite_id'), dispos, 'Disponibilité');

            // --- Formulaire d'édition (préserver la sélection si déjà posée) ---
            fillSelectWithPreserve(document.getElementById('modifier-categorie-equipement'), cats, 'Catégorie Des Equipements', true);
            fillSelectWithPreserve(document.getElementById('modifier-disponibilite-appareil'), dispos, 'Disponibilité', true);

            // --- Select de filtre (sidebar au-dessus du tableau) ---
            fillCategoryFilterWithPreserve(document.getElementById('categoryFilter'), cats, 'Catégorie', true);

        } catch (e) {
            console.error('[loadEquipementLookups]', e);
            window.toast?.('Erreur chargement listes: ' + e.message, true);
        }
    }

    // Lance au moment d’ouvrir la modale
    document.addEventListener('DOMContentLoaded', () => {
        // si la modale est déjà dans le DOM :
        loadEquipementLookups();
    });

    // ---------------------- CONFIG REST ----------------------
    const REST_BASE = (window.PMSettings?.restUrl || '/wp-json').replace(/\/$/, ''); // ex: "/wp-json"
    const HEADERS_JSON = {
        'X-WP-Nonce': window.PMSettings?.nonce || '',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    };
    const HEADERS_UPLOAD = {
        'X-WP-Nonce': window.PMSettings?.nonce || '',
        'Accept': 'application/json'
    };

    // ---------------------- HELPERS ----------------------
    async function postJSON(endpoint, data) {
        const res = await fetch(`${REST_BASE}${endpoint}`, {
            method: 'POST',
            headers: HEADERS_JSON,
            credentials: 'include',
            body: JSON.stringify(data)
        });
        if (!res.ok) {
            const msg = await res.text().catch(() => res.statusText);
            throw new Error(`POST ${endpoint} → ${res.status} ${msg}`);
        }
        return res.json();
    }
    // ---- helpers spécifiques edit ----
    function basenameFromUrl(url = '') {
        try { return decodeURIComponent(new URL(url, location.origin).pathname.split('/').pop() || ''); }
        catch { return (url || '').split('/').pop() || ''; }
    }
    function $modalEdit() { return $('#modalModifierEquipement'); }
    function $wrapEdit() { return $('#modalModifierEquipement .popup-form'); }

    // API liées (si routes actives côté PHP)
    async function postEquipementProtocole(equipementId, fichierUrl) {
        return postJSON('/plateforme-directeurderecherche/v1/equipement_protocole', {
            id_recherche_equipement: parseInt(equipementId, 10),
            fichier: String(fichierUrl || '')
        });
    }
    async function postConditionsEntretien(equipementId, { periodicite, consignes, fichier_contrat }) {
        return postJSON('/plateforme-directeurderecherche/v1/conditions_entretien', {
            id_recherche_equipement: parseInt(equipementId, 10),
            periodicite: String(periodicite || ''),
            consignes: String(consignes || ''),
            fichier_contrat: String(fichier_contrat || '')
        });
    }
    // Upload d'un fichier vers /wp/v2/media, renvoie l'URL (source_url)
    async function uploadMedia(file, title = '') {
        if (!file) return '';
        const url = `${REST_BASE}/wp/v2/media?title=${encodeURIComponent(title || file.name)}`;
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                ...HEADERS_UPLOAD,
                'Content-Disposition': `attachment; filename="${file.name}"`,
                'Content-Type': file.type || 'application/octet-stream'
            },
            credentials: 'include',
            body: file
        });
        if (!res.ok) {
            const msg = await res.text().catch(() => res.statusText);
            throw new Error(`UPLOAD ${file.name} → ${res.status} ${msg}`);
        }
        const j = await res.json();
        return j?.source_url || '';
    }

    // ------- NEW: reset + close helpers -------
    function resetEquipementForm() {
        const form = document.querySelector('#modalAjouterEquipement .popup-form');
        if (!form) return;
        // Reset standard
        form.reset();
        // Vider les champs miroirs pour les fichiers
        form.querySelectorAll('.input-file-text').forEach(el => { el.value = ''; });
        // Par sécurité, vider explicitement les <input type="file">
        form.querySelectorAll('input[type="file"]').forEach(f => { try { f.value = ''; } catch (_) { } });
        // Remettre les selects sur la première option
        ['categorie_id', 'disponibilite_id', 'statut', 'periodicite'].forEach(id => {
            const sel = form.querySelector(`#${id}`);
            if (sel) sel.selectedIndex = 0;
        });
    }

    function closeModalAjouterEquipement() {
        const modal = document.getElementById('modalAjouterEquipement');
        if (modal) modal.style.display = 'none';
        // Optionnel: enlever une éventuelle classe d’ouverture sur <body>
        document.body.classList.remove('modal-open');
    }

    // Récupération des valeurs du formulaire (par placeholders/id)
    function getEquipementFormValues() {
        const form = document.querySelector('#modalAjouterEquipement .popup-form');
        if (!form) throw new Error('Formulaire introuvable');

        const categorie_id = parseInt(document.getElementById('categorie_id')?.value || '', 10) || 0;
        const disponibilite_id = parseInt(document.getElementById('disponibilite_id')?.value || '', 10) || 0;
        const statut = document.getElementById('statut')?.value || '';

        const nom_appareil = form.querySelector('input[placeholder="Nom de l\'appareil"]')?.value?.trim() || '';
        const modele = form.querySelector('input[placeholder="Modèle"]')?.value?.trim() || '';
        const spcification_technique = form.querySelector('textarea')?.value?.trim() || '';

        // Files (depuis le champ texte miroir)
        const protoText = form.querySelector('.input-file-text[placeholder="Protocole d\'utilisation"]');
        const protocoleFile = protoText
            ? protoText.closest('.input-file-wrapper')?.querySelector('label input[type="file"]')?.files?.[0] || null
            : null;

        const contratText = form.querySelector('.input-file-text[placeholder="Contrat"]');
        const contratFile = contratText
            ? contratText.closest('.input-file-wrapper')?.querySelector('label input[type="file"]')?.files?.[0] || null
            : null;

        const periodicite = document.getElementById('periodicite')?.value || '';
        const consignes = form.querySelector('input[placeholder="Consignes"]')?.value?.trim() || '';

        const lieu = form.querySelector('input[placeholder="Lieu de l\'appareil"]')?.value?.trim() || '';

        // Images multiples
        const imagesText = form.querySelector('.input-file-text[placeholder="Importer des images..."]');
        const imagesFiles = imagesText
            ? imagesText.closest('.input-file-wrapper')?.querySelector('label input[type="file"][multiple]')?.files || []
            : [];

        return {
            categorie_id, disponibilite_id, statut,
            nom_appareil, modele, spcification_technique,
            protocoleFile, contratFile,
            periodicite, consignes,
            lieu,
            imagesFiles: Array.from(imagesFiles) // Convertir en array pour boucle
        };
    }
    function validateEquipement(v) {
        const errors = [];
        if (!v.categorie_id) errors.push('Catégorie');
        if (!v.nom_appareil) errors.push('Nom de l’appareil');
        if (!v.modele) errors.push('Modèle');
        if (!v.statut) errors.push('Statut');
        if (!v.disponibilite_id) errors.push('Disponibilité');
        return errors;
    }

    // ---------------------- CREATE FLOW ----------------------
    async function createEquipement() {
        const btn = document.querySelector('#popupContainerEquipement .btn-enregistrer');
        const loaderOn = () => { if (btn) { btn.disabled = true; btn.dataset._txt = btn.textContent; btn.textContent = 'Enregistrement…'; } };
        const loaderOff = () => { if (btn) { btn.disabled = false; btn.textContent = btn.dataset._txt || 'Enregistrer'; } };

        try {
            const v = getEquipementFormValues();
            const errors = validateEquipement(v);
            if (errors.length) throw new Error('Champs requis : ' + errors.join(', '));

            loaderOn();

            // 1) Upload des fichiers si fournis
            const protocole_url = await uploadMedia(v.protocoleFile, `Protocole - ${v.nom_appareil}`).catch(e => {
                console.error('Upload protocole échoué:', e);
                return '';
            });
            const contrat_url = await uploadMedia(v.contratFile, `Contrat - ${v.nom_appareil}`).catch(e => {
                console.error('Upload contrat échoué:', e);
                return '';
            });

            // Upload des images multiples
            const imagesUrls = [];
            for (const file of v.imagesFiles) {
                const url = await uploadMedia(file, `Image - ${v.nom_appareil || 'Equipement'}`).catch(e => {
                    console.error('Upload image échoué:', e);
                    return '';
                });
                if (url) imagesUrls.push(url);
            }

            // 2) Création de l’équipement + champs liés
            const payload = {
                categorie_id: v.categorie_id,
                disponibilite_id: v.disponibilite_id,
                modele: v.modele,
                nom_appareil: v.nom_appareil,
                statut: v.statut,
                spcification_technique: v.spcification_technique,
                protocole_fichier: protocole_url,
                contrat_fichier: contrat_url,
                periodicite: v.periodicite,
                consignes: v.consignes,
                lieu: v.lieu,
                images: imagesUrls
            };

            const created = await postJSON('/plateforme-directeurderecherche/v1/equipement', payload);

            // ✅ Succès : toast + reset + fermeture + refresh liste si dispo
            window.toast?.('Appareil créé avec succès', false) || alert('Appareil créé');
            resetEquipementForm();
            closeModalAjouterEquipement();
            if (typeof window.reloadEquipements === 'function') window.reloadEquipements();

            return created;
        } catch (e) {
            console.error('[createEquipement]', e);
            window.toast?.(e.message || 'Échec création', true) || alert(e.message || 'Échec création');
            throw e;
        } finally {
            loaderOff();
        }
    }
    // ---------------------- UI BINDINGS ----------------------
    document.addEventListener('DOMContentLoaded', () => {
        // Bouton Enregistrer
        const btn = document.querySelector('#popupContainerEquipement .btn-enregistrer');
        if (btn) btn.addEventListener('click', (e) => { e.preventDefault(); createEquipement(); });

        // Affichage du nom des fichiers choisis
        document.querySelectorAll('#modalAjouterEquipement .input-file-wrapper').forEach(w => {
            const file = w.querySelector('label input[type="file"]');
            const text = w.querySelector('.input-file-text');
            if (file && text) {
                file.addEventListener('change', () => { text.value = file.files?.[0]?.name || ''; });
            }
        });
    });
    // Miroir pour le champ Images (multiple) dans AJOUT et ÉDITION
    $(document).on('change', '.input-file-wrapper input[type="file"][multiple]', function () {
        const files = this.files;
        const $text = $(this).closest('.input-file-wrapper').find('.input-file-text');
        if (files && files.length > 0) {
            if (files.length === 1) {
                $text.val(files[0].name); // Nom du fichier si un seul
            } else {
                $text.val(`${files.length} images sélectionnées`); // Compteur si multiple
            }
        } else {
            $text.val('');
        }
    });

    // ========== CONFIG ==========
    const AUTH_HEADERS = {                         // <- manquant chez toi
        'X-WP-Nonce': window.PMSettings?.nonce || '',
        'Accept': 'application/json'
    };

    // ========== HELPERS ==========
    async function getJSON(url) {
        const res = await fetch(url, { headers: AUTH_HEADERS, credentials: 'include' });
        if (!res.ok) throw new Error(`${url} → ${res.status}`);
        return res.json();
    }
    function esc(s = '') { return String(s).replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m])); }
    function fmtDate(d) {
        if (!d) return '—';
        const dt = new Date(String(d).replace(' ', 'T'));
        if (isNaN(dt)) return d;
        const dd = String(dt.getDate()).padStart(2, '0');
        const mm = String(dt.getMonth() + 1).padStart(2, '0');
        const yy = dt.getFullYear();
        return `${dd}-${mm}-${yy}`;
    }
    function statutIconHTML(statut) {
        switch (String(statut || '').toLowerCase()) {
            case 'fonctionnel': return '<i class="fa fa-check-circle" style="color:#A6A485;"></i>';
            case 'en_panne': return '<i class="fa-solid fa-triangle-exclamation" style="color:#BF0404;"></i>';
            case 'en_maintenance': return '<i class="fa-solid fa-screwdriver-wrench" style="color:#DDACA7;"></i>';
            case 'hors_service': return '<i class="fa fa-times-circle" style="color:#888;"></i>';
            default: return '<i class="fa fa-minus-circle" style="color:#ccc;"></i>';
        }
    }
    function actionMenuHtml(id, protoUrl = '') {
        const direct = !!protoUrl;
        const linkAttrs = direct
            ? `href="${escAttr(protoUrl)}" target="_blank" rel="noopener"`
            : `href="#"`;
        return `
            <div class="actions">
              <button class="action-btn" type="button" aria-haspopup="true" aria-expanded="false">...</button>
              <div class="dropdown-menu">
                <a ${linkAttrs}
                   class="action-protocole${direct ? ' direct' : ''}"
                   data-id="${id}"
                   data-url="${escAttr(protoUrl)}">
                  <i class="fa fa-file-alt"></i> Protocole d'utilisation
                </a>
                <a href="#" class="openModifierEquipementModal" data-id="${id}"><i class="fa fa-edit"></i> Modifier</a>
                <a href="#" class="action-supprimer" data-id="${id}" style="color:#BF0404;"><i class="fa fa-trash"></i> Supprimer</a>
              </div>
            </div>
          `;
    }

    // ========= API EQUIP/MAINT =========
    async function fetchEquipements({ q = '', categorie_id = '', disponibilite_id = '', page = 1, per_page = 100 } = {}) {
        const params = new URLSearchParams();
        if (q) params.set('q', q);
        if (categorie_id) params.set('categorie_id', categorie_id);
        if (disponibilite_id) params.set('disponibilite_id', disponibilite_id);
        params.set('page', page);
        params.set('per_page', per_page);
        return getJSON(`${REST_BASE}/plateforme-directeurderecherche/v1/equipement?${params.toString()}`);
    }
    async function fetchLastMaintenance(equipementId) {
        // idéalement renvoyé directement côté backend (JOIN MAX(date)), sinon:
        const url = `${REST_BASE}/plateforme-directeurderecherche/v1/maintenance?equipement_id=${encodeURIComponent(equipementId)}&per_page=1`;
        const rows = await getJSON(url);
        const m = Array.isArray(rows) ? rows[0] : null;
        return m?.date_fin || m?.date_debut || '';
    }
    async function getProtocoleURL(equipementId) {
        const url = `${REST_BASE}/plateforme-directeurderecherche/v1/equipement_protocole?equipement_id=${encodeURIComponent(equipementId)}&per_page=1`;
        const rows = await getJSON(url);
        const p = Array.isArray(rows) ? rows[0] : null;
        return p?.fichier || '';
    }
    async function deleteEquipement(id) {
        const res = await fetch(`${REST_BASE}/plateforme-directeurderecherche/v1/equipement/${id}`, {
            method: 'DELETE', headers: AUTH_HEADERS, credentials: 'include'
        });
        if (!res.ok) throw new Error('Delete failed');
    }

    // ========= DATATABLES =========
    function escAttr(s = '') { return String(s).replace(/"/g, '&quot;'); }

    let dtEquip = null;
    let dtEventsBound = false;
    function actionMenuHtml(id, protoUrl = '') {
        const direct = !!protoUrl;
        const linkAttrs = direct
            ? `href="${escAttr(protoUrl)}" target="_blank" rel="noopener"`
            : `href="#"`;

        return `
            <div class="actions">
              <button class="action-btn" type="button" aria-haspopup="true" aria-expanded="false">...</button>
              <div class="dropdown-menu">
                <a ${linkAttrs}
                   class="action-protocole${direct ? ' direct' : ''}"
                   data-id="${id}"
                   data-url="${escAttr(protoUrl)}">
                  <i class="fa fa-file-alt"></i> Protocole d'utilisation
                </a>
                <a href="#" class="openModifierEquipementModal" data-id="${id}"><i class="fa fa-edit"></i> Modifier</a>
                <a href="#" class="action-supprimer" data-id="${id}" style="color:#BF0404;"><i class="fa fa-trash"></i> Supprimer</a>
              </div>
            </div>
          `;
    }
    // URL de redirection "mon-labo" (vient de l'attribut data-details-url du <table>)
    const DETAILS_BASE_URL = ($('#equipementsTable').data('details-url') || '/uscr_details_plateforme').toString();

    function buildRows(items) {
        return (items || []).map(e => ([
            `<input type="checkbox" class="row-check" data-id="${e.id}">`,
            esc(e.nom_appareil || ''),
            esc(e.categorie_label || ''), // Assuming "Etablissement" maps to categorie_label for now
            esc(String(e.unites || '—')), // Placeholder for "Unités"
            esc(fmtDate(e.date_creation || '')),
            `<a class="details-icon" href="${escAttr(DETAILS_BASE_URL)}?equipement_id=${escAttr(e.id)}" title="Voir les détails">
               <i class="fa fa-eye"></i>
             </a>`,
            actionMenuHtml(e.id, e.protocole_fichier || '')
        ]));
    }

    async function reloadEquipements