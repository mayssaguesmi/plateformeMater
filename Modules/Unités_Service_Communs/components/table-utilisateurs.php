<!-- Icônes + Datepicker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
:root{
  --ink:#2A2916; --line:#EBE9D7; --muted:#6E6D55; --chip:#E9E7D7; --chip-active:#A6A485;
  --danger:#D71920; --danger-700:#b8151a; --accent:#C60000;
}
body{font-family:'Segoe UI',sans-serif;background:#f9f9f9}

/* ---------- Container & Tabs ---------- */
.container-card{border-radius:12px;box-shadow:0 0 8px rgba(0,0,0,.05);background:#fff}
.tabs-header{display:flex;gap:10px;}
.tab-btn{
  flex:1;padding:14px 18px;font-weight:700;border:none;background:#A6A485;color:#fff;
  cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;gap:10px;border-radius:12px 12px 0 0
}
.tab-btn.active{background:#fff;color:var(--ink);}
.tab-panel{display:none}
.tab-panel.active{display:block;padding:20px}

/* ---------- Toolbars ---------- */
.table-controls{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;margin-bottom:16px}
.filter-group{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
.search-box{display:flex;align-items:center;border:1px solid #d8d4b7;border-radius:8px;padding:0 10px;background:#fff;min-width:240px}
.search-box i{color:#666;margin-right:6px}
.search-input{padding:10px 6px;border:none;outline:none;font-size:14px;background:#fff;width:100%}

.select-wrap{position:relative}
.select-wrap select{
  padding:10px 36px 10px 12px;border-radius:8px;border:1px solid #d8d4b7;background:#fff;font-size:14px;appearance:none;cursor:pointer;min-width:220px
}
.select-wrap .chev{position:absolute;right:10px;top:50%;transform:translateY(-50%);color:#6b7280}

.filter-actions{display:flex;gap:10px;align-items:center}
.icon-btn{
  width:40px;height:40px;background:#fff;border-radius:10px;border:1px solid #ddd;
  box-shadow:0 0 5px rgba(0,0,0,.06);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--accent)
}
.add-btn{
  background:var(--danger);color:#fff;border:none;border-radius:8px;padding:10px 16px;font-weight:700;display:flex;gap:8px;align-items:center
}
.add-btn:hover{background:var(--danger-700)}

/* ---------- Table (head/body séparés) ---------- */
.table-wrap{background:#fff;border-radius:12px;padding:6px}
.table{width:100%;border-collapse:separate;border-spacing:0}
.table thead{background:#f3f1e9;transform:translateY(-12px)}
.table th,.table td{padding:14px;text-align:left}
.table th{border:0}
.table td{border:1px solid var(--line)}
/* coins arrondis */
.table thead tr:first-child th:first-child{border-top-left-radius:12px;border-bottom-left-radius:12px}
.table thead tr:first-child th:last-child{border-top-right-radius:12px;border-bottom-right-radius:12px}
.table tbody tr:first-child td:first-child{border-top-left-radius:12px}
.table tbody tr:first-child td:last-child{border-top-right-radius:12px}
.table tbody tr:last-child td:first-child{border-bottom-left-radius:12px}
.table tbody tr:last-child td:last-child{border-bottom-right-radius:12px}

/* Actions dropdown */
.actions{position:relative;display:inline-block}
.action-btn{background:transparent;border:none;font-size:20px;cursor:pointer;padding:5px;width:36px;height:36px;border-radius:8px}
.action-btn:hover{background:#e6e6de}
.dropdown-menu{
  display:none;position:absolute;top:100%;right:0;min-width:200px;background:#fff;border:1px solid #d8d4b7;border-radius:8px;
  box-shadow:0 4px 8px rgba(0,0,0,.1);z-index:1000;padding:6px 0
}
.dropdown-menu a{display:flex;align-items:center;gap:10px;padding:9px 14px;text-decoration:none;font-size:14px;color:var(--ink)}
.dropdown-menu a:hover{background:#f5f5f5}
.dropdown-menu.show{display:block}

/* Pagination « ‹ 1 2 › » */
.pager{display:flex;justify-content:flex-end;gap:6px;margin:12px 6px 0}
.pbtn{border-radius:8px;border:2px solid var(--accent);background:#fff;color:var(--accent);font-weight:600;cursor:pointer;padding:6px 10px;min-width:34px}
.pbtn.active{background:var(--accent);color:#fff}
.pbtn.disabled{opacity:.5;cursor:default}

/* ---------- Sidebars ---------- */
.overlay{position:fixed;inset:0;background:rgba(0,0,0,.5);display:none;justify-content:flex-end;z-index:9999}
.drawer{background:#fff;width:480px;height:100%;display:flex;flex-direction:column;box-shadow:-4px 0 10px rgba(0,0,0,.1)}
.drawer-header{display:flex;justify-content:space-between;align-items:center;padding:18px 22px;box-shadow:0 5px 16px #0001}
.drawer-header h2{margin:0;font-size:18px;color:#2A2916}
.drawer-save{background:#c62828;color:#fff;border:none;padding:8px 16px;border-radius:6px;cursor:pointer}
.drawer-body{padding:22px;overflow:auto}
.form-group{margin-bottom:14px}
.form-group label{display:block;font-weight:600;color:#6E6D55;font-size:14px;margin-bottom:6px}
.form-group input,.form-group select,.form-group textarea{
  width:100%;padding:10px 12px;border:1px solid #b5af8e;border-radius:7px;font-size:14px
}
.form-row{display:flex;gap:12px}
.form-row .form-group{flex:1}
.radio-row{display:flex;gap:18px;align-items:center}
.help{font-size:12px;color:#777;margin-top:4px}

/* Patch header tableaux (force le fond sur chaque th) */
.container-card .table thead th{
  background-color: #f3f1e9 !important;  /* la couleur voulue */
}

/* (facultatif) si le fond “passe derrière” après le translate */
.container-card .table thead th{
  position: relative;
  z-index: 1;
  /* Option fallback custom */
.drawer input[type="radio"].radio-red{
  appearance:none; -webkit-appearance:none; -moz-appearance:none;
  width:16px;height:16px;border:2px solid #C60000;border-radius:50%;
  display:inline-grid;place-content:center;cursor:pointer;background:#fff;
}
.drawer input[type="radio"].radio-red::before{
  content:""; width:8px;height:8px;border-radius:50%;
  transform:scale(0); transition:transform .12s ease-in-out; background:#C60000;
}
.drawer input[type="radio"].radio-red:checked::before{ transform:scale(1); }

}
/* Radios en rouge dans les sidebars */
.drawer input[type="radio"]{
  accent-color: #C60000;   /* rouge de ta charte */
}

</style>

<div class="container-card">
  <!-- Onglets -->
  <div class="tabs-header">
    <button class="tab-btn active" data-tab="t1">
      <img src="/wp-content/plugins/plateforme-master/images/icons/3270942.png" alt="" style="width:26px" onerror="this.style.display='none'">
      Responsables par équipement
    </button>
    <button class="tab-btn" data-tab="t2">
      <img src="/wp-content/plugins/plateforme-master/images/icons/12669704.png" alt="" style="width:26px" onerror="this.style.display='none'">
      Utilisateurs autorisés
    </button>
  </div>

  <!-- ===== TAB 1 : RESPONSABLES ===== -->
  <div class="tab-panel active" id="t1">
    <div class="table-controls">
      <div class="filter-group">
        <div class="search-box">
          <i class="fa fa-search"></i>
          <input type="text" class="search-input" id="r_search" placeholder="Recherchez...">
        </div>
        <div class="select-wrap">
          <select id="r_equip">
            <option value="">Équipement</option>
            <option>Microscope Electronique</option>
            <option>Serveur HPC</option>
            <option>Spectromètre RMN</option>
          </select>
          <i class="fa-solid fa-chevron-down chev"></i>
        </div>
      </div>
      <div class="filter-actions">
        <button class="add-btn" id="r_add"><i class="fa-solid fa-plus"></i>Ajouter un responsable</button>
        <button class="icon-btn" title="Filtrer"><i class="fa-solid fa-filter"></i></button>
        <button class="icon-btn" title="Exporter"><i class="fa-solid fa-download"></i></button>
      </div>
    </div>

    <div class="table-wrap">
      <table class="table" id="r_table">
        <thead>
          <tr>
            <th style="width:40px"><input type="checkbox" id="r_checkall"></th>
            <th>Équipement</th>
            <th>Responsable principal</th>
            <th>Contact</th>
            <th>Téléphone</th>
            <th style="width:80px">Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <div class="pager" id="r_pager"></div>
    </div>
  </div>

  <!-- ===== TAB 2 : UTILISATEURS ===== -->
  <div class="tab-panel" id="t2">
    <div class="table-controls">
      <div class="filter-group">
        <div class="search-box">
          <i class="fa fa-search"></i>
          <input type="text" class="search-input" id="u_search" placeholder="Recherchez...">
        </div>
        <div class="select-wrap">
          <select id="u_equip">
            <option value="">Équipement</option>
            <option>Microscope Electronique</option>
            <option>Spectromètre RMN</option>
            <option>Tous équipements: BioTech</option>
          </select>
          <i class="fa-solid fa-chevron-down chev"></i>
        </div>
      </div>
      <div class="filter-actions">
        <button class="add-btn" id="u_add"><i class="fa-solid fa-plus"></i>Ajout d’un utilisateur</button>
        <button class="icon-btn" title="Filtrer"><i class="fa-solid fa-filter"></i></button>
        <button class="icon-btn" title="Exporter"><i class="fa-solid fa-download"></i></button>
      </div>
    </div>

    <div class="table-wrap">
      <table class="table" id="u_table">
        <thead>
          <tr>
            <th style="width:40px"><input type="checkbox" id="u_checkall"></th>
            <th>Nom & Prénom</th>
            <th>Rôle / Profil</th>
            <th>Équipement autorisé</th>
            <th>Droits (période / quota)</th>
            <th style="width:80px">Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <div class="pager" id="u_pager"></div>
    </div>
  </div>
</div>

<!-- ===== Sidebar: Ajouter/Modifier un responsable ===== -->
<div class="overlay" id="r_overlay">
  <div class="drawer">
    <div class="drawer-header">
      <h2 id="r_title">Ajouter un responsable</h2>
      <button class="drawer-save" id="r_save">Enregistrer</button>
    </div>
    <div class="drawer-body">
      <div class="form-group">
        <label for="r_eq">Équipement</label>
        <select id="r_eq">
          <option value="">Sélection..</option>
          <option>Microscope Electronique</option>
          <option>Serveur HPC</option>
          <option>Spectromètre RMN</option>
        </select>
      </div>
      <div class="form-group">
        <label for="r_nom">Responsable principal</label>
        <input type="text" id="r_nom" placeholder="Ex: Dr. Mohamed Ali">
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="r_mail">Email professionnel</label>
          <input type="email" id="r_mail" placeholder="prenom.nom@domaine.tn">
        </div>
        <div class="form-group">
          <label for="r_tel">Téléphone</label>
          <input type="text" id="r_tel" placeholder="22 555 444">
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ===== Sidebar: Ajouter/Modifier un utilisateur ===== -->
<div class="overlay" id="u_overlay">
  <div class="drawer">
    <div class="drawer-header">
      <h2 id="u_title">Ajouter un utilisateur</h2>
      <button class="drawer-save" id="u_save">Enregistrer</button>
    </div>
    <div class="drawer-body">
      <div class="form-group">
        <label for="u_user">Utilisateur</label>
        <select id="u_user">
          <option value="">Sélection..</option>
          <option>Nadia Trabelsi</option>
          <option>Sofiene Ben Said</option>
          <option>Rania Ghai</option>
        </select>
      </div>
      <div class="form-group">
        <label for="u_mail">Email professionnel</label>
        <input type="email" id="u_mail" placeholder="ex: n.trabelsi@utm.tn">
      </div>
      <div class="form-group">
        <label for="u_tel">Téléphone</label>
        <input type="text" id="u_tel" placeholder="71 333 222">
      </div>
      <div class="form-group">
        <label for="u_eq">Équipement assigné</label>
        <select id="u_eq">
          <option value="">Sélection..</option>
          <option>Microscope Electronique</option>
          <option>Spectromètre RMN</option>
          <option>Tous équipements: BioTech</option>
        </select>
      </div>
      <div class="form-group">
        <label for="u_periode">Période d’autorisation</label>
        <input type="text" id="u_periode" placeholder="jj/mm/aaaa - jj/mm/aaaa">
        <div class="help">Sélectionnez une plage de dates.</div>
      </div>
      <div class="form-group">
        <label>Droits d’accès</label>
        <div class="radio-row">
          <label><input type="radio" name="droit" value="illimite" class="radio-red" checked> Accès illimité</label>
<label><input type="radio" name="droit" value="quota" class="radio-red"> Quota d'heures</label>

        </div>
      </div>
      <div class="form-group" id="u_quota_wrap" style="display:none;">
        <label for="u_quota">Nombre d’heures</label>
        <input type="number" id="u_quota" min="1" step="1" placeholder="Ex: 20">
      </div>
    </div>
  </div>
</div>

<!-- JS libs -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/fr.js"></script>

<script>
(() => {
  /* ===================== Données statiques ===================== */
  let R = [
    {id:1, equipement:'Microscope Electronique', nom:'Dr. Mohamed Ali', mail:'m.ali@utm.tn', tel:'22 555 444'},
    {id:2, equipement:'Serveur HPC', nom:'Ing. Karim Zouari', mail:'k.zouari@utm.tn', tel:'71 333 222'},
    {id:3, equipement:'Spectromètre RMN', nom:'Mme. Sameh Ben Salem', mail:'s.salem@utm.tn', tel:'71 654 111'}
  ];
  let U = [
    {id:1, nom:'Nadia Trabelsi', role:'Doctorante', eq:'Microscope Electronique', droits:'20H / Mois'},
    {id:2, nom:'Sofiene Ben Said', role:'Chercheur Invité', eq:'Spectromètre RMN', droits:'Accès illimité'},
    {id:3, nom:'Rania Ghai', role:'Technicien Labo', eq:'Tous équipements: BioTech', droits:'Responsable Maintenance'}
  ];

  /* ===================== Helpers UI ===================== */
  const $ = s => document.querySelector(s);
  const $$ = s => Array.from(document.querySelectorAll(s));
  const esc = s => String(s??'').replace(/[&<>"']/g, m=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;' }[m]));
  const paginate = (arr, page, per=5) => arr.slice((page-1)*per, (page)*per);

  /* ===================== Onglets ===================== */
  $$('.tab-btn').forEach(b=>{
    b.addEventListener('click',()=>{
      $$('.tab-btn').forEach(x=>x.classList.remove('active'));
      b.classList.add('active');
      $$('.tab-panel').forEach(p=>p.classList.remove('active'));
      $('#'+b.dataset.tab).classList.add('active');
    });
  });

  /* ===================== TAB 1 : RESPONSABLES ===================== */
  let rFiltered = [...R], rPage=1, rPer=5, rEditId=null;

  const rRender = () => {
    const tb = $('#r_table tbody'); tb.innerHTML = '';
    const rows = paginate(rFiltered, rPage, rPer);
    if (!rows.length){
      tb.innerHTML = `<tr><td colspan="6" style="text-align:center;color:#888;">Aucun enregistrement</td></tr>`;
    } else {
      rows.forEach(o=>{
        const tr = document.createElement('tr'); tr.dataset.id=o.id;
        tr.innerHTML = `
          <td><input type="checkbox" class="row-check"></td>
          <td>${esc(o.equipement)}</td>
          <td>${esc(o.nom)}</td>
          <td>${esc(o.mail)}</td>
          <td>${esc(o.tel)}</td>
          <td>
            <div class="actions">
              <button class="action-btn">⋯</button>
              <div class="dropdown-menu">
                <a href="#" class="r-edit"><i class="fa-regular fa-pen-to-square"></i>Modifier</a>
                <a href="#" class="r-fiche"><i class="fa-regular fa-id-card"></i>Fiche responsable</a>
                <a href="#" class="r-del" style="color:#b8151a"><i class="fa-regular fa-trash-can"></i>Supprimer</a>
              </div>
            </div>
          </td>`;
        tb.appendChild(tr);
      });
    }
    rRenderPager();
  };

  const rRenderPager = () => {
    const total = Math.max(1, Math.ceil(rFiltered.length / rPer));
    const p = $('#r_pager'); p.innerHTML = '';
    const mk = (label, go, dis=false, act=false) => {
      const b = document.createElement('button');
      b.className = `pbtn ${dis?'disabled':''} ${act?'active':''}`.trim();
      b.innerHTML = label; b.disabled = dis; b.addEventListener('click',()=>{ if(!dis){ rPage=go; rRender(); }});
      p.appendChild(b);
    };
    mk('&laquo;',1,rPage===1); mk('&lsaquo;',Math.max(1,rPage-1),rPage===1);
    for(let i=1;i<=total;i++) mk(String(i),i,false,i===rPage);
    mk('&rsaquo;',Math.min(total,rPage+1),rPage===total); mk('&raquo;',total,rPage===total);
  };

  const rApplyFilters = () => {
    const q = ($('#r_search').value || '').toLowerCase();
    const eq = ($('#r_equip').value || '').toLowerCase();
    rFiltered = R.filter(o=>{
      const hay = [o.equipement,o.nom,o.mail,o.tel].join(' ').toLowerCase();
      if (q && !hay.includes(q)) return false;
      if (eq && o.equipement.toLowerCase() !== eq) return false;
      return true;
    });
    rPage = 1; rRender();
  };

  // events zone
  $('#r_search').addEventListener('input', rApplyFilters);
  $('#r_equip').addEventListener('change', rApplyFilters);
  $('#r_checkall').addEventListener('change', e=>{
    $$('#r_table .row-check').forEach(ch => ch.checked = e.target.checked);
  });

  // dropdown actions
  document.body.addEventListener('click', e=>{
    // open/close menu
    const btn = e.target.closest('#r_table .action-btn');
    if(btn){
      const wrap = btn.closest('.actions'); const menu = wrap.querySelector('.dropdown-menu');
      $$('.dropdown-menu').forEach(m=>m.classList.remove('show'));
      menu.classList.add('show'); e.preventDefault(); return;
    }
    if(!e.target.closest('.actions')) $$('.dropdown-menu').forEach(m=>m.classList.remove('show'));

    // action handlers
    const a = e.target.closest('#r_table .dropdown-menu a'); if(a){
      e.preventDefault();
      const tr = a.closest('tr'); const id = Number(tr.dataset.id);
      if(a.classList.contains('r-edit')){ rOpen(true,id); return; }
      if(a.classList.contains('r-fiche')){ alert('Fiche responsable (démo statique).'); return;}
      if(a.classList.contains('r-del')){
        if(confirm('Supprimer ce responsable ?')){ R = R.filter(x=>x.id!==id); rApplyFilters(); }
        return;
      }
    }
  });

  // sidebar Responsable
  const rOpen = (edit=false, id=null) => {
    $('#r_title').textContent = edit ? 'Modifier un responsable' : 'Ajouter un responsable';
    $('#r_overlay').style.display='flex'; document.body.style.overflow='hidden';
    rEditId = null;
    $('#r_eq').value = ''; $('#r_nom').value=''; $('#r_mail').value=''; $('#r_tel').value='';
    if(edit){
      const o = R.find(x=>x.id===id); if(o){ rEditId=id; $('#r_eq').value=o.equipement; $('#r_nom').value=o.nom; $('#r_mail').value=o.mail; $('#r_tel').value=o.tel; }
    }
  };
  const rClose = ()=>{ $('#r_overlay').style.display='none'; document.body.style.overflow=''; rEditId=null; };
  $('#r_add').addEventListener('click', ()=>rOpen(false,null));
  $('#r_overlay').addEventListener('click', e=>{ if(e.target.id==='r_overlay') rClose(); });
  document.addEventListener('keydown', e=>{ if(e.key==='Escape') rClose(); });
  $('#r_save').addEventListener('click', ()=>{
    const eq = $('#r_eq').value.trim(), nom=$('#r_nom').value.trim(), mail=$('#r_mail').value.trim(), tel=$('#r_tel').value.trim();
    if(!eq || !nom){ alert('Équipement et Responsable requis.'); return; }
    if(rEditId){
      const i = R.findIndex(x=>x.id===rEditId); if(i>-1) R[i]={...R[i],equipement:eq,nom,mail,tel};
    }else{
      const nid = R.length ? Math.max(...R.map(x=>x.id))+1 : 1;
      R.push({id:nid,equipement:eq,nom,mail,tel});
    }
    rApplyFilters(); rClose();
  });

  /* ===================== TAB 2 : UTILISATEURS ===================== */
  let uFiltered = [...U], uPage=1, uPer=5, uEditId=null;

  const uRender = () => {
    const tb = $('#u_table tbody'); tb.innerHTML = '';
    const rows = paginate(uFiltered, uPage, uPer);
    if(!rows.length){
      tb.innerHTML = `<tr><td colspan="6" style="text-align:center;color:#888;">Aucun enregistrement</td></tr>`;
    }else{
      rows.forEach(o=>{
        const tr = document.createElement('tr'); tr.dataset.id=o.id;
        tr.innerHTML = `
          <td><input type="checkbox" class="row-check"></td>
          <td>${esc(o.nom)}</td>
          <td>${esc(o.role)}</td>
          <td>${esc(o.eq)}</td>
          <td>${esc(o.droits)}</td>
          <td>
            <div class="actions">
              <button class="action-btn">⋯</button>
              <div class="dropdown-menu">
                <a href="#" class="u-edit"><i class="fa-regular fa-pen-to-square"></i>Modifier</a>
                <a href="#" class="u-fiche"><i class="fa-regular fa-id-card"></i>Fiche utilisateur</a>
                <a href="#" class="u-del" style="color:#b8151a"><i class="fa-regular fa-trash-can"></i>Supprimer</a>
              </div>
            </div>
          </td>`;
        tb.appendChild(tr);
      });
    }
    uRenderPager();
  };

  const uRenderPager = () => {
    const total = Math.max(1, Math.ceil(uFiltered.length / uPer));
    const p = $('#u_pager'); p.innerHTML = '';
    const mk = (label, go, dis=false, act=false) => {
      const b = document.createElement('button');
      b.className = `pbtn ${dis?'disabled':''} ${act?'active':''}`.trim();
      b.innerHTML = label; b.disabled = dis; b.addEventListener('click',()=>{ if(!dis){ uPage=go; uRender(); }});
      p.appendChild(b);
    };
    mk('&laquo;',1,uPage===1); mk('&lsaquo;',Math.max(1,uPage-1),uPage===1);
    for(let i=1;i<=total;i++) mk(String(i),i,false,i===uPage);
    mk('&rsaquo;',Math.min(total,uPage+1),uPage===total); mk('&raquo;',total,uPage===total);
  };

  const uApplyFilters = () => {
    const q = ($('#u_search').value || '').toLowerCase();
    const eq = ($('#u_equip').value || '').toLowerCase();
    uFiltered = U.filter(o=>{
      const hay = [o.nom,o.role,o.eq,o.droits].join(' ').toLowerCase();
      if (q && !hay.includes(q)) return false;
      if (eq && o.eq.toLowerCase() !== eq) return false;
      return true;
    });
    uPage=1; uRender();
  };

  // events zone
  $('#u_search').addEventListener('input', uApplyFilters);
  $('#u_equip').addEventListener('change', uApplyFilters);
  $('#u_checkall').addEventListener('change', e=>{
    $$('#u_table .row-check').forEach(ch => ch.checked = e.target.checked);
  });

  // dropdown actions
  document.body.addEventListener('click', e=>{
    const btn = e.target.closest('#u_table .action-btn');
    if(btn){
      const wrap = btn.closest('.actions'); const menu = wrap.querySelector('.dropdown-menu');
      $$('.dropdown-menu').forEach(m=>m.classList.remove('show'));
      menu.classList.add('show'); e.preventDefault(); return;
    }
    if(!e.target.closest('.actions')) $$('.dropdown-menu').forEach(m=>m.classList.remove('show'));

    const a = e.target.closest('#u_table .dropdown-menu a');
    if(a){
      e.preventDefault();
      const tr = a.closest('tr'); const id = Number(tr.dataset.id);
      if(a.classList.contains('u-edit')){ uOpen(true,id); return; }
      if(a.classList.contains('u-fiche')){ alert('Fiche utilisateur (démo statique).'); return;}
      if(a.classList.contains('u-del')){
        if(confirm('Supprimer cet utilisateur ?')){ U = U.filter(x=>x.id!==id); uApplyFilters(); }
        return;
      }
    }
  });

  // sidebar Utilisateur
  const uOpen = (edit=false, id=null) => {
    $('#u_title').textContent = edit ? 'Modifier un utilisateur' : 'Ajouter un utilisateur';
    $('#u_overlay').style.display='flex'; document.body.style.overflow='hidden';
    uEditId = null;
    $('#u_user').value=''; $('#u_mail').value=''; $('#u_tel').value=''; $('#u_eq').value=''; $('#u_quota').value='';
    document.querySelectorAll('input[name="droit"]').forEach(r=> r.checked = (r.value==='illimite'));
    $('#u_quota_wrap').style.display='none';

    if(edit){
      const o = U.find(x=>x.id===id);
      if(o){
        uEditId=id;
        $('#u_user').value=o.nom; $('#u_mail').value=''; $('#u_tel').value='';
        $('#u_eq').value = (o.eq.includes('Tous équipements')?'Tous équipements: BioTech':o.eq);
        if(/accès illimité/i.test(o.droits)){ document.querySelector('input[name="droit"][value="illimite"]').checked = true; }
        else{
          document.querySelector('input[name="droit"][value="quota"]').checked = true;
          $('#u_quota_wrap').style.display='block';
          const m = o.droits.match(/(\d+)\s*h/i); if(m) $('#u_quota').value = m[1];
        }
      }
    }
  };
  const uClose = ()=>{ $('#u_overlay').style.display='none'; document.body.style.overflow=''; uEditId=null; };
  $('#u_add').addEventListener('click', ()=>uOpen(false,null));
  $('#u_overlay').addEventListener('click', e=>{ if(e.target.id==='u_overlay') uClose(); });
  document.addEventListener('keydown', e=>{ if(e.key==='Escape') uClose(); });
  $('#u_save').addEventListener('click', ()=>{
    const nom=$('#u_user').value.trim(); const eq=$('#u_eq').value.trim();
    const droit = document.querySelector('input[name="droit"]:checked')?.value || 'illimite';
    let droits = (droit==='illimite') ? 'Accès illimité' : ((+$('#u_quota').value||0)+'H / Mois');
    if(!nom || !eq){ alert('Utilisateur et Équipement requis.'); return; }
    if(droit==='quota' && !(+$('#u_quota').value)){ alert("Saisir un quota d'heures."); return; }

    if(uEditId){
      const i = U.findIndex(x=>x.id===uEditId); if(i>-1) U[i] = { ...U[i], nom, eq, droits };
    }else{
      const nid = U.length ? Math.max(...U.map(x=>x.id))+1 : 1;
      U.push({id:nid, nom, role:'—', eq, droits});
    }
    uApplyFilters(); uClose();
  });

  /* Période d’autorisation (UI) */
  flatpickr.localize(flatpickr.l10ns.fr);
  flatpickr('#u_periode', { mode:'range', dateFormat:'d/m/Y' });

  // radio toggle
  $$('.drawer input[name="droit"]').forEach(r=>{
    r.addEventListener('change', ()=>{
      const quota = document.querySelector('input[name="droit"][value="quota"]').checked;
      $('#u_quota_wrap').style.display = quota ? 'block':'none';
    });
  });

  /* ===================== Init ===================== */
  rApplyFilters();
  uApplyFilters();
})();
</script>
