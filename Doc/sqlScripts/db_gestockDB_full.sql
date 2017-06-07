-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 07 Juin 2017 à 13:49
-- Version du serveur :  5.6.15-log
-- Version de PHP :  5.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `gestockdb`
--

-- --------------------------------------------------------

--
-- Structure de la table `carts`
--

CREATE TABLE IF NOT EXISTS `carts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUser_fk` int(11) NOT NULL,
  `dateOrder` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `idUser_carts_idx` (`idUser_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `carts_has_stocks`
--

CREATE TABLE IF NOT EXISTS `carts_has_stocks` (
  `id` int(11) NOT NULL,
  `idCart_fk` int(11) NOT NULL,
  `idStock_has_product_fk` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idStock_carts_has_products_idx` (`idStock_has_product_fk`),
  KEY `idCart_carts_has_products` (`idCart_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'CPU'),
(2, 'CPU Cooling'),
(3, 'Motherboard'),
(4, 'RAM'),
(5, 'Hard Drive'),
(6, 'GPU'),
(7, 'Case'),
(8, 'Power Supply');

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `brand` varchar(30) NOT NULL,
  `price` double NOT NULL,
  `alertQuantity` int(11) NOT NULL,
  `imgName` varchar(50) NOT NULL,
  `idCategory_fk` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idProduct_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `idCategory_products_idx` (`idCategory_fk`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

--
-- Contenu de la table `products`
--

INSERT INTO `products` (`id`, `name`, `brand`, `price`, `alertQuantity`, `imgName`, `idCategory_fk`) VALUES
(1, 'VS650', 'Corsair', 69, 5, 'corsairVS650.jpg', 8),
(2, 'RM750x', 'Corsair', 135, 5, 'corsairRM750x.jpg', 8),
(3, 'SuperNOVA G2', 'EVGA', 118, 5, 'evgaSuperNOVAG2.jpg', 8),
(4, 'Crystal 460X RGB', 'Corsair', 179, 5, 'corsairCrystal460XRGB.jpg', 7),
(5, 'Define R5 Black', 'Fractal', 119, 5, 'fractalDefineR5Black.jpg', 7),
(6, 'Source 340 Elite', 'NZXT', 119, 5, 'nzxtSource340Elite.jpg', 7),
(7, 'Geforce GTX 1050 Ti OC 4G', 'Gigabyte', 160, 5, 'gigabyteGeforceGTX1050Ti.jpg', 6),
(8, 'Geforce GTX 1070 GAMING X 8G', 'MSI', 459, 5, 'msiGeforceGTX1070GAMINGX.jpg', 6),
(9, 'Geforce GTX 1080 Ti STRIX 11G', 'Asus', 869, 5, 'asusGeforceGTX1080Ti.jpg', 6),
(10, 'Black 2To', 'Western Digital', 137, 5, 'westernDigitalBlack2To.jpg', 5),
(11, 'Red Pro 4To', 'Western Digital', 242, 5, 'westernDigitalRedPro4To.jpg', 5),
(12, 'Barracuda', 'Seagate', 132, 5, 'seagateBarracuda4To.jpg', 5),
(13, 'Fury 2x8Go', 'HyperX', 160, 5, 'hyperXFury2x8.jpg', 4),
(14, 'Vengeance LPX 2x8Go', 'Corsair', 150, 5, 'corsairVengeanceLPX2x8.jpg', 4),
(15, 'Ripjaws V 2x16', 'G.Skill', 285, 5, 'g.skillRipjawsV2x16.jpg', 4),
(16, 'STRIX Z270F GAMING', 'Asus', 196, 5, 'asusSTRIXZ270GAMING.jpg', 3),
(17, 'Z170XP-SLI', 'Gigabyte', 169, 5, 'gigabyteZ170XP-SLI.jpg', 3),
(18, 'Z270 Gaming Pro Carbon', 'MSI', 183, 5, 'msiZ270GamingProCarbon.jpg', 3),
(19, 'H100i v2', 'Corsair', 139, 5, 'corsairH100iv2.jpg', 2),
(20, 'Dark Rock 3', 'Be Quiet !', 79, 5, 'beQuietDarkRock3.jpg', 2),
(21, 'Dark Rock 3 PRO', 'Be Quiet !', 89, 5, 'beQuietDarkRockPRO3.jpg', 2),
(22, 'Core i7 7700K BOX 4.2GHz', 'Intel', 365, 5, 'intelCorei77700KBOX.jpg', 1),
(23, 'Core i5 7600K BOX 3.8GHz', 'Intel', 256, 5, 'intelCorei57600KBOX.jpg', 1),
(24, 'Pentium G4560 3.5GHz', 'Intel', 74.6, 5, 'intelPentiumG4560.jpg', 1);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `products_with_category`
--
CREATE TABLE IF NOT EXISTS `products_with_category` (
`id` int(11)
,`name` varchar(50)
,`brand` varchar(30)
,`price` double
,`alertQuantity` int(11)
,`imgName` varchar(50)
,`coategory` varchar(45)
);
-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(2, 'Admin'),
(1, 'User');

-- --------------------------------------------------------

--
-- Structure de la table `stocks`
--

CREATE TABLE IF NOT EXISTS `stocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shelf` char(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `stocks`
--

INSERT INTO `stocks` (`id`, `shelf`) VALUES
(1, 'A1'),
(2, 'A2'),
(3, 'A3'),
(4, 'B1'),
(5, 'B2'),
(6, 'B3'),
(7, 'C1'),
(8, 'C2'),
(9, 'C3');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` char(40) NOT NULL,
  `money` double NOT NULL,
  `idRole_fk` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  KEY `idRole_users_idx` (`idRole_fk`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `money`, `idRole_fk`) VALUES
(1, 'GestockAdmin', 'f71a45cccb65de2fba32df9c3e386677989433eb', 10000, 2),
(2, 'UserTest1', 'f5249ff6ce1714a780097b538f1a25c9ee2019b3', 500, 1),
(3, 'UserTest2', '29ecc870799029da842a7f10c44a74b8c34e774d', 500, 1);

-- --------------------------------------------------------

--
-- Structure de la vue `products_with_category`
--
DROP TABLE IF EXISTS `products_with_category`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `products_with_category` AS select `products`.`id` AS `id`,`products`.`name` AS `name`,`products`.`brand` AS `brand`,`products`.`price` AS `price`,`products`.`alertQuantity` AS `alertQuantity`,`products`.`imgName` AS `imgName`,`categories`.`name` AS `coategory` from (`products` join `categories`) where (`products`.`idCategory_fk` = `categories`.`id`);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `idUser_carts` FOREIGN KEY (`idUser_fk`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `carts_has_stocks`
--
ALTER TABLE `carts_has_stocks`
  ADD CONSTRAINT `idCart_carts_has_products` FOREIGN KEY (`idCart_fk`) REFERENCES `carts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `idStock_carts_has_products` FOREIGN KEY (`idStock_has_product_fk`) REFERENCES `stocks_has_products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `idCategory_products` FOREIGN KEY (`idCategory_fk`) REFERENCES `categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `idRole_users` FOREIGN KEY (`idRole_fk`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

CREATE USER 'gestockAdminDB'@'127.0.0.1' IDENTIFIED BY 'gestockTPI2017';
GRANT ALL PRIVILEGES ON `GestockDB`.* TO 'gestockAdminDB';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
