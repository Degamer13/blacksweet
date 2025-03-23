-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-03-2025 a las 17:00:33
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `subasta`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `number` int(11) NOT NULL,
  `race_id` bigint(20) UNSIGNED NOT NULL,
  `ejemplar_name` varchar(255) NOT NULL,
  `cliente` varchar(255) NOT NULL,
  `monto1` decimal(10,2) NOT NULL,
  `monto2` decimal(10,2) NOT NULL,
  `monto3` decimal(10,2) NOT NULL,
  `monto4` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `porcentaje` decimal(10,2) NOT NULL,
  `pote` decimal(10,2) DEFAULT NULL,
  `acumulado` decimal(10,2) DEFAULT NULL,
  `total_pagar` decimal(10,2) NOT NULL,
  `total_subasta` decimal(10,2) NOT NULL,
  `subasta1` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subasta2` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subasta3` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subasta4` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ejemplar_race`
--

CREATE TABLE `ejemplar_race` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `race_id` bigint(20) UNSIGNED NOT NULL,
  `ejemplar_name` varchar(255) NOT NULL,
  `status` enum('activar','desactivar') NOT NULL DEFAULT 'desactivar',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(8, '2025_03_05_193819_create_ejemplar_table', 1),
(31, '2014_10_12_000000_create_users_table', 2),
(32, '2014_10_12_100000_create_password_reset_tokens_table', 2),
(33, '2014_10_12_100000_create_password_resets_table', 2),
(34, '2019_08_19_000000_create_failed_jobs_table', 2),
(35, '2019_12_14_000001_create_personal_access_tokens_table', 2),
(36, '2025_03_05_165032_create_permission_tables', 2),
(37, '2025_03_05_180957_create_races_table', 2),
(38, '2025_03_07_202626_create_ejemplar_race_table', 2),
(39, '2025_03_08_213736_create_remates_table', 2),
(40, '2025_03_20_160637_create_bitacora_table', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'show-admin', 'web', '2025-03-22 15:39:23', '2025-03-22 15:39:23'),
(2, 'role-list', 'web', '2025-03-22 15:39:23', '2025-03-22 15:39:23'),
(3, 'role-show', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(4, 'role-search', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(5, 'role-create', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(6, 'role-edit', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(7, 'role-delete', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(8, 'user-list', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(9, 'user-show', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(10, 'user-search', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(11, 'user-create', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(12, 'user-edit', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(13, 'user-delete', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(14, 'permission-list', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(15, 'permission-show', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(16, 'permission-search', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(17, 'permission-create', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(18, 'permission-edit', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(19, 'permission-delete', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(20, 'remate-list', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(21, 'remate-logros', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(22, 'remate-show', 'web', '2025-03-22 15:39:24', '2025-03-22 15:39:24'),
(23, 'remate-search', 'web', '2025-03-22 15:39:25', '2025-03-22 15:39:25'),
(24, 'remate-create', 'web', '2025-03-22 15:39:25', '2025-03-22 15:39:25'),
(25, 'remate-edit', 'web', '2025-03-22 15:39:25', '2025-03-22 15:39:25'),
(26, 'remate-delete', 'web', '2025-03-22 15:39:25', '2025-03-22 15:39:25'),
(27, 'race-list', 'web', '2025-03-22 15:39:26', '2025-03-22 15:39:26'),
(28, 'race-show', 'web', '2025-03-22 15:39:26', '2025-03-22 15:39:26'),
(29, 'race-search', 'web', '2025-03-22 15:39:26', '2025-03-22 15:39:26'),
(30, 'race-create', 'web', '2025-03-22 15:39:26', '2025-03-22 15:39:26'),
(31, 'race-edit', 'web', '2025-03-22 15:39:26', '2025-03-22 15:39:26'),
(32, 'race-delete', 'web', '2025-03-22 15:39:27', '2025-03-22 15:39:27'),
(33, 'parametro-list', 'web', '2025-03-22 15:39:27', '2025-03-22 15:39:27'),
(34, 'parametro-show', 'web', '2025-03-22 15:39:27', '2025-03-22 15:39:27'),
(35, 'parametro-search', 'web', '2025-03-22 15:39:27', '2025-03-22 15:39:27'),
(36, 'parametro-create', 'web', '2025-03-22 15:39:27', '2025-03-22 15:39:27'),
(37, 'parametro-edit', 'web', '2025-03-22 15:39:27', '2025-03-22 15:39:27'),
(38, 'parametro-delete', 'web', '2025-03-22 15:39:28', '2025-03-22 15:39:28'),
(39, 'bitacora-list', 'web', '2025-03-22 15:39:28', '2025-03-22 15:39:28'),
(40, 'bitacora-search', 'web', '2025-03-22 15:39:28', '2025-03-22 15:39:28'),
(41, 'bitacora-pdf', 'web', '2025-03-22 15:39:28', '2025-03-22 15:39:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `races`
--

CREATE TABLE `races` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `races`
--

INSERT INTO `races` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Carrera 1', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(2, 'Carrera 2', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(3, 'Carrera 3', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(4, 'Carrera 4', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(5, 'Carrera 5', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(6, 'Carrera 6', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(7, 'Carrera 7', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(8, 'Carrera 8', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(9, 'Carrera 9', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(10, 'Carrera 10', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(11, 'Carrera 11', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(12, 'Carrera 12', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(13, 'Carrera 13', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(14, 'Carrera 14', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(15, 'Carrera 15', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(16, 'Carrera 16', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(17, 'Carrera 17', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(18, 'Carrera 18', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(19, 'Carrera 19', '2025-03-22 15:40:38', '2025-03-22 15:40:38'),
(20, 'Carrera 20', '2025-03-22 15:40:38', '2025-03-22 15:40:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `remates`
--

CREATE TABLE `remates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `number` int(11) NOT NULL,
  `race_id` bigint(20) UNSIGNED NOT NULL,
  `ejemplar_name` varchar(255) NOT NULL,
  `cliente` varchar(255) NOT NULL,
  `monto1` decimal(10,2) NOT NULL,
  `monto2` decimal(10,2) NOT NULL,
  `monto3` decimal(10,2) NOT NULL,
  `monto4` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `porcentaje` decimal(10,2) NOT NULL,
  `pote` decimal(10,2) DEFAULT NULL,
  `acumulado` decimal(10,2) DEFAULT NULL,
  `total_pagar` decimal(10,2) NOT NULL,
  `total_subasta` decimal(10,2) NOT NULL,
  `subasta1` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subasta2` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subasta3` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subasta4` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-03-22 15:39:43', '2025-03-22 15:39:43'),
(2, 'ventas', 'web', '2025-03-22 15:43:14', '2025-03-22 15:43:14'),
(3, 'usuarios', 'web', '2025-03-22 15:43:58', '2025-03-22 15:43:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(20, 2),
(21, 1),
(21, 2),
(21, 3),
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(24, 1),
(24, 2),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2y$12$LO2ejMDm59Nw6lFktMe30ujvmsNo2ffmhtcAR1StDA5bPoPPqKa3S', NULL, '2025-03-22 15:39:43', '2025-03-22 15:39:43'),
(2, 'ventas', 'ventas@gmail.com', NULL, '$2y$12$zPHaNFlDXgWjlPfevn7cy.r3gbMZdPvztKsH.rWgWNf2o.9XsIbqC', NULL, '2025-03-22 15:44:39', '2025-03-22 15:44:39'),
(3, 'usuario', 'usuario@gmail.com', NULL, '$2y$12$/lDvSB4rahcEaPCDrZR24eAtyj9XRzRvbAUdtL/MmFF.TqrVLZUzy', NULL, '2025-03-22 15:45:18', '2025-03-22 15:45:18');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bitacora_race_id_foreign` (`race_id`);

--
-- Indices de la tabla `ejemplar_race`
--
ALTER TABLE `ejemplar_race`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ejemplar_race_race_id_foreign` (`race_id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `races`
--
ALTER TABLE `races`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `remates`
--
ALTER TABLE `remates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `remates_race_id_foreign` (`race_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indices de la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ejemplar_race`
--
ALTER TABLE `ejemplar_race`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `races`
--
ALTER TABLE `races`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `remates`
--
ALTER TABLE `remates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_race_id_foreign` FOREIGN KEY (`race_id`) REFERENCES `races` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ejemplar_race`
--
ALTER TABLE `ejemplar_race`
  ADD CONSTRAINT `ejemplar_race_race_id_foreign` FOREIGN KEY (`race_id`) REFERENCES `races` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `remates`
--
ALTER TABLE `remates`
  ADD CONSTRAINT `remates_race_id_foreign` FOREIGN KEY (`race_id`) REFERENCES `races` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
