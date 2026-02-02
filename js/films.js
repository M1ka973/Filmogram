// ===============================
// Données exemples (mock database)
// ===============================
// Ici, on définit un tableau d'objets représentant des films.
// Dans une vraie application, ces données viendraient d'une base de données ou d'une API.
const films = [
    // Films tout public (age = 1)
    {
        id: 1,
        title: "Le Roi Lion",
        genre: "Animation",
        age: 1, // 1 signifie "tout public"
        price: 12.99,
        image: "🦁"
    },
    {
        id: 2,
        title: "Toy Story",
        genre: "Animation",
        age: 1,
        price: 11.99,
        image: "🤖"
    },
    {
        id: 3,
        title: "Frozen",
        genre: "Animation",
        age: 1,
        price: 13.99,
        image: "❄️"
    },
    {
        id: 4,
        title: "Coco",
        genre: "Animation",
        age: 1,
        price: 12.99,
        image: "💀"
    },
    {
        id: 5,
        title: "Spider-Man: Into the Spider-Verse",
        genre: "Animation",
        age: 1,
        price: 14.99,
        image: "🕷️"
    },
    {
        id: 6,
        title: "Les Minions",
        genre: "Comédie",
        age: 1,
        price: 10.99,
        image: "💛"
    },
    {
        id: 7,
        title: "Paddington",
        genre: "Comédie",
        age: 1,
        price: 9.99,
        image: "🧸"
    },
    {
        id: 8,
        title: "Mary Poppins",
        genre: "Musical",
        age: 1,
        price: 11.99,
        image: "☂️"
    },
    
    // Films 12+ (age = 12)
    {
        id: 9,
        title: "Avengers: Endgame",
        genre: "Action",
        age: 12,
        price: 15.99,
        image: "🦸‍♂️"
    },
    {
        id: 10,
        title: "La La Land",
        genre: "Romance",
        age: 12,
        price: 11.99,
        image: "🎭"
    },
    {
        id: 11,
        title: "Interstellar",
        genre: "Science-Fiction",
        age: 12,
        price: 14.99,
        image: "🚀"
    },
    {
        id: 12,
        title: "Inception",
        genre: "Thriller",
        age: 12,
        price: 13.99,
        image: "🌀"
    },
    {
        id: 13,
        title: "Black Panther",
        genre: "Action",
        age: 12,
        price: 16.99,
        image: "🐾"
    },
    {
        id: 14,
        title: "Wonder Woman",
        genre: "Action",
        age: 12,
        price: 15.99,
        image: "🛡️"
    },
    {
        id: 15,
        title: "Jurassic World",
        genre: "Science-Fiction",
        age: 12,
        price: 17.99,
        image: "🦕"
    },
    {
        id: 16,
        title: "The Greatest Showman",
        genre: "Musical",
        age: 12,
        price: 12.99,
        image: "🎪"
    },
    {
        id: 17,
        title: "Ready Player One",
        genre: "Science-Fiction",
        age: 12,
        price: 14.99,
        image: "🎮"
    },
    {
        id: 18,
        title: "The Martian",
        genre: "Science-Fiction",
        age: 12,
        price: 13.99,
        image: "🚀"
    },
    
    // Films 16+ (age = 16)
    {
        id: 19,
        title: "Get Out",
        genre: "Horreur",
        age: 16,
        price: 13.99,
        image: "😱"
    },
    {
        id: 20,
        title: "Superbad",
        genre: "Comédie",
        age: 16,
        price: 9.99,
        image: "😂"
    },
    {
        id: 21,
        title: "Deadpool",
        genre: "Action",
        age: 16,
        price: 16.99,
        image: "💀"
    },
    {
        id: 22,
        title: "The Hangover",
        genre: "Comédie",
        age: 16,
        price: 11.99,
        image: "🍺"
    },
    {
        id: 23,
        title: "American Pie",
        genre: "Comédie",
        age: 16,
        price: 10.99,
        image: "🥧"
    },
    {
        id: 24,
        title: "The Conjuring",
        genre: "Horreur",
        age: 16,
        price: 14.99,
        image: "👻"
    },
    {
        id: 25,
        title: "Insidious",
        genre: "Horreur",
        age: 16,
        price: 13.99,
        image: "👹"
    },
    {
        id: 26,
        title: "The Purge",
        genre: "Horreur",
        age: 16,
        price: 12.99,
        image: "🔪"
    },
    {
        id: 27,
        title: "21 Jump Street",
        genre: "Comédie",
        age: 16,
        price: 11.99,
        image: "👮"
    },
    
    // Films 18+ (age = 18)
    {
        id: 28,
        title: "Le Parrain",
        genre: "Drame",
        age: 18,
        price: 16.99,
        image: "🎬"
    },
    {
        id: 29,
        title: "Pulp Fiction",
        genre: "Crime",
        age: 18,
        price: 15.99,
        image: "💉"
    },
    {
        id: 30,
        title: "Fight Club",
        genre: "Drame",
        age: 18,
        price: 14.99,
        image: "👊"
    },
    {
        id: 31,
        title: "The Wolf of Wall Street",
        genre: "Drame",
        age: 18,
        price: 17.99,
        image: "💰"
    },
    {
        id: 32,
        title: "Goodfellas",
        genre: "Crime",
        age: 18,
        price: 16.99,
        image: "🔫"
    },
    {
        id: 33,
        title: "Scarface",
        genre: "Crime",
        age: 18,
        price: 15.99,
        image: "💊"
    },
    {
        id: 34,
        title: "Taxi Driver",
        genre: "Drame",
        age: 18,
        price: 14.99,
        image: "🚕"
    },
    {
        id: 35,
        title: "American Psycho",
        genre: "Thriller",
        age: 18,
        price: 13.99,
        image: "🪓"
    },
    {
        id: 36,
        title: "The Silence of the Lambs",
        genre: "Thriller",
        age: 18,
        price: 15.99,
        image: "🦋"
    }
];

