<!--
/** =========================================================================
* FRONT — Project Details
* - This is a static representation based on the provided screenshots.
* - The original PHP logic and dynamic JavaScript have been removed as
* they were tied to the previous data structure.
* ====================================================================== */
-->
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .custom-project-wrapper {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .custom-content-box {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .custom-box-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        /* border-bottom: 1px solid #EBE9D7; */
        box-shadow: 0 0 22px #00000012;
    }

    .custom-box-header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #2A2916;
    }

    .custom-body-title {
        padding: 15px 20px;
        box-shadow: 0 5px 16px #00000012;
        font-size: 18px;
        font-weight: 700;
        color: #2A2916;
        margin-top: 30px;
        margin-bottom: 15px;
        margin-inline: -20px
    }

    .custom-box-body {
        padding: 40px 20px;
    }

    .custom-header-buttons {
        display: flex;
        gap: 10px;
    }

    .custom-header-buttons a {
        text-decoration: none;
    }

    .custom-button {
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid transparent;
    }

    .custom-button.retire-btn {
        background-color: transparent;
        color: var(--danger);
        padding: 4px 12px;
        font-size: 13px;
    }

    .custom-button.retire-btn:hover {
        background-color: #fdf0f0;
    }

    .custom-button-main {
        background-color: #BF0404;
        color: #fff;
        border-color: #BF0404;
    }

    .custom-button-alt {
        background-color: #fff;
        color: #BF0404;
        border: 1px solid #BF0404;
    }

    .custom-icon-button {
        width: 36px;
        height: 36px;
        border: none;
        background-color: #fff;
        color: #6E6D55;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    /* Info Section */
    .custom-details-list .custom-details-item {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #EBE9D7;
        align-items: center;
    }

    .custom-details-list .custom-details-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .custom-details-list .custom-details-item:first-child {
        padding-top: 0;
    }


    .custom-details-label {
        font-weight: 700;
        color: #2A2916;
        width: 200px;
        flex-shrink: 0;
    }

    .custom-details-value {
        color: #6E6D55;
    }

    .custom-details-value a {
        color: #3987DF;
        text-decoration: none;
    }

    .custom-details-value a:hover {
        text-decoration: underline;
    }

    /* Objectives Section */
    .custom-goals-list {
        list-style-type: none;
        padding-left: 0;
        margin: 0;
        counter-reset: objective-counter;
    }

    .custom-goals-list li {
        counter-increment: objective-counter;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 12px;
        color: #6E6D55;
    }

    .custom-goals-list li::before {
        content: counter(objective-counter) ".";
        font-weight: 700;
        color: #BF0404;
    }

    /* Tables */
    .custom-data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .custom-data-table thead th {
        background: #f3f1e9;
        padding: 12px 15px;
        text-align: left;
        font-weight: 700;
        color: #2A2916;
        border-bottom: 1px solid #EBE9D7;
    }

    .custom-data-table th:first-child {
        border-top-left-radius: 8px;
    }

    .custom-data-table th:last-child {
        border-top-right-radius: 8px;
        text-align: center;
    }


    .custom-data-table tbody td {
        padding: 12px 15px;
        border-bottom: 1px solid #EBE9D7;
        color: #6E6D55;
        vertical-align: middle;
        justify-content: center;
        text-align: center;
    }

    .custom-data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .custom-data-table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 8px;
    }

    .custom-data-table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 8px;
    }

    .custom-empty-table-cell {
        text-align: center;
        padding: 40px !important;
        color: #aaa;

    }

    .custom-empty-table-cell i {
        font-size: 40px;
        margin-bottom: 10px;
        display: block;
        color: #ddd;
    }

    .custom-attachment-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .custom-attachment-link:hover {
        text-decoration: underline;
    }

    .custom-attachment-link i {
        font-size: 18px;
    }

    /* Status & Progress */
    .custom-tag {
        padding: 0;
        font-weight: normal;
        font-size: 14px;
        color: #2A2916;
        background-color: transparent;
    }

    .custom-tag-green,
    .custom-tag-yellow,
    .custom-tag-gray {
        background-color: transparent;
        color: #2A2916;
    }

    .progress-container {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .custom-progress-indicator {
        width: 100%;
        background-color: #EBE9D7;
        border-radius: 8px;
        overflow: hidden;
        height: 10px;
    }

    .custom-progress-indicator-fill {
        height: 100%;
        background: linear-gradient(to bottom, #BF0404, #b2141a);
        border-radius: 8px;
        width: 0%;
        /* Animation Added */
        transition: width 1.5s cubic-bezier(0.25, 0.1, 0.25, 1);
    }

    .progress-percentage {
        color: #6E6D55;
        font-size: 14px;
        font-weight: 600;
        min-width: 40px;
        text-align: right;
    }

    .custom-table-actions {
        display: flex;
        gap: 8px;
    }

    /* Modal Styles */

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
        width: 466px;
        height: 100%;
        box-shadow: -4px 0 10px rgba(0, 0, 0, 0.1);
        overflow-y: auto;
    }

    .popup-container-phases {
        padding-top: 0;
    }

    .popup-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 20px 25px 10px 25px;
        box-shadow: 0px 5px 16px #00000029;
    }

    form.popup-form {
        padding: 0 25px 25px 25px;
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

    .popup-form input,
    .popup-form select,
    .popup-form textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #b5af8e;
        border-radius: 7px;
        font-size: 14px;
    }

    .input-file-wrapper {
        display: flex;
        align-items: center;
        border-radius: 6px;
        overflow: hidden;
        width: 100%;
        background-color: white;
    }

    .input-file-text {
        flex: 1;
        border: 1px solid #b5af8e;
        padding: 10px 12px;
        font-size: 14px;
        color: #555;
        border-radius: 7px 0px 0px 7px !important;
        background-color: transparent;
    }

    .input-file-text:focus {
        outline: none;
    }

    .btn-importer {
        background-color: #b5af8e;
        color: white;
        padding: 11px 16px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        border: none;
    }

    .modal-overlay label {
        min-width: 180px;
        font-weight: 600;
        color: #6E6D55;
        flex-shrink: 0;
        margin-bottom: 7px;
    }

    .form-section-box {
        border: 1px solid #EBE9D7;
        border-radius: 8px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 10px;
        position: relative;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .add-task-button,
    .add-membre-button {
        color: #BF0404;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        font-size: 14px;
        margin-top: 10px;
    }

    .add-task-button i,
    .add-membre-button i {
        border: 2px solid #BF0404;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: bold;
    }

    /* STYLES FOR TEAM PROJECT TABLE */
    .team-table {
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .team-table thead th {
        text-align: center;
        border-bottom: none;
    }

    .team-table tbody .custom-empty-table-cell {
        border: 1px solid #EBE9D7;
        border-radius: 8px;
        padding: 50px 20px !important;
        border-bottom: 1px solid #EBE9D7;
    }

    .team-table .custom-empty-table-cell img {
        opacity: 0.6;
        margin-bottom: 15px;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .profile-pic {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        margin-right: 12px;
        object-fit: cover;
    }

    .member-name-cell {
        display: flex;
        align-items: center;
    }

    /* General Dropdown Menu Styles */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        background-color: #fff;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.1);
        z-index: 1;
        border-radius: 8px;
        border: 1px solid #EBE9D7;
        overflow: hidden;
    }

    .dropdown-menu a,
    .dropdown-menu .dropdown-item {
        color: #2A2916;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        font-size: 14px;
        border-bottom: 1px solid #EBE9D7;
    }

    .dropdown-menu a:last-child,
    .dropdown-menu .dropdown-item:last-child {
        border-bottom: none;
    }

    .dropdown-menu a:hover,
    .dropdown-menu .dropdown-item:hover {
        background-color: #f9f9f9;
    }

    .dropdown-menu .dropdown-item.disabled {
        color: #ccc;
        pointer-events: none;
        cursor: default;
        background-color: transparent;
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-toggle::after {
        display: none;
    }

    /* STYLES FOR PHASES MODAL */
    .phase-section,
    .task-section {
        position: relative;
        padding: 20px;
        border: 1px solid #EBE9D7;
        border-radius: 8px;
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .delete-item-btn {
        /* margin-top: 15px; */
        padding-top: 15px;
        text-align: right;
        background: none;
        border-top: 1px solid #EBE9D7;
        border-bottom: 0;
        border-left: 0;
        border-right: 0;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
    }

    .delete-item-btn.rubrique-btn {
        color: #aaa;
    }

    .delete-item-btn.rubrique-btn:hover {
        color: var(--danger);
    }


    .add-phase-button {
        color: #BF0404;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        font-size: 14px;
        margin-top: 20px;
    }

    .add-phase-button i {
        font-size: 22px;
    }

    /* STYLES FOR MODIFIER PHASES MODAL */
    .phase-accordion {
        margin-top: 20px;
    }

    .phase-accordion:last-child {
        border-bottom: none;
    }

    .phase-header {
        margin-inline: -25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        padding: 15px;
        box-shadow: 0px 5px 16px #00000029;
        /* border-radius: 8px; */
        background: #fff;
        position: relative;
        z-index: 2;
        top: -15px
    }

    .phase-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #2A2916;
    }

    .phase-body {
        padding: 25px 15px 15px 15px;
        border: 1px solid #EBE9D7;
        border-radius: 8px;
        margin-top: 0px;
        position: relative;
        z-index: 1;
    }

    .nb-text {
        font-size: 13px;
        color: #6E6D55;
        margin-top: 0;
        margin-bottom: 15px;
    }

    .phase-progress {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .phase-progress .progress-container {
        flex-grow: 1;
    }

    .phase-status {
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        border: 1px solid;
    }

    .phase-status-encours {
        color: #d89e00;
        background-color: #fff8e6;
        border-color: #d89e00;
    }

    .phase-status-prevu {
        color: #6E6D55;
        background-color: #f1f1f1;
        border-color: #EBE9D7;
    }

    .sub-task-form {
        padding: 20px;
        border: 1px solid #EBE9D7;
        border-radius: 8px;
        margin-top: 15px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .sub-task-form .form-group {
        gap: 0;
    }

    /* STYLES FOR AJOUTER TACHE MODAL */
    .phase-header-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #EBE9D7;
    }

    .phase-header-info h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
    }

    .date-fields-group {
        display: flex;
        gap: 15px;
    }

    .date-fields-group .form-group {
        flex: 1;
    }

    .date-input-container {
        position: relative;
    }


    /* STYLES FOR DÉPENSE (EXPENSE) SECTION */
    :root {
        --ink: #2A2916;
        --line: #EBE9D7;
        --muted: #6E6D55;
        --chip: #E9E7D7;
        --chip-active: #A6A485;
        --danger: #BF0404;
    }

    .expense-section {
        border-radius: 12px;
        box-shadow: 0 0 8px rgba(0, 0, 0, .05)
    }

    .expense-tabs {
        display: flex;
    }

    .expense-tab-btn {
        flex: 1;
        padding: 15px 20px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background: var(--chip);
        color: var(--muted);
        border-top-left-radius: 11px;
        border-top-right-radius: 11px;
    }

    .expense-tab-btn:not(:last-child) {
        margin-right: 15px;
    }

    .expense-tab-btn.active {
        background: #fff;
        color: var(--ink);
        margin-bottom: -1px;
        /* border: 1px solid var(--line); */
        border-bottom: 1px solid #fff;
        z-index: 2;
    }

    .expense-content {
        padding: 25px;
        background: #fff;
        /* border: 1px solid var(--line); */
        border-radius: 0 0px 12px 12px;
        position: relative;
        z-index: 1;
    }

    .expense-tab-panel {
        display: none
    }

    .expense-tab-panel.active {
        display: block
    }

    .expense-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }

    .expense-controls .expense-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .expense-search-box {
        display: flex;
        align-items: center;
        border: 1px solid #d8d4b7;
        border-radius: 8px;
        padding: 0 10px;
        background: #fff;
        min-width: 240px;
    }

    .expense-search-box i {
        color: #666;
        margin-right: 6px
    }

    .expense-filter-input {
        padding: 10px 6px;
        border: none;
        outline: none;
        font-size: 14px;
        background: #fff;
        width: 100%;
    }

    .expense-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .add-expense-btn {
        background: #fff;
        color: var(--danger);
        border: 1px solid var(--danger);
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .add-expense-btn:hover {
        background: #fdf0f0
    }

    .expense-icon-btn {
        width: 38px;
        height: 38px;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--muted)
    }

    .expense-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .expense-table thead {
        text-align: center;
        background: #f3f1e9
    }

    .expense-table tbody {
        text-align: center;
    }

    .expense-table th {
        padding: 14px;
        text-align: left;
        border: none;
        border-bottom: 1px solid var(--line);
    }

    .expense-table th:first-child {
        border-top-left-radius: 12px;
    }

    .expense-table th:last-child {
        text-align: center;
        border-top-right-radius: 12px;
    }

    .expense-table td {
        padding: 14px;
        text-align: left;
        border-bottom: 1px solid var(--line);
    }

    .dataTables_wrapper .dataTables_paginate {
        display: flex;
        justify-content: end;
        align-items: center;
        gap: 5px;
        margin-top: 20px;
        font-size: 14px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid var(--line) !important;
        color: var(--muted) !important;
        padding: 8px 14px;
        border-radius: 8px;
        background: #fff !important;
        font-weight: 700;
        cursor: pointer;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--danger) !important;
        border-color: var(--danger) !important;
        color: #fff !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:not(.current):hover {
        background: #f9f9f9 !important
    }

    #depenseTable_wrapper .bottom,
    #rebriquesTable_wrapper .bottom {
        padding-top: 20px;
    }

    /* STYLES FOR AJOUTER RUBRIQUE MODAL */
    .montant-total-box {
        background-color: #f3f1e9;
        border-radius: 8px;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .montant-total-box .label {
        font-weight: 700;
        color: #2A2916;
    }

    .montant-total-box .value {
        font-weight: 700;
        font-size: 18px;
        /* color: #BF0404; */
    }

    .add-rubrique-button {
        color: #BF0404;
        font-weight: 700;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        font-size: 14px;
        margin-top: 10px;
    }

    .add-rubrique-button i {
        border: 2px solid #BF0404;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: bold;
    }

    .rubrique-item-container {
        position: relative;
    }

    /* STYLES FOR AJOUTER DEPENSE MODAL */
    .montant-alloue-box {
        background-color: #f3f1e9;
        border-radius: 8px;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 20px 0;
    }

    .montant-alloue-box .label {
        font-weight: 700;
        color: #2A2916;
    }

    .montant-alloue-box .value {
        font-weight: 700;
        font-size: 18px;
        color: #2A2916;
    }

    .input-with-unit {
        position: relative;
    }

    .input-with-unit input {
        padding-right: 50px;
    }

    .input-with-unit span {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6E6D55;
        font-weight: 600;
    }

    #modalAjouterDepense textarea,
    #modalModifierDepense textarea {
        resize: vertical;
        min-height: 80px;
    }


    #teamTable,
    #equipeTable,
    #tasksTable,
    #tab-rb,
    #tab-depense {
        border: none !important;
        box-shadow: none !important;
        border-collapse: separate;
        border-spacing: 0
    }

    #teamTable th,
    #equipeTable th,
    #tasksTable th,
    #tab-rb th,
    #tab-depense th {
        border: 0
    }

    #teamTable td,
    #equipeTable td,
    #tasksTable td,
    #tab-rb td,
    #tab-depense td {
        border: 1px solid var(--line)
    }

    #teamTable thead,
    #equipeTable thead,
    #tasksTable thead,
    #tab-rb thead,
    #tab-depense thead {
        position: static;
        transform: translateY(-15px)
    }

    #teamTable tbody tr:first-child td,
    #equipeTable tbody tr:first-child td,
    #tasksTable tbody tr:first-child td,
    #tab-rb tbody tr:first-child td,
    #tab-depense tbody tr:first-child td {
        border-top: 1px solid var(--line) !important
    }

    /* arrondis */
    #teamTable thead tr:first-child th:first-child,
    #equipeTable thead tr:first-child th:first-child,
    #tasksTable thead tr:first-child th:first-child,
    #tab-rb thead tr:first-child th:first-child,
    #tab-depense thead tr:first-child th:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px
    }

    #teamTable thead tr:first-child th:last-child,
    #equipeTable thead tr:first-child th:last-child,
    #tasksTable thead tr:first-child th:last-child,
    #tab-rb thead tr:first-child th:last-child,
    #tab-depense thead tr:first-child th:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px
    }

    #teamTable tbody tr:last-child td:first-child,
    #equipeTable tbody tr:last-child td:first-child,
    #tasksTable tbody tr:last-child td:first-child,
    #tab-rb tbody tr:last-child td:first-child,
    #tab-depense tbody tr:last-child td:first-child {
        border-bottom-left-radius: 12px
    }

    #teamTable tbody tr:last-child td:last-child,
    #equipeTable tbody tr:last-child td:last-child,
    #tasksTable tbody tr:last-child td:last-child,
    #tab-rb tbody tr:last-child td:last-child,
    #tab-depense tbody tr:last-child td:last-child {
        border-bottom-right-radius: 12px
    }

    #teamTable tbody tr:first-child td:first-child,
    #equipeTable tbody tr:first-child td:first-child,
    #tasksTable tbody tr:first-child td:first-child,
    #tab-rb tbody tr:first-child td:first-child,
    #tab-depense tbody tr:first-child td:first-child {
        border-top-left-radius: 12px
    }

    #teamTable tbody tr:first-child td:last-child,
    #equipeTable tbody tr:first-child td:last-child,
    #tasksTable tbody tr:first-child td:last-child,
    #tab-rb tbody tr:first-child td:last-child,
    #tab-depense tbody tr:first-child td:last-child {
        border-top-right-radius: 12px
    }

