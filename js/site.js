// Script global : menu mobile + nav selon session
(function () {
  function initMobileNav() {
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    if (!navToggle || !navMenu) return;

    navToggle.addEventListener('click', () => {
      navMenu.classList.toggle('active');
    });
  }

  function setLoggedOutNav(basePath) {
    const navPanier = document.getElementById('nav-panier');
    const navProfil = document.getElementById('nav-profil');
    const navConnexion = document.getElementById('nav-connexion');

    if (navPanier) navPanier.style.display = 'none';
    if (navProfil) navProfil.style.display = 'none';
    if (navConnexion) navConnexion.innerHTML = `<a href="${basePath}login.php">Connexion</a>`;
  }

  function setLoggedInNav(basePath) {
    const navPanier = document.getElementById('nav-panier');
    const navProfil = document.getElementById('nav-profil');
    const navConnexion = document.getElementById('nav-connexion');

    if (navPanier) navPanier.style.display = '';
    if (navProfil) navProfil.style.display = '';
    if (navConnexion) navConnexion.innerHTML = `<a href="${basePath}logout.php">Déconnexion</a>`;
  }

  // Expose une API simple pour les pages en sous-dossier (ex: /Vue/)
  window.Filmogram = window.Filmogram || {};
  window.Filmogram.init = function init(options) {
    const basePath = (options && typeof options.basePath === 'string') ? options.basePath : '';

    initMobileNav();

    fetch(`${basePath}check_session.php`, { credentials: 'include' })
      .then(r => r.ok ? r.json() : Promise.reject())
      .then(data => {
        if (data && data.logged_in) setLoggedInNav(basePath);
        else setLoggedOutNav(basePath);
      })
      .catch(() => setLoggedOutNav(basePath));
  };

  document.addEventListener('DOMContentLoaded', () => {
    // Base path configurable via <body data-base-path="../">
    const basePath = (document.body && document.body.dataset && typeof document.body.dataset.basePath === 'string')
      ? document.body.dataset.basePath
      : '';
    window.Filmogram.init({ basePath });
  });
})();

