<?php
/**
 * admin.php — Tableau de bord principal administrateur FILMOGRAM
 */
require_once 'check_admin_session.php';
require_once '../db_connect.php';

// Récupérer le nom de l'admin connecté
$stmt = $pdo->prepare("SELECT prenom, nom FROM user WHERE iduser = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();
$adminName    = $admin ? htmlspecialchars($admin['prenom'] . ' ' . $admin['nom']) : 'Admin';
$adminInitials = $admin ? strtoupper(substr($admin['prenom'],0,1).substr($admin['nom'],0,1)) : 'A';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard — FILMOGRAM</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="css/admin.css" rel="stylesheet">
</head>
<body>

<div class="app-layout">

  <!-- ════════════════════════════════════════════════════════
       SIDEBAR
  ════════════════════════════════════════════════════════ -->
  <aside class="sidebar" id="sidebar">

    <!-- Logo -->
    <div class="sidebar-logo">
      <div class="logo-icon"><i class="fas fa-film"></i></div>
      <div>
        <h1>FILMOGRAM</h1>
        <span>Administration</span>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">
      <div class="nav-label">Gestion</div>

      <div class="nav-item active" data-nav="films" onclick="navigateTo('films')">
        <i class="fas fa-film"></i>
        <span>Films</span>
        <span class="badge" id="badge-films">…</span>
      </div>

      <div class="nav-item" data-nav="users" onclick="navigateTo('users')">
        <i class="fas fa-users"></i>
        <span>Utilisateurs</span>
        <span class="badge" id="badge-users">…</span>
      </div>

      <div class="nav-label">Paramètres</div>

      <div class="nav-item" data-nav="security" onclick="navigateTo('security')">
        <i class="fas fa-shield-alt"></i>
        <span>Sécurité & 2FA</span>
      </div>
    </nav>

    <!-- Admin info -->
    <div class="sidebar-footer">
      <div class="admin-card">
        <div class="admin-avatar"><?= $adminInitials ?></div>
        <div class="info">
          <div class="name"><?= $adminName ?></div>
          <div class="role">Administrateur</div>
        </div>
        <button class="logout-btn" onclick="location.href='admin_logout.php'" title="Déconnexion">
          <i class="fas fa-sign-out-alt"></i>
        </button>
      </div>
    </div>
  </aside>

  <!-- ════════════════════════════════════════════════════════
       MAIN
  ════════════════════════════════════════════════════════ -->
  <div class="main-content">

    <!-- Topbar -->
    <div class="topbar">
      <div style="display:flex;align-items:center;gap:12px">
        <button class="btn btn-secondary btn-sm" id="menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')" style="display:none">
          <i class="fas fa-bars"></i>
        </button>
        <h2 id="topbar-title">🎬 Gestion des Films</h2>
      </div>
      <div class="topbar-actions">
        <a href="../Vue/film.html" target="_blank" class="view-site-btn">
          <i class="fas fa-external-link-alt"></i> Voir le site
        </a>
      </div>
    </div>

    <!-- Content -->
    <div class="content-area">

      <!-- ── STATS CARDS (visibles dans toutes les sections) ── -->
      <div class="stats-grid" style="display:none" id="stats-row">
        <div class="stat-card">
          <div class="stat-icon films"><i class="fas fa-film"></i></div>
          <div class="stat-info">
            <div class="number" id="stat-films">…</div>
            <div class="label">Films au catalogue</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon users"><i class="fas fa-users"></i></div>
          <div class="stat-info">
            <div class="number" id="stat-users">…</div>
            <div class="label">Utilisateurs inscrits</div>
          </div>
        </div>
      </div>

      <!-- ════════════════════════════════════════════════════
           SECTION : GESTION DES FILMS
           Affiche la grille de films avec la couverture, description
           et les boutons d'actions (Ajouter, Modifier, Supprimer).
      ════════════════════════════════════════════════════ -->
      <div class="section active" id="section-films">

        <div class="section-header">
          <h3>Catalogue de Films</h3>
          <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
            <!-- Barre de recherche pour les films -->
            <div class="search-wrap">
              <i class="fas fa-search"></i>
              <input type="text" id="film-search" placeholder="Rechercher un film…" oninput="filterFilmsGrid()">
            </div>
            <!-- Bouton pour ouvrir le modal d'ajout de film -->
            <button class="btn btn-primary" onclick="openAddFilm()">
              <i class="fas fa-plus"></i> Ajouter un film
            </button>
          </div>
        </div>

        <!-- Conteneur grid rempli dynamiquement par JS (renderFilmsGrid) -->
        <div class="admin-films-grid" id="admin-films-grid"></div>
      </div>

      <!-- ════════════════════════════════════════════════════
           SECTION : GESTION DES UTILISATEURS
           Affiche le tableau des utilisateurs inscrits avec
           leurs informations et rôles. Permet d'éditer,
           promouvoir/rétrograder ou supprimer des comptes.
      ════════════════════════════════════════════════════ -->
      <div class="section" id="section-users">

        <div class="section-header">
          <h3>Utilisateurs inscrits</h3>
          <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
            <!-- Barre de recherche pour les utilisateurs -->
            <div class="search-wrap">
              <i class="fas fa-search"></i>
              <input type="text" id="user-search" placeholder="Rechercher un utilisateur…" oninput="filterUsersTable()">
            </div>
            <!-- Bouton pour ouvrir le modal d'ajout d'utilisateur -->
            <button class="btn btn-primary" onclick="openAddUser()">
              <i class="fas fa-user-plus"></i> Créer un compte
            </button>
          </div>
        </div>

        <!-- Tableau des utilisateurs -->
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>#ID</th>
                <th>Nom complet</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Rôle</th>
                <th>Actions</th>
              </tr>
            </thead>
            <!-- Contenu du tableau rempli dynamiquement par JS (renderUsersTable) -->
            <tbody id="users-tbody">
              <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">Chargement…</td></tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- ════════════════════════════════════════════════════
           SECTION : SÉCURITÉ & 2FA
           Permet à l'administrateur connecté de configurer
           sa question et réponse secrète pour la double auth.
      ════════════════════════════════════════════════════ -->
      <div class="section" id="section-security">

        <div class="section-header">
          <h3>Sécurité & Double Authentification</h3>
        </div>

        <div class="security-card">
          <h4><i class="fas fa-question-circle"></i> Question secrète actuelle</h4>
          <p style="color:var(--muted);font-size:13px;margin-top:6px">
            Cette question est posée lors de votre connexion (étape 2 de la double authentification).
          </p>
          <div class="current-q">
            <i class="fas fa-shield-alt"></i>
            <span id="current-question">Chargement…</span>
          </div>

          <div class="security-divider"></div>

          <h4><i class="fas fa-edit"></i> Modifier la question secrète</h4>
          <p style="color:var(--muted);font-size:13px;margin:6px 0 18px">
            La réponse est insensible à la casse et sera hashée avant stockage.
          </p>

          <!-- Formulaire de sécurité -->
          <div class="form-group" style="margin-bottom:16px">
            <label>Nouvelle question</label>
            <input type="text" id="sec-question" placeholder="Ex : Quel est votre film préféré ?">
          </div>
          <div class="form-group" style="margin-bottom:20px">
            <label>Nouvelle réponse secrète</label>
            <input type="text" id="sec-answer" placeholder="Votre réponse (insensible à la casse)" autocomplete="off">
          </div>
          <button class="btn btn-success" onclick="saveSecurity()">
            <i class="fas fa-save"></i> Enregistrer les changements
          </button>
        </div>
      </div>

    </div><!-- /content-area -->
  </div><!-- /main-content -->
</div><!-- /app-layout -->

<!-- ════════════════════════════════════════════════════════════
     MODAL — Ajouter / Modifier un film
════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="film-modal">
  <div class="modal">
    <h3 id="film-modal-title"><i class="fas fa-film"></i> Ajouter un film</h3>
    <form id="film-form" onsubmit="event.preventDefault(); saveFilm()">
      <div class="form-grid">
        <div class="form-group">
          <label>Titre du film *</label>
          <input type="text" id="film-nom" placeholder="Ex : Inception" required>
        </div>
        <div class="form-group">
          <label>Genre *</label>
          <input type="text" id="film-genre" placeholder="Ex : Action" required>
        </div>
        <div class="form-group">
          <label>Prix (€) *</label>
          <input type="number" id="film-prix" step="0.01" min="0.01" placeholder="Ex : 12.99" required>
        </div>
        <div class="form-group">
          <label>Classification d'âge</label>
          <select id="film-age">
            <option value="0">Tout public</option>
            <option value="1">Tout public (1+)</option>
            <option value="12">12+</option>
            <option value="16">16+</option>
            <option value="18">18+</option>
          </select>
        </div>
        <div class="form-group">
          <label>Emoji / Icône</label>
          <input type="text" id="film-image" placeholder="🎬" maxlength="4">
        </div>
        <div class="form-group">
          <label>Image (URL ou chemin)</label>
          <input type="text" id="film-poster" placeholder="../img/film.jpg">
        </div>
        <div class="form-group full">
          <label>Synopsis</label>
          <textarea id="film-synopsis" placeholder="Description du film…"></textarea>
        </div>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-secondary" onclick="closeModal('film-modal')">Annuler</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<!-- ════════════════════════════════════════════════════════════
     MODAL — Créer un utilisateur
════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="user-modal">
  <div class="modal">
    <h3><i class="fas fa-user-plus"></i> Créer un compte</h3>
    <form id="user-form" onsubmit="event.preventDefault(); saveUser()">
      <div class="form-grid">
        <div class="form-group">
          <label>Prénom *</label>
          <input type="text" id="user-prenom" required>
        </div>
        <div class="form-group">
          <label>Nom *</label>
          <input type="text" id="user-nom" required>
        </div>
        <div class="form-group">
          <label>Email *</label>
          <input type="email" id="user-email" required>
        </div>
        <div class="form-group">
          <label>Téléphone</label>
          <input type="tel" id="user-tel">
        </div>
        <div class="form-group full">
          <label>Adresse</label>
          <input type="text" id="user-adresse">
        </div>
        <div class="form-group">
          <label>Mot de passe *</label>
          <input type="password" id="user-password" required>
        </div>
        <div class="form-group">
          <label>Rôle</label>
          <select id="user-role">
            <option value="user">Utilisateur</option>
            <option value="admin">Administrateur</option>
          </select>
        </div>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn btn-secondary" onclick="closeModal('user-modal')">Annuler</button>
        <button type="submit" class="btn btn-success"><i class="fas fa-user-plus"></i> Créer</button>
      </div>
    </form>
  </div>
</div>

<!-- ════════════════════════════════════════════════════════════
     MODAL — Modifier un utilisateur
════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="user-edit-modal">
  <div class="modal">
    <h3><i class="fas fa-user-edit"></i> Modifier l'utilisateur</h3>
    <form id="user-edit-form" onsubmit="event.preventDefault(); saveEditUser()">
      <div class="form-grid">
        <div class="form-group">
          <label>Prénom *</label>
          <input type="text" id="edit-user-prenom" required>
        </div>
        <div class="form-group">
          <label>Nom *</label>
          <input type="text" id="edit-user-nom" required>
        </div>
        <div class="form-group">
          <label>Email *</label>
          <input type="email" id="edit-user-email" required>
        </div>
        <div class="form-group">
          <label>Téléphone</label>
          <input type="tel" id="edit-user-tel">
        </div>
        <div class="form-group full">
          <label>Adresse</label>
          <input type="text" id="edit-user-adresse">
        </div>
        <div class="form-group">
          <label>Nouveau mot de passe <span style="color:var(--muted);font-weight:400">(laisser vide = inchangé)</span></label>
          <input type="password" id="edit-user-password" autocomplete="new-password" placeholder="Nouveau mot de passe…">
        </div>
        <div class="form-group">
          <label>Rôle</label>
          <select id="edit-user-role">
            <option value="user">Utilisateur</option>
            <option value="admin">Administrateur</option>
          </select>
        </div>
      </div>
      <input type="hidden" id="edit-user-id">
      <div class="modal-actions">
        <button type="button" class="btn btn-secondary" onclick="closeModal('user-edit-modal')">Annuler</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<!-- ════════════════════════════════════════════════════════════
     MODAL — Confirmation de suppression
════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="confirm-modal">
  <div class="modal confirm-modal">
    <div class="warn-icon"><i class="fas fa-exclamation-triangle"></i></div>
    <h3 style="justify-content:center">Confirmer la suppression</h3>
    <p id="confirm-delete-text">Êtes-vous sûr ?</p>
    <div class="modal-actions" style="justify-content:center">
      <button class="btn btn-secondary" onclick="closeModal('confirm-modal')">Annuler</button>
      <button class="btn btn-danger" style="background:rgba(231,76,60,0.2);color:var(--danger);border-color:var(--danger)" onclick="executeDelete()">
        <i class="fas fa-trash"></i> Supprimer définitivement
      </button>
    </div>
  </div>
</div>

<!-- ════════  TOAST CONTAINER  ════════ -->
<div class="toast-container" id="toast-container"></div>

<script src="js/admin.js"></script>
<script>
// Afficher les stats
document.addEventListener('DOMContentLoaded', async function() {
  const [filmsData, usersData] = await Promise.all([
    apiFetch('api/films_api.php', 'GET'),
    apiFetch('api/users_api.php', 'GET'),
  ]);
  if (filmsData?.success) {
    const n = filmsData.films.length;
    const b = document.getElementById('badge-films');
    if (b) b.textContent = n;
  }
  if (usersData?.success) {
    const n = usersData.users.length;
    const b = document.getElementById('badge-users');
    if (b) b.textContent = n;
  }

  // Mobile toggle
  const mq = window.matchMedia('(max-width:768px)');
  const toggleBtn = document.getElementById('menu-toggle');
  function checkMobile() { if(toggleBtn) toggleBtn.style.display = mq.matches ? '' : 'none'; }
  mq.addEventListener('change', checkMobile);
  checkMobile();
});
</script>
</body>
</html>
