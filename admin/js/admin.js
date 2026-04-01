/**
 * admin.js — Logique front-end du panneau admin FILMOGRAM
 */

'use strict';

// ─── API BASE ───────────────────────────────────────────────
const API = {
  films:    'api/films_api.php',
  users:    'api/users_api.php',
  security: 'api/security_api.php',
};

// ─── ÉTAT ──────────────────────────────────────────────────
let allFilms = [];
let allUsers = [];
let editingFilmId = null;
let editingUserId = null;
let deleteTarget  = { type: null, id: null };

// ─── INIT ───────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  navigateTo('films');
  loadStats();
});

// ─── NAVIGATION ─────────────────────────────────────────────
function navigateTo(section) {
  document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));

  const target = document.getElementById('section-' + section);
  const navBtn  = document.querySelector(`[data-nav="${section}"]`);
  if (target) target.classList.add('active');
  if (navBtn) navBtn.classList.add('active');

  const titles = { films: '🎬 Gestion des Films', users: '👥 Gestion des Utilisateurs', security: '🛡️ Sécurité & 2FA' };
  const topbarTitle = document.getElementById('topbar-title');
  if (topbarTitle) topbarTitle.textContent = titles[section] || '';

  if (section === 'films')    loadFilms();
  if (section === 'users')    loadUsers();
  if (section === 'security') loadSecurity();
}

// ─── STATS ──────────────────────────────────────────────────
async function loadStats() {
  const [filmsData, usersData] = await Promise.all([
    apiFetch(API.films, 'GET'),
    apiFetch(API.users, 'GET'),
  ]);
  if (filmsData?.success) document.getElementById('stat-films').textContent = filmsData.films.length;
  if (usersData?.success) document.getElementById('stat-users').textContent = usersData.users.length;
}

// ─────────────────────────────────────────────────────────────
//  SECTION FILMS
// ─────────────────────────────────────────────────────────────
async function loadFilms() {
  const grid = document.getElementById('admin-films-grid');
  grid.innerHTML = loadingHtml();

  const data = await apiFetch(API.films, 'GET');
  if (!data?.success) { grid.innerHTML = errorHtml(); return; }
  allFilms = data.films;

  renderFilmsGrid(allFilms);
  loadStats();
}

function renderFilmsGrid(films) {
  const grid = document.getElementById('admin-films-grid');
  if (films.length === 0) { grid.innerHTML = emptyHtml('film', 'Aucun film dans le catalogue'); return; }

  grid.innerHTML = films.map(f => `
    <div class="admin-film-card" id="film-card-${f.idfilm}">
      <div class="film-emoji">${f.image || '🎬'}</div>
      <div class="film-info">
        <div class="film-title" title="${esc(f.nom)}">${esc(f.nom)}</div>
        <div class="film-meta">
          <span class="tag tag-genre">${esc(f.genre)}</span>
          <span class="tag tag-price">${parseFloat(f.prix).toFixed(2)}€</span>
          <span class="tag tag-age">${f.age == 0 || f.age == 1 ? 'Tout public' : f.age + '+'}</span>
        </div>
        <div class="film-actions">
          <button class="btn btn-edit btn-sm" onclick="openEditFilm(${f.idfilm})"><i class="fas fa-pencil-alt"></i></button>
          <button class="btn btn-danger btn-sm" onclick="confirmDelete('film', ${f.idfilm}, '${esc(f.nom)}')"><i class="fas fa-trash"></i></button>
        </div>
      </div>
    </div>
  `).join('');
}

function filterFilmsGrid() {
  const q = document.getElementById('film-search')?.value.toLowerCase() || '';
  const filtered = allFilms.filter(f =>
    f.nom.toLowerCase().includes(q) ||
    f.genre.toLowerCase().includes(q)
  );
  renderFilmsGrid(filtered);
}

// ── Modal Ajout / Édition film ──────────────────────────────
function openAddFilm() {
  editingFilmId = null;
  document.getElementById('film-modal-title').textContent = '➕ Ajouter un film';
  document.getElementById('film-form').reset();
  document.getElementById('film-image').value = '🎬';
  openModal('film-modal');
}