/* Accordéon Tâches */
.tache-accordion {
  border: 1px solid #EBE9D7;
  margin-bottom: 12px;
  overflow: hidden;
}

.tache-header {
    font-weight: 600;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    padding: 15px;
    box-shadow: 0px 5px 16px #00000029;
    border-radius: 0px;
    background: #fff;
    position: relative;
    z-index: 2;
}
.popup-container {
    background-color: white;
    width: 466px;
    height: 100%;
    box-shadow: -4px 0 10px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
}
.tache-header span {
  font-size: 15px;
  color: #000000ff;
}

.tache-header i {
  transition: transform 0.3s ease;
}

.tache-header.open i {
  transform: rotate(180deg);
}

.tache-body {
    display: none;
    padding: 15px;
    background: #fff;
    padding: 25px 15px 15px 15px;
    border: 1px solid #EBE9D7;
    border-radius: 8px;
    position: relative;
    z-index: 1;
    margin: 20px;
}
.tache-body strong {
    font-weight: 600;
    color: #6E6D55;
    flex-shrink: 0;
    margin-bottom: 7px;
}
.tache-body p {
  margin: 6px 0;
  font-size: 14px;
  color: #444;
}

.tache-actions {
  margin-top: 10px;
  display: flex;
  gap: 10px;
}

.tache-actions button {
    border: none;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    background-color: transparent;
}

.btn-edit {
  background-color: #007bff;
  color: white;
}

.btn-delete {
  background-color: #c62828;
  color: white;
}
button.btn-edit.js-edit-tache {
    background-color: #fff;
    color: #BF0404;
    border: 1px solid #BF0404;
}
.swal2-container.swal2-center.swal2-backdrop-show {
    z-index: 999999999999999;
}

/* Styles existants pour custom-data-table et team-table */
.custom-data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.custom-data-table thead th {
    background: #f3f1e9;
    padding: 12px 15px;
    text-align: left;
    font-weight: 700;
    color: #2A2916;
    border-bottom: 1px solid #EBE9D7;
}

.custom-data-table th:first-child {
    border-top-left-radius: 8px;
}

.custom-data-table th:last-child {
    border-top-right-radius: 8px;
    text-align: center;
}

.custom-data-table tbody td {
    padding: 12px 15px;
    border-bottom: 1px solid #EBE9D7;
    color: #6E6D55;
    vertical-align: middle;
    justify-content: center;
    text-align: center;
}

.custom-data-table tbody tr:last-child td {
    border-bottom: none;
}

.custom-data-table tbody tr:last-child td:first-child {
    border-bottom-left-radius: 8px;
}

.custom-data-table tbody tr:last-child td:last-child {
    border-bottom-right-radius: 8px;
}

.custom-empty-table-cell {
    text-align: center;
    padding: 40px !important;
    color: #aaa;
}

