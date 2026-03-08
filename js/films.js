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
        image: "🦁",
        // Synopsis et image très simples, faciles à modifier
        synopsis: "Un jeune lion doit accepter son destin de roi après la mort tragique de son père.",
        poster: "../img/roi_lion.jpg"
    },
    {
        id: 2,
        title: "Toy Story",
        genre: "Animation",
        age: 1,
        price: 11.99,
        image: "🤖",
        synopsis: "Les jouets d'un petit garçon prennent vie et vivent des aventures inattendues.",
        poster: "../img/tout_public_film.jpg"
    },
    {
        id: 3,
        title: "Frozen",
        genre: "Animation",
        age: 1,
        price: 13.99,
        image: "❄️",
        synopsis: "Deux soeurs se retrouvent grâce à la magie de la glace et de l'amour fraternel.",
        poster: "../img/tout_public_film.jpg"
    },
    {
        id: 4,
        title: "Coco",
        genre: "Animation",
        age: 1,
        price: 12.99,
        image: "💀",
        synopsis: "Un garçon passionné de musique explore le monde des morts pour comprendre son passé.",
        poster: "../img/tout_public_film.jpg"
    },
    {
        id: 5,
        title: "Spider-Man: Into the Spider-Verse",
        genre: "Animation",
        age: 1,
        price: 14.99,
        image: "🕷️",
        synopsis: "Plusieurs versions de Spider-Man se rencontrent dans un univers animé explosif.",
        poster: "../img/tout_public_film.jpg"
    },
    {
        id: 6,
        title: "Les Minions",
        genre: "Comédie",
        age: 1,
        price: 10.99,
        image: "💛",
        synopsis: "De petits êtres jaunes cherchent le méchant parfait à servir.",
        poster: "../img/tout_public_film.jpg"
    },
    {
        id: 7,
        title: "Paddington",
        genre: "Comédie",
        age: 1,
        price: 9.99,
        image: "🧸",
        synopsis: "Un ours venu du Pérou découvre la vie à Londres dans une famille accueillante.",
        poster: "../img/tout_public_film.jpg"
    },
    {
        id: 8,
        title: "Mary Poppins",
        genre: "Musical",
        age: 1,
        price: 11.99,
        image: "☂️",
        synopsis: "Une nounou magique transforme le quotidien d'une famille avec chansons et fantaisie.",
        poster: "../img/tout_public_film.jpg"
    },
    
    // Films 12+ (age = 12)
    {
        id: 9,
        title: "Avengers: Endgame",
        genre: "Action",
        age: 12,
        price: 15.99,
        image: "🦸‍♂️",
        synopsis: "Les Avengers se réunissent une dernière fois pour inverser les effets du claquement de Thanos.",
        poster: "../img/twelve_film.jpg"
    },
    {
        id: 10,
        title: "La La Land",
        genre: "Romance",
        age: 12,
        price: 11.99,
        image: "🎭",
        synopsis: "Une actrice et un musicien vivent une histoire d'amour rythmée par leurs rêves à Hollywood.",
        poster: "../img/drama.jpg"
    },
    {
        id: 11,
        title: "Interstellar",
        genre: "Science-Fiction",
        age: 12,
        price: 14.99,
        image: "🚀",
        synopsis: "Des astronautes voyagent à travers un trou de ver pour trouver une nouvelle planète habitable.",
        poster: "../img/fantastic.jpg"
    },
    {
        id: 12,
        title: "Inception",
        genre: "Thriller",
        age: 12,
        price: 13.99,
        image: "🌀",
        synopsis: "Un voleur d'idées infiltre les rêves pour implanter un souvenir dans l'esprit d'un homme.",
        poster: "../img/fantastic.jpg"
    },
    {
        id: 13,
        title: "Black Panther",
        genre: "Action",
        age: 12,
        price: 16.99,
        image: "🐾",
        synopsis: "Le roi du Wakanda protège son royaume et son héritage contre de nouvelles menaces.",
        poster: "../img/hero_backgrd_001.jpg"
    },
    {
        id: 14,
        title: "Wonder Woman",
        genre: "Action",
        age: 12,
        price: 15.99,
        image: "🛡️",
        synopsis: "Une guerrière amazone quitte son île pour mettre fin à une grande guerre.",
        poster: "../img/hero_backgrd_001.jpg"
    },
    {
        id: 15,
        title: "Jurassic World",
        genre: "Science-Fiction",
        age: 12,
        price: 17.99,
        image: "🦕",
        synopsis: "Un parc de dinosaures renaît, mais une créature génétiquement modifiée s'échappe.",
        poster: "../img/fantastic.jpg"
    },
    {
        id: 16,
        title: "The Greatest Showman",
        genre: "Musical",
        age: 12,
        price: 12.99,
        image: "🎪",
        synopsis: "L'ascension d'un homme qui crée un spectacle unique avec des artistes marginaux.",
        poster: "../img/drama.jpg"
    },
    {
        id: 17,
        title: "Ready Player One",
        genre: "Science-Fiction",
        age: 12,
        price: 14.99,
        image: "🎮",
        synopsis: "Dans un monde virtuel géant, un joueur cherche un secret caché par le créateur du jeu.",
        poster: "../img/fantastic.jpg"
    },
    {
        id: 18,
        title: "The Martian",
        genre: "Science-Fiction",
        age: 12,
        price: 13.99,
        image: "🚀",
        synopsis: "Un astronaute abandonné sur Mars essaie de survivre en attendant un sauvetage.",
        poster: "../img/fantastic.jpg"
    },
    
    // Films 16+ (age = 16)
    {
        id: 19,
        title: "Get Out",
        genre: "Horreur",
        age: 16,
        price: 13.99,
        image: "😱",
        synopsis: "Une visite chez les beaux-parents tourne au cauchemar pour un jeune homme.",
        poster: "../img/drama.jpg"
    },
    {
        id: 20,
        title: "Superbad",
        genre: "Comédie",
        age: 16,
        price: 9.99,
        image: "😂",
        synopsis: "Deux lycéens vivent une soirée complètement déjantée avant la fin de leurs études.",
        poster: "../img/foreign_film.jpg"
    },
    {
        id: 21,
        title: "Deadpool",
        genre: "Action",
        age: 16,
        price: 16.99,
        image: "💀",
        synopsis: "Un anti-héros bavard cherche à se venger après une expérience qui l'a transformé.",
        poster: "../img/hero_backgrd_001.jpg"
    },
    {
        id: 22,
        title: "The Hangover",
        genre: "Comédie",
        age: 16,
        price: 11.99,
        image: "🍺",
        synopsis: "Après une nuit à Las Vegas, un groupe d'amis tente de reconstituer ce qui s'est passé.",
        poster: "../img/foreign_film.jpg"
    },
    {
        id: 23,
        title: "American Pie",
        genre: "Comédie",
        age: 16,
        price: 10.99,
        image: "🥧",
        synopsis: "Des lycéens décident de tout faire pour perdre leur virginité avant la fin de l'année.",
        poster: "../img/foreign_film.jpg"
    },
    {
        id: 24,
        title: "The Conjuring",
        genre: "Horreur",
        age: 16,
        price: 14.99,
        image: "👻",
        synopsis: "Un couple d'enquêteurs du paranormal affronte une présence maléfique dans une maison.",
        poster: "../img/drama.jpg"
    },
    {
        id: 25,
        title: "Insidious",
        genre: "Horreur",
        age: 16,
        price: 13.99,
        image: "👹",
        synopsis: "Un garçon plongé dans le coma devient la cible d'esprits qui veulent posséder son corps.",
        poster: "../img/drama.jpg"
    },
    {
        id: 26,
        title: "The Purge",
        genre: "Horreur",
        age: 16,
        price: 12.99,
        image: "🔪",
        synopsis: "Une nuit par an, tous les crimes sont autorisés, et une famille doit survivre.",
        poster: "../img/drama.jpg"
    },
    {
        id: 27,
        title: "21 Jump Street",
        genre: "Comédie",
        age: 16,
        price: 11.99,
        image: "👮",
        synopsis: "Deux policiers infiltrés retournent au lycée pour démanteler un trafic de drogue.",
        poster: "../img/foreign_film.jpg"
    },
    
    // Films 18+ (age = 18)
    {
        id: 28,
        title: "Le Parrain",
        genre: "Drame",
        age: 18,
        price: 16.99,
        image: "🎬",
        synopsis: "La saga d'une puissante famille mafieuse et de son héritier réticent.",
        poster: "../img/foreign_film.jpg"
    },
    {
        id: 29,
        title: "Pulp Fiction",
        genre: "Crime",
        age: 18,
        price: 15.99,
        image: "💉",
        synopsis: "Des histoires criminelles entremêlées, pleines de dialogues cultes et de violence stylisée.",
        poster: "../img/foreign_film.jpg"
    },
    {
        id: 30,
        title: "Fight Club",
        genre: "Drame",
        age: 18,
        price: 14.99,
        image: "👊",
        synopsis: "Un employé de bureau fonde un club de combat clandestin qui lui échappe rapidement.",
        poster: "../img/drama.jpg"
    },
    {
        id: 31,
        title: "The Wolf of Wall Street",
        genre: "Drame",
        age: 18,
        price: 17.99,
        image: "💰",
        synopsis: "L'ascension et la chute d'un courtier en bourse adepte des excès.",
        poster: "../img/foreign_film.jpg"
    },
    {
        id: 32,
        title: "Goodfellas",
        genre: "Crime",
        age: 18,
        price: 16.99,
        image: "🔫",
        synopsis: "L'histoire vraie d'un gangster qui gravit les échelons de la mafia.",
        poster: "../img/foreign_film.jpg"
    },
    {
        id: 33,
        title: "Scarface",
        genre: "Crime",
        age: 18,
        price: 15.99,
        image: "💊",
        synopsis: "Un immigré cubain devient un puissant baron de la drogue à Miami.",
        poster: "../img/foreign_film.jpg"
    },
    {
        id: 34,
        title: "Taxi Driver",
        genre: "Drame",
        age: 18,
        price: 14.99,
        image: "🚕",
        synopsis: "Un vétéran solitaire devient chauffeur de taxi et sombre dans la paranoïa.",
        poster: "../img/drama.jpg"
    },
    {
        id: 35,
        title: "American Psycho",
        genre: "Thriller",
        age: 18,
        price: 13.99,
        image: "🪓",
        synopsis: "Un riche trader mène une double vie de tueur en série.",
        poster: "../img/drama.jpg"
    },
    {
        id: 36,
        title: "The Silence of the Lambs",
        genre: "Thriller",
        age: 18,
        price: 15.99,
        image: "🦋",
        synopsis: "Une jeune agent du FBI consulte un tueur brillant pour arrêter un autre criminel.",
        poster: "../img/drama.jpg"
    },

    // ---- Ajouts catalogue (variété) ----
    {
        id: 37,
        title: "The Dark Knight",
        genre: "Action",
        age: 12,
        price: 13.99,
        image: "🦇",
        synopsis: "Batman affronte le Joker dans une lutte intense pour l'âme de Gotham."
    },
    {
        id: 38,
        title: "Dune",
        genre: "Science-Fiction",
        age: 12,
        price: 16.49,
        image: "🏜️",
        synopsis: "Sur Arrakis, Paul Atreides découvre un destin lié à l'épice et aux Fremen."
    },
    {
        id: 39,
        title: "Parasite",
        genre: "Thriller",
        age: 16,
        price: 12.99,
        image: "🏠",
        synopsis: "Deux familles que tout oppose se croisent et déclenchent une spirale imprévisible."
    },
    {
        id: 40,
        title: "Your Name",
        genre: "Animation",
        age: 12,
        price: 11.49,
        image: "🌠",
        synopsis: "Deux adolescents échangent mystérieusement leurs vies à travers le temps."
    },
    {
        id: 41,
        title: "Whiplash",
        genre: "Drame",
        age: 12,
        price: 10.99,
        image: "🥁",
        synopsis: "Un jeune batteur pousse ses limites sous la pression d'un professeur exigeant."
    },
    {
        id: 42,
        title: "Shutter Island",
        genre: "Thriller",
        age: 16,
        price: 12.49,
        image: "🏝️",
        synopsis: "Un marshal enquête sur une disparition dans un asile isolé, où rien n'est stable."
    },
    {
        id: 43,
        title: "The Grand Budapest Hotel",
        genre: "Comédie",
        age: 12,
        price: 9.99,
        image: "🛎️",
        synopsis: "Un concierge légendaire et son protégé vivent une aventure rocambolesque."
    },
    {
        id: 44,
        title: "Mad Max: Fury Road",
        genre: "Action",
        age: 16,
        price: 14.49,
        image: "🚗",
        synopsis: "Une course-poursuite furieuse dans un désert post-apocalyptique."
    },
    {
        id: 45,
        title: "Knives Out",
        genre: "Crime",
        age: 12,
        price: 11.99,
        image: "🕵️",
        synopsis: "Un détective enquête sur la mort d'un auteur, au cœur d'une famille explosive."
    },
    {
        id: 46,
        title: "The Notebook",
        genre: "Romance",
        age: 12,
        price: 9.49,
        image: "💌",
        synopsis: "Une histoire d'amour intense qui traverse les années et les obstacles."
    },
    {
        id: 47,
        title: "A Quiet Place",
        genre: "Horreur",
        age: 16,
        price: 12.99,
        image: "🤫",
        synopsis: "Dans un monde où le bruit attire les monstres, une famille survit en silence."
    },
    {
        id: 48,
        title: "The Matrix",
        genre: "Science-Fiction",
        age: 16,
        price: 13.99,
        image: "💾",
        synopsis: "Un hacker découvre la vérité sur la réalité et rejoint une rébellion."
    },
    {
        id: 49,
        title: "Se7en",
        genre: "Crime",
        age: 18,
        price: 12.99,
        image: "📦",
        synopsis: "Deux détectives traquent un tueur en série inspiré par les sept péchés capitaux."
    },
    {
        id: 50,
        title: "Le Voyage de Chihiro",
        genre: "Animation",
        age: 1,
        price: 10.99,
        image: "🐉",
        synopsis: "Une jeune fille explore un monde d'esprits pour sauver ses parents."
    },
    {
        id: 51,
        title: "Mamma Mia!",
        genre: "Musical",
        age: 1,
        price: 9.99,
        image: "🎶",
        synopsis: "Sur une île grecque, une future mariée cherche l'identité de son père."
    },
    {
        id: 52,
        title: "John Wick",
        genre: "Action",
        age: 16,
        price: 14.99,
        image: "🔫",
        synopsis: "Un ancien tueur à gages reprend du service dans un monde impitoyable."
    },
    {
        id: 53,
        title: "The Truman Show",
        genre: "Comédie",
        age: 12,
        price: 9.99,
        image: "📺",
        synopsis: "Un homme découvre que sa vie entière est une émission de télévision."
    },
    {
        id: 54,
        title: "Her",
        genre: "Romance",
        age: 12,
        price: 10.49,
        image: "🤖",
        synopsis: "Un homme tombe amoureux d'une intelligence artificielle à la voix envoûtante."
    },
    {
        id: 55,
        title: "The Shining",
        genre: "Horreur",
        age: 18,
        price: 13.49,
        image: "🪓",
        synopsis: "Un hôtel isolé et une présence inquiétante font basculer une famille."
    },
    {
        id: 56,
        title: "The Social Network",
        genre: "Drame",
        age: 12,
        price: 9.99,
        image: "💻",
        synopsis: "La création d'un réseau social devient une guerre d'ego et de procès."
    },
    {
        id: 57,
        title: "The Imitation Game",
        genre: "Drame",
        age: 12,
        price: 10.99,
        image: "🔐",
        synopsis: "Alan Turing tente de briser le code Enigma pendant la Seconde Guerre mondiale."
    },
    {
        id: 58,
        title: "Zombieland",
        genre: "Comédie",
        age: 16,
        price: 10.49,
        image: "🧟",
        synopsis: "Quatre survivants traversent un monde infesté de zombies avec humour et règles."
    },
    {
        id: 59,
        title: "The Departed",
        genre: "Crime",
        age: 18,
        price: 12.99,
        image: "🕶️",
        synopsis: "Un policier infiltré et un taupe dans la police se traquent mutuellement."
    },
    {
        id: 60,
        title: "Inside Out",
        genre: "Animation",
        age: 1,
        price: 11.99,
        image: "🧠",
        synopsis: "Les émotions d'une enfant s'organisent en équipe pour l'aider à grandir."
    }
];