function openEditFilm(id) {
  const film = allFilms.find(f => f.idfilm == id);
  if (!film) return;
  editingFilmId = id;
  document.getElementById('film-modal-title').textContent = '✏️ Modifier le film';
  document.getElementById('film-nom').value      = film.nom;
  document.getElementById('film-genre').value    = film.genre;
  document.getElementById('film-prix').value     = film.prix;
  document.getElementById('film-age').value      = film.age;
  document.getElementById('film-image').value    = film.image || '🎬';
  document.getElementById('film-synopsis').value = film.synopsis || '';
  document.getElementById('film-poster').value   = film.poster || '';
  openModal('film-modal');
}

async function saveFilm() {
  const nom      = document.getElementById('film-nom').value.trim();
  const genre    = document.getElementById('film-genre').value.trim();
  const prix     = parseFloat(document.getElementById('film-prix').value);
  const age      = parseInt(document.getElementById('film-age').value);
  const image    = document.getElementById('film-image').value.trim() || '🎬';
  const synopsis = document.getElementById('film-synopsis').value.trim();
  const poster   = document.getElementById('film-poster').value.trim();

  if (!nom || !genre || isNaN(prix) || prix <= 0) {
    showToast('Remplissez tous les champs obligatoires.', 'error'); return;
  }

  const body = { nom, genre, prix, age, image, synopsis, poster };
  if (editingFilmId) body.idfilm = editingFilmId;

  const method = editingFilmId ? 'PUT' : 'POST';
  const data   = await apiFetch(API.films, method, body);

  if (data?.success) {
    closeModal('film-modal');
    showToast(data.message, 'success');
    loadFilms();
  } else {
    showToast(data?.message || 'Erreur lors de la sauvegarde.', 'error');
  }
}

// ─────────────────────────────────────────────────────────────
//  SECTION UTILISATEURS
// ─────────────────────────────────────────────────────────────
async function loadUsers() {
  const tbody = document.getElementById('users-tbody');
  tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;padding:40px">${loadingHtml()}</td></tr>`;

  const data = await apiFetch(API.users, 'GET');
  if (!data?.success) { tbody.innerHTML = `<tr><td colspan="6">${errorHtml()}</td></tr>`; return; }
  allUsers = data.users;

  renderUsersTable(allUsers);
  loadStats();
}

function renderUsersTable(users) {
  const tbody = document.getElementById('users-tbody');
  if (users.length === 0) {
    tbody.innerHTML = `<tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">Aucun utilisateur trouvé</td></tr>`;
    return;
  }
  tbody.innerHTML = users.map(u => {
    const isAdmin = u.role === 'admin';
    const grantIcon = isAdmin ? 'fa-user-minus' : 'fa-user-shield';
    const grantTitle = isAdmin ? 'Révoquer admin' : 'Promouvoir admin';
    const grantClass = isAdmin ? 'btn-role-revoke' : 'btn-role-grant';
    return `
    <tr id="user-row-${u.iduser}">
      <td><span style="color:var(--muted);font-size:12px">#${u.iduser}</span></td>
      <td>
        <div style="display:flex;align-items:center;gap:10px">
          <div class="user-avatar-sm">${esc(u.prenom[0])}${esc(u.nom[0])}</div>
          <span>${esc(u.prenom)} ${esc(u.nom)}</span>
        </div>
      </td>
      <td style="color:var(--muted)">${esc(u.email)}</td>
      <td style="color:var(--muted)">${esc(u.tel || '—')}</td>
      <td><span class="role-badge ${u.role}">${isAdmin ? '👑 Admin' : '👤 User'}</span></td>
      <td>
        <div class="td-actions">
          <button class="btn btn-edit btn-sm" onclick="openEditUser(${u.iduser})" title="Modifier">
            <i class="fas fa-pencil-alt"></i>
          </button>
          <button class="btn btn-sm ${grantClass}" onclick="toggleRole(${u.iduser})" title="${grantTitle}">
            <i class="fas ${grantIcon}"></i>
          </button>
          <button class="btn btn-danger btn-sm" onclick="confirmDelete('user', ${u.iduser}, '${esc(u.prenom)} ${esc(u.nom)}')" title="Supprimer">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </td>
    </tr>`;
  }).join('');
}

