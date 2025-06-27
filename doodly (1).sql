-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 22 mai 2025 à 11:36
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
-- Base de données : `doodly`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`) VALUES
(1, 'Planners', NULL),
(2, 'Journals and Notebooks', NULL),
(3, 'Sketchbooks', NULL),
(4, 'Leather Journals', 2),
(5, 'Lined Notebooks', 2),
(6, 'Planners', NULL),
(7, 'Journals and Notebooks', NULL),
(8, 'Sketchbooks', NULL),
(9, 'Grid Journals', 2),
(10, 'Lined Notebooks', 2);

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','shipped','delivered','cancelled') DEFAULT 'pending',
  `total` decimal(10,2) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `status`, `total`, `name`, `payment_method`, `shipping_address`, `city`, `postal_code`, `phone`) VALUES
(2, 4, '2025-05-04 01:02:31', 'delivered', 32.97, '', '', '', '', '', ''),
(8, 4, '2025-05-19 15:25:19', 'shipped', 16.99, '', 'cash', '9 Place de I\'Europe Residence ARPEJ millénium', 'Velizy-villacoublay', '78140', '');

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`) VALUES
(2, 2, 6, 3, 12.99),
(11, 8, 14, 1, 16.99);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `sales_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category`, `image`, `stock`, `category_id`, `sales_count`) VALUES
(1, 'Floral Planner', 'A5 undated daily planner with time blocks', 12.99, 'Planners', 'Doodly images/produit1.jpg', 99, 1, 1),
(2, 'Minimalist Journal', 'Lined A5 journal for daily thoughts', 9.99, 'Journalsandnotebooks', 'Doodly images/image1.jpg\n', 80, 2, 1),
(3, 'Leather Journal', 'Premium leather journal with blank pages', 19.99, 'Journals', 'Doodly images/image2.jpg', 50, 3, 1),
(4, 'Sketchbook', 'Blank A4 sketchbook for artists', 16.99, 'Sketchbooks', 'Doodly images/sec2.jpg', 30, 4, 1),
(5, 'Hardcover Sketchbook', 'Sturdy hardcover A4 sketchbook', 14.00, 'Sketchbooks', 'Doodly images/sketch1.jpg', 40, 4, 0),
(6, 'Weekly Planner', 'Undated weekly planner with to-do lists', 11.99, 'Planners', 'Doodly images/planner1.jpg', 60, 1, 0),
(7, 'Travel Journal', 'Compact journal ', 9.99, 'Journalsandnotebooks', 'Doodly images/travel.jpg', 70, 2, 0),
(8, 'Dot Grid Notebook', 'A5 notebook with dot grid pages', 8.49, 'Journalsandnotebooks', 'Doodly images/dotgrid.jpg', 90, 2, 0),
(10, 'Calligraphy Pad', 'Smooth pages for calligraphy practice', 13.99, 'Sketchbooks', 'Doodly images/calligraphy.jpg', 25, 4, 4),
(11, 'Fitness Journal', 'Track workouts and meals', 12.99, 'Journalsandnotebooks', 'Doodly images/fitness.jpg', 45, 2, 0),
(12, 'Mindfulness Journal', 'Guided journal for mindfulness and gratitude', 15.99, 'Journalsandnotebooks', 'Doodly images/mindfulness.jpg', 40, 2, 0),
(13, 'Project Planner', 'Organize personal and professional projects', 17.20, 'Planners', 'Doodly images/project.jpg', 50, 1, 0),
(14, 'Watercolor Sketchbook', 'Thick pages for watercolor painting', 16.99, 'Sketchbooks', 'Doodly images/watercolor.jpg', 19, 4, 0);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `address`, `phone`, `date_created`) VALUES
(3, 'Admin User', 'admin@example.com', 'adminpass', 'admin', '789 Admin Ave', '1122334455', '2025-05-12 19:13:10'),
(4, 'amira', 'amirakilito@gmail.com', '$2y$10$tOS.hekV8WnXqQekKHa1m.yCRrayQovKYqSzeHn9n.ipaclBMcd4O', 'customer', '9 Place de I\'Europe Residence ARPEJ millénium', '0749795805', '2025-05-11 19:13:05'),
(11, 'amirak', 'admin1@example.com', '$2y$10$iRSWuGAHEE5wgBjCXTJ3UO5pK93RhXRGh8QCYviat6vVDLvlSdSQ2', 'admin', NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_1` (`user_id`);

--
-- Index pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category` (`category_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