// ===============================
// Variables globales pour la pagination
// ===============================
let currentPage = 1;
const filmsPerPage = 12; // Nombre de films par page
let currentFilteredFilms = []; // Films actuellement filtrés

// ===============================
// Éléments du DOM (initialisés au chargement)
// ===============================
let filmsGrid = null;     // Zone où afficher les films
let searchInput = null;   // Champ de recherche
let genreFilter = null;   // Sélecteur de genre
let ageFilter = null;     // Sélecteur d'âge
let priceFilter = null;   // Sélecteur de prix

// ===============================
// Initialisation de la page
// ===============================
document.addEventListener('DOMContentLoaded', function() {
    filmsGrid = document.getElementById('filmsGrid');
    searchInput = document.getElementById('searchInput');
    genreFilter = document.getElementById('genreFilter');
    ageFilter = document.getElementById('ageFilter');
    priceFilter = document.getElementById('priceFilter');

    if (!filmsGrid) return; // page non catalogue

    populateGenreFilter();
    displayFilms(films);       // Affiche tous les films au chargement
    setupEventListeners();     // Active les filtres et la recherche
    cartManager.updateCartCount(); // Met à jour le compteur du panier
});

function populateGenreFilter() {
    if (!genreFilter) return;
    const existing = new Set(Array.from(genreFilter.querySelectorAll('option')).map(o => o.value));
    const genres = Array.from(new Set(films.map(f => String(f.genre || '').trim()).filter(Boolean)))
        .sort((a, b) => a.localeCompare(b, 'fr'));
    genres.forEach(g => {
        const value = g.toLowerCase();
        if (existing.has(value)) return;
        const opt = document.createElement('option');
        opt.value = value;
        opt.textContent = g;
        genreFilter.appendChild(opt);
    });
}

