CREATE DATABASE IF NOT EXISTS warhammer_shop;
USE warhammer_shop;

-- Table des figurines en vente et exposition
CREATE TABLE figurines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    faction VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    etat ENUM('neuf', 'monte', 'peint', 'pro-painted') DEFAULT 'neuf',
    en_exposition BOOLEAN DEFAULT FALSE,
    vendeur_id INT, -- Lien vers le vendeur
    date_ajout DATE NOT NULL
);

-- Table des vendeurs
CREATE TABLE vendeurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    pseudo VARCHAR(50) NOT NULL UNIQUE,
    mail VARCHAR(100) NOT NULL UNIQUE,
    pays VARCHAR(100) NOT NULL,
    note_moyenne DECIMAL(3,2) DEFAULT 0.00,
    date_inscription DATE NOT NULL
);

-- Table des administrateurs du site
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    droits ENUM('moderateur', 'gestionnaire', 'admin') DEFAULT 'moderateur'
);

-- Table des valeurs (prix et cotes des figurines)
CREATE TABLE valeur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    figurine_id INT, -- Lien vers la figurine
    prix_vente DECIMAL(8,2) NOT NULL,
    cote_marche DECIMAL(8,2) NOT NULL,
    date_estimation DATE NOT NULL,
    FOREIGN KEY (figurine_id) REFERENCES figurines(id)
);

-- Ajout de la clé étrangère vendeur sur figurines
ALTER TABLE figurines
ADD FOREIGN KEY (vendeur_id) REFERENCES vendeurs(id);

-- Insertion des vendeurs
INSERT INTO vendeurs (nom, prenom, pseudo, mail, pays, note_moyenne, date_inscription) VALUES
('Merlet', 'Lucas', 'GrimDankLucas', 'lucas@example.com', 'France', 4.85, '2024-01-15'),
('Durand', 'Marc', 'PaintFather', 'marc.durand@example.com', 'France', 4.50, '2024-03-10'),
('Smith', 'Emily', 'WaaaghQueen', 'emily.smith@example.com', 'Royaume-Uni', 4.92, '2024-02-01'),
('Kowalski', 'Piotr', 'DeathGuardPro', 'piotr.k@example.com', 'Pologne', 4.30, '2024-05-20');

-- Insertion des figurines
INSERT INTO figurines (nom, faction, description, etat, en_exposition, vendeur_id, date_ajout) VALUES
('Roboute Guilliman', 'Ultramarines', 'Primarque des Ultramarines, peint aux couleurs officielles avec socle décoré. Peinture niveau concours.', 'pro-painted', TRUE, 1, '2024-06-01'),
('Escouade Intercessors x10', 'Space Marines', 'Escouade complète de 10 Intercessors, montée et sous-couchée, prête à peindre.', 'monte', FALSE, 2, '2024-07-12'),
('Mortarion', 'Death Guard', 'Primarque démon de la Death Guard, peint avec effets de rouille et vert-de-gris. Pièce maîtresse.', 'pro-painted', TRUE, 4, '2024-08-03'),
('Boyz Orks x20', 'Orks', 'Vingt Boyz Orks encore sous blister, jamais ouverts.', 'neuf', FALSE, 3, '2024-09-15'),
('Magnus le Rouge', 'Thousand Sons', 'Primarque des Thousand Sons monté et peint, quelques retouches à prévoir sur les ailes.', 'peint', TRUE, 1, '2024-10-01');

-- Insertion des admins
INSERT INTO admins (login, password_hash, droits) VALUES
('admin1', MD5('admin123'), 'admin'),
('gestionnaire1', MD5('gestion123'), 'gestionnaire'),
('modo1', MD5('modo123'), 'moderateur');

-- Insertion des valeurs
INSERT INTO valeur (figurine_id, prix_vente, cote_marche, date_estimation) VALUES
(1, 180.00, 210.00, '2024-11-01'),
(2, 45.00, 55.00, '2024-11-01'),
(3, 250.00, 280.00, '2024-11-05'),
(4, 38.50, 42.00, '2024-11-10'),
(5, 165.00, 190.00, '2024-11-12');