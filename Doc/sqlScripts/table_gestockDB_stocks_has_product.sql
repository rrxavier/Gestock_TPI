-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 16 Juin 2017 à 13:29
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

--
-- Contenu de la table `stocks_has_product`
--

INSERT INTO `stocks_has_product` (`id`, `idStock_fk`, `idProduct_fk`, `quantity`) VALUES
(1, 1, 1, 10),
(2, 1, 2, 10),
(3, 1, 3, 10),
(4, 2, 4, 10),
(5, 2, 5, 10),
(6, 2, 6, 10),
(7, 3, 7, 10),
(8, 3, 8, 10),
(9, 3, 9, 10),
(10, 4, 10, 10),
(11, 4, 11, 10),
(12, 4, 12, 10),
(13, 5, 13, 10),
(14, 5, 14, 10),
(15, 5, 15, 10),
(16, 6, 16, 10),
(17, 9, 17, 10),
(18, 6, 18, 10),
(19, 7, 19, 10),
(20, 7, 20, 10),
(21, 7, 21, 10),
(22, 8, 22, 10),
(23, 8, 23, 10),
(24, 8, 24, 10);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
