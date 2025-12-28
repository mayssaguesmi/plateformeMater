<!-- External dependencies -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>

<style>
/* ====== Base layout ====== */
.custom-project-wrapper {
  display: flex;
  flex-direction: column;
  gap: 20px;
  background-color: white;
  padding: 20px;
  box-shadow: 0 0 22px #00000012;
  border-radius: 10px;
}

.custom-content-box {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0px 3px 16px #00000029;
}

.custom-content-box:first-child .custom-box-header {
  box-shadow: 0 5px 16px #00000012;
}

.custom-box-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  box-shadow: 0 5px 16px #00000012;
}

.custom-box-header h2 {
  margin: 0;
  font-size: 18px;
  font-weight: 700;
  color: #2A2916;
  display: flex;
  align-items: center;
  gap: 10px;
}

.header-icon {
  border-radius: 8px;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #BF0404;
  position: relative;
}

.header-buttons {
  display: flex;
  gap: 10px;
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

.custom-box-body {
  padding: 20px 20px 40px;
}

/* ====== Info section ====== */
.info-header-container {
  padding: 20px;
  border-bottom: 1px solid #ECEBE3;
  box-shadow: 0 5px 16px #00000012;
}

.info-header-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
}

.info-header-grid h3 {
  margin: 0;
  font-size: 18px;
  font-weight: 700;
  color: #2A2916;
}

.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 40px;
  padding: 20px;
}

.custom-details-list .custom-details-item {
  display: flex;
  padding: 10px 0;
  align-items: center;
}

.custom-details-label {
  font-weight: 500;
  color: #6E6D55;
  width: 150px;
  flex-shrink: 0;
}

.custom-details-value {
  color: #2A2916;
  font-weight: 500;
}

.director-details {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 20px;
}

.profile-pic {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
  flex-shrink: 0;
}

.director-contact .custom-details-item {
  padding: 6px 0;
  border-bottom: none;
}

.director-contact .custom-details-label {
  width: 300px;
}

/* ====== Mission & objectifs ====== */
.mission-text {
  color: #2A2916;
  font-weight: 500;
  line-height: 1.6;
}

.objectives-list {
  list-style-type: none;
  padding-left: 0;
  margin-top: 20px;
}

.objectives-list li {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 12px;
  color: #2A2916;
  font-weight: 500;
}

.objectives-list li i {
  color: #BF0404;
  background-color: #fdf0f0;
  padding: 3px;
  border-radius: 4px;
}

/* ====== Organisation & Structure (table) ====== */
.custom-data-table {
  width: 100%;
  border: none !important;
  box-shadow: none !important;
  border-collapse: separate;
  border-spacing: 0;
  margin-top: 15px;
}

.custom-data-table thead {
  position: static;
  transform: translateY(-15px);
}

.custom-data-table thead th {
  border: 0;
  background: #f3f1e9;
  padding: 12px 15px;
  text-align: left;
  font-weight: 500;
  color: #2A2916;
}

.custom-data-table tbody td {
  border: 1px solid #A6A4853D;
  padding: 12px 15px;
  color: #2A2916;
  vertical-align: middle;
  text-align: left;
  font-weight: 500;
}

.custom-data-table tbody td a {
  color: #3987DF;
  text-decoration: underline;
  font-weight: 500;
}