// ===============================
// Variables globales pour la pagination
// ===============================
let currentPage = 1;
const filmsPerPage = 12; // Nombre de films par page
let currentFilteredFilms = []; // Films actuellement filtrés

// ===============================
// Récupération des éléments du DOM
// ===============================
const filmsGrid = document.getElementById('filmsGrid');   // Zone où afficher les films
const searchInput = document.getElementById('searchInput'); // Champ de recherche
const genreFilter = document.getElementById('genreFilter'); // Sélecteur de genre
const ageFilter = document.getElementById('ageFilter');     // Sélecteur d'âge
const priceFilter = document.getElementById('priceFilter'); // Sélecteur de prix

// ===============================
// Initialisation de la page
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    displayFilms(films);       // Affiche tous les films au chargement
    setupEventListeners();     // Active les filtres et la recherche
    cartManager.updateCartCount(); // Met à jour le compteur du panier
});

// ===============================
// Ajout des écouteurs d'événements
// ===============================
function setupEventListeners() {
    searchInput.addEventListener('input', filterFilms);   // Recherche dynamique
    genreFilter.addEventListener('change', filterFilms);  // Filtre par genre
    ageFilter.addEventListener('change', filterFilms);    // Filtre par âge
    priceFilter.addEventListener('change', filterFilms);  // Filtre par prix
    
    // Écouteurs pour la pagination
    const prevBtn = document.querySelector('[data-page="prev"]');
    const nextBtn = document.querySelector('[data-page="next"]');  
    
    if (prevBtn) {
        prevBtn.addEventListener('click', goToPreviousPage);
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', goToNextPage);
    }
}

// ===============================
// Affichage des films
// ===============================
function displayFilms(filmsToShow) {
    currentFilteredFilms = filmsToShow; // Sauvegarde pour la pagination
    currentPage = 1; // Retour à la première page lors d'un nouveau filtre
    
    // Calcul du nombre total de pages
    const totalPages = Math.ceil(filmsToShow.length / filmsPerPage);
    
    // Mise à jour de la pagination
    updatePagination(totalPages);
    
    // Affichage des films de la page courante
    displayFilmsPage(filmsToShow, currentPage);
}