function filterUsersTable() {
  const q = document.getElementById('user-search')?.value.toLowerCase() || '';
  const filtered = allUsers.filter(u =>
    `${u.prenom} ${u.nom}`.toLowerCase().includes(q) ||
    u.email.toLowerCase().includes(q)
  );
  renderUsersTable(filtered);
}

function openAddUser() {
  document.getElementById('user-form').reset();
  openModal('user-modal');
}

// ─── ÉDITION UTILISATEUR ──────────────────────────────────────
function openEditUser(id) {
  const u = allUsers.find(u => u.iduser == id);
  if (!u) return;
  document.getElementById('edit-user-id').value      = u.iduser;
  document.getElementById('edit-user-prenom').value  = u.prenom;
  document.getElementById('edit-user-nom').value     = u.nom;
  document.getElementById('edit-user-email').value   = u.email;
  document.getElementById('edit-user-tel').value     = u.tel || '';
  document.getElementById('edit-user-adresse').value = u.adresse || '';
  document.getElementById('edit-user-role').value    = u.role;
  document.getElementById('edit-user-password').value = '';
  openModal('user-edit-modal');
}

async function saveEditUser() {
  const id      = parseInt(document.getElementById('edit-user-id').value);
  const prenom  = document.getElementById('edit-user-prenom').value.trim();
  const nom     = document.getElementById('edit-user-nom').value.trim();
  const email   = document.getElementById('edit-user-email').value.trim();
  const tel     = document.getElementById('edit-user-tel').value.trim();
  const adresse = document.getElementById('edit-user-adresse').value.trim();
  const role    = document.getElementById('edit-user-role').value;
  const password = document.getElementById('edit-user-password').value;

  if (!prenom || !nom || !email) {
    showToast('Remplissez les champs obligatoires.', 'error'); return;
  }

  const body = { iduser: id, prenom, nom, email, tel, adresse, role };
  if (password) body.password = password;

  const data = await apiFetch(API.users, 'PUT', body);
  if (data?.success) {
    closeModal('user-edit-modal');
    showToast(data.message, 'success');
    loadUsers();
  } else {
    showToast(data?.message || 'Erreur lors de la mise à jour.', 'error');
  }
}

// ─── GRANT / REVOKE RÔLE ─────────────────────────────────────
async function toggleRole(id) {
  const u = allUsers.find(u => u.iduser == id);
  if (!u) return;
  const action = u.role === 'admin' ? 'révoquer les droits admin de' : 'promouvoir en admin';
  if (!confirm(`Voulez-vous ${action} ${u.prenom} ${u.nom} ?`)) return;

  const data = await apiFetch(API.users, 'PATCH', { iduser: id });
  if (data?.success) {
    showToast(data.message, 'success');
    loadUsers();
  } else {
    showToast(data?.message || 'Erreur.', 'error');
  }
}

async function saveUser() {
  const prenom   = document.getElementById('user-prenom').value.trim();
  const nom      = document.getElementById('user-nom').value.trim();
  const email    = document.getElementById('user-email').value.trim();
  const adresse  = document.getElementById('user-adresse').value.trim();
  const tel      = document.getElementById('user-tel').value.trim();
  const password = document.getElementById('user-password').value;
  const role     = document.getElementById('user-role').value;

  if (!prenom || !nom || !email || !password) {
    showToast('Champs obligatoires manquants.', 'error'); return;
  }

  const data = await apiFetch(API.users, 'POST', { prenom, nom, email, adresse, tel, password, role });
  if (data?.success) {
    closeModal('user-modal');
    showToast(data.message, 'success');
    loadUsers();
  } else {
    showToast(data?.message || 'Erreur lors de la création.', 'error');
  }
}