.custom-empty-table-cell i {
    font-size: 40px;
    margin-bottom: 10px;
    display: block;
    color: #ddd;
}

/* Styles spécifiques pour team-table */
.team-table {
    border-collapse: separate;
    border-spacing: 0 10px;
}

.team-table thead th {
    text-align: center;
    border-bottom: none;
}

.team-table tbody .custom-empty-table-cell {
    border: 1px solid #EBE9D7;
    border-radius: 8px;
    padding: 50px 20px !important;
    border-bottom: 1px solid #EBE9D7;
}

/* Styles spécifiques pour #table-pieces pour correspondre à #tasksTable */
#table-pieces {
    border: none !important;
    box-shadow: none !important;
    border-collapse: separate;
    border-spacing: 0 10px;
}

#table-pieces thead {
    position: static;
    transform: translateY(-15px);
}

#table-pieces th {
    border: 0;
    text-align: center;
    background: #f3f1e9;
    padding: 14px;
}

#table-pieces td {
    border: 1px solid var(--line);
    text-align: center;
    padding: 14px;
}

#table-pieces thead tr:first-child th:first-child {
    border-top-left-radius: 12px;
    border-bottom-left-radius: 12px;
}

#table-pieces thead tr:first-child th:last-child {
    border-top-right-radius: 12px;
    border-bottom-right-radius: 12px;
}

#table-pieces tbody tr:first-child td:first-child {
    border-top-left-radius: 12px;
}

#table-pieces tbody tr:first-child td:last-child {
    border-top-right-radius: 12px;
}

#table-pieces tbody tr:last-child td:first-child {
    border-bottom-left-radius: 12px;
}

#table-pieces tbody tr:last-child td:last-child {
    border-bottom-right-radius: 12px;
}
</style>
<div class="custom-project-wrapper">

  <!-- Informations générales -->
  <div class="custom-content-box">
    <div class="custom-box-header">
      <h2>Informations générales</h2>
      <div class="custom-header-buttons">
        <a href="#" class="custom-button custom-button-main">Publier espace</a>
      </div>
    </div>
    <div class="custom-box-body">
      <div class="custom-details-list" id="infos-generales"></div>
      <h2 class="custom-body-title">Objectifs du projet</h2>
      <ol class="custom-goals-list" id="objectifs-list"></ol>
    </div>
  </div>

  <!-- Équipe du projet -->
  <div class="custom-content-box">
    <div class="custom-box-header">
      <h2>Équipe du projet</h2>
      <div class="custom-header-buttons">
        <a href="#" id="ajouterMembreBtn" class="custom-button custom-button-alt">Ajouter</a>
      </div>
    </div>
    <div class="custom-box-body">
      <table class="custom-data-table team-table" id="equipeTable">
        <thead>
          <tr>
            <th>Orcid</th>
            <th>Nom complet</th>
            <th>Rôle dans le projet</th>
            <th>Email</th>
            <th>Doc.</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <!-- Liste des phases de projet -->
  <div class="custom-content-box">
    <div class="custom-box-header">
      <h2>Liste des phases de projet</h2>
      <div class="custom-header-buttons">
        <a href="#" class="custom-button custom-button-alt">Générer Gantt</a>
        <div class="dropdown">
          <button class="custom-button custom-button-alt dropdown-toggle" id="tasksActionsBtn">
            Actions ▼
          </button>
          <div class="dropdown-menu" aria-labelledby="tasksActionsBtn">
            <a class="dropdown-item" href="#" id="ajouterPhasesBtn">Ajouter les phases</a>
          </div>
        </div>
      </div>
    </div>
    <div class="custom-box-body">
      <table class="custom-data-table team-table" id="tasksTable">
        <thead>
          <tr>
            <th>N°</th>
            <th>Phase</th>
            <th>Etat</th>
            <th>Progression</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <!-- Pièces jointes associées au projet -->
  <div class="custom-content-box">
    <div class="custom-box-header">
      <h2>Pièces jointes associées au projet</h2>
      <div class="custom-header-buttons">
        <a href="#" id="modifierPiecesJointesBtn" class="custom-button custom-button-alt">Modifier</a>
        <button class="expense-icon-btn" title="Télécharger"><img width="20px" src="/wp-content/plugins/plateforme-master/images/icons/upload-red.png" alt=""></button>
      </div>
    </div>
    <div class="custom-box-body">
      <table class="custom-data-table team-table" id="table-pieces">
        <thead>
          <tr>
            <th>Ref_Doc</th>
            <th>Type de document</th>
            <th>Fichier</th>
            <th>Version</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <!-- Dépenses -->
  <div class="expense-section">
    <div class="expense-tabs">
      <button class="expense-tab-btn active" data-tab="tab-rb">Rubriques budgétaire</button>
      <button class="expense-tab-btn" data-tab="tab-depense">Dépenses</button>
    </div>
    <div class="expense-content">
      <div class="expense-tab-panel active" id="tab-rb">
        <div class="expense-controls">
          <div class="expense-search-box">
            <input type="text" class="expense-filter-input" id="rebriquesSearch" placeholder="Recherchez..."> <i class="fa fa-search"></i>
          </div>
          <div class="expense-actions">
            <a href="#" id="addRubriqueBtn" class="add-expense-btn">Ajouter rubrique</a>
            <button class="expense-icon-btn" title="Exporter"><img width="20px" src="/wp-content/plugins/plateforme-master/images/icons/upload-red.png" alt=""></button>
          </div>
        </div>
        <table class="expense-table" id="rebriquesTable">
          <thead>
            <tr>
              <th>Ref</th>
              <th>Rubrique budgétaire</th>
              <th>Montant Alloué</th>
              <th>Reste</th>
              <th>Pièces jointe</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="expense-tab-panel" id="tab-depense">
        <div class="expense-controls">
          <div class="expense-search-box">
            <input type="text" class="expense-filter-input" id="depenseSearch" placeholder="Recherchez..."> <i class="fa fa-search"></i>
          </div>
          <div class="expense-actions">
            <a href="#" id="addDepenseBtn" class="add-expense-btn">Ajouter dépense</a>
            <button class="expense-icon-btn" title="Exporter"><img width="20px" src="/wp-content/plugins/plateforme-master/images/icons/upload-red.png" alt=""></button>
          </div>
        </div>
        <table class="expense-table" id="depenseTable">
          <thead>
            <tr>
              <th>Ref</th>
              <th>Rubrique budgétaire</th>
              <th>Désignation</th>
              <th>Montant</th>
              <th>Pièces jointe</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal: Équipe -->
<div class="modal-overlay" id="modalObjectifs" style="display:none;">
  <div class="popup-container">
    <div class="popup-header">
      <h2>Définir l'équipe</h2>
      <button class="btn-enregistrer" id="btnSaveEquipe">Enregistrer</button>
    </div>
    <form class="popup-form">
      <div id="membres-container">
        <div class="form-section-box">
          <div class="form-group">
            <label for="membre">Membre</label>
            <select id="membre"><option value="">Sélectionnez...</option></select>
          </div>
          <div class="form-group">
            <label for="role">Rôle</label>
            <select id="role">
              <option value="">Sélectionnez...</option>
              <option value="responsable">Responsable Du Projet</option>
              <option value="co-responsable">Co-responsable</option>
              <option value="membre">Membre</option>
            </select>
          </div>
          <div class="form-group">
            <label for="doc_membre">Pièce jointe</label>
            <div class="input-upload" style="display:flex;gap:8px">
              <input type="text" id="doc_membre_text" placeholder="importer" readonly
                     style="flex:1;padding:8px;border:1px solid #ddd;border-radius:6px;">
              <button type="button" id="btnPickFile" class="importer-btn"
                      style="padding:8px 14px;border-radius:6px;border:0;background:#b8ad8a;color:#fff;cursor:pointer;">
                <i class="fa-solid fa-upload" style="margin-right:6px"></i>Importer
              </button>
              <input type="file" id="doc_membre" style="display:none" />
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Pièces jointes -->
<div class="modal-overlay" id="modalPiecesJointes" style="display:none;">
  <div class="popup-container">
    <div class="popup-header">
      <h2>Pièces jointes associées au projet</h2>
      <button class="btn-enregistrer" id="btnSavePiecesJointes">Enregistrer</button>
    </div>
    <form class="popup-form">
  <div class="form-section-box">
    <div class="form-group">
      <label for="type-document">Type de Document</label>
      <input type="text" id="type-document" name="type_document" required>
    </div>
    <div class="form-group">
      <label for="fichier">Fichier</label>
      <input type="file" id="fichier" name="fichier">
    </div>
    <div class="form-group">
      <label for="version">Version</label>
      <input type="text" id="version" name="version" placeholder="Ex: 1.0">
    </div>
  </div>
</form>
  </div>
</div>

<!-- Modal: Ajouter Phases -->
<div class="modal-overlay" id="modalAjouterPhases" style="display:none;">
  <div class="popup-container popup-container-phases">
    <div class="popup-header">
      <h2>Ajouter les Phases</h2>
      <button class="btn-enregistrer" id="btnSavePhases">Enregistrer</button>
    </div>
    <form class="popup-form">
      <div id="phases-container">
        <div class="phase-section">
          <div class="form-group"><label>Titre Du Phase</label><input type="text"></div>
          <div class="form-group"><label>Etat</label>
            <select><option>Prévu</option><option>En cours</option><option>Terminé</option></select>
          </div>
        </div>
      </div>
      <button type="button" class="add-phase-button" id="addPhaseBtn">➕ Ajouter Une Phase</button>
    </form>
  </div>
</div>

<!-- Modal: Ajouter Tâche -->
<div class="modal-overlay" id="modalAjouterTache" style="display:none;">
  <div class="popup-container popup-container-phases">
    <div class="popup-header">
      <h2>Ajouter tâche</h2>
      <button class="btn-enregistrer" id="btnSaveTaches">Enregistrer</button>
    </div>
    <form class="popup-form">
      <div id="add-task-phase-header" class="phase-header-info"></div>
      <div id="taches-container"></div>
      <button type="button" class="add-phase-button add-task-button-inner">
        <img width="20" src="/wp-content/plugins/plateforme-master/images/icons/Groupe 335.png" alt="">
        Ajouter Une Tache
      </button>
    </form>
  </div>
</div>