// ===============================
// Ajout des écouteurs d'événements
// ===============================
function setupEventListeners() {
    if (searchInput) searchInput.addEventListener('input', filterFilms);     // Recherche dynamique
    if (genreFilter) genreFilter.addEventListener('change', filterFilms);    // Filtre par genre
    if (ageFilter) ageFilter.addEventListener('change', filterFilms);        // Filtre par âge
    if (priceFilter) priceFilter.addEventListener('change', filterFilms);    // Filtre par prix
    
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
            <button class="details-btn" onclick="toggleFilmDetails(${film.id})">
                <i class="fas fa-info-circle"></i> Voir le synopsis
            </button>
            <div class="film-extra" id="film-extra-${film.id}">
                <div class="film-extra-content">
                    <div class="film-poster-wrap ${film.poster ? '' : 'missing'}">
                        ${film.poster ? `<img class="film-poster" src="${film.poster}" alt="Affiche de ${film.title}" onerror="this.remove(); this.parentElement.classList.add('missing');">` : ``}
                    </div>
                    <p class="film-synopsis">${film.synopsis || 'Synopsis à venir.'}</p>
                </div>
            </div>
        </div>
    `;

    return card;
}

// Ouverture / fermeture très simple de la fenêtre déroulante
function toggleFilmDetails(filmId) {
    const extra = document.getElementById(`film-extra-${filmId}`);
    if (!extra) return;
    const isOpen = extra.classList.contains('open');
    extra.classList.toggle('open', !isOpen);
}

// ===============================
// Filtrage des films
// ===============================
function filterFilms() {
    const searchTerm = (searchInput ? searchInput.value : '').toLowerCase(); 
    const selectedGenre = genreFilter ? genreFilter.value : '';            
    const selectedAge = ageFilter ? ageFilter.value : '';                
    const selectedPrice = priceFilter ? priceFilter.value : '';            

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

// Les styles du catalogue sont dans css/style.css