.custom-data-table tbody tr:first-child td { border-top: 1px solid #A6A4853D !important; }
.custom-data-table thead tr:first-child th:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
.custom-data-table thead tr:first-child th:last-child  { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }
.custom-data-table tbody tr:last-child td:first-child  { border-bottom-left-radius: 12px; }
.custom-data-table tbody tr:last-child td:last-child   { border-bottom-right-radius: 12px; }
.custom-data-table tbody tr:first-child td:first-child { border-top-left-radius: 12px; }
.custom-data-table tbody tr:first-child td:last-child  { border-top-right-radius: 12px; }

/* ====== Bottom grid: Chiffres Clés & Contact ====== */
.section-title {
  font-size: 18px;
  font-weight: 700;
  color: #2A2916;
  margin-bottom: 15px;
}

.bottom-grid {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 20px;
  align-items: stretch;
}

.key-figures-grid {
  margin-top: 31px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 25px 20px;
}

.figure-box {
  background: #fff;
  border-radius: 12px;
  padding: 15px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border: 1px solid #f0f0f0;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  position: relative;
}

.figure-box::before {
  content: '';
  position: absolute;
  left: 0;
  top: 50%;
  transform: translate(-50%, -50%);
  width: 4px;
  height: 35px;
  background-color: #BF0404;
  border-radius: 0 7px 7px 0;
}

.figure-label {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 700;
  color: #2A2916;
}

.figure-value {
  font-weight: 700;
  font-size: 18px;
  color: #2A2916;
  background-color: #ECEBE3;
  padding: 8px 12px;
  border-radius: 6px;
}

.contact-item { margin-bottom: 15px; }
.contact-label {
  display: block;
  font-weight: 700;
  color: #6E6D55;
  margin-bottom: 8px;
}
.contact-value {
  display: block;
  color: #2A2916;
  font-weight: 600;
  padding-bottom: 15px;
  border-bottom: 1px solid #f0f0f0;
}
.contact-item:last-child .contact-value { border-bottom: none; padding-bottom: 0; }

/* ====== Modal & Quill ====== */
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
  width: 500px;
  height: 100%;
  box-shadow: -4px 0 10px rgba(0, 0, 0, 0.1);
  overflow-y: auto;
}

.popup-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 25px;
  box-shadow: 0px 5px 16px #00000029;
  position: sticky;
  top: 0;
  background: white;
  z-index: 10;
}

form.popup-form { padding: 25px; }
.form-group { margin-bottom: 20px; }

.popup-form label {
  display: block;
  font-weight: 600;
  color: #6E6D55;
  margin-bottom: 8px;
  font-size: 14px;
}