<!-- Modal: Modifier Phase -->
<div class="modal-overlay" id="modalModifierPhase" style="display:none;">
  <div class="popup-container popup-container-phases">
    <div class="popup-header">
      <h2 id="modifierPhaseTitle">Modifier la phase</h2>
      <button class="btn-enregistrer" id="btnSavePhase">Enregistrer</button>
    </div>
    <form class="popup-form">
      <input type="hidden" id="modifierPhaseId">
      <div class="form-group"><label for="modifierPhaseTitre">Titre de la phase</label><input type="text" id="modifierPhaseTitre"></div>
      <div class="form-group"><label for="modifierPhaseEtat">État</label>
        <select id="modifierPhaseEtat">
          <option value="Prévu">Prévu</option>
          <option value="En cours">En cours</option>
          <option value="Terminé">Terminé</option>
        </select>
      </div>
    </form>
  </div>
</div>

<!-- Modal: Détails des Tâches -->
<div class="modal-overlay" id="modalDetailsTaches" style="display:none;">
  <div class="popup-container popup-container-phases">
    <div class="popup-header">
      <h2>Détails des tâches</h2>
      <button class="btn-enregistrer" onclick="closeModal('modalDetailsTaches')">Fermer</button>
    </div>
    <div class="popup-form" id="accordionTaches"></div>
  </div>
</div>

<!-- Modal: Modifier Tâche -->
<div class="modal-overlay" id="modalModifierTache" style="display:none;">
  <div class="popup-container popup-container-phases">
    <div class="popup-header">
      <h2 id="modifierTacheTitle">Modifier : </h2>
      <button class="btn-enregistrer" id="btnSaveModifierTache">Enregistrer</button>
    </div>
    <form class="popup-form">
      <div class="form-group"><label>Titre</label><input type="text" id="modifierTacheInputTitre"></div>
      <div class="form-group"><label>État</label>
        <select id="modifierTacheInputEtat">
          <option value="Prévu">Prévu</option>
          <option value="En cours">En cours</option>
          <option value="Terminé">Terminé</option>
        </select>
      </div>
    </form>
  </div>
</div>
<!-- Modal for Ajouter Rubrique -->
<div class="modal-overlay" id="modalAjouterRubrique" style="display: none;">
    <div class="popup-container">
        <div class="popup-header">
            <h2>Rubriques budgétaire</h2>
            <button class="btn-enregistrer" id="btnSaveRubrique">Enregistrer</button>
        </div>
        <form class="popup-form">
            <div class="montant-total-box">
                <span class="label">Montant Total :</span>
                <span class="value" id="montanttotal"></span>
            </div>
            <div id="rubriques-container">
                <div class="rubrique-item-container">
                    <div class="form-section-box">
                        <div class="form-group">
                            <label>Rubrique Budgétaire</label>
                            <input type="text" id="rubriqueNom">
                        </div>
                        <div class="form-group">
                            <label>Montant a ne pas dépassé :</label>
                            <input type="number" id="rubriqueMontant">
                        </div>
                          <div class="form-group">
                            <label>Pièce jointe</label>
                            <div class="input-file-wrapper">
                                <input class="input-file-text" readonly placeholder="importer">
                                <input type="file" id="rubriqueFile" class="input-file-hidden" style="display:none">
                                <button type="button" class="btn-importer">
                                    <img src="/wp-content/plugins/plateforme-master/images/icons/27) Icon-uploadwhite.png"
                                        alt="Icon-uploadwhite.png" width="12px">
                                    Importer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--<button type="button" class="add-rubrique-button">
                <img width="20px" src="/wp-content/plugins/plateforme-master/images/icons/Groupe 335.png"
                    alt="Groupe 335.png">
                Ajouter Autre
            </button>-->
        </form>
    </div>
</div>


<!-- Modal for Ajouter Dépense -->
<div class="modal-overlay" id="modalAjouterDepense" style="display: none;">
    <div class="popup-container">
        <div class="popup-header">
            <h2>Dépense</h2>
            <button class="btn-enregistrer" id="btnSaveDepense">Enregistrer</button>
        </div>
        <form class="popup-form">
            <div class="form-group">
                <label>Rubrique Budgétaire</label>
                <select id="rubriques-select"></select>
            </div>
            <div class="montant-alloue-box">
                <span class="label">Montant Alloué :</span>
                <span class="value"></span>
            </div>
            <div class="form-group">
                <label>Montant</label>
                <div class="input-with-unit">
                    <input type="number" id="depenseMontant" value="">
                    <span>TND</span>
                </div>
            </div>
            <div class="form-group">
                <label>Désignation</label>
                <textarea rows="4" id="depenseDesignation"></textarea>
            </div>
             <div class="form-group">
                <label>Pièce jointe</label>
                <div class="input-file-wrapper">
                    <input class="input-file-text" readonly placeholder="importer">
                    <input type="file" id="depenseFile" class="input-file-hidden" style="display:none">
                    <button type="button" class="btn-importer"><i class="fa-solid fa-filter"></i>
                        Importer</button>
                </div>
            </div>
            
        </form>
    </div>
</div>


<!-- Modal for Modifier Rubrique -->
<div class="modal-overlay" id="modalModifierRubrique" style="display: none;">
    <div class="popup-container">
        <div class="popup-header">
            <h2>Modifier Rubrique</h2>
            <button class="btn-enregistrer">Enregistrer</button>
        </div>
        <form class="popup-form">
            <div class="montant-total-box">
                <span class="label">Montant Total :</span>
                <span class="value" id="montanttotal2"></span>
            </div>
            <div class="form-section-box">
                <div class="form-group">
                    <label>Rubrique Budgétaire</label>
                    <input type="text" value="">
                </div>
                <div class="form-group">
                    <label>Montant a ne pas dépassé :</label>
                    <input type="number" value="">
                </div>
                <div class="form-group">
                    <label>Pièce jointe</label>
                    <div class="input-file-wrapper">
                        <input class="input-file-text" readonly placeholder="importer">
                        <input type="file" class="input-file-hidden" style="display:none">
                        <button type="button" class="btn-importer">
                            <!-- <i class="fa fa-file-arrow-up"></i>  -->
                            <img src="/wp-content/plugins/plateforme-master/images/icons/27) Icon-uploadwhite.png"
                                alt="Icon-uploadwhite.png" width="12px">
                            Importer
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Modifier Dépense -->
<div class="modal-overlay" id="modalModifierDepense" style="display: none;">
    <div class="popup-container">
        <div class="popup-header">
            <h2>Modifier Dépense</h2>
            <button class="btn-enregistrer">Enregistrer</button>
        </div>
        <form class="popup-form">
            <div class="form-group">
                <label>Rubrique Budgétaire</label>
                <select id="rubriques-select2"></select>

            </div>
            <!--<div class="montant-alloue-box">
                <span class="label">Montant Alloué :</span>
                <span class="value"></span>
            </div>-->
            <div class="form-group">
                <label>Montant</label>
                <div class="input-with-unit">
                    <input type="text" value="">
                    <span>TND</span>
                </div>
            </div>
            <div class="form-group">
                <label>Désignation</label>
                <textarea rows="4"></textarea>
            </div>
            <div class="form-group">
                <label>Pièce jointe</label>
                <div class="input-file-wrapper">
                    <input class="input-file-text" readonly placeholder="importer">
                    <input type="file" class="input-file-hidden" style="display:none">
                    <button type="button" class="btn-importer"><i class="fa-solid fa-filter"></i>
                        Importer</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php if (function_exists('wp_create_nonce')) : ?>
<script>
  window.wpApiSettings = window.wpApiSettings || {};
  window.wpApiSettings.nonce = '<?php echo wp_create_nonce('wp_rest'); ?>';
  window.wpApiSettings.restURL = '<?php echo esc_url_raw(rest_url()); ?>';
</script>
<?php endif; ?>

<script>
/* ===== Variables globales ===== */
const REST_BASE = (window?.wpApiSettings?.restURL || '/wp-json').replace(/\/$/, '');
const API_NS    = '/plateforme-pmo/v1';
const NONCE     = window?.wpApiSettings?.nonce || window?.PMSettings?.nonce || '';

const baseHeaders = { Accept: 'application/json' };
if (NONCE) baseHeaders['X-WP-Nonce'] = NONCE;

/* ===== Utils ===== */
const ICON_ATTACH = '/wp-content/plugins/plateforme-master/images/icons/27) Icon-attach-2.png';
const ICON_TRASH  = '/wp-content/plugins/plateforme-master/imagesED/trash-2-red.png';
function closeModal(id){ const m=document.getElementById(id); if(m) m.style.display='none'; }
function fmtMoney(v){ return isNaN(+v)?'—':(+v).toLocaleString('fr-FR')+' TND'; }

function getProjectId(){
  const q = new URLSearchParams(location.search).get('id');
  if (q && !isNaN(+q)) return parseInt(q,10);
  const root = document.getElementById('projectRoot'); // optionnel (fallback data-attr)
  const d = root?.dataset?.projectId;
  if (d && !isNaN(+d)) return parseInt(d,10);
  return null;
}
const PROJECT_ID = getProjectId();

/* ===== Fonctions API ===== */
async function fetchJSON(url, opts = {}){
  const r = await fetch(url, { credentials:'same-origin', headers: baseHeaders, ...opts });
  const t = await r.text();
  let j; try{ j=JSON.parse(t); }catch{ j={raw:t}; }
  if(!r.ok){ throw new Error(j?.message || ('HTTP '+r.status)); }
  return j;
}
const apiGet = (p)   => fetchJSON(REST_BASE + p);
const apiPost = (p,b)=> fetchJSON(REST_BASE + p, { method:'POST', headers:{...baseHeaders,'Content-Type':'application/json'}, body: JSON.stringify(b||{}) });
const apiPut  = (p,b)=> fetchJSON(REST_BASE + p, { method:'PUT',  headers:{...baseHeaders,'Content-Type':'application/json'}, body: JSON.stringify(b||{}) });
const apiDel  = (p)  => fetchJSON(REST_BASE + p, { method:'DELETE' });

/* Compat */
async function wpFetch(endpoint){
  const r = await fetch(`${REST_BASE}${endpoint}`, { credentials:'same-origin', headers:{ 'X-WP-Nonce': NONCE } });
  return r.json();
}

