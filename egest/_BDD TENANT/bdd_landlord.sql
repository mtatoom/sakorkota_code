
-- BASE LANDLORD--
-- Base de données : atyket_control
CREATE TABLE `tenants` (
  `id` varchar(255) PRIMARY KEY,          -- Identifiant unique (ex: 'boutique-bebe', 'mode-mada')
  `nom_boutique` varchar(255) NOT NULL,
  `db_name` varchar(255) NOT NULL,        -- Nom de la base de données SQL dédiée
  `db_username` varchar(255),
  `db_password` varchar(255),
  `plan_abonnement` varchar(50),          -- ex: 'Premium', 'Gratuit'
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `domaines` (
  `id` bigint UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  `domaine` varchar(255) NOT NULL UNIQUE, -- ex: mode.atyket.com
  `tenant_id` varchar(255) NOT NULL,
  FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;