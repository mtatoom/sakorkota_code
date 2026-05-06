SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- Table : utilisateurs
-- Rôle : Gestion des accès employés.
-- --------------------------------------------------------
CREATE TABLE `utilisateurs` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` ENUM('admin', 'manager', 'vendeur') DEFAULT 'vendeur',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table : categories
-- Rôle : Classification hiérarchique (ex: Mode > Chaussures).
-- --------------------------------------------------------
CREATE TABLE `categories` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `parent_id` bigint UNSIGNED DEFAULT NULL, -- Pour les sous-catégories
  `nom` varchar(100) NOT NULL,               -- ex: "Chaussures", "Scolaire"
  `slug` varchar(150) UNIQUE,                -- ex: "chaussures-femmes"
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  CONSTRAINT `fk_cat_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table : produits
-- Rôle : Catalogue d'articles avec gestion de la cible (Homme/Femme).
-- --------------------------------------------------------
CREATE TABLE `produits` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `categorie_id` bigint UNSIGNED DEFAULT NULL,
  `sku` varchar(50) NOT NULL UNIQUE,        -- Référence unique (ex: CH-001)
  `nom` varchar(255) NOT NULL,
  `description` text NULL,
  -- La cible permet de filtrer (Homme, Femme, Enfant) sans multiplier les catégories
  `cible` ENUM('Homme', 'Femme', 'Enfant', 'Bébé', 'Mixte') DEFAULT 'Mixte',
  `prix_achat` decimal(12, 2) DEFAULT 0,    -- Pour calculer la marge
  `prix_vente` decimal(12, 2) NOT NULL,
  `quantite_stock` int NOT NULL DEFAULT 0,
  `seuil_alerte` int DEFAULT 5,             -- Alerte rupture de stock
  `est_actif` boolean DEFAULT true,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  CONSTRAINT `fk_prod_cat` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table : clients
-- Rôle : Fichier client (CRM).
-- --------------------------------------------------------
CREATE TABLE `clients` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `nom_complet` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `total_depense` decimal(12, 2) DEFAULT 0, -- Pour la fidélité
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table : ventes
-- Rôle : En-tête de facture / Commande.
-- --------------------------------------------------------
CREATE TABLE `ventes` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `numero_commande` varchar(50) UNIQUE,     -- ex: CMD-2024-0001
  `client_id` bigint UNSIGNED DEFAULT NULL,
  `utilisateur_id` bigint UNSIGNED DEFAULT NULL, -- Vendeur ayant fait la saisie
  `total_ht` decimal(12, 2) NOT NULL,
  `total_ttc` decimal(12, 2) NOT NULL,
  `frais_livraison` decimal(12, 2) DEFAULT 0,
  `statut_paiement` ENUM('attente', 'paye', 'annule', 'rembourse') DEFAULT 'attente',
  `statut_livraison` ENUM('preparation', 'expedie', 'livre') DEFAULT 'preparation',
  `mode_paiement` varchar(100),             -- ex: "Cash", "Orange Money"
  `date_vente` timestamp DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  CONSTRAINT `fk_vente_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  CONSTRAINT `fk_vente_user` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table : vente_lignes
-- Rôle : Détails des articles vendus (remplace le JSON panier).
-- --------------------------------------------------------
CREATE TABLE `vente_lignes` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `vente_id` bigint UNSIGNED NOT NULL,
  `produit_id` bigint UNSIGNED NOT NULL,
  `quantite` int NOT NULL,
  `prix_unitaire` decimal(12, 2) NOT NULL,  -- Prix au moment de la vente
  `total_ligne` decimal(12, 2) NOT NULL,    -- quantite * prix_unitaire
  CONSTRAINT `fk_ligne_vente` FOREIGN KEY (`vente_id`) REFERENCES `ventes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ligne_produit` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table : mouvements_stock
-- Rôle : Journal d'audit pour chaque article entrant ou sortant.
-- --------------------------------------------------------
CREATE TABLE `mouvements_stock` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `produit_id` bigint UNSIGNED NOT NULL,
  `quantite` int NOT NULL,                 -- Positif (achat) ou Négatif (vente)
  `type` ENUM('vente', 'achat', 'retour', 'perte', 'ajustement') NOT NULL,
  `reference_id` bigint UNSIGNED DEFAULT NULL, -- ID de la vente ou du bon d'achat lié
  `commentaire` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_stock_produit` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;