/* ===== Dépenses (table) ===== */
async function loadDepenses(projetId){
  try{
    const res = await apiGet(`${API_NS}/projets/${projetId}/depenses`);
    const tb  = document.querySelector('#depenseTable tbody');
    tb.innerHTML = res.map(d => {
      const ref = d.ref || (d.budget_id ? `BUD-${d.budget_id}` : '—');
      return `
        <tr>
          <td>${ref}</td>
          <td>${d.budget_rubrique || '—'}</td>
          <td>${d.designation || '—'}</td>
          <td>${fmtMoney(d.montant || 0)}</td>
          <td>${d.piece_url ? `<a href="${d.piece_url}" target="_blank"><img width="20" src="/wp-content/plugins/plateforme-master/images/icons/upload-red.png" alt=""></a>` : '—'}</td>
          <td>
            <div class="dropdown">
              <button class="custom-icon-button dropdown-toggle">⋮</button>
              <div class="dropdown-menu">
                <a href="#" class="dropdown-item js-modifier-depense" data-id="${d.id}">Modifier</a>
                <a href="#" class="dropdown-item js-delete-depense" data-id="${d.id}">Supprimer</a>
              </div>
            </div>
          </td>
        </tr>`;
    }).join('');
  }catch(err){
    console.warn('Erreur chargement dépenses:', err.message);
    document.querySelector('#depenseTable tbody').innerHTML = '<tr><td colspan="6">Erreur de chargement</td></tr>';
  }
}
async function fillRubriquesSelect(selectId, projectId, selectedId=null){
  const select = document.getElementById(selectId);
  if(!select) return;

  select.innerHTML = '<option value="">Chargement…</option>';
  try{
    const rubriques = await apiGet(`${API_NS}/projets/${projectId}/budgets`);
    if(Array.isArray(rubriques) && rubriques.length){
      select.innerHTML = '<option value="">Sélectionnez une rubrique…</option>';
      rubriques.forEach(r=>{
        const opt = document.createElement('option');
        opt.value = String(r.id);
        opt.textContent = r.rubrique || '—';
        select.appendChild(opt);
      });
      if (selectedId != null) {
        select.value = String(selectedId); // <— après avoir rempli
      }
    }else{
      select.innerHTML = '<option value="">Aucune rubrique disponible</option>';
    }
  }catch(e){
    console.warn('Erreur rubriques:', e.message);
    select.innerHTML = '<option value="">Erreur de chargement</option>';
  }
}

