-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : lun. 09 mars 2026 à 13:18
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `camwater_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `abonnes`
--

CREATE TABLE `abonnes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `adresse` text NOT NULL,
  `numero_compteur` varchar(255) NOT NULL,
  `date_abonnement` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `abonnes`
--

INSERT INTO `abonnes` (`id`, `nom`, `prenom`, `telephone`, `adresse`, `numero_compteur`, `date_abonnement`, `created_at`, `updated_at`) VALUES
(8, 'royn', 'mbondi', '+237690000010', 'Yaoundé, Essos', 'COMP-2024-011', '2024-03-01', '2026-03-09 08:45:47', '2026-03-09 08:45:47'),
(9, 'rayan', 'prince', '+237690000110', 'douala, logpom', 'COMP-2025-011', '2026-03-01', '2026-03-09 08:46:36', '2026-03-09 08:46:36'),
(10, 'delia', 'mbondi', '+237690000110', 'douala, logpo - Nouvelle adresse', 'COMP-2025-111', '2026-03-01', '2026-03-09 08:47:30', '2026-03-09 11:06:29'),
(13, 'kendra', 'mbondi', '+237690000110', 'bafoussam, logpom', 'COMP-2026-112', '2026-03-01', '2026-03-09 09:44:26', '2026-03-09 09:44:26'),
(14, 'kendra', 'mbondi', '+237690000110', 'bafoussam, dschang', 'COMP-2026-102', '2026-03-01', '2026-03-09 09:52:53', '2026-03-09 09:52:53'),
(15, 'kendra', 'mbondi', '+237690000110', 'bafoussam, dschang', 'COMP-2026-002', '2026-03-01', '2026-03-09 11:02:06', '2026-03-09 11:02:06');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `consommations`
--

CREATE TABLE `consommations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `abonne_id` bigint(20) UNSIGNED NOT NULL,
  `index_precedent` int(11) NOT NULL,
  `index_actuel` int(11) NOT NULL,
  `consommation` int(11) NOT NULL DEFAULT 0,
  `date_releve` date NOT NULL,
  `operateur_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `consommations`
--

INSERT INTO `consommations` (`id`, `abonne_id`, `index_precedent`, `index_actuel`, `consommation`, `date_releve`, `operateur_id`, `created_at`, `updated_at`) VALUES
(3, 8, 150, 165, 15, '2024-03-08', 3, '2026-03-09 08:50:34', '2026-03-09 08:50:34'),
(4, 9, 160, 190, 30, '2026-03-08', 3, '2026-03-09 08:51:08', '2026-03-09 08:51:08'),
(5, 9, 160, 190, 30, '2026-03-08', 3, '2026-03-09 11:01:25', '2026-03-09 11:01:25');

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `abonne_id` bigint(20) UNSIGNED NOT NULL,
  `consommation_id` bigint(20) UNSIGNED NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_generation` date NOT NULL,
  `statut` enum('impayee','payee') NOT NULL DEFAULT 'impayee',
  `operateur_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `factures`
--

INSERT INTO `factures` (`id`, `abonne_id`, `consommation_id`, `montant`, `date_generation`, `statut`, `operateur_id`, `created_at`, `updated_at`) VALUES
(5, 8, 3, 7500.00, '2026-03-09', 'impayee', 3, '2026-03-09 08:50:34', '2026-03-09 08:50:34'),
(6, 9, 4, 15000.00, '2026-03-09', 'impayee', 3, '2026-03-09 08:51:08', '2026-03-09 08:51:08'),
(7, 8, 3, 10000.00, '2024-03-08', 'impayee', 1, '2026-03-09 08:51:50', '2026-03-09 08:51:50'),
(8, 8, 3, 10000.00, '2024-03-08', 'impayee', 1, '2026-03-09 08:51:52', '2026-03-09 08:51:52'),
(9, 9, 5, 15000.00, '2026-03-09', 'impayee', 3, '2026-03-09 11:01:25', '2026-03-09 11:01:25'),
(10, 8, 3, 10000.00, '2024-03-08', 'impayee', 1, '2026-03-09 11:01:30', '2026-03-09 11:01:30');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_03_08_154703_create_operateurs_table', 1),
(5, '2026_03_08_154713_create_abonnes_table', 1),
(6, '2026_03_08_154720_create_consommations_table', 1),
(7, '2026_03_08_154721_create_factures_table', 1),
(8, '2026_03_08_154721_create_paiements_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `operateurs`
--

CREATE TABLE `operateurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'operateur',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `operateurs`
--

INSERT INTO `operateurs` (`id`, `nom`, `email`, `mot_de_passe`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin Principal', 'royn@camwater.com', '$2y$12$6hQjvastD8QE/iNxa/owyekTt9/BR47kpV7LDFJE3WOR/TgNHJnvi', 'admin', '2026-03-09 10:04:45', '2026-03-09 11:15:19'),
(2, 'Operateur Jean', 'mbondi@camwater.com', '$2y$12$aye5rSqrNh40RaEYc627yOUX2bDwDZaOgNuy0Le8y0Nv5HVuy7RCG', 'operateur', '2026-03-09 10:04:45', '2026-03-09 11:14:57'),
(3, 'Operateur Marie', 'verges@camwater.com', '$2y$12$961rwQ9Cw9z0WjgkRxmhMOLHQSKcVUZ/0Bke13YjR3u7RIqXbPit.', 'operateur', '2026-03-09 10:04:45', '2026-03-09 11:15:35');

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

CREATE TABLE `paiements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `facture_id` bigint(20) UNSIGNED NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_paiement` date NOT NULL,
  `mode_paiement` enum('cash','mobile_money','virement') NOT NULL,
  `operateur_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `abonnes`
--
ALTER TABLE `abonnes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `abonnes_numero_compteur_unique` (`numero_compteur`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Index pour la table `consommations`
--
ALTER TABLE `consommations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consommations_abonne_id_foreign` (`abonne_id`),
  ADD KEY `consommations_operateur_id_foreign` (`operateur_id`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factures_abonne_id_foreign` (`abonne_id`),
  ADD KEY `factures_consommation_id_foreign` (`consommation_id`),
  ADD KEY `factures_operateur_id_foreign` (`operateur_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `operateurs`
--
ALTER TABLE `operateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `operateurs_email_unique` (`email`);

--
-- Index pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paiements_facture_id_foreign` (`facture_id`),
  ADD KEY `paiements_operateur_id_foreign` (`operateur_id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `abonnes`
--
ALTER TABLE `abonnes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `consommations`
--
ALTER TABLE `consommations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `operateurs`
--
ALTER TABLE `operateurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `paiements`
--
ALTER TABLE `paiements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `consommations`
--
ALTER TABLE `consommations`
  ADD CONSTRAINT `consommations_abonne_id_foreign` FOREIGN KEY (`abonne_id`) REFERENCES `abonnes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consommations_operateur_id_foreign` FOREIGN KEY (`operateur_id`) REFERENCES `operateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `factures_abonne_id_foreign` FOREIGN KEY (`abonne_id`) REFERENCES `abonnes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `factures_consommation_id_foreign` FOREIGN KEY (`consommation_id`) REFERENCES `consommations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `factures_operateur_id_foreign` FOREIGN KEY (`operateur_id`) REFERENCES `operateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD CONSTRAINT `paiements_facture_id_foreign` FOREIGN KEY (`facture_id`) REFERENCES `factures` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `paiements_operateur_id_foreign` FOREIGN KEY (`operateur_id`) REFERENCES `operateurs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
