-- Supprime et recrée la base de données
DROP DATABASE IF EXISTS filmogram_dtb;
CREATE DATABASE filmogram_dtb;
USE filmogram_dtb;

-- =============================
-- TABLE UTILISATEUR
-- =============================
CREATE TABLE user (
    iduser INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    prenom VARCHAR(20) NOT NULL,
    nom VARCHAR(20) NOT NULL,
    adresse VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    tel VARCHAR(15),
    password TEXT NOT NULL
);

-- =============================
-- TABLE FILM
-- =============================
CREATE TABLE film (
    idfilm INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    age INT DEFAULT 0,
    genre VARCHAR(30) NOT NULL,
    prix DECIMAL(10,2) NOT NULL
);

-- =============================
-- TABLE PANIER
-- =============================
CREATE TABLE panier (
    idpanier INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    prixtotal DECIMAL(10,2),
    user_ID INT NOT NULL,
    film_ID INT NOT NULL,
    CONSTRAINT fk_panier_user FOREIGN KEY (user_ID) REFERENCES users(iduser) ON DELETE CASCADE,
    CONSTRAINT fk_panier_film FOREIGN KEY (film_ID) REFERENCES film(idfilm) ON DELETE CASCADE
);

-- =============================
-- DONNÉES UTILISATEURS
-- =============================
INSERT INTO users (prenom, nom, adresse, email, tel, mot_de_passe)
VALUES
('Admin', 'Test', '1 rue du Cinéma', 'admin@filmogram.test', '0600000000', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Julie', 'Martin', '23 avenue Lumière', 'julie.martin@mail.com', '0611223344', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Lucas', 'Durand', '45 rue du Septième Art', 'lucas.durand@mail.com', '0622334455', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Sophie', 'Bernard', '12 place des Films', 'sophie.bernard@mail.com', '0633445566', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
('Maxime', 'Petit', '78 boulevard Popcorn', 'maxime.petit@mail.com', '0644556677', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- =============================
-- DONNÉES FILMS
-- =============================
INSERT INTO film (nom, age, genre, prix)
VALUES
('Inception', 12, 'Science-fiction', 14.99),
('The Dark Knight', 12, 'Action', 12.99),
('Interstellar', 10, 'Science-fiction', 15.99),
('Le Roi Lion', 0, 'Animation', 9.99),
('Gladiator', 16, 'Historique', 11.99),
('Titanic', 10, 'Romance', 10.99),
('Avatar', 10, 'Science-fiction', 13.99),
('Joker', 16, 'Drame', 12.49),
('Forrest Gump', 6, 'Comédie dramatique', 9.49),
('La La Land', 0, 'Comédie musicale', 10.49),
('Dune', 12, 'Science-fiction', 16.49),
('Spider-Man: No Way Home', 10, 'Super-héros', 13.49),
('Oppenheimer', 14, 'Drame', 15.49),
('Barbie', 0, 'Comédie', 11.49),
('The Matrix', 16, 'Action', 13.99);

-- =============================
-- DONNÉES PANIER (exemples)
-- =============================
INSERT INTO panier (prixtotal, user_ID, film_ID)
VALUES
(14.99, 2, 1),
(27.98, 3, 2),
(25.98, 4, 3),
(9.99, 5, 4),
(26.98, 2, 7);
