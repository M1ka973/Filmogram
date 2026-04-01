-- Supprime et recrée la base de données
DROP DATABASE IF EXISTS filmogram_dtb;
CREATE DATABASE filmogram_dtb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
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
    password TEXT NOT NULL,
    role ENUM('user','admin') DEFAULT 'user'
);

-- =============================
-- TABLE FILM
-- =============================
CREATE TABLE film (
    idfilm INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    age INT DEFAULT 0,
    genre VARCHAR(30) NOT NULL,
    prix DECIMAL(10,2) NOT NULL,
    synopsis TEXT,
    image VARCHAR(10),
    poster VARCHAR(255)
);

-- =============================
-- TABLE PANIER
-- =============================
CREATE TABLE panier (
    idpanier INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    prixtotal DECIMAL(10,2),
    user_ID INT NOT NULL,
    film_ID INT NOT NULL,
    CONSTRAINT fk_panier_user FOREIGN KEY (user_ID) REFERENCES user(iduser) ON DELETE CASCADE,
    CONSTRAINT fk_panier_film FOREIGN KEY (film_ID) REFERENCES film(idfilm) ON DELETE CASCADE
);

-- =============================
-- TABLE SÉCURITÉ ADMIN (double authentification)
-- =============================
CREATE TABLE admin_security (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    question TEXT NOT NULL,
    answer_hash TEXT NOT NULL,
    CONSTRAINT fk_adminsec_user FOREIGN KEY (admin_id) REFERENCES user(iduser) ON DELETE CASCADE
);

-- =============================
-- COMPTE ADMIN PAR DÉFAUT ET AUTRES UTILISATEURS
-- email: admin@filmogram.com | mdp: Admin123!
-- =============================
INSERT INTO user (prenom, nom, adresse, email, tel, password, role)
VALUES ('Admin', 'Filmogram', '1 rue du Cinéma', 'admin@filmogram.com', '0600000000',
        '$2y$10$Y0tB.3bNrqZv0KxbN.b7eOx8JbSB3GpLzv97.wL/MrjDOQlWQQnJu', 'admin');

-- DONNÉES UTILISATEURS (exemples)
INSERT INTO user (prenom, nom, adresse, email, tel, password, role) VALUES
('Julie', 'Martin', '23 avenue Lumière', 'julie.martin@mail.com', '0611223344', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Lucas', 'Durand', '45 rue du Septième Art', 'lucas.durand@mail.com', '0622334455', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Sophie', 'Bernard', '12 place des Films', 'sophie.bernard@mail.com', '0633445566', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Maxime', 'Petit', '78 boulevard Popcorn', 'maxime.petit@mail.com', '0644556677', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('Admin', 'Filmogram', '1 Boulevard du cinema Paris', 'admin@filmogram.test', '0600000000', '$2y$10$Y0tB.3bNrqZv0KxbN.b7eOx8JbSB3GpLzv97.wL/MrjDOQlWQQnJu', 'admin');

-- =============================
-- SÉCURITÉ ADMIN
-- Réponse secrète par défaut: filmogram
-- =============================
INSERT INTO admin_security (admin_id, question, answer_hash)
VALUES (1, 'Quel est le nom de cette plateforme ?',
        '$2y$10$Y0tB.3bNrqZv0KxbN.b7eOx8JbSB3GpLzv97.wL/MrjDOQlWQQnJu'),
       ((SELECT iduser FROM user WHERE email = 'admin@filmogram.test'), 'Quel est le nom de cette plateforme ?',
        '$2y$10$Y0tB.3bNrqZv0KxbN.b7eOx8JbSB3GpLzv97.wL/MrjDOQlWQQnJu');

-- =============================
-- DONNÉES FILMS
-- =============================
INSERT INTO film (nom, age, genre, prix, synopsis, image) VALUES
('Inception', 12, 'Science-fiction', 14.99, 'Un voleur d''idées infiltre les rêves pour implanter un souvenir dans l''esprit d''un homme.', '🌀'),
('The Dark Knight', 12, 'Action', 12.99, 'Batman affronte le Joker dans une lutte intense pour l''âme de Gotham.', '🦇'),
('Interstellar', 12, 'Science-fiction', 15.99, 'Des astronautes voyagent à travers un trou de ver pour trouver une nouvelle planète habitable.', '🚀'),
('Le Roi Lion', 0, 'Animation', 9.99, 'Un jeune lion doit accepter son destin de roi après la mort tragique de son père.', '🦁'),
('Gladiator', 16, 'Historique', 11.99, 'Un général romain trahi devient gladiateur pour venger sa famille.', '⚔️'),
('Titanic', 12, 'Romance', 10.99, 'Un amour naissant sur le paquebot le plus célèbre de l''histoire.', '🚢'),
('Avatar', 12, 'Science-fiction', 13.99, 'Un soldat paralysé explore une planète alien aux confins de l''univers.', '🌿'),
('Joker', 16, 'Drame', 12.49, 'Un homme marginalisé bascule dans la folie et devient un symbole du chaos.', '🃏'),
('Forrest Gump', 12, 'Comédie dramatique', 9.49, 'Un homme ordinaire traverse l''histoire américaine avec un cœur d''or.', '🏃'),
('La La Land', 12, 'Comédie musicale', 10.49, 'Une actrice et un musicien vivent une histoire d''amour rythmée par leurs rêves.', '🎭'),
('Dune', 12, 'Science-fiction', 16.49, 'Sur Arrakis, Paul Atreides découvre un destin lié à l''épice et aux Fremen.', '🏜️'),
('Spider-Man: No Way Home', 12, 'Super-héros', 13.49, 'Peter Parker demande à Doctor Strange d''effacer sa révélation identitaire.', '🕷️'),
('Oppenheimer', 16, 'Drame', 15.49, 'L''histoire du père de la bombe atomique et de ses doutes.', '💥'),
('Barbie', 1, 'Comédie', 11.49, 'Barbie quitte Barbieland pour découvrir le monde réel.', '💗'),
('The Matrix', 16, 'Action', 13.99, 'Un hacker découvre la vérité sur la réalité et rejoint une rébellion.', '💾');

-- =============================
-- DONNÉES PANIER (exemples)
-- =============================
INSERT INTO panier (prixtotal, user_ID, film_ID) VALUES
(14.99, 2, 1),
(12.99, 3, 2),
(15.99, 4, 3),
(9.99, 5, 4);
