-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 08 Juin 2017 à 13:44
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

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
,`category` varchar(45)
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

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `email` varchar(75) NOT NULL,
  `password` char(40) NOT NULL,
  `money` double NOT NULL,
  `idRole_fk` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  KEY `idRole_users_idx` (`idRole_fk`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Structure de la vue `products_with_category`
--
DROP TABLE IF EXISTS `products_with_category`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `products_with_category` AS select `products`.`id` AS `id`,`products`.`name` AS `name`,`products`.`brand` AS `brand`,`products`.`price` AS `price`,`products`.`alertQuantity` AS `alertQuantity`,`products`.`imgName` AS `imgName`,`categories`.`name` AS `category` from (`products` join `categories`) where (`products`.`idCategory_fk` = `categories`.`id`);

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