.popup-header h2 { font-size: 18px; margin: 0; color: #2A2916; }

.btn-enregistrer {
  background-color: #BF0404;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 700;
}

.popup-form input[type="text"],
.popup-form input[type="email"],
.popup-form input[type="tel"],
.popup-form select,
.popup-form textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #b5af8e;
  border-radius: 7px;
  font-size: 14px;
  box-sizing: border-box;
  background-color: #fff;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}

.popup-form select {
  background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%236E6D55%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E');
  background-repeat: no-repeat;
  background-position: right 10px top 50%;
  background-size: .65em auto;
}

.popup-form textarea { height: 150px; resize: vertical; }

.ql-toolbar.ql-snow {
  border-radius: 6px 6px 0 0;
  background-color: #ECEBE3;
  border: 1px solid #DBD9C3;
  padding: 8px;
}

.empty-placeholder { color: #6E6D55; font-style: italic; }
</style>

<div class="custom-project-wrapper">
  <!-- Présentation -->
  <div class="custom-content-box">
    <div class="custom-box-header">
      <h2>
        <span class="header-icon">
          <img width="30px" src="/wp-content/plugins/plateforme-master/images/icons/2274790.png" alt="2274790.png">
        </span>
        Présentation
      </h2>
      <div class="header-buttons">
        <a href="#" class="custom-button custom-button-alt openmodalObjectifs">Modifier</a>
        <a href="#" class="custom-button custom-button-main">
          <img width="20px" src="/wp-content/plugins/plateforme-master/images/icons/internet-svgrepo-com-white.png" alt="internet"> Publier web
        </a>
      </div>
    </div>
  </div>

  <!-- Informations détaillés & Directeur -->
  <div class="custom-content-box">
    <div class="info-header-container">
      <div class="info-header-grid">
        <h3>Informations détaillés</h3>
        <h3>Directeur</h3>
      </div>
    </div>
    <div class="info-grid">
      <div>
        <div class="custom-details-list">
          <div class="custom-details-item">
            <span class="custom-details-label">Création :</span>
            <span class="custom-details-value empty-placeholder" id="creation-year">Non défini</span>
          </div>
          <div class="custom-details-item">
            <span class="custom-details-label">Localisation :</span>
            <span class="custom-details-value empty-placeholder" id="location">Non défini</span>
          </div>
          <div class="custom-details-item">
            <span class="custom-details-label">Email :</span>
            <span class="custom-details-value empty-placeholder" id="email">Non défini</span>
          </div>
          <div class="custom-details-item">
            <span class="custom-details-label">Téléphone :</span>
            <span class="custom-details-value empty-placeholder" id="phone">Non défini</span>
          </div>
        </div>
      </div>
      <div>
       <div class="director-details">
  <img id="director-avatar" src="/wp-content/plugins/plateforme-master/images/icons/Groupe de masques 467.png"
       alt="Directeur" class="profile-pic">

  <div class="director-contact custom-details-list">
    <div class="custom-details-item">
      <span class="custom-details-label">Nom et prénom du coordinateur :</span>
      <span class="custom-details-value" id="director-name">—</span>
    </div>
    <div class="custom-details-item">
      <span class="custom-details-label">Grade :</span>
      <span class="custom-details-value" id="director-grade">-</span>
    </div>
    <div class="custom-details-item">
      <span class="custom-details-label">Spécialité :</span>
      <span class="custom-details-value" id="director-spec">-</span>
    </div>
    <div class="custom-details-item">
      <span class="custom-details-label">Email académique :</span>
      <span class="custom-details-value" id="director-email">—</span>
    </div>
    <div class="custom-details-item">
      <span class="custom-details-label">Téléphone professionnel :</span>
      <span class="custom-details-value" id="director-phone">—</span>
    </div>
  </div>
</div>

      </div>
    </div>
  </div>

  <!-- Mission & Objectifs -->
  <div class="custom-content-box">
    <div class="custom-box-header">
      <h2>Mission & Objectifs</h2>
    </div>
    <div class="custom-box-body">
      <p class="mission-text empty-placeholder" id="mission-text">Aucune mission définie</p>
      <h4 style="font-weight: 600; color: #6E6D55; font-size: 14px; margin-top: 20px;">Objectifs principaux :</h4>
      <ul class="objectives-list" id="objectives-list">
        <li class="empty-placeholder">Aucun objectif défini</li>
      </ul>
    </div>
  </div>

  <!-- Organisation & Structure -->
  <!-- <div class="custom-content-box">
    <div class="custom-box-header">
      <h2>Organisation & Structure</h2>
    </div>
    <div class="custom-box-body">
      <table class="custom-data-table">
        <thead>
          <tr>
            <th>Nom complet</th>
            <th>Rôle</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody id="org-structure-body">
          <tr>
            <td>Pr. Hatem Ben Youssef</td>
            <td>Directeur CEIP</td>
            <td><a href="mailto:hatem.youssef@utm.tn">hatem.youssef@utm.tn</a></td>
          </tr>
          <tr>
            <td>Mme. Salma Trabelsi</td>
            <td>Responsable Plateformes Digitales</td>
            <td><a href="mailto:salma.trabelsi@utm.tn">salma.trabelsi@utm.tn</a></td>
          </tr>
          <tr>
            <td>Dr. Yassine Ayari</td>
            <td>Chargé Des Partenariats</td>
            <td><a href="mailto:yassine.ayari@etu.utm.tn">yassine.ayari@etu.utm.tn</a></td>
          </tr>
          <tr>
            <td>M. Amine Mejri</td>
            <td>Responsable Support & Assistance</td>
            <td><a href="mailto:amine.mejri@etu.utm.tn">amine.mejri@etu.utm.tn</a></td>
          </tr>
          <tr>
            <td>Mme. Nour Ben Romdhane</td>
            <td>Assistante Administrative</td>
            <td><a href="mailto:nour.T@etu.utm.tn">nour.T@etu.utm.tn</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div> -->

  <!-- Chiffres Clés + Contact & Support -->
  <div class="bottom-grid">
    <div class="custom-content-box">
      <div class="custom-box-header">
        <h2>Chiffres Clés</h2>
      </div>
      <div class="custom-box-body">
        <div class="key-figures-grid">
          <div class="figure-box">
            <span class="figure-label">Projets Accompagnés</span>
            <span class="figure-value" id="fig-projects">+120</span>
          </div>
          <div class="figure-box">
            <span class="figure-label">Documents Déposés</span>
            <span class="figure-value" id="fig-docs">+500</span>
          </div>
          <div class="figure-box">
            <span class="figure-label">Partenariats</span>
            <span class="figure-value" id="fig-partners">35</span>
          </div>
          <div class="figure-box">
            <span class="figure-label">Satisfaction</span>
            <span class="figure-value" id="fig-satisfaction">96%</span>
          </div>
        </div>
      </div>
    </div>

    <div class="custom-content-box">
      <div class="custom-box-header">
        <h2>Contact & Support</h2>
      </div>
      <div class="custom-box-body">
        <div class="contact-info">
          <div class="contact-item">
            <span class="contact-label">Email :</span>
            <span class="contact-value" id="contact-email-display">Non défini</span>
          </div>
          <div class="contact-item">
            <span class="contact-label">Localisation :</span>
            <span class="contact-value" id="contact-location-display">Non défini</span>
          </div>
          <div class="contact-item">
            <span class="contact-label">Téléphone :</span>
            <span class="contact-value" id="contact-phone-display">Non défini</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal-overlay" id="modalObjectifs" style="display: none;">
  <div class="popup-container" id="popupContainerObjectifs">
    <div class="popup-header">
      <h2>Modifier la présentation</h2>
      <button class="btn-enregistrer" id="btnSavePresentation" type="button">Enregistrer</button>
    </div>
    <form class="popup-form">
      <div class="form-group">
        <label for="modal-creation-year">Année de création</label>
        <select id="modal-creation-year" name="creation_year">
          <option value="">Sélectionner une année</option>
          <option value="2025">2025</option>
          <option value="2024">2024</option>
          <option value="2023">2023</option>
          <option value="2022">2022</option>
          <option value="2021">2021</option>
          <option value="2020">2020</option>
          <option value="2019">2019</option>
          <option value="2018">2018</option>
          <option value="2017">2017</option>
        </select>
      </div>
      <div class="form-group">
        <label for="contact-email">Email</label>
        <input type="email" id="contact-email" name="email" placeholder="ex: contact@ceip.tn">
      </div>
      <div class="form-group">
        <label for="modal-location">Localisation</label>
        <input type="text" id="modal-location" name="location" placeholder="ex: Technopole El Ghazala, Tunis">
      </div>
      <div class="form-group">
        <label for="contact-phone">Téléphone</label>
        <input type="tel" id="contact-phone" name="phone" placeholder="ex: +216 71 000 123">
      </div>
      <div class="form-group">
        <label>Mission & Objectifs</label>
        <div id="objectifSpecifique" style="height: 150px;"></div>
      </div>
    </form>
  </div>
</div>

<?php if ( is_user_logged_in() ) : ?>
  <script>
    window.pmsettings = {
      rest_root: <?php echo json_encode( esc_url_raw( rest_url() ) ); ?>,
      nonce:     <?php echo json_encode( wp_create_nonce( 'wp_rest' ) ); ?>
    };
  </script>
<?php else: ?>
  <p>Vous devez être connecté pour accéder à la présentation.</p>
<?php endif; ?>

<script>
(function($) {
  const REST_ROOT = (window.pmsettings && pmsettings.rest_root) || '/wp-json/';
  const NONCE     = (window.pmsettings && pmsettings.nonce) || '';
  const API       = REST_ROOT.replace(/\/$/, '') + '/plateforme-recherche/v1';

  const quill = new Quill('#objectifSpecifique', {
    theme: 'snow',
    placeholder: 'Saisissez la mission et les objectifs...',
    modules: { toolbar: [['bold','italic','link'], [{ list: 'bullet' }]] }
  });

  const esc = (s) => ('' + (s ?? '')).replace(/[&<>"']/g, m => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'}[m]));

  let currentPresentationId = null;

  async function loadPresentation() {
    try {
      const res = await fetch(`${API}/presentation?page=1&per_page=1`, {
        headers: { 'X-WP-Nonce': NONCE, 'Accept': 'application/json' },
        credentials: 'same-origin'
      });

      let rows = [];
      if (res.status === 200) rows = await res.json();
      else if (res.status !== 404) {
        const errBody = await res.json().catch(() => ({}));
        throw new Error(errBody.message || ('HTTP ' + res.status));
      }

      if (rows.length > 0) {
        const data = rows[0];
        currentPresentationId = data.id;
// ----- Bloc Directeur (dyn) -----
if (data.director) {
  renderDirector(data.director, data);
} else {
  const d = await fetchCurrentUserAsDirector();
  renderDirector(d, data);
}

        // Info détaillés
        $('#creation-year').text(data.creation_year || 'Non défini').removeClass('empty-placeholder');
        $('#location').text(data.location || 'Non défini').removeClass('empty-placeholder');
        $('#email').text(data.email || 'Non défini').removeClass('empty-placeholder');
        $('#phone').text(data.phone || 'Non défini').removeClass('empty-placeholder');

        // Contact & Support (autre bloc)
        $('#contact-email-display').text(data.email || 'Non défini');
        $('#contact-location-display').text(data.location || 'Non défini');
        $('#contact-phone-display').text(data.phone || 'Non défini');

        // Mission & Objectifs
        $('#mission-text').html(data.mission_html || 'Aucune mission définie')
                          .toggleClass('empty-placeholder', !data.mission_html);

        let objectives = '';
        if (data.mission_delta) {
          try {
            const delta = JSON.parse(data.mission_delta);
            if (delta.ops) {
              delta.ops.forEach(op => {
                if (op.insert && typeof op.insert === 'string') {
                  const lines = op.insert.split('\n').filter(line => line.trim());
                  lines.forEach(line => {
                    objectives += `
                      <li>
                        <img width="20px" src="/wp-content/plugins/plateforme-master/images/icons/27) Icon-checkmark-square-2.png" alt="ok">
                        ${esc(line)}
                      </li>`;
                  });
                }
              });
            }
          } catch (e) { console.error('Error parsing mission_delta:', e); }
        }
        $('#objectives-list').html(objectives || '<li class="empty-placeholder">Aucun objectif défini</li>')
                             .removeClass('empty-placeholder');
      } else {
        ['#creation-year','#location','#email','#phone'].forEach(sel => $(sel).text('Non défini').addClass('empty-placeholder'));
        $('#contact-email-display').text('Non défini');
        $('#contact-location-display').text('Non défini');
        $('#contact-phone-display').text('Non défini');
        $('#mission-text').html('Aucune mission définie').addClass('empty-placeholder');
        $('#objectives-list').html('<li class="empty-placeholder">Aucun objectif défini</li>').addClass('empty-placeholder');
      }
    } catch (e) {
      console.error('Load presentation error:', e);
      alert("Impossible de charger la présentation.");
    }
  }
function renderDirector(d, presData = {}) {
  // d = { full_name, display_name, grade, specialite, email, phone, avatar } (si dispo)
  const name = d && (d.full_name || d.display_name) ? (d.full_name || d.display_name) : '—';
  $('#director-name').text(name);

  $('#director-grade').text(d && d.grade ? d.grade : '-');
  $('#director-spec').text(d && d.specialite ? d.specialite : '-');

  // Email
  const email = (d && d.email) ? d.email : (presData.email || '—');
  if (email && email !== '—') {
    $('#director-email').html(`<a href="mailto:${email}">${email}</a>`);
  } else {
    $('#director-email').text('—');
  }

  // Téléphone
  const phone = (d && d.phone) ? d.phone : (presData.phone || '—');
  if (phone && phone !== '—') {
    $('#director-phone').html(`<a href="tel:${phone.replace(/\s+/g,'')}">${phone}</a>`);
  } else {
    $('#director-phone').text('—');
  }

  // Avatar
  if (d && d.avatar) {
    $('#director-avatar').attr('src', d.avatar);
  } else {
    $('#director-avatar').attr('src','/wp-content/plugins/plateforme-master/images/icons/Groupe de masques 467.png');
  }
}
async function fetchCurrentUserAsDirector() {
  const PROFILE_API = ( (window.pmsettings && pmsettings.rest_root) || '/wp-json/' ).replace(/\/$/, '') + '/plateforme-profile/v1';
  try {
    const r = await fetch(`${PROFILE_API}/profile`, {
      headers: { 'X-WP-Nonce': (window.pmsettings && pmsettings.nonce) || '', 'Accept':'application/json' },
      credentials: 'same-origin'
    });
    if (!r.ok) throw new Error('HTTP '+r.status);
    const p = await r.json();

    // fabrique un objet "director-like"
    const grade = (p.grade && (p.grade.intitule || p.grade.code)) || '';
    const spec  = (p.specialite && (p.specialite.intitule || p.specialite.code)) || '';

    // email / phone pro si dispo, sinon perso
    let email = '';
    let phone = '';
    try {
      const ai = typeof p.academic_info === 'string' ? JSON.parse(p.academic_info || '{}') : (p.academic_info || {});
      email = ai.email_acad || p.email1 || '';
      phone = ai.tel_pro    || p.tel    || '';
    } catch (_) {
      email = p.email1 || '';
      phone = p.tel    || '';
    }

    return {
      full_name: [p.prenom, p.nom].filter(Boolean).join(' ') || (p.display_name || ''),
      display_name: p.display_name || '',
      grade,
      specialite: spec,
      email,
      phone,
      avatar: p.avatar || ''
    };
  } catch(e) {
    console.warn('Fallback profile fetch failed:', e);
    return null;
  }
}

  // Modal open/close
  const btn   = document.querySelector(".openmodalObjectifs");
  const modal = document.getElementById("modalObjectifs");
  const popup = document.getElementById("popupContainerObjectifs");

  if (btn && modal) {
    btn.addEventListener("click", async function(event) {
      event.preventDefault();
      modal.style.display = "flex";

      // Reset
      $('#modal-creation-year').val('');
      $('#contact-email').val('');
      $('#modal-location').val('');
      $('#contact-phone').val('');
      quill.setContents({});

      // Populate with existing
      if (currentPresentationId) {
        try {
          const res = await fetch(`${API}/presentation/${currentPresentationId}`, {
            headers: { 'X-WP-Nonce': NONCE, 'Accept': 'application/json' },
            credentials: 'same-origin'
          });
          if (!res.ok) throw new Error('HTTP ' + res.status);
          const data = await res.json();
          $('#modal-creation-year').val(data.creation_year || '');
          $('#contact-email').val(data.email || '');
          $('#modal-location').val(data.location || '');
          $('#contact-phone').val(data.phone || '');
          try { quill.setContents(data.mission_delta ? JSON.parse(data.mission_delta) : {}); }
          catch (e) { console.error('Error setting Quill contents:', e); }
        } catch (e) {
          console.error('Populate modal error:', e);
          alert("Impossible de charger les données pour modification.");
        }
      }
    });
  }

  if (modal && popup) {
    modal.addEventListener("click", function(e) {
      if (popup && !popup.contains(e.target) && e.target === modal) {
        modal.style.display = "none";
      }
    });
  }

  // Save
  $('#btnSavePresentation').on('click', async function(e) {
    e.preventDefault();

    const creationYear = $('#modal-creation-year').val();
    const location     = $('#modal-location').val();
    const email        = $('#contact-email').val();
    const phone        = $('#contact-phone').val();
    const missionHtml  = quill.root.innerHTML;
    const missionDelta = JSON.stringify(quill.getContents());

    if (!creationYear || !location || !email) {
      alert('Veuillez renseigner au minimum "Année de création", "Localisation" et "Email".');
      return;
    }

    const payload = {
      creation_year: creationYear,
      location: location,
      email: email,
      phone: phone || null,
      mission_html: missionHtml,
      mission_delta: missionDelta
    };

    try {
      const isUpdate = currentPresentationId !== null;
      const url = isUpdate ? `${API}/presentation/${currentPresentationId}` : `${API}/presentation`;
      const method = isUpdate ? 'PUT' : 'POST';

      const res = await fetch(url, {
        method,
        headers: {
          'X-WP-Nonce': NONCE,
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        credentials: 'same-origin',
        body: JSON.stringify(payload)
      });

      const body = res.ok ? await res.json() : await res.json().catch(() => ({ message: `HTTP ${res.status}` }));
      if (!res.ok) throw new Error(body.message || 'Erreur lors de l\'enregistrement');

      if (!isUpdate) currentPresentationId = body.id;

      await loadPresentation();
      modal.style.display = "none";
      alert("Présentation enregistrée avec succès !");
    } catch (e) {
      console.error('Save presentation error:', e);
      alert("Impossible d’enregistrer la présentation : " + e.message);
    }
  });

  $(document).ready(function() { loadPresentation(); });
})(jQuery);
</script>