// ─────────────────────────────────────────────────────────────
//  SECTION SÉCURITÉ / 2FA
// ─────────────────────────────────────────────────────────────
async function loadSecurity() {
  const data = await apiFetch(API.security, 'GET');
  if (data?.success) {
    const el = document.getElementById('current-question');
    if (el) el.textContent = data.question || 'Aucune question configurée.';
  }
}

async function saveSecurity() {
  const question = document.getElementById('sec-question').value.trim();
  const answer   = document.getElementById('sec-answer').value.trim().toLowerCase();

  if (!question || !answer) {
    showToast('La question et la réponse sont obligatoires.', 'error'); return;
  }

  const data = await apiFetch(API.security, 'PUT', { question, answer });
  if (data?.success) {
    showToast(data.message, 'success');
    document.getElementById('sec-question').value = '';
    document.getElementById('sec-answer').value   = '';
    loadSecurity();
  } else {
    showToast(data?.message || 'Erreur.', 'error');
  }
}

// ─────────────────────────────────────────────────────────────
//  SUPPRESSION (confirm modal)
// ─────────────────────────────────────────────────────────────
function confirmDelete(type, id, name) {
  deleteTarget = { type, id };
  const labelMap = { film: 'le film', user: "l'utilisateur" };
  const txt = document.getElementById('confirm-delete-text');
  if (txt) txt.textContent = `Supprimer ${labelMap[type] || type} « ${name} » ? Cette action est irréversible.`;
  openModal('confirm-modal');
}

async function executeDelete() {
  const { type, id } = deleteTarget;
  const urlMap = { film: API.films, user: API.users };
  const keyMap = { film: 'idfilm', user: 'iduser' };

  const data = await apiFetch(urlMap[type], 'DELETE', { [keyMap[type]]: id });
  closeModal('confirm-modal');

  if (data?.success) {
    showToast(data.message, 'success');
    if (type === 'film') loadFilms();
    if (type === 'user') loadUsers();
  } else {
    showToast(data?.message || 'Erreur lors de la suppression.', 'error');
  }
}

// ─────────────────────────────────────────────────────────────
//  MODALS
// ─────────────────────────────────────────────────────────────
function openModal(id) {
  document.getElementById(id).classList.add('open');
}
function closeModal(id) {
  document.getElementById(id).classList.remove('open');
}

// Fermer au clic sur l'overlay
document.addEventListener('click', e => {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('open');
  }
});

// ─────────────────────────────────────────────────────────────
//  TOAST NOTIFICATIONS
// ─────────────────────────────────────────────────────────────
function showToast(message, type = 'info') {
  const icons = { success: 'fa-check-circle', error: 'fa-times-circle', info: 'fa-info-circle' };
  const container = document.getElementById('toast-container');

  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `<i class="fas ${icons[type]} toast-icon"></i><span>${esc(message)}</span>`;
  container.appendChild(toast);

  setTimeout(() => toast.remove(), 3200);
}

// ─────────────────────────────────────────────────────────────
//  HELPER : fetch wrapper
// ─────────────────────────────────────────────────────────────
async function apiFetch(url, method = 'GET', body = null) {
  try {
    const opts = {
      method,
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
    };
    if (body) opts.body = JSON.stringify(body);
    const res  = await fetch(url, opts);
    return await res.json();
  } catch (e) {
    console.error('API error:', e);
    return null;
  }
}

// ─────────────────────────────────────────────────────────────
//  HELPER : HTML snippets
// ─────────────────────────────────────────────────────────────
function loadingHtml() {
  return `<div class="loading"><div class="spinner"></div><p>Chargement…</p></div>`;
}
function errorHtml() {
  return `<div class="empty-state"><i class="fas fa-exclamation-triangle"></i><p>Erreur de chargement.</p></div>`;
}
function emptyHtml(icon, msg) {
  return `<div class="empty-state" style="grid-column:1/-1"><i class="fas fa-box-open"></i><p>${msg}</p></div>`;
}
function esc(str) {
  if (!str) return '';
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