// ===============================
// Affichage d'une page spécifique
// ===============================
function displayFilmsPage(filmsToShow, page) {
    filmsGrid.innerHTML = ''; // Réinitialise la grille
    
    // Si aucun film ne correspond aux critères
    if (filmsToShow.length === 0) {
        filmsGrid.innerHTML = `
            <div class="no-results">
                <h3>Aucun film trouvé</h3>
                <p>Essayez de modifier vos critères de recherche</p>
            </div>
        `;
        return;
    }
    
    // Calcul des indices de début et fin pour la page courante
    const startIndex = (page - 1) * filmsPerPage;
    const endIndex = startIndex + filmsPerPage;
    const filmsForCurrentPage = filmsToShow.slice(startIndex, endIndex);
    
    // Création d'une carte pour chaque film de la page courante
    filmsForCurrentPage.forEach(film => {
        const filmCard = createFilmCard(film);
        filmsGrid.appendChild(filmCard);
    });
}

// ===============================
// Création d'une "carte film"
// ===============================
function createFilmCard(film) {
    const card = document.createElement('div');
    card.className = 'film-card';

    // HTML d'une carte film
    card.innerHTML = `
        <div class="film-image">
            ${film.image}
        </div>
        <div class="film-info">
            <h3 class="film-title">${film.title}</h3>
            <div class="film-details">
                <span>${film.genre}</span>
                <span>${film.age === 1 ? 'Tout public' : film.age + '+'}</span>
            </div>
            <div class="film-price">${film.price.toFixed(2)}€</div>
            <button class="add-to-cart" onclick="addToCart(${film.id})">
                <i class="fas fa-shopping-cart"></i> Ajouter au panier
            </button>
        </div>
    `;

    return card;
}

// ===============================
// Filtrage des films
// ===============================
function filterFilms() {
    const searchTerm = searchInput.value.toLowerCase(); 
    const selectedGenre = genreFilter.value;            
    const selectedAge = ageFilter.value;                
    const selectedPrice = priceFilter.value;            

    let filteredFilms = films.filter(film => {
        const matchesSearch = film.title.toLowerCase().includes(searchTerm);
        const matchesGenre = !selectedGenre || film.genre.toLowerCase() === selectedGenre.toLowerCase();

     // === FILTRE ÂGE CORRIGÉ ===
    let matchesAge = true;
    if (selectedAge !== '') {
    const selectedAgeNum = parseInt(selectedAge);
    // Affiche uniquement les films de l'âge exact sélectionné
    matchesAge = film.age === selectedAgeNum;
}

        let matchesPrice = true;
        if (selectedPrice) {
            const [min, max] = selectedPrice.split('-').map(p => p === '+' ? Infinity : parseFloat(p));
            matchesPrice = film.price >= min && (max === Infinity || film.price <= max);
        }

        return matchesSearch && matchesGenre && matchesAge && matchesPrice;
    });

    displayFilms(filteredFilms);
}

// ===============================
// Mise à jour de la pagination
// ===============================
function updatePagination(totalPages) {
    const pagination = document.querySelector('.pagination');
    
    // Supprime tous les boutons de page sauf prev et next
    const pageButtons = pagination.querySelectorAll('.page-btn:not([data-page="prev"]):not([data-page="next"])');
    pageButtons.forEach(btn => btn.remove());
    
    // Ajoute les boutons de page
    for (let i = 1; i <= totalPages; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.className = 'page-btn';
        pageBtn.setAttribute('data-page', i);
        pageBtn.textContent = i;
        
        // Active la première page par défaut
        if (i === 1) {
            pageBtn.classList.add('active');
        }
        
        // Ajoute l'écouteur d'événement
        pageBtn.addEventListener('click', () => goToPage(i));
        
        // Insère avant le bouton "next"
        const nextBtn = pagination.querySelector('[data-page="next"]');
        pagination.insertBefore(pageBtn, nextBtn);
    }
    
    // Met à jour les boutons prev/next
    updateNavigationButtons(totalPages);
}