/* ===== DOM Ready ===== */
document.addEventListener('DOMContentLoaded', async () => {
  if(!PROJECT_ID){ alert('ID projet manquant'); return; }

  /* Inputs fichier (rubrique + dépense) */
  document.querySelector('#modalAjouterRubrique .btn-importer')?.addEventListener('click', () => document.getElementById('rubriqueFile').click());
  document.getElementById('rubriqueFile')?.addEventListener('change', e => {
    document.querySelector('#modalAjouterRubrique .input-file-text').value = e.target.files?.[0]?.name || 'importer';
  });
  document.querySelector('#modalAjouterDepense .btn-importer')?.addEventListener('click', () => document.getElementById('depenseFile').click());
  document.getElementById('depenseFile')?.addEventListener('change', e => {
    document.querySelector('#modalAjouterDepense .input-file-text').value = e.target.files?.[0]?.name || 'importer';
  });

  /* Tabs Dépenses/Budgets */
  document.querySelectorAll('.expense-tab-btn').forEach(btn=>{
    btn.addEventListener('click',()=>{
      document.querySelectorAll('.expense-tab-btn').forEach(b=>b.classList.remove('active'));
      document.querySelectorAll('.expense-tab-panel').forEach(p=>p.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById(btn.dataset.tab)?.classList.add('active');
    });
  });

  /* ===== 1) Infos ===== */
  let project=null;
  try { project = await apiGet(`${API_NS}/projets/${PROJECT_ID}`); } catch(e){ alert(e.message); return; }
  (function renderInfos(){
    const c  = document.getElementById('infos-generales');
    const dd = project.date_debut || '—';
    const df = project.date_fin   || '—';
    const fin= project.financement ? fmtMoney(project.financement) : '—';
    c.innerHTML = `
      <div class="custom-details-item"><span class="custom-details-label">Intitulé complet :</span><span class="custom-details-value">${project.intitule || '—'}</span></div>
      <div class="custom-details-item"><span class="custom-details-label">Période :</span><span class="custom-details-value">${dd} – ${df}</span></div>
      <div class="custom-details-item"><span class="custom-details-label">Financement :</span><span class="custom-details-value">${fin}</span></div>`;
    const ol = document.getElementById('objectifs-list');
    ol.innerHTML = '';
    const objectifs = (project.objectifs || '').split(/\n|;/).map(s=>s.trim()).filter(Boolean);
    (objectifs.length?objectifs:['—']).forEach(o=>{ const li=document.createElement('li'); li.textContent=o; ol.appendChild(li); });
  })();

  /* ===== 2) ÉQUIPE ===== */
  let TEAM=[];
  async function loadTeam(){ try{ TEAM = await apiGet(`${API_NS}/projets/${PROJECT_ID}/membres`); }catch{ TEAM=[]; } renderTeam(); }
  function renderTeam(){
    const tb = document.querySelector('#equipeTable tbody');
    if(!TEAM.length){ tb.innerHTML='<tr><td colspan="6" class="custom-empty-table-cell">Aucun membre trouvé</td></tr>'; return; }
    tb.innerHTML = TEAM.map(m=>`
      <tr data-mid="${m.id}">
        <td>${m.orcid || '—'}</td>
        <td>${m.full_name || '—'}</td>
        <td>${m.role_projet || '—'}</td>
        <td><a href="mailto:${m.email || ''}">${m.email || '—'}</a></td>
        <td class="cell-center">${m.doc_url ? `<a href="${m.doc_url}" target="_blank"><img src="${ICON_ATTACH}" style="width:20px;height:20px"></a>` : `<img src="${ICON_ATTACH}" style="width:20px;height:20px;opacity:.35">`}</td>
        <td class="cell-center"><img src="${ICON_TRASH}" class="js-del-member" title="Supprimer" style="width:20px;height:20px;cursor:pointer"></td>
      </tr>`).join('');
  }
  document.getElementById('ajouterMembreBtn').addEventListener('click', e=>{ e.preventDefault(); document.getElementById('modalObjectifs').style.display='flex'; });
  document.getElementById('modalObjectifs').addEventListener('click', e=>{ if(e.target.id==='modalObjectifs') e.currentTarget.style.display='none'; });
  document.getElementById('btnPickFile')?.addEventListener('click', ()=>document.getElementById('doc_membre').click());
  document.getElementById('doc_membre')?.addEventListener('change', e=>document.getElementById('doc_membre_text').value = e.target.files?.[0]?.name || '');
  document.getElementById('btnSaveEquipe').addEventListener('click', async()=>{
    const user_id = +document.getElementById('membre').value || 0;
    const role    = document.getElementById('role').value || 'membre';
    if(!user_id){ alert('Sélectionnez un membre'); return; }
    const fd = new FormData();
    fd.append('user_id', user_id);
    fd.append('role_projet', role);
    const f = document.getElementById('doc_membre')?.files?.[0]; if (f) fd.append('piece_jointe', f);
    const r = await fetch(`${REST_BASE}${API_NS}/projets/${PROJECT_ID}/membres`, { method:'POST', credentials:'same-origin', headers: NONCE?{'X-WP-Nonce':NONCE}:{}, body: fd });
    const j = await r.json(); if(!r.ok){ alert(j?.message || 'Erreur'); return; }
    document.getElementById('modalObjectifs').style.display='none';
    await loadTeam();
  });
  document.querySelector('#equipeTable').addEventListener('click', async (e)=>{
    const tr = e.target.closest('tr'); if(!tr) return;
    if(e.target.closest('.js-del-member')){
      if(!confirm('Supprimer ce membre ?')) return;
      const mid = tr.dataset.mid;
      try{ await apiDel(`${API_NS}/projets/${PROJECT_ID}/membres/${mid}`); await loadTeam(); }catch(err){ alert(err.message); }
    }
  });

  /* ===== 3) Phases ===== */
  let PHASES=[];
  async function loadPhases(){ try{ PHASES = await apiGet(`${API_NS}/projets/${PROJECT_ID}/phases`); }catch{ PHASES=[]; } renderPhases(); }
  function tagClass(etat){ return /terminé/i.test(etat)?'custom-tag-green':/en\s*cours/i.test(etat)?'custom-tag-yellow':'custom-tag-gray'; }
  function renderPhases(){
    const tb = document.querySelector('#tasksTable tbody'); tb.innerHTML='';
    PHASES.forEach((p,i)=>{
      const pr = Math.max(0, Math.min(100, Number(p.progression||0)));
      tb.insertAdjacentHTML('beforeend', `
        <tr data-pid="${p.id}">
          <td>${i+1}</td>
          <td>${p.titre || p.titre_phase || ''}</td>
          <td class="cell-center"><span class="custom-tag ${tagClass(p.etat)}">${p.etat || '—'}</span></td>
          <td><div class="progress-container"><div class="custom-progress-indicator"><div class="custom-progress-indicator-fill" style="width:${pr}%"></div></div><span class="progress-percentage">${pr}%</span></div></td>
          <td class="custom-table-actions">
            <div class="dropdown">
              <button class="dropdown-toggle custom-icon-button" title="Actions"><i class="fa-solid fa-ellipsis"></i></button>
              <div class="dropdown-menu">
                <a href="#" class="dropdown-item js-phase-add-task" data-phase="${p.id}">Ajouter des tâches</a>
                <a href="#" class="dropdown-item js-phase-details"  data-phase="${p.id}">Détails des tâches</a>
                <a href="#" class="dropdown-item js-phase-edit"     data-phase="${p.id}">Modifier phase</a>
                <a href="#" class="dropdown-item js-phase-done"     data-phase="${p.id}">Marquer terminée</a>
                <a href="#" class="dropdown-item js-phase-del"      data-phase="${p.id}">Supprimer</a>
              </div>
            </div>
          </td>
        </tr>`);
    });
    if(!PHASES.length) tb.innerHTML = '<tr><td colspan="5" style="text-align:center;color:#888">Aucune phase</td></tr>';
  }
  document.addEventListener('click', (e)=>{
    const t = e.target.closest('.dropdown-toggle');
    document.querySelectorAll('.dropdown-menu.show').forEach(m=>m.classList.remove('show'));
    if(t) t.nextElementSibling?.classList.toggle('show');
  });

  const modalAjouterPhases = document.getElementById('modalAjouterPhases');
  document.getElementById('ajouterPhasesBtn').addEventListener('click', e=>{ e.preventDefault(); modalAjouterPhases.style.display='flex'; });
  modalAjouterPhases.addEventListener('click', e=>{ if(e.target.id==='modalAjouterPhases') modalAjouterPhases.style.display='none'; });
  document.getElementById('addPhaseBtn').addEventListener('click', ()=>{
    const wrap = document.createElement('div');
    wrap.className='phase-section';
    wrap.innerHTML = `<div class="form-group"><label>Titre Du Phase</label><input type="text"></div>
                      <div class="form-group"><label>Etat</label><select><option>Prévu</option><option>En cours</option><option>Terminé</option></select></div>
                      <button type="button" class="delete-item-btn">Supprimer ce bloc</button>`;
    wrap.querySelector('.delete-item-btn').onclick = ()=>wrap.remove();
    document.getElementById('phases-container').appendChild(wrap);
  });
  document.getElementById('btnSavePhases').addEventListener('click', async ()=>{
    const sections = Array.from(document.querySelectorAll('#phases-container .phase-section'));
    for (const [idx,s] of sections.entries()){
      const titre = (s.querySelector('input')?.value || '').trim();
      const etat  = s.querySelector('select')?.value || 'Prévu';
      if(!titre) continue;
      const progression = /Terminé/i.test(etat)?100:0;
      try{ await apiPost(`${API_NS}/projets/${PROJECT_ID}/phases`, { titre, etat, progression, position: idx+1 }); }catch(e){ alert('Erreur ajout phase: '+e.message); }
    }
    modalAjouterPhases.style.display='none';
    await loadPhases();
  });

  /* Tâches */
  const modalAjouterTache = document.getElementById('modalAjouterTache');
  const addTaskHeader     = document.getElementById('add-task-phase-header');
  const tachesContainer   = document.getElementById('taches-container');
  const btnSaveTaches     = document.getElementById('btnSaveTaches');
  const btnAddTaskInner   = document.querySelector('.add-task-button-inner');

  function buildMembersOptions(selectedId=null){
    return (TEAM||[]).map(m=>{
      const val = m.user_id || m.id || '';
      const label = m.full_name || m.email || ('ID '+val);
      const sel = (selectedId && String(selectedId)===String(val))?'selected':'';
      return `<option value="${val}" ${sel}>${label}</option>`;
    }).join('') || '<option value="">Aucun membre trouvé</option>';
  }
  function makeTaskSection(preset=null, disableRemove=false){
    const wrap = document.createElement('div');
    wrap.className='task-section';
    const _etat = preset?.etat || 'Prévu';
    wrap.innerHTML = `
      <div class="form-group"><label>Titre</label><input type="text" class="task-titre" value="${preset?.titre || ''}"></div>
      <div class="form-group"><label>Etat</label>
        <select class="task-etat">
          <option ${/en\s*cours/i.test(_etat)?'':'selected'}>Prévu</option>
          <option ${/en\s*cours/i.test(_etat)?'selected':''}>En Cours</option>
          <option ${/terminé/i.test(_etat)?'selected':''}>Terminé</option>
        </select>
      </div>
      <div class="form-group"><label>Membre</label><select class="membres_taches">${buildMembersOptions(preset?.membre_id || preset?.assignee_user_id || null)}</select></div>
      <div class="form-group"><label>Date Debut</label><div class="date-input-container"><input type="date" class="task-date-debut" value="${preset?.date_debut || ''}"></div></div>
      <div class="date-fields-group">
        <div class="form-group"><label>Date Fin Prévu</label><div class="date-input-container"><input type="date" class="task-date-fin" value="${preset?.date_fin_prevu || ''}"></div></div>
        <div class="form-group"><label>Date Limite</label><div class="date-input-container"><input type="date" class="task-date-limite" value="${preset?.date_limite || ''}"></div></div>
      </div>
      ${disableRemove?'':'<button type="button" class="delete-item-btn">Supprimer cette tâche</button>'}`;
    wrap.querySelector('.delete-item-btn')?.addEventListener('click',()=>wrap.remove());
    return wrap;
  }
  function openTaskSidebarForCreate(phase){
    modalAjouterTache.dataset.mode='create';
    modalAjouterTache.dataset.pid = String(phase.id);
    addTaskHeader.innerHTML = `<h4>Phase ${phase.position||''} : ${phase.titre||''}</h4><span class="phase-status">${phase.etat||''}</span>`;
    tachesContainer.innerHTML='';
    tachesContainer.appendChild(makeTaskSection());
    btnAddTaskInner.style.display='';
    modalAjouterTache.style.display='flex';
  }
  async function openTaskSidebarForEdit(pid,tid){
    let t=null; try{ t = await apiGet(`${API_NS}/projets/${PROJECT_ID}/phases/${pid}/taches/${tid}`); }catch(e){ alert('Erreur chargement tâche : '+e.message); return; }
    const phase = PHASES.find(p=>+p.id===+pid) || { id:pid, titre:'', etat:'' };
    modalAjouterTache.dataset.mode='edit';
    modalAjouterTache.dataset.pid = String(pid);
    modalAjouterTache.dataset.tid = String(tid);
    addTaskHeader.innerHTML = `<h4>Modifier la tâche</h4><div class="phase-chip"><b>Phase:</b> ${phase.titre}</div>`;
    tachesContainer.innerHTML='';
    tachesContainer.appendChild(makeTaskSection(t,true));
    btnAddTaskInner.style.display='none';
    modalAjouterTache.style.display='flex';
  }
  btnAddTaskInner?.addEventListener('click', e=>{ e.preventDefault(); tachesContainer.appendChild(makeTaskSection()); });
  modalAjouterTache.addEventListener('click', e=>{ if(e.target.id==='modalAjouterTache') modalAjouterTache.style.display='none'; });
  btnSaveTaches.addEventListener('click', async()=>{
    const mode = modalAjouterTache.dataset.mode || 'create';
    const pid  = +modalAjouterTache.dataset.pid || 0;
    if(!pid){ alert('Phase introuvable'); return; }

    if(mode==='edit'){
      const sec   = tachesContainer.querySelector('.task-section');
      const titre = (sec.querySelector('.task-titre')?.value || '').trim();
      if(!titre){ alert('Titre requis'); return; }
      const etat0 = sec.querySelector('.task-etat')?.value || 'Prévu';
      const etat  = /terminé/i.test(etat0)?'Terminé':/en\s*cours/i.test(etat0)?'En cours':'Prévu';
      const membre= parseInt(sec.querySelector('.membres_taches')?.value || '0',10) || null;
      const d1 = sec.querySelector('.task-date-debut')?.value || null;
      const d2 = sec.querySelector('.task-date-fin')?.value || null;
      const d3 = sec.querySelector('.task-date-limite')?.value || null;
      const tid= +modalAjouterTache.dataset.tid;
      try{ await apiPut(`${API_NS}/projets/${PROJECT_ID}/phases/${pid}/taches/${tid}`, { titre, etat, membre_id:membre, date_debut:d1, date_fin_prevu:d2, date_limite:d3 }); }catch(e){ alert('Erreur mise à jour : '+e.message); return; }
      modalAjouterTache.style.display='none';
      await openModalDetailsTaches(pid); await recalcPhaseProgressFromTasks(pid); return;
    }

    const blocks = Array.from(tachesContainer.querySelectorAll('.task-section'));
    if(!blocks.length){ alert('Ajouter au moins une tâche'); return; }
    for(const b of blocks){
      const titre = (b.querySelector('.task-titre')?.value || '').trim(); if(!titre) continue;
      const etat0 = b.querySelector('.task-etat')?.value || 'Prévu';
      const etat  = /terminé/i.test(etat0)?'Terminé':/en\s*cours/i.test(etat0)?'En cours':'Prévu';
      const membre= parseInt(b.querySelector('.membres_taches')?.value || '0',10) || null;
      const d1 = b.querySelector('.task-date-debut')?.value || null;
      const d2 = b.querySelector('.task-date-fin')?.value || null;
      const d3 = b.querySelector('.task-date-limite')?.value || null;
      try{ await apiPost(`${API_NS}/projets/${PROJECT_ID}/phases/${pid}/taches`, { titre, etat, membre_id:membre, assignee_user_id:membre, date_debut:d1, date_fin_prevu:d2, date_limite:d3 }); }catch(e){ alert('Erreur ajout tâche: '+e.message); }
    }
    modalAjouterTache.style.display='none';
    await recalcPhaseProgressFromTasks(pid); await loadPhases();
  });

  const modalDetailsTaches = document.getElementById('modalDetailsTaches');
  const accordionTaches    = document.getElementById('accordionTaches');
  async function openModalDetailsTaches(pid){
    accordionTaches.innerHTML='Chargement…';
    try{
      const tasks = await apiGet(`${API_NS}/projets/${PROJECT_ID}/phases/${pid}/taches`);
      accordionTaches.innerHTML = (!Array.isArray(tasks) || !tasks.length)
        ? '<div style="padding:8px;color:#777">Aucune tâche</div>'
        : tasks.map(t=>`
            <div class="accordion-item" data-tid="${t.id}" data-pid="${pid}">
              <div class="accordion-header"><strong>${t.titre || ''}</strong> <span class="chip">${t.etat || ''}</span></div>
              <div class="accordion-body">
                <div><b>Membre:</b> ${t.assignee_name || t.membre_nom || '—'}</div>
                <div><b>Début:</b> ${t.date_debut || '—'} | <b>Fin prévu:</b> ${t.date_fin_prevu || '—'} | <b>Limite:</b> ${t.date_limite || '—'}</div>
                <div class="row-actions" style="margin-top:8px"><a href="#" class="js-modifier-tache">Modifier</a> | <a href="#" class="js-supprimer-tache">Supprimer</a></div>
              </div>
            </div>`).join('');
    }catch(e){ accordionTaches.innerHTML='Erreur: '+e.message; }
    modalDetailsTaches.style.display='flex';
  }
  modalDetailsTaches.addEventListener('click', e=>{ if(e.target.id==='modalDetailsTaches') modalDetailsTaches.style.display='none'; });
  accordionTaches.addEventListener('click', async (e)=>{
    const it = e.target.closest('.accordion-item'); if(!it) return;
    const pid = +it.dataset.pid, tid = +it.dataset.tid;
    if(e.target.closest('.js-supprimer-tache')){
      e.preventDefault();
      if(!confirm('Supprimer cette tâche ?')) return;
      try{ await apiDel(`${API_NS}/projets/${PROJECT_ID}/phases/${pid}/taches/${tid}`); await openModalDetailsTaches(pid); await recalcPhaseProgressFromTasks(pid); }catch(err){ alert(err.message); }
      return;
    }
    if(e.target.closest('.js-modifier-tache')){ e.preventDefault(); await openTaskSidebarForEdit(pid,tid); return; }
  });

  /* Actions phase */
  document.addEventListener('click', async (e)=>{
    const btn = e.target.closest('.js-phase-edit, .js-phase-done, .js-phase-del, .js-phase-add-task, .js-phase-details');
    if(!btn) return;
    e.preventDefault();
    const row = btn.closest('tr');
    const pid = parseInt(btn.dataset.phase || row?.dataset.pid || '0', 10);
    if(!pid){ console.warn('Phase ID introuvable'); return; }
    btn.closest('.dropdown-menu')?.classList.remove('show');

    if(btn.matches('.js-phase-add-task')){ const phase = PHASES.find(p=>+p.id===pid); if(phase) openTaskSidebarForCreate(phase); return; }
    if(btn.matches('.js-phase-details')){ await openModalDetailsTaches(pid); return; }
    if(btn.matches('.js-phase-edit')){
      const cur = PHASES.find(p=>+p.id===pid); if(!cur) return;
      document.getElementById('modifierPhaseId').value   = String(pid);
      document.getElementById('modifierPhaseTitre').value= cur.titre || cur.titre_phase || '';
      document.getElementById('modifierPhaseEtat').value = cur.etat || 'Prévu';
      document.getElementById('modifierPhaseTitle').textContent = `Modifier la phase #${pid}`;
      document.getElementById('modalModifierPhase').style.display='flex';
      return;
    }
    if(btn.matches('.js-phase-done')){ try{ await apiPut(`${API_NS}/projets/${PROJECT_ID}/phases/${pid}`, { etat:'Terminé', progression:100 }); await loadPhases(); }catch(err){ alert(err.message); } return; }
    if(btn.matches('.js-phase-del')){ if(!confirm('Supprimer cette phase ?')) return; try{ await apiDel(`${API_NS}/projets/${PROJECT_ID}/phases/${pid}`); await loadPhases(); }catch(err){ alert(err.message); } }
  });
  document.getElementById('btnSavePhase').addEventListener('click', async ()=>{
    const pid  = +document.getElementById('modifierPhaseId').value;
    const titre= document.getElementById('modifierPhaseTitre').value || '';
    const etat = document.getElementById('modifierPhaseEtat').value || 'Prévu';
    const progression = /Terminé/i.test(etat) ? 100 : undefined;
    try{ await apiPut(`${API_NS}/projets/${PROJECT_ID}/phases/${pid}`, { titre, etat, ...(progression!==undefined?{progression}:{}) }); }catch(err){ alert(err.message); return; }
    document.getElementById('modalModifierPhase').style.display='none';
    await loadPhases();
  });

  /* Progression phase */
  async function recalcPhaseProgressFromTasks(pid){
    try{
      const tasks = await apiGet(`${API_NS}/projets/${PROJECT_ID}/phases/${pid}/taches`);
      const total = Array.isArray(tasks)?tasks.length:0;
      const done  = tasks.filter(t=>/terminé/i.test(t.etat||'')).length;
      const anyEnCours = tasks.some(t=>/en\s*cours/i.test(t.etat||''));
      const pct = total ? Math.round((done/total)*100) : 0;
      let etat='Prévu';
      if(pct===100 && total>0) etat='Terminé';
      else if(anyEnCours) etat='En cours';
      await apiPut(`${API_NS}/projets/${PROJECT_ID}/phases/${pid}`, { progression:pct, etat });
    }catch(e){ console.warn('Recalc progression failed:', e.message); }
  }

  /* ===== 4) Pièces jointes ===== */
  async function loadPieces(){ try{ await renderPieces(); }catch{} }
  async function renderPieces(){
    const r = await fetch(`${REST_BASE}${API_NS}/projets/${PROJECT_ID}/pieces`, { credentials:'same-origin', headers: NONCE?{'X-WP-Nonce':NONCE}:{} });
    const data = await r.json();
    const t = document.getElementById('table-pieces');
    t.innerHTML = `<thead><tr><th>Réf. Doc</th><th>Type</th><th>Fichier</th><th>Version</th><th>Date</th></tr></thead>
      <tbody>${data.map(row=>`<tr><td>${row.ref_doc||'—'}</td><td>${row.type_document||'—'}</td><td>${row.doc_url?row.doc_url.split('/').pop():'—'}</td><td>${row.version||'—'}</td><td>${row.date||'—'}</td></tr>`).join('')}</tbody>`;
  }
  document.getElementById('modifierPiecesJointesBtn').addEventListener('click', e=>{
    e.preventDefault();
    document.getElementById('type-document').value='';
    document.getElementById('version').value='';
    document.getElementById('modalPiecesJointes').style.display='flex';
  });
  document.getElementById('modalPiecesJointes').addEventListener('click', e=>{ if(e.target.id==='modalPiecesJointes') e.currentTarget.style.display='none'; });
  document.getElementById('btnSavePiecesJointes').addEventListener('click', async ()=>{
    const type_doc = document.getElementById('type-document').value.trim();
    const version  = document.getElementById('version').value.trim();
    if(!type_doc){ alert('Type de document requis'); return; }
    const fd = new FormData();
    fd.append('type_document', type_doc);
    fd.append('version', version);
    const f = document.getElementById('fichier')?.files?.[0]; if(f) fd.append('fichier', f);
    const r = await fetch(`${REST_BASE}${API_NS}/projets/${PROJECT_ID}/pieces`, { method:'POST', credentials:'same-origin', headers: NONCE?{'X-WP-Nonce':NONCE}:{}, body: fd });
    const j = await r.json(); if(!r.ok){ alert(j?.message || "Erreur lors de l'enregistrement"); return; }
    document.getElementById('modalPiecesJointes').style.display='none'; await loadPieces();
  });

  /* ===== 5) Rubriques budgétaires ===== */
  async function loadBudgets(projetId){
    try{
      const res = await apiGet(`${API_NS}/projets/${projetId}/budgets`);
      const tb  = document.querySelector('#rebriquesTable tbody');
      tb.innerHTML = res.map(r=>`
        <tr>
          <td>${r.ref_code || '—'}</td>
          <td>${r.rubrique || '—'}</td>
          <td>${fmtMoney(r.montant_max || 0)}</td>
          <td>${fmtMoney((r.montant_max||0) - (r.montant_alloue||0))}</td>
          <td>${r.fichier_url ? `<a href="${r.fichier_url}" target="_blank"><img width="20" src="/wp-content/plugins/plateforme-master/images/icons/upload-red.png" alt=""></a>` : '—'}</td>
          <td>
            <div class="dropdown">
              <button class="custom-icon-button dropdown-toggle">⋮</button>
              <div class="dropdown-menu">
                <a href="#" class="dropdown-item js-modifier-rubrique" data-id="${r.id}">Modifier</a>
                <a href="#" class="dropdown-item js-delete-rubrique"   data-id="${r.id}">Supprimer</a>
              </div>
            </div>
          </td>
        </tr>`).join('');
    }catch(err){
      console.warn('Erreur chargement rubriques:', err.message);
      document.querySelector('#rebriquesTable tbody').innerHTML = '<tr><td colspan="6">Erreur de chargement</td></tr>';
    }
  }

  // Ouvrir modal "Ajouter Rubrique"
  document.getElementById('addRubriqueBtn').addEventListener('click', async (e)=>{
    e.preventDefault();
    document.getElementById('rubriqueNom').value='';
    document.getElementById('rubriqueMontant').value='';
    document.getElementById('rubriqueFile').value='';
    document.querySelector('#modalAjouterRubrique .input-file-text').value='importer';
    try{
      const res = await fetch(`${REST_BASE}${API_NS}/projets/${PROJECT_ID}/budgets`, { credentials:'include', headers:{ 'X-WP-Nonce': NONCE } });
      const all = await res.json();
      const total = all.reduce((s,r)=> s + Number(r.montant_alloue||0), 0);
      document.getElementById('montanttotal').textContent = total.toLocaleString('fr-FR')+' TND';
    }catch(err){
      console.warn('Erreur chargement montant total:', err.message);
      document.getElementById('montanttotal').textContent='—';
    }
    document.getElementById('modalAjouterRubrique').style.display='flex';
  });

  // Enregistrer nouvelle Rubrique
  document.getElementById('btnSaveRubrique').addEventListener('click', async (e)=>{
    e.preventDefault();
    const nom   = document.getElementById('rubriqueNom').value.trim();
    const mMax  = document.getElementById('rubriqueMontant').value || 0;
    const file  = document.getElementById('rubriqueFile').files[0];
    if(!nom){ Swal.fire({icon:'warning', text:'Veuillez saisir un nom de rubrique'}); return; }
    const fd = new FormData();
    fd.append('rubrique', nom);
    fd.append('montant_max', mMax);
    fd.append('montant_alloue', 0);
    if(file) fd.append('fichier', file);
    try{
      const res = await fetch(`${REST_BASE}${API_NS}/projets/${PROJECT_ID}/budgets`, { method:'POST', body: fd, credentials:'include', headers:{ 'X-WP-Nonce': NONCE } });
      const data = await res.json();
      if(!res.ok) throw new Error(data.message || 'Erreur serveur');
      Swal.fire({icon:'success', text:'Rubrique ajoutée avec succès'});
      closeModal('modalAjouterRubrique');
      await loadBudgets(PROJECT_ID);
    }catch(err){ Swal.fire({icon:'error', text: err.message}); }
  });

  // Modifier / Supprimer Rubrique
  document.addEventListener('click', async (e)=>{
    const btn = e.target.closest('.js-modifier-rubrique, .js-delete-rubrique');
    if(!btn) return;

    if(btn.classList.contains('js-modifier-rubrique')){
      e.preventDefault();
      const rubriqueId = btn.dataset.id;
      if(!rubriqueId){ Swal.fire({icon:'error', text:'ID de rubrique invalide'}); return; }
      try{
        const r = await apiGet(`${API_NS}/budgets/${rubriqueId}`);
        document.querySelector('#modalModifierRubrique input[type="text"]').value   = r.rubrique || '';
        document.querySelector('#modalModifierRubrique input[type="number"]').value = r.montant_max || 0;
        document.querySelector('#modalModifierRubrique .input-file-text').value     = r.fichier_url ? r.fichier_url.split('/').pop() : 'importer';
        document.getElementById('modalModifierRubrique').dataset.rubriqueId = rubriqueId;
        document.getElementById('modalModifierRubrique').style.display='flex';
      }catch(err){ Swal.fire({icon:'error', text: err.message || 'Erreur au chargement de la rubrique'}); }
      return;
    }

    if(btn.classList.contains('js-delete-rubrique')){
      e.preventDefault();
      const rubriqueId = btn.dataset.id;
      const confirmDlg = await Swal.fire({ title:'Supprimer ?', showCancelButton:true });
      if(!confirmDlg.isConfirmed) return;
      try{ await apiDel(`${API_NS}/budgets/${rubriqueId}`); await loadBudgets(PROJECT_ID); Swal.fire({icon:'success', text:'Rubrique supprimée'}); }
      catch(err){ Swal.fire({icon:'error', text: err.message || 'Suppression impossible'}); }
    }
  });

  // Sauvegarde "Modifier Rubrique"
  document.querySelector('#modalModifierRubrique .btn-enregistrer').addEventListener('click', async ()=>{
    const rubriqueId = document.getElementById('modalModifierRubrique').dataset.rubriqueId;
    if(!rubriqueId) return;
    const nom  = document.querySelector('#modalModifierRubrique input[type="text"]').value.trim();
    const mMax = document.querySelector('#modalModifierRubrique input[type="number"]').value;
    const file = document.querySelector('#modalModifierRubrique input[type="file"]').files[0];

    const fd = new FormData();
    fd.append('rubrique', nom);
    fd.append('montant_max', mMax);
    if(file) fd.append('fichier', file); // POST accepté sur la route d'update

    try{
      const res = await fetch(`${REST_BASE}${API_NS}/budgets/${rubriqueId}`, { method:'POST', credentials:'include', headers:{ 'X-WP-Nonce': NONCE }, body: fd });
      const data = await res.json();
      if(!res.ok) throw new Error(data.message || 'Erreur API');
      Swal.fire({icon:'success', text:'Rubrique mise à jour avec succès'});
      closeModal('modalModifierRubrique');
      await loadBudgets(PROJECT_ID);
    }catch(err){ Swal.fire({icon:'error', text: err.message}); }
  });

  /* ===== 6) Dépenses : open modals / CRUD ===== */
  document.getElementById('addDepenseBtn').addEventListener('click', async (e)=>{
    e.preventDefault();
    document.getElementById('rubriques-select').innerHTML = '<option value="">Chargement...</option>';
    document.getElementById('depenseMontant').value = '';
    document.getElementById('depenseDesignation').value = '';
    document.getElementById('depenseFile').value = '';
    document.querySelector('#modalAjouterDepense .input-file-text').value='importer';
    document.querySelector('#modalAjouterDepense .montant-alloue-box .value').textContent='—';
    try{
      const res = await fetch(`${REST_BASE}${API_NS}/projets/${PROJECT_ID}/budgets`, { credentials:'include', headers:{ 'X-WP-Nonce': NONCE } });
      const rubriques = await res.json();
      const select = document.getElementById('rubriques-select');
      select.innerHTML = '<option value="">Sélectionnez une rubrique...</option>';
      if(rubriques.length) rubriques.forEach(r=> select.innerHTML += `<option value="${r.id}" data-max="${r.montant_max || 0}">${r.rubrique || '—'}</option>`);
      else select.innerHTML = '<option value="">Aucune rubrique disponible</option>';
    }catch(err){
      console.warn('Erreur chargement rubriques:', err.message);
      document.getElementById('rubriques-select').innerHTML = '<option value="">Erreur chargement</option>';
    }
    document.getElementById('modalAjouterDepense').style.display='flex';
  });

  document.getElementById('modalAjouterRubrique').addEventListener('click', e=>{ if(e.target.id==='modalAjouterRubrique') e.currentTarget.style.display='none'; });
  document.getElementById('modalAjouterDepense').addEventListener('click', e=>{ if(e.target.id==='modalAjouterDepense') e.currentTarget.style.display='none'; });

  document.getElementById('rubriques-select').addEventListener('change', (e)=>{
    const sel = e.target.options[e.target.selectedIndex];
    const max = parseFloat(sel?.dataset?.max || 0);
    document.querySelector('#modalAjouterDepense .montant-alloue-box .value').textContent = max.toLocaleString('fr-FR')+' TND';
  });

  // Clicks modifier/supprimer dépense
  document.addEventListener('click', async (e)=>{
    const btn = e.target.closest('.js-modifier-depense, .js-delete-depense');
    if(!btn) return;

    if(btn.classList.contains('js-modifier-depense')){
  e.preventDefault();
  const depenseId = btn.dataset.id;

  // 1) Charger la dépense
  let depense;
  try{
    depense = await apiGet(`${API_NS}/depenses/${depenseId}`);
  }catch(err){
    Swal.fire({icon:'error', text: err.message});
    return;
  }

  // 2) Peupler le select d’édition + présélectionner
  await fillRubriquesSelect('rubriques-select2', PROJECT_ID, depense.budget_id);

  // 3) Remplir les autres champs
  document.querySelector('#modalModifierDepense input[type="text"]').value = depense.montant ?? '';
  document.querySelector('#modalModifierDepense textarea').value = depense.designation || '';
  document.querySelector('#modalModifierDepense .input-file-text').value =
    depense.piece_url ? depense.piece_url.split('/').pop() : 'importer';

  // 4) Ouvrir le modal
  const modal = document.getElementById('modalModifierDepense');
  modal.dataset.depenseId = depenseId;
  modal.style.display = 'flex';
  return;
}

    if(btn.classList.contains('js-delete-depense')){
      e.preventDefault();
      const depenseId = btn.dataset.id;
      const confirmDlg = await Swal.fire({ title:'Supprimer ?', showCancelButton:true });
      if(!confirmDlg.isConfirmed) return;
      try{
        await apiDel(`${API_NS}/depenses/${depenseId}`);  // PLURIEL
        await loadDepenses(PROJECT_ID);
      }catch(err){ Swal.fire({icon:'error', text: err.message}); }
    }
  });

  // Save "modifier dépense"
 document.querySelector('#modalModifierDepense .btn-enregistrer').addEventListener('click', async () => {
  const depenseId  = document.getElementById('modalModifierDepense').dataset.depenseId;
  const rubriqueId = document.getElementById('rubriques-select2').value;
const montant = document.querySelector('#modalModifierDepense input[type="text"]').value;
  const designation= document.querySelector('#modalModifierDepense textarea').value.trim();
  const file       = document.querySelector('#modalModifierDepense input[type="file"]').files[0];

  const fd = new FormData();
  fd.append('budget_id', rubriqueId);
  fd.append('montant', montant.replace(/\s+/g,'').replace(',', '.'));
  fd.append('designation', designation);
  if (file) fd.append('piece_jointe', file);
  fd.append('_method', 'PUT');            // <-- important pour que la route d’update l’accepte

  try {
    const res   = await fetch(`${REST_BASE}${API_NS}/depenses/${depenseId}`, {
      method: 'POST',                     // <-- POST pour accepter multipart
      body: fd,
      credentials: 'include',
      headers: { 'X-WP-Nonce': NONCE }
    });
    const data  = await res.json();
    if (!res.ok) throw new Error(data.message || 'Erreur API');
    Swal.fire({ icon:'success', text:'Dépense modifiée avec succès' });
    closeModal('modalModifierDepense');
    await loadDepenses(PROJECT_ID);
  } catch (err) {
    Swal.fire({ icon:'error', text: err.message });
  }
});
// ---- MODAL "Modifier Dépense": branchement des inputs fichier
document.querySelector('#modalModifierDepense .btn-importer')
  ?.addEventListener('click', () => {
    document.querySelector('#modalModifierDepense input[type="file"]').click();
  });

document.querySelector('#modalModifierDepense input[type="file"]')
  ?.addEventListener('change', (e) => {
    const fileText = document.querySelector('#modalModifierDepense .input-file-text');
    fileText.value = e.target.files?.[0]?.name || 'importer';
  });

  // Save "ajouter dépense"
  document.getElementById('btnSaveDepense').addEventListener('click', async ()=>{
    const rubriqueId = document.getElementById('rubriques-select').value;
    const montant    = document.getElementById('depenseMontant').value;
    const designation= document.getElementById('depenseDesignation').value.trim();
    const file       = document.getElementById('depenseFile').files[0];
    if(!rubriqueId || !montant || !designation){ Swal.fire({icon:'warning', text:'Rubrique, montant et désignation sont requis'}); return; }
    const fd = new FormData();
    fd.append('budget_id', rubriqueId);
    fd.append('montant', montant);
    fd.append('designation', designation);
    if(file) fd.append('piece_jointe', file);
    try{
      const res = await fetch(`${REST_BASE}${API_NS}/projets/${PROJECT_ID}/depenses`, { method:'POST', body: fd, credentials:'include', headers:{ 'X-WP-Nonce': NONCE } });
      const data = await res.json();
      if(!res.ok) throw new Error(data.message || 'Erreur API');
      Swal.fire({icon:'success', text:'Dépense ajoutée avec succès'});
      closeModal('modalAjouterDepense');
      await loadDepenses(PROJECT_ID);
    }catch(err){ Swal.fire({icon:'error', text: err.message}); }
  });

  /* ===== Start ===== */
  await loadTeam();
  await loadPhases();
  await loadPieces();
  await loadBudgets(PROJECT_ID);
  await loadDepenses(PROJECT_ID);
});

/* ===== Helper debug si besoin ===== */
function renderDepenses(data){ console.log('renderDepenses (placeholder):', data); }
</script>