// ===============================
// Mise à jour des boutons de navigation
// ===============================
function updateNavigationButtons(totalPages) {
    const prevBtn = document.querySelector('[data-page="prev"]');
    const nextBtn = document.querySelector('[data-page="next"]');
    
    // Désactive prev si on est à la première page
    prevBtn.disabled = currentPage === 1;
    prevBtn.classList.toggle('disabled', currentPage === 1);
    
    // Désactive next si on est à la dernière page
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.classList.toggle('disabled', currentPage === totalPages);
}

// ===============================
// Aller à une page spécifique
// ===============================
function goToPage(page) {
    if (page < 1 || page > Math.ceil(currentFilteredFilms.length / filmsPerPage)) {
        return;
    }
    
    currentPage = page;
    
    // Met à jour l'état actif des boutons de page
    const pageButtons = document.querySelectorAll('.page-btn:not([data-page="prev"]):not([data-page="next"])');
    pageButtons.forEach(btn => {
        btn.classList.remove('active');
        if (parseInt(btn.getAttribute('data-page')) === page) {
            btn.classList.add('active');
        }
    });
    
    // Affiche les films de la page courante
    displayFilmsPage(currentFilteredFilms, currentPage);
    
    // Met à jour les boutons de navigation
    updateNavigationButtons(Math.ceil(currentFilteredFilms.length / filmsPerPage));
}

// ===============================
// Navigation vers la page précédente/suivante
// ===============================
function goToPreviousPage() {
    if (currentPage > 1) {
        goToPage(currentPage - 1);
    }
}

function goToNextPage() {
    const totalPages = Math.ceil(currentFilteredFilms.length / filmsPerPage);
    if (currentPage < totalPages) {
        goToPage(currentPage + 1);
    }
}

// ===============================
// Gestion du panier avec localStorage
// ===============================
class CartManager {
    constructor() {
        this.cart = this.loadCart();
    }

    loadCart() {
        const cart = localStorage.getItem('filmogram_cart');
        return cart ? JSON.parse(cart) : [];
    }

    saveCart() {
        localStorage.setItem('filmogram_cart', JSON.stringify(this.cart));
        this.updateCartCount();
    }

    addToCart(film) {
        const existingItem = this.cart.find(item => item.id === film.id);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            this.cart.push({
                id: film.id,
                title: film.title,
                genre: film.genre,
                price: film.price,
                image: film.image,
                quantity: 1
            });
        }
        
        this.saveCart();
        showNotification(`${film.title} ajouté au panier !`, 'success');
    }

    updateCartCount() {
        const count = this.cart.reduce((total, item) => total + item.quantity, 0);
        const cartCountElement = document.getElementById('cartCount');
        if (cartCountElement) {
            cartCountElement.textContent = count;
        }
    }
}

// Instance globale du gestionnaire de panier
const cartManager = new CartManager();

// ===============================
// Ajouter un film au panier
// ===============================
function addToCart(filmId) {
    const film = films.find(f => f.id === filmId);
    if (film) {
        cartManager.addToCart(film);
    }
}

// ===============================
// Notifications (succès, info…)
// ===============================
function showNotification(message, type = 'info') {
    // Création de l'élément notification
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()">&times;</button>
    `;

    // Style de la notification
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#4CAF50' : '#2196F3'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: slideIn 0.3s ease;
    `;

    // Style du bouton "fermer"
    const button = notification.querySelector('button');
    button.style.cssText = `
        background: none;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    `;

    // Ajoute la notification à la page
    document.body.appendChild(notification);

    // Disparition automatique après 3 secondes
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 3000);
}

// ===============================
// Animation CSS + style additionnel
// ===============================
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Style si aucun film n'est trouvé */
    .no-results {
        grid-column: 1 / -1;
        text-align: center;
        padding: 3rem;
        color: #666;
    }

    .no-results h3 {
        margin-bottom: 1rem;
        color: #333;
    }
    
    /* Style pour les boutons de pagination désactivés */
    .page-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
`;
document.head.appendChild(style);

// ===============================
// Menu mobile (toggle burger)
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active'); // Affiche ou cache le menu
        });
    }
});