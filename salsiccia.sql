-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 24, 2026 at 11:06 AM
-- Server version: 11.8.3-MariaDB-1+b1 from Debian
-- PHP Version: 8.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `salsiccia`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

CREATE TABLE `categorie` (
  `id_categoria` int(10) UNSIGNED NOT NULL,
  `descrizione_cat` varchar(30) DEFAULT NULL,
  `testo_bottone` varchar(20) NOT NULL,
  `colore` varchar(7) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorie`
--

INSERT INTO `categorie` (`id_categoria`, `descrizione_cat`, `testo_bottone`, `colore`) VALUES
(19, 'Fruttati Invec.', 'Fruttati Invec.', '03'),
(17, 'Sake', 'Sake', '02'),
(20, 'Junmai', 'Junmai', '05');

-- --------------------------------------------------------

--
-- Table structure for table `contatori`
--

CREATE TABLE `contatori` (
  `id_contatore` smallint(6) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `limite_qta` smallint(6) NOT NULL,
  `controllo_periodo` char(1) NOT NULL,
  `data_da` datetime NOT NULL,
  `data_a` datetime NOT NULL,
  `attivo` char(1) NOT NULL,
  `attivo_app` char(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contatori`
--

INSERT INTO `contatori` (`id_contatore`, `nome`, `limite_qta`, `controllo_periodo`, `data_da`, `data_a`, `attivo`, `attivo_app`) VALUES
(11, 'Bocc', 100, 'F', '2025-06-06 00:00:00', '2025-06-06 00:00:00', 'T', 'T');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` smallint(6) NOT NULL,
  `user_id` varchar(50) NOT NULL DEFAULT '',
  `PASSWORD` varchar(50) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `user_id`, `PASSWORD`) VALUES
(1, 'root', '*30008BC520788A8BBC4328BE0EE7E7311E9358CE');

-- --------------------------------------------------------

--
-- Table structure for table `ordini`
--

CREATE TABLE `ordini` (
  `id_ordine` int(10) UNSIGNED NOT NULL,
  `data_ora` datetime DEFAULT NULL,
  `tipo` varchar(3) DEFAULT NULL,
  `totale` float DEFAULT NULL,
  `n_pezzi` int(10) UNSIGNED DEFAULT NULL,
  `id_cassa` tinyint(3) UNSIGNED DEFAULT NULL,
  `chiuso` char(1) DEFAULT NULL,
  `num_biglietti` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ordini`
--

INSERT INTO `ordini` (`id_ordine`, `data_ora`, `tipo`, `totale`, `n_pezzi`, `id_cassa`, `chiuso`, `num_biglietti`) VALUES
(1, '2025-10-30 15:20:07', 'nor', 13, 1, 1, '1', 1),
(2, '2025-10-30 15:29:18', 'nor', 8, 1, 1, '1', 1),
(3, '2025-10-30 15:31:02', 'nor', 8, 2, 1, '1', 1),
(4, '2025-10-30 15:51:12', 'nor', 42, 3, 1, '1', 1),
(5, '2025-10-30 15:55:16', 'nor', 4, 1, 1, '1', 1),
(7, '2025-10-30 16:55:35', 'nor', 10, 1, 1, '1', 1),
(9, '2025-10-30 17:00:57', 'nor', 3, 2, 1, '1', 1),
(11, '2025-10-30 17:13:07', 'nor', 16, 3, 1, '1', 1),
(12, '2025-10-30 18:30:30', 'nor', 4, 2, 1, '1', 1),
(16, '2025-10-31 12:18:38', 'nor', 4, 1, 1, '1', 1),
(19, '2025-10-31 14:12:27', 'nor', 8, 2, 1, '1', 1),
(21, '2025-10-31 14:52:40', 'nor', 4, 1, 1, '1', 1),
(24, '2025-10-31 16:17:37', 'nor', 3, 1, 1, '1', 1),
(25, '2025-10-31 17:04:49', 'nor', 5, 1, 1, '1', 1),
(26, '2025-10-31 17:57:56', 'nor', 2, 1, 1, '1', 1),
(27, '2025-10-31 18:37:31', 'nor', 42, 3, 1, '1', 1),
(28, '2025-10-31 18:41:18', 'nor', 15, 2, 1, '1', 1),
(29, '2025-10-31 19:16:12', 'nor', 4, 1, 1, '1', 1),
(30, '2025-10-31 19:41:14', 'nor', 5, 1, 1, '1', 1),
(31, '2025-10-31 20:38:28', 'nor', 1, 1, 1, '1', 1),
(32, '2025-10-31 20:39:01', 'nor', 1, 1, 1, '1', 1),
(36, '2025-11-01 10:24:44', 'nor', 1, 1, 1, '1', 1),
(37, '2025-11-01 10:48:58', 'nor', 2, 1, 1, '1', 1),
(38, '2025-11-01 11:04:56', 'nor', 12, 1, 1, '1', 1),
(39, '2025-11-01 11:34:57', 'nor', 4, 1, 1, '1', 1),
(40, '2025-11-01 11:35:58', 'nor', 2, 1, 1, '1', 1),
(41, '2025-11-01 11:39:12', 'nor', 3, 1, 1, '1', 1),
(42, '2025-11-01 11:40:56', 'nor', 22, 3, 1, '1', 1),
(43, '2025-11-01 11:45:04', 'nor', 8, 1, 1, '1', 1),
(44, '2025-11-01 11:48:20', 'nor', 10, 1, 1, '1', 1),
(45, '2025-11-01 11:49:13', 'nor', 16, 5, 1, '1', 1),
(46, '2025-11-01 11:57:52', 'nor', 8, 2, 1, '1', 1),
(53, '2025-11-01 12:32:15', 'nor', 8, 2, 1, '1', 1),
(54, '2025-11-01 12:33:06', 'nor', 25, 3, 1, '1', 1),
(55, '2025-11-01 12:35:43', 'nor', 40, 3, 1, '1', 1),
(56, '2025-11-01 12:38:50', 'nor', 16, 3, 1, '1', 1),
(58, '2025-11-01 12:50:08', 'nor', 5, 1, 1, '1', 1),
(70, '2025-11-01 14:20:34', 'nor', 39, 1, 1, '1', 1),
(60, '2025-11-01 13:13:35', 'nor', 7, 2, 1, '1', 1),
(61, '2025-11-01 13:22:27', 'nor', 15, 3, 1, '1', 1),
(62, '2025-11-01 13:33:24', 'nor', 3, 1, 1, '1', 1),
(63, '2025-11-01 13:37:53', 'nor', 6, 2, 1, '1', 1),
(64, '2025-11-01 13:41:21', 'nor', 2, 1, 1, '1', 1),
(65, '2025-11-01 13:46:48', 'nor', 7, 1, 1, '1', 1),
(66, '2025-11-01 13:53:56', 'nor', 15, 1, 1, '1', 1),
(67, '2025-11-01 13:58:13', 'nor', 3, 1, 1, '1', 1),
(68, '2025-11-01 14:01:00', 'nor', 10, 5, 1, '1', 1),
(69, '2025-11-01 14:02:16', 'nor', 5, 1, 1, '1', 1),
(71, '2025-11-01 14:40:14', 'nor', 3, 1, 1, '1', 1),
(72, '2025-11-01 14:49:50', 'nor', 5, 1, 1, '1', 1),
(73, '2025-11-01 15:08:19', 'nor', 8, 1, 1, '1', 1),
(74, '2025-11-01 15:09:03', 'nor', 5, 1, 1, '1', 1),
(75, '2025-11-01 15:11:45', 'nor', 7, 1, 1, '1', 1),
(76, '2025-11-01 15:16:24', 'nor', 4, 2, 1, '1', 1),
(77, '2025-11-01 15:21:54', 'nor', 3, 1, 1, '1', 1),
(78, '2025-11-01 15:28:38', 'nor', 40, 2, 1, '1', 1),
(79, '2025-11-01 15:30:55', 'nor', 10, 1, 1, '1', 1),
(80, '2025-11-01 15:34:05', 'nor', 15, 1, 1, '1', 1),
(81, '2025-11-01 15:35:56', 'nor', 2, 1, 1, '1', 1),
(82, '2025-11-01 15:41:12', 'nor', 12, 1, 1, '1', 1),
(83, '2025-11-01 15:53:16', 'nor', 3, 1, 1, '1', 1),
(84, '2025-11-01 15:56:09', 'nor', 4, 1, 1, '1', 1),
(85, '2025-11-01 16:04:13', 'nor', 14, 2, 1, '1', 1),
(86, '2025-11-01 16:13:04', 'nor', 17, 2, 1, '1', 1),
(87, '2025-11-01 16:14:37', 'nor', 12, 1, 1, '1', 1),
(88, '2025-11-01 16:20:37', 'nor', 2, 1, 1, '1', 1),
(89, '2025-11-01 16:22:57', 'nor', 10, 1, 1, '1', 1),
(90, '2025-11-01 16:24:54', 'nor', 42, 3, 1, '1', 1),
(91, '2025-11-01 16:33:11', 'nor', 9, 2, 1, '1', 1),
(98, '2025-11-01 16:49:12', 'nor', 4, 1, 1, '1', 1),
(93, '2025-11-01 16:37:32', 'nor', 22, 1, 1, '1', 1),
(94, '2025-11-01 16:38:07', 'nor', 5, 1, 1, '1', 1),
(96, '2025-11-01 16:43:12', 'nor', 8, 1, 1, '1', 1),
(97, '2025-11-01 16:44:37', 'nor', 26, 1, 1, '1', 1),
(99, '2025-11-01 16:51:05', 'nor', 2, 1, 1, '1', 1),
(100, '2025-11-01 17:01:37', 'nor', 5, 2, 1, '1', 1),
(101, '2025-11-01 17:06:28', 'nor', 24, 2, 1, '1', 1),
(102, '2025-11-01 17:09:27', 'nor', 12, 1, 1, '1', 1),
(103, '2025-11-01 17:25:15', 'nor', 7, 1, 1, '1', 1),
(104, '2025-11-01 17:28:47', 'nor', 3, 1, 1, '1', 1),
(105, '2025-11-01 17:29:58', 'nor', 5, 1, 1, '1', 1),
(106, '2025-11-01 17:45:34', 'nor', 30, 1, 1, '1', 1),
(107, '2025-11-01 17:50:55', 'nor', 6, 2, 1, '1', 1),
(109, '2025-11-01 17:54:10', 'nor', 7, 2, 1, '1', 1),
(110, '2025-11-01 17:57:08', 'nor', 15, 1, 1, '1', 1),
(111, '2025-11-01 18:11:47', 'nor', 12, 1, 1, '1', 1),
(112, '2025-11-01 18:31:01', 'nor', 12, 1, 1, '1', 1),
(113, '2025-11-01 19:27:10', 'nor', 8, 2, 1, '1', 1),
(116, '2025-11-01 20:19:04', 'nor', 20, 3, 1, '1', 1),
(117, '2025-11-01 20:22:23', 'nor', 10, 1, 1, '1', 1),
(118, '2025-11-02 10:05:22', 'nor', 11, 2, 1, '1', 1),
(121, '2025-11-02 10:45:23', 'nor', 12, 1, 1, '1', 1),
(122, '2025-11-02 10:46:20', 'nor', 9, 3, 1, '1', 1),
(123, '2025-11-02 10:48:05', 'nor', 22, 1, 1, '1', 1),
(124, '2025-11-02 10:50:14', 'nor', 8, 1, 1, '1', 1),
(125, '2025-11-02 10:54:27', 'nor', 6, 4, 1, '1', 1),
(126, '2025-11-02 11:10:08', 'nor', 4, 1, 1, '1', 1),
(128, '2025-11-02 11:22:20', 'nor', 24, 3, 1, '1', 1),
(129, '2025-11-02 11:29:34', 'nor', 25, 2, 1, '1', 1),
(131, '2025-11-02 11:31:45', 'nor', 5, 1, 1, '1', 1),
(132, '2025-11-02 11:32:50', 'nor', 16, 3, 1, '1', 1),
(133, '2025-11-02 11:35:46', 'nor', 8, 2, 1, '1', 1),
(134, '2025-11-02 11:38:30', 'nor', 3, 3, 1, '1', 1),
(135, '2025-11-02 11:41:51', 'nor', 40, 1, 1, '1', 1),
(136, '2025-11-02 11:44:15', 'nor', 8, 2, 1, '1', 1),
(137, '2025-11-02 11:47:07', 'nor', 6, 6, 1, '1', 1),
(139, '2025-11-02 11:55:31', 'nor', 5, 2, 1, '1', 1),
(140, '2025-11-02 12:04:45', 'nor', 2, 1, 1, '1', 1),
(141, '2025-11-02 12:08:03', 'nor', 20, 2, 1, '1', 1),
(142, '2025-11-02 12:16:45', 'nor', 12, 1, 1, '1', 1),
(143, '2025-11-02 12:25:02', 'nor', 8, 1, 1, '1', 1),
(144, '2025-11-02 12:28:55', 'nor', 9, 1, 1, '1', 1),
(145, '2025-11-02 12:41:39', 'nor', 2, 1, 1, '1', 1),
(146, '2025-11-02 12:46:29', 'nor', 26, 1, 1, '1', 1),
(147, '2025-11-02 12:48:15', 'nor', 10, 1, 1, '1', 1),
(148, '2025-11-02 13:10:41', 'nor', 7, 2, 1, '1', 1),
(151, '2025-11-02 13:50:14', 'nor', 34, 2, 1, '1', 1),
(150, '2025-11-02 13:25:23', 'nor', 52, 2, 1, '1', 1),
(152, '2025-11-02 14:10:08', 'nor', 5, 1, 1, '1', 1),
(153, '2025-11-02 14:17:32', 'nor', 25, 2, 1, '1', 1),
(154, '2025-11-02 14:18:34', 'nor', 2, 1, 1, '1', 1),
(155, '2025-11-02 14:34:08', 'nor', 2, 1, 1, '1', 1),
(156, '2025-11-02 14:36:29', 'nor', 5, 1, 1, '1', 1),
(157, '2025-11-02 14:41:24', 'nor', 12, 2, 1, '1', 1),
(158, '2025-11-02 14:48:38', 'nor', 3, 1, 1, '1', 1),
(159, '2025-11-02 14:53:29', 'nor', 4, 1, 1, '1', 1),
(160, '2025-11-02 14:57:05', 'nor', 5, 1, 1, '1', 1),
(161, '2025-11-02 15:05:34', 'nor', 12, 1, 1, '1', 1),
(162, '2025-11-02 15:06:53', 'nor', 12, 1, 1, '1', 1),
(163, '2025-11-02 15:26:31', 'nor', 4, 1, 1, '1', 1),
(165, '2025-11-02 15:32:47', 'nor', 7, 2, 1, '1', 1),
(166, '2025-11-02 15:35:59', 'nor', 24, 4, 1, '1', 1),
(167, '2025-11-02 15:38:21', 'nor', 4, 2, 1, '1', 1),
(168, '2025-11-02 15:55:18', 'nor', 4, 1, 1, '1', 1),
(169, '2025-11-02 15:56:16', 'nor', 13, 2, 1, '1', 1),
(170, '2025-11-02 16:00:07', 'nor', 20, 2, 1, '1', 1),
(171, '2025-11-02 16:09:23', 'nor', 10, 1, 1, '1', 1),
(172, '2025-11-02 16:10:23', 'nor', 30, 3, 1, '1', 1),
(173, '2025-11-02 16:14:46', 'nor', 12, 1, 1, '1', 1),
(174, '2025-11-02 16:16:20', 'nor', 10, 1, 1, '1', 1),
(175, '2025-11-02 16:23:41', 'nor', 45, 3, 1, '1', 1),
(176, '2025-11-02 16:25:39', 'nor', 6, 2, 1, '1', 1),
(177, '2025-11-02 16:32:53', 'nor', 5, 1, 1, '1', 1),
(178, '2025-11-02 16:51:09', 'nor', 8, 2, 1, '1', 1),
(179, '2025-11-02 16:52:36', 'nor', 31, 6, 1, '1', 1),
(181, '2025-11-02 16:56:19', 'nor', 15, 2, 1, '1', 1),
(183, '2025-11-02 16:57:47', 'nor', 6, 2, 1, '1', 1),
(185, '2025-11-02 17:03:24', 'nor', 24, 2, 1, '1', 1),
(186, '2025-11-02 17:06:49', 'nor', 12, 1, 1, '1', 1),
(187, '2025-11-02 17:07:24', 'nor', 5, 1, 1, '1', 1),
(188, '2025-11-02 17:10:14', 'nor', 8, 1, 1, '1', 1),
(189, '2025-11-02 17:10:48', 'nor', 5, 1, 1, '1', 1),
(190, '2025-11-02 17:18:17', 'nor', 12, 1, 1, '1', 1),
(191, '2025-11-02 17:19:30', 'nor', 16, 4, 1, '1', 1),
(192, '2025-11-02 17:21:27', 'nor', 8, 3, 1, '1', 1),
(193, '2025-11-02 17:25:43', 'nor', 2, 1, 1, '1', 1),
(195, '2025-11-02 17:33:37', 'nor', 16, 3, 1, '1', 1),
(196, '2025-11-02 17:46:14', 'nor', 15, 4, 1, '1', 1),
(197, '2025-11-02 18:01:15', 'nor', 10, 2, 1, '1', 1),
(198, '2025-11-02 18:18:34', 'nor', 16, 3, 1, '1', 1),
(199, '2025-11-02 18:21:41', 'nor', 5, 1, 1, '1', 1),
(200, '2025-11-02 18:35:04', 'nor', 4, 2, 1, '1', 1),
(201, '2025-11-03 11:26:49', 'nor', 24, 2, 1, '1', 1),
(202, '2025-11-03 11:45:59', 'nor', 8, 2, 1, '1', 1),
(203, '2025-11-03 12:28:17', 'nor', 11, 3, 1, '1', 1),
(204, '2025-11-03 12:47:03', 'nor', 5, 1, 1, '1', 1),
(205, '2025-11-03 14:49:04', 'nor', 14, 2, 1, '1', 1),
(206, '2025-11-03 15:01:14', 'nor', 73, 7, 1, '1', 1),
(207, '2025-11-03 15:10:35', 'nor', 20, 4, 1, '1', 1),
(208, '2025-11-07 21:10:40', 'nor', 14, 2, 1, '1', 1),
(209, '2025-11-07 23:19:26', 'nor', 2, 1, 1, '1', 1),
(210, '2025-11-12 23:40:04', 'nor', 11, 2, 1, '1', 1),
(211, '2025-11-13 23:03:49', 'nor', 30, 1, 1, '1', 1),
(212, '2025-11-15 23:24:40', 'nor', 16, 1, 1, '1', 1),
(213, '2025-11-15 23:25:18', 'nor', 10, 1, 1, '1', 1),
(214, '2025-11-15 23:32:03', 'nor', 4, 1, 1, '1', 1),
(215, '2025-11-15 23:32:30', 'nor', 4, 1, 1, '1', 1),
(216, '2025-11-15 23:33:04', 'nor', 5, 1, 1, '1', 1),
(217, '2025-11-15 23:34:18', 'nor', 44, 2, 1, '1', 1),
(218, '2025-11-16 09:59:15', 'nor', 8, 1, 1, '1', 1),
(220, '2025-11-16 10:46:11', 'nor', 20, 5, 1, '1', 1),
(221, '2025-11-16 12:09:29', 'nor', 9, 1, 1, '1', 1),
(222, '2025-11-16 12:18:37', 'nor', 16, 4, 1, '1', 1),
(223, '2025-11-16 12:39:10', 'nor', 10, 1, 1, '1', 1),
(224, '2025-11-16 12:58:02', 'nor', 4, 1, 1, '1', 1),
(226, '2025-11-16 13:27:52', 'nor', 8, 2, 1, '1', 1),
(227, '2025-11-16 13:34:03', 'nor', 9, 1, 1, '1', 1),
(228, '2025-11-16 14:03:47', 'nor', 8, 1, 1, '1', 1),
(229, '2025-11-16 14:10:40', 'nor', 12, 2, 1, '1', 1),
(230, '2025-11-16 14:23:32', 'nor', 10, 1, 1, '1', 1),
(232, '2025-11-16 15:00:03', 'nor', 10, 2, 1, '1', 1),
(233, '2025-11-16 15:05:19', 'nor', 3, 1, 1, '1', 1),
(234, '2025-11-16 15:18:06', 'nor', 4, 1, 1, '1', 1),
(235, '2025-11-16 15:41:30', 'nor', 15, 1, 1, '1', 1),
(241, '2025-12-13 13:59:56', 'nor', 17, 2, 1, '1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `prodotti`
--

CREATE TABLE `prodotti` (
  `id_prodotto` int(10) UNSIGNED NOT NULL,
  `descrizione_prod` varchar(100) DEFAULT NULL,
  `prezzo` float DEFAULT NULL,
  `iva` float NOT NULL,
  `testo_biglietto` varchar(100) DEFAULT NULL,
  `olpp` char(1) NOT NULL DEFAULT 'F',
  `barcode` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prodotti`
--

INSERT INTO `prodotti` (`id_prodotto`, `descrizione_prod`, `prezzo`, `iva`, `testo_biglietto`, `olpp`, `barcode`) VALUES
(2339, 'Money box lucky cat big White ', 29, 0.22, 'Money box lucky cat big White ', 'T', '8715226952409'),
(2340, 'Money box lucky cat big Gold ', 29, 0.22, 'Money box lucky cat big Gold ', 'T', '8715226952430'),
(2319, 'Bacchette Bunny con aiuto ', 3, 0.22, 'Bacchette Bunny con aiuto ', 'T', '8715226940758'),
(2304, 'Bacchette Cat con aiuto ', 2, 0.22, 'Bacchette Cat con aiuto ', 'T', '8715226940734'),
(2310, 'Helper bacchette assortiti', 2, 0.22, 'Helper bacchette assortiti', 'T', '8715226940574'),
(2338, 'Bacchette Bear con aiuto', 3, 0.22, 'Bacchette Bear con aiuto', 'T', '8715226940727'),
(78, 'Cucchiaio per Thè Matcha - Chashaku', 2, 0.22, 'Cucchiaio per Thè Matcha - Chashaku', 'T', '8715226892712'),
(1088, 'Tojiro Kombi-Schleifstein Kornung #220 #1000', 70, 0.22, 'Tojiro Kombi-Schleifstein Kornung #220 #1000', 'T', '8715226882010'),
(2315, 'Matcha Set H7 UZU', 30, 0.22, 'Matcha Set H7 UZU', 'T', '8715226493018'),
(2314, 'Matcha Set H7 HIKARI', 30, 0.22, 'Matcha Set H7 HIKARI', 'T', '8715226490130'),
(2334, 'Piatto Sushi Giapponese Artigianale Gold Green', 10, 0.22, 'Piatto Sushi Giapponese Artigianale Gold Green', 'T', '8715226441514'),
(2336, 'Ciotola Hikari 17cm ', 15, 0.22, 'Ciotola Hikari 17cm ', 'T', '8715226430006'),
(2271, 'Ciotola giapponese Shima17cm', 15, 0.22, 'Ciotola giapponese Shima17cm', 'T', '8715226430129'),
(2307, 'Ciotola Japan Mix', 8, 0.22, 'Ciotola Japan Mix', 'T', '8715226411890'),
(2313, 'Matcha Set H7 KI', 30, 0.22, 'Matcha Set H7 KI', 'T', '8715226429239'),
(1670, 'Piatto wave in ceramica Giapponese 22cm', 12, 0.22, 'Piatto wave in ceramica Giapponese 22cm', 'T', '8715226410077'),
(2337, 'Piatto Hana light green 26x3cm', 15, 0.22, 'Piatto Hana light green 26x3cm', 'T', '8715226401457'),
(2270, 'Ciotola giapponese Hana blue 17cm', 15, 0.22, 'Ciotola giapponese Hana blue 17cm', 'T', '8715226408500'),
(2309, 'SET PER SAKE BLUE/BROWN 500 ML', 30, 0.22, 'SET PER SAKE BLUE/BROWN 500 ML', 'T', '8715226401198'),
(2308, 'SET PER SAKE NERO', 32, 0.22, 'SET PER SAKE NERO', 'T', '8715226401181'),
(2268, 'Ciotola giapponese Ki 22cm', 18, 0.22, 'Ciotola giapponese Ki 22cm', 'T', '8715226401051'),
(2274, 'Ciotolina portasalsa Tokusa', 5, 0.22, 'Ciotolina portasalsa Tokusa', 'T', '8715226381278'),
(2312, 'Matcha whisk holder verde', 4, 0.22, 'Matcha whisk holder verde', 'T', '8715226350052'),
(2280, 'Piatto salsa grigio 9x6,5cm', 2, 0.22, 'Piatto salsa grigio 9x6,5cm', 'T', '8715226350472'),
(1881, 'Appoggio per bacchette Ogawa', 2, 0.22, 'Appoggio per bacchette Ogawa', 'T', '8715226303638'),
(2335, 'Piatto sushi Yaketa ', 12, 0.22, 'Piatto sushi Yaketa ', 'T', '8715226308213'),
(1013, 'Bacchette singole Nere Lucide', 2, 0.22, 'Bacchette singole Nere Lucide', 'T', '8715226064164'),
(2333, 'Chopstick set Flower pattern 22.5cm', 5, 0.22, 'Chopstick set Flower pattern 22.5cm', 'T', '8715226064065'),
(2341, 'Cucchiaio in bamboo Ramen', 4, 0.22, 'Cucchiaio in bamboo Ramen', 'T', '8715226062948'),
(2342, 'Chopstick set Japanese motif', 12, 0.22, 'Chopstick set Japanese motif', 'T', '8715226062313'),
(2343, 'Set bacchette blu', 4, 0.22, 'Set bacchette blu', 'T', '8715226062023'),
(2261, 'Chopstick set Seigaiha', 12, 0.22, 'Chopstick set Seigaiha', 'T', '8715226062207'),
(141, 'Ventaglio fiorato', 4, 0.22, 'Ventaglio fiorato', 'T', '8715226035935'),
(651, 'Ciotola per Ramen Spirale Blu', 15, 0.22, 'Ciotola per Ramen Spirale Blu', 'T', '8715222000517'),
(1308, 'Ciotola Flower 17cm', 15, 0.22, 'Ciotola Flower 17cm', 'T', '8715221825395'),
(2303, 'Bicchiere artigianale giapponese', 5, 0.22, 'Bicchiere artigianale giapponese', 'T', '8715221823308'),
(1322, 'Bonito Katsuobushi-Scaglie di tonno affumicato Wadakyu 40gr', 9, 0.1, 'Bonito Katsuobushi-Scaglie di tonno affumicato Wadakyu 40gr', 'T', '8436566460033'),
(2289, 'Spatola per Tamagoyaki', 4, 0.22, 'Spatola per Tamagoyaki', 'T', '8411922454383'),
(2284, 'SUPPORTA PARA TEIERA PIANO JAVA', 4, 0.22, 'SUPPORTA PARA TEIERA PIANO JAVA', 'T', '8411922440539'),
(2285, 'TETERA HIERRO FUNDIDO MALASIA 0.8LT', 23, 0.22, 'TETERA HIERRO FUNDIDO MALASIA 0.8LT', 'T', '8411922088915'),
(2287, 'SET 2 2 TAZZINE CON PIATTINI CEYLAN', 17, 0.22, 'SET 2 2 TAZZINE CON PIATTINI CEYLAN', 'T', '8411922439427'),
(2282, 'TETERA HIERRO FUNDIDO CEYLAN 0.7 LT', 23, 0.22, 'TETERA HIERRO FUNDIDO CEYLAN 0.7 LT', 'T', '8411922084986'),
(2281, 'Pentola per Tamagoyaki antiaderente', 18, 0.22, 'Pentola per Tamagoyaki antiaderente', 'T', '8411922082951'),
(292, 'Ciotolina rotonda porta salsa Bianca', 3, 0.22, 'Ciotolina rotonda porta salsa Bianca', 'T', '8020931081321'),
(1277, 'Ciotolina rettangolare Nera porta salsa', 3, 0.22, 'Ciotolina rettangolare Nera porta salsa', 'T', '8020931080614'),
(1677, 'Mochi Assortiti Biyori', 7, 0.1, 'Mochi Assortiti Biyori', 'T', '8020931040939'),
(2068, 'Set 5 bacchette blue flrowers con appoggi e confezione regalo', 8, 0.22, 'Set 5 bacchette blue flrowers con appoggi e confezione regalo', 'T', '8020931042476'),
(1749, 'Alghe wakame essicate Biyori 40gr', 4, 0.1, 'Alghe wakame essicate Biyori 40gr', 'T', '8020931040656'),
(1396, 'Sesamo nero Biyori', 5, 0.1, 'Sesamo nero Biyori', 'T', '8020931040236'),
(1397, 'Sesamo tostato al wasabi Biyori', 5, 0.1, 'Sesamo tostato al wasabi Biyori', 'T', '8020931040243'),
(1398, 'Sesamo bianco tostato Biyori', 5, 0.1, 'Sesamo bianco tostato Biyori', 'T', '8020931040229'),
(1004, 'Infusore per Tè - Brocca Grigia', 5, 0.22, 'Infusore per Tè - Brocca Grigia', 'T', '8020931038394'),
(999, 'Infusore per Tè - Brocca Bordeaux', 5, 0.22, 'Infusore per Tè - Brocca Bordeaux', 'T', '80209310384240'),
(524, 'Alghe Kombu 50 gr', 4, 0.1, 'Alghe Kombu 50 gr', 'T', '8020931037397'),
(1208, 'Bacchette grandi per cucina 33 cm', 3, 0.22, 'Bacchette grandi per cucina 33 cm', 'T', '8020931034624'),
(273, '5 paia di bacchette leaf style', 5, 0.22, '5 paia di bacchette leaf style', 'T', '8020931033665'),
(331, 'Frullino in Bambù per Tè Matcha - Chasen', 10, 0.22, 'Frullino in Bambù per Tè Matcha - Chasen', 'T', '8020931033122'),
(278, '5 paia di bacchette geisha style', 5, 0.22, '5 paia di bacchette geisha style', 'T', '8020931033023'),
(268, 'Bacchette singole Jap-design', 1, 0.22, 'Bacchette singole Jap-design', 'T', '8020931032941'),
(271, 'Bacchette singole Jap-design blu', 1, 0.22, 'Bacchette singole Jap-design blu', 'T', '8020931032927'),
(276, 'Appoggio per bacchette in bambu', 1, 0.22, 'Appoggio per bacchette in bambu', 'T', '8020931032842'),
(270, 'Bacchette singole Jap-design Geisha', 1, 0.22, 'Bacchette singole Jap-design Geisha', 'T', '8020931032903'),
(337, 'Appoggio per bacchette bianco', 1, 0.22, 'Appoggio per bacchette bianco', 'T', '8020931031876'),
(2155, 'Appoggio per bacchette in legno', 1, 0.22, 'Appoggio per bacchette in legno', 'T', '8020931032828'),
(336, 'Appoggio per bacchette nero', 1, 0.22, 'Appoggio per bacchette nero', 'T', '8020931031845'),
(323, 'Stuoietta bambù - makisu \"S\"', 3, 0.22, 'Stuoietta bambù - makisu \"S\"', 'T', '8020931030671'),
(1694, 'Set di 5 paia di bacchette in acciaio inox', 5, 0.22, 'Set di 5 paia di bacchette in acciaio inox', 'T', '6932577038633'),
(225, 'Alghe nori Biyori 10 fogli ', 3, 0.1, 'Alghe nori Biyori 10 fogli ', 'T', '6910191801857'),
(2531, 'Hatsumago Yukikoibana Sparkling 500ml', 14, 0.22, '', 'T', 'S193\'0500'),
(1118, 'Saiky Miso 500g', 12, 0.1, 'Saiky Miso 500g', 'T', '4970333000045'),
(2530, 'Amabuki I LOVE SUSHI 720ML', 25, 0.22, '', 'T', 'S281\'0720'),
(565, 'Mizkan Ajipon Ponzu', 6, 0.1, 'Mizkan Ajipon Ponzu', 'T', '49685183'),
(1481, 'Aceto di Riso artigianle Tobaya Suten 200ml', 10, 0.1, 'Aceto di Riso artigianle Tobaya Suten 200ml', 'T', '4964607026817'),
(645, 'Pietra per Affilare i Coltelli King K80', 38, 0.22, 'Pietra per Affilare i Coltelli King K80', 'T', '4963188104105'),
(2317, 'Tojiro Kombi-Schleifstein Kornung #220 #1000', 70, 0.22, 'Tojiro Kombi-Schleifstein Kornung #220 #1000', 'T', '4960375014322'),
(1612, 'Ciotolina Rettangolare porta salsa Japan Flower', 6, 0.22, 'Ciotolina Rettangolare porta salsa Japan Flower', 'T', '4956941303006'),
(1503, 'Pietra per affilatura grana 1000 con guida Naniwa', 32, 0.22, 'Pietra per affilatura grana 1000 con guida Naniwa', 'T', '4955571750464'),
(2225, 'Salsa Yakiniku Aritaya 70ml', 9, 0.1, 'Salsa Yakiniku Aritaya 70ml', 'T', '4937988032005'),
(1504, 'Pietra di molatura e rettifica grana 150 Naniwa', 15, 0.22, 'Pietra di molatura e rettifica grana 150 Naniwa', 'T', '4955571285034'),
(2224, 'Salsa di soia Dashi Shoyu Aritaya 70ml', 9, 0.1, 'Salsa di soia Dashi Shoyu Aritaya 70ml', 'T', '4937988013004'),
(510, 'Wasabi in Polvere S&B', 3, 0.1, 'Wasabi in Polvere S&B', 'T', '49181173'),
(2222, 'Salsa di soia Saishikomi Aritaya 70ml', 10, 0.1, 'Salsa di soia Saishikomi Aritaya 70ml', 'T', '4937988012007'),
(2226, 'Salsa di soia Tamari giapponese 100ml', 15, 0.1, 'Salsa di soia Tamari giapponese 100ml', 'T', '4904128300652'),
(982, 'Dorayaki-Dolcetti Giapponesi Ripieni', 9, 0.1, 'Dorayaki-Dolcetti Giapponesi Ripieni', 'T', '4902752166002'),
(1818, 'Kit Miso Ramen Itsuki 188g', 4, 0.1, 'Kit Miso Ramen Itsuki 188g', 'T', '4901726014974'),
(2532, 'Konishi Hiyashibori Gold 720ml', 35, 0.22, '', 'T', 'S093\'0720'),
(2027, 'Kit Kiushu Tonkotsu Ramen Itsuki 182g', 4, 0.1, 'Kit Kiushu Tonkotsu Ramen Itsuki 182g', 'T', '4901726014967'),
(1206, 'Patatine al gusto Teriyaki - Koikeya', 3, 0.1, 'Patatine al gusto Teriyaki - Koikeya', 'T', '4901335006087'),
(2345, 'Shiro Miso Soup 3 serv 10g', 4, 0.1, 'Shiro Miso Soup 3 serv 10g', 'T', '4901515359026'),
(1202, 'Patatine al Wasabi e Nori - Koikeya', 3, 0.1, 'Patatine al Wasabi e Nori - Koikeya', 'T', '4901335006070'),
(512, 'Wasabi sauce S&B', 5, 0.1, 'Wasabi sauce S&B', 'T', '4901002106553'),
(497, 'S&B  Togarashi Nanami-Mix 7 spezie Giapponesi ', 5, 0.1, 'S&B  Togarashi Nanami-Mix 7 spezie Giapponesi ', 'T', '4901002168858'),
(2055, 'Salsa per Curry Giapponese Golden- S', 7, 0.1, 'Salsa per Curry Giapponese Golden- S', 'T', '4901002075415'),
(511, 'Wasabi in tubetto', 4, 0.1, 'Wasabi in tubetto', 'T', '4901002075484'),
(598, 'Tè Matcha- Tè Verde in Polvere 100g', 15, 0.1, 'Tè Matcha- Tè Verde in Polvere 100g', 'T', '4560117650551'),
(2535, 'Masumi Kaya 720ml', 30, 0.22, '', 'T', 'S217\'0720'),
(1482, 'Succo di yuzu Ito Noen 100ml', 10, 0.1, 'Succo di yuzu Ito Noen 100ml', 'T', '4515244222287'),
(31, 'Grattuggia per Zenzero', 8, 0.22, 'Grattuggia per Zenzero', 'T', '4513454100821'),
(2529, 'Masumi Karakuchi Gold 180ml', 5, 0.22, '', 'T', 'S166\'0180'),
(1502, 'Pietra per affilatura combinata 1000-3000 Kochling', 38, 0.22, 'Pietra per affilatura combinata 1000-3000 Kochling', 'T', '4260270070349'),
(1351, 'Ciotolina porta salsa in Bambù', 3, 0.22, 'Ciotolina porta salsa in Bambù', 'T', '4260266397030'),
(2230, 'Mandolina in quercia bianca e resina AS per katsuobushi', 48, 0.22, 'Mandolina in quercia bianca e resina AS per katsuobushi', 'T', '3701184011338'),
(1550, 'Miso bianco (shiro) artigianale con yuzu Kantoya 100gr', 8, 0.1, 'Miso bianco (shiro) artigianale con yuzu Kantoya 100gr', 'T', '3701184001803'),
(1477, 'Miso aka dashi artigianale Kantoya 100gr', 5, 0.1, 'Miso aka dashi artigianale Kantoya 100gr', 'T', '3701184001773'),
(1476, 'Miso rosso artigianale Kantoya 100gr', 4, 0.1, 'Miso rosso artigianale Kantoya 100gr', 'T', '3701184001766'),
(1475, 'Miso bianco (shiro) artigianale Kantoya 100gr', 4, 0.1, 'Miso bianco (shiro) artigianale Kantoya 100gr', 'T', '3701184001735'),
(1676, 'Alghe kombu tenere ESAN 70gr', 10, 0.1, 'Alghe kombu tenere ESAN 70gr', 'T', '3701184001421'),
(1164, 'Tofu Mori-nu compatto 307g', 4, 0.1, 'Tofu Mori-nu compatto 307g', 'T', '1240000017724'),
(796, 'Sukina Udon 600g', 4, 0.1, 'Sukina Udon 600g', 'T', '087703146433'),
(443, 'Tofu Mori-nu compatto 307g', 4, 0.1, 'Tofu Mori-nu compatto 307g', 'T', '1240000017717'),
(500, 'Preparato per Curry Giapponese Piccante- S', 5, 0.1, 'Preparato per Curry Giapponese Piccante- S', 'T', '074880057386'),
(1053, 'Salsa per Curry Giapponese - S', 5, 0.1, 'Salsa per Curry Giapponese - S', 'T', '074880057379'),
(1159, 'Preparato per Curry con Verdure Golden Piccante Medio- S', 5, 0.1, 'Preparato per Curry con Verdure Golden Piccante Medio- S', 'T', '074880040616'),
(1583, 'Preparato per Curry con Verdure Golden- S', 4, 0.1, 'Preparato per Curry con Verdure Golden- S', 'T', '074880040609'),
(2029, 'Golden Curry Hot 92g', 5, 0.1, 'Golden Curry Hot 92g', 'T', '074880030068'),
(1472, 'Pasta di yuzu e peperoncino - Yuzukoshō S&B 43g', 4, 0.1, 'Pasta di yuzu e peperoncino - Yuzukoshō S&B 43g', 'T', '074880020083'),
(734, 'Te Verde in Lattina Pokka 300ml', 3, 0.1, 'Te Verde in Lattina Pokka 300ml', 'T', '074410741860'),
(2196, 'Aceto di riso 500ml Mizkan', 6, 0.1, 'Aceto di riso 500ml Mizkan', 'T', '073575273278'),
(2318, 'Ciotola Hana light green 17cm', 15, 0.22, 'Ciotola Hana light green 17cm', 'T', ' 	8715226410299'),
(2306, 'Lucky Fish Black', 5, 0.22, 'Lucky Fish Black', 'T', '8715226952447'),
(2305, 'LUCKY FISH GOLD', 5, 0.22, 'LUCKY FISH GOLD', 'T', '8715226952454'),
(2320, 'Money box lucky cat white', 8, 0.22, 'Money box lucky cat white', 'T', '8715226952461'),
(2311, 'Lucky cat gold', 8, 0.22, 'Lucky cat gold', 'T', '8715226952478'),
(937, 'Oshibaku-Stampo per Sushi a base larga', 22, 0.22, 'Oshibaku-Stampo per Sushi a base larga', 'T', '8717591394006'),
(41, 'Pinza levaspine angolata', 5, 0.22, 'Pinza levaspine angolata', 'T', '8717591398455'),
(2344, 'Cracker di riso giapponesi 150gr', 4, 0.1, 'Cracker di riso giapponesi 150gr', 'T', '8717677861330'),
(1366, 'Cracker di riso al wasabi 150gr', 4, 0.1, 'Cracker di riso al wasabi 150gr', 'T', '8717677862894'),
(2332, 'Wasabi Peanuts 40g', 4, 0.1, 'Wasabi Peanuts 40g', 'T', '8717703615418'),
(63, 'Set Sake Giapponese per 4 persone-Green Cosmos Design', 30, 0.22, 'Set Sake Giapponese per 4 persone-Green Cosmos Design', 'T', '8717825282819'),
(1899, 'Piatto Laccato Japan ', 20, 0.22, 'Piatto Laccato Japan ', 'T', '8717825284134'),
(151, 'Ciotola Fish Tokyo Design', 13, 0.22, 'Ciotola Fish Tokyo Design', 'T', '8718144088731'),
(2323, 'Dispenser per salsa di soia black and gold 180ml', 10, 0.22, 'Dispenser per salsa di soia black and gold 180ml', 'T', '8718754061957'),
(68, 'Cucchiaio in ceramica Tajimi', 3, 0.22, 'Cucchiaio in ceramica Tajimi', 'T', '8718969934992'),
(2327, 'Tea Pots Cups ', 8, 0.22, 'Tea Pots Cups ', 'T', '8718969939492'),
(2328, 'Tea Pots Cups ', 8, 0.22, 'Tea Pots Cups ', 'T', '8718969939508'),
(2326, 'Set nippon ble 2 ciotole e bacchette tokyo design', 25, 0.22, 'Set nippon ble 2 ciotole e bacchette tokyo design', 'T', '8719323536326'),
(2041, 'Alghe Nori per Onigiri in film plastico 10pz', 3, 0.1, 'Alghe Nori per Onigiri in film plastico 10pz', 'T', '8809441990459'),
(2533, 'Kuromatsu Kenbishi 900ml', 23, 0.22, '', 'T', 'S135\'0900'),
(2534, 'Yonetsuru Pink Kappa 720ml', 25, 0.22, '', 'T', 'S181\'0720'),
(2078, 'Shiso Umeboshi Senza Additivi 140g - Marui ', 13, 0.1, 'Shiso Umeboshi Senza Additivi 140g - Marui ', 'T', 'FVC006'),
(2249, 'Sapone  al carbone - Takesumi', 7, 0.22, 'Sapone  al carbone - Takesumi', 'T', 'NBC005'),
(2247, 'Bomba da bagno - Sakura', 7, 0.22, 'Bomba da bagno - Sakura', 'T', 'NBC014'),
(2245, 'Balsamo per labbra - Sakura', 6, 0.22, 'Balsamo per labbra - Sakura', 'T', 'NBC020'),
(1715, 'Guida per affilatura coltelli', 10, 0.22, 'Guida per affilatura coltelli', 'T', 'NKN021'),
(2521, 'Amabuki I LOVE SUSHI 300ml', 12, 0.22, '', 'T', 'S281\'0300'),
(2522, 'Aratama Tsuyahime 300ml', 12, 0.22, '', 'T', 'S002\'0300'),
(2523, 'Dewanoyuki Nigori 300ml', 12, 0.22, '', 'T', 'S232\'0300'),
(2524, 'Dewanoyuki Onikoroshi 300ml', 12, 0.22, '', 'T', 'S270\'0300'),
(2525, 'Kinran Junmai 300ml', 12, 0.22, '', 'T', 'S052\'0300'),
(2526, 'Tohokuizumi Namazume 300ml', 12, 0.22, '', 'T', 'S003\'0300'),
(2527, 'Azumarikishi Nama Can 180ml', 5, 0.22, '', 'T', 'S183\'0180'),
(2528, 'Kuromatsu Kenbishi 180ml', 5, 0.22, '', 'T', 'S135\'0180'),
(2504, 'Set sake giapponese', 30, 0.22, 'Set sake giapponese', 'F', '1111223'),
(2503, 'Helper per bacchette', 2, 0.22, 'Helper per bacchette', 'F', '12567'),
(2502, 'Balsamo labbra', 7, 0.22, 'Balsamo labbra', 'F', '12356'),
(2501, 'Saponetta artigianle', 7, 0.22, 'Saponetta artigianle', 'F', '1234'),
(2500, 'Bomba da bagno', 7, 0.22, 'Bomba da bagno', 'F', '12345'),
(2505, 'Postwave Rice GlutenFree Beer 330ml', 4, 0.22, 'Postwave Rice GlutenFree Beer 330ml', 'F', 'xxxxx123345'),
(14, 'Degustazione 1 sake ', 3, 0.22, 'Degustazione 1 sake ', 'F', ''),
(15, 'Degustazione 2 sake ', 5, 0.22, 'Degustazione 2 sake ', 'F', ''),
(16, 'Degustazione 5 sake ', 10, 0.22, 'Degustazione 5 sake ', 'F', ''),
(17, 'Kombucha Yuzu e Rosamarino 330ml\r\n', 5, 0.1, 'Kombucha Yuzu e Rosamarino 330ml', 'F', '805698365192'),
(18, 'Uno 4%', 1, 0.04, 'Uno 4%', 'F', ''),
(19, 'Due 4%', 2, 0.04, 'Due 4%', 'F', ''),
(20, 'Cinque 4%', 5, 0.04, 'Cinque 4%', 'F', ''),
(21, 'Dieci 4%', 10, 0.04, 'Dieci 4%', 'F', ''),
(22, 'Uno 10%', 1, 0.1, 'Uno 10%', 'F', ''),
(23, 'Due 10%', 2, 0.1, 'Due 10%', 'F', ''),
(24, 'Cinque 10%', 5, 0.1, 'Cinque 10%', 'F', ''),
(25, 'Dieci 10%', 10, 0.1, 'Dieci 10%', 'F', ''),
(26, 'Uno 22%', 1, 0.22, 'Uno 22%', 'F', ''),
(27, 'Due 22%', 2, 0.22, 'Due 22%', 'F', ''),
(28, 'Cinque 22%', 5, 0.22, 'Cinque 22%', 'F', ''),
(29, 'Dieci 22%', 10, 0.22, 'Dieci 22%', 'F', ''),
(2346, 'Tofu Shiro Miso Soup  3 serv 10g', 4, 0.1, 'Tofu Shiro Miso Soup  3 serv 10g', 'F', '4901515359040'),
(2506, 'Dashi granulare Shimaya 8x5g', 4, 0.1, 'Dashi granulare Shimaya 8x5g', 'F', '4901740115237'),
(2507, 'Kombu Dashi granulare Shimaya 16x8g', 8, 0.1, 'Kombu Dashi granulare Shimaya 16x8g', 'F', '4901740151976'),
(2508, 'Cracker di riso giapponesi', 4, 0.1, 'Cracker di riso giapponesi', 'F', '4901035210326'),
(2509, 'Ghishi Soba 250g', 4, 0.1, 'Ghishi Soba 250g\r\n', 'F', '4978065013012'),
(2510, 'Hakubaku organic udon 270g', 4, 0.1, 'Hakubaku organic udon 270g', 'F', '837328000005'),
(2511, 'Te verde Sencha BIO di Kagoshima 100g', 9, 0.1, 'Te verde Sencha BIO di Kagoshima 100g', 'F', '4903148031584'),
(2512, '4903148031584\r\n', NULL, 0, NULL, 'F', ''),
(2513, 'Te verde genmaicha BIO di Kagoshima 180g', 9, 0.1, 'Te verde genmaicha BIO di Kagoshima 180g', 'F', '4903148031591'),
(2514, 'Te Hojicha di Uji 100g ', 9, 0.1, 'Te Hojicha di Uji 100g ', 'F', '4903148005455'),
(2515, 'La-Yu Olio di sesamo piccante 33ml', 5, 0.11, 'La-Yu Olio di sesamo piccante 33ml\r\n\r\n', 'F', '074880020304'),
(2516, 'Genmaicha Te verde con riso integrale tostato 10x2g', 5, 0.1, 'Genmaicha Te verde con riso integrale tostato 10x2g', 'F', '4560117650384'),
(2517, 'Yamama te verde 10x2g', 5, 0.1, 'Yamama te verde 10x2g', 'F', '4560117650360'),
(2518, 'Mortatio tradizione in ceramica 12cm', 20, 1.22, 'Mortatio tradizione in ceramica 12cm', 'F', '4901601291384'),
(2519, 'Set da sake ideogrammi ', 10, 0.22, 'Set da sake ideogrammi ', 'F', '8020931033849'),
(2520, 'Set da sake 4 persone homemade', 10, 0.22, 'Set da sake 4 persone homemade', 'F', '8020931081390'),
(2536, 'Sawanoizumi 720ml', 30, 0.22, '', 'T', 'S252\'0720'),
(2537, 'hakushika koshu 10 years 500ml', 30, 0.22, '', 'T', 'S293\'0500'),
(2538, 'Yukimi 720ml', 30, 0.22, '', 'T', 'S304\'0720'),
(2539, 'Tatenokawa Seiryu 720ml', 40, 0.22, '', 'T', 'S272\'0720'),
(2540, 'Kamikokoro Kissui 720ml', 330, 0.22, '', 'T', 'S292\'0720'),
(2541, 'Amabuki Matcha Yuzu 720ml', 35, 0.22, '', 'T', 'S291\'0720'),
(2542, 'Kodakara Mango 720ml', 35, 0.22, '', 'T', 'S275\'0720'),
(2543, 'Kodakara Sumomo 720ml', 35, 0.22, '', 'T', 'S040\'0720'),
(2544, 'Kodakara Yuzu 720ml', 35, 0.22, '', 'T', 'S039\'0720'),
(2545, 'Chiyokotobuki Umeshu 500ml', 17, 0.22, '', 'T', 'S073\'0500'),
(2546, 'Acou Rum White 500ml', 40, 0.22, '', 'T', 'SR01\'0500'),
(2547, 'Amabuki Mugi 720ml', 40, 0.22, '', 'T', 'SH04\'0720'),
(2548, 'Gin Impact 700ml', 39, 0.22, '', 'T', 'GS06\'0700'),
(2549, 'Tensonkourin Imo 720ml', 45, 0.22, '', 'T', 'SH06\'0720'),
(2550, 'Yamadaichi Murasaki 720ml', 35, 0.22, '', 'T', 'SH02\'0720'),
(2551, 'Postwave Gluten Free Rice Beer 330ml', 5, 0.22, '', 'T', 'B014\'0330'),
(2552, 'Shirayuki Edo Genshu 720ml', 35, 0.22, '', 'T', 'S097\'0720'),
(2553, 'Otokoyama Raijin 720ml', 23, 0.22, '', 'T', 'S104\'0720'),
(2554, 'Sasayaki Malt Whisky 700ml', 40, 0.22, '', 'T', 'WS07\'0700'),
(2555, 'Amabuki Junmai Daiginjo Banana Yeast 720ml', 35, 0.22, '', 'T', 'S294\'0720');

-- --------------------------------------------------------

--
-- Table structure for table `prodotti_categorie`
--

CREATE TABLE `prodotti_categorie` (
  `id_prodotto` int(11) NOT NULL,
  `id_categoria` int(10) UNSIGNED NOT NULL,
  `posizione` tinyint(3) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prodotti_categorie`
--

INSERT INTO `prodotti_categorie` (`id_prodotto`, `id_categoria`, `posizione`) VALUES
(14, 17, 1),
(15, 17, 2),
(16, 17, 3),
(2500, 14, 1),
(2501, 14, 2),
(2502, 14, 3),
(2503, 14, 5),
(2504, 14, 6),
(2505, 3, 1),
(2505, 16, 1),
(2506, 16, 2),
(2507, 16, 3),
(2521, 20, 1),
(2525, 20, 2),
(2533, 19, 5),
(2534, 20, 3),
(2537, 19, 7),
(2541, 19, 4),
(2542, 19, 1),
(2543, 19, 2),
(2544, 19, 3),
(2552, 19, 6);

-- --------------------------------------------------------

--
-- Table structure for table `prodotti_contatori`
--

CREATE TABLE `prodotti_contatori` (
  `id_contatore` smallint(6) NOT NULL,
  `id_prodotto` smallint(6) NOT NULL,
  `quantita` smallint(6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prodotti_contatori`
--

INSERT INTO `prodotti_contatori` (`id_contatore`, `id_prodotto`, `quantita`) VALUES
(11, 77, 1);

-- --------------------------------------------------------

--
-- Table structure for table `righe_ordini`
--

CREATE TABLE `righe_ordini` (
  `id_ordine` int(10) UNSIGNED NOT NULL,
  `id_prodotto` int(10) UNSIGNED NOT NULL,
  `quantita` int(10) UNSIGNED DEFAULT NULL,
  `totale` float DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `righe_ordini`
--

INSERT INTO `righe_ordini` (`id_ordine`, `id_prodotto`, `quantita`, `totale`) VALUES
(1, 2078, 1, 13),
(2, 2307, 1, 8),
(3, 2303, 2, 8),
(4, 2270, 2, 30),
(4, 2342, 1, 12),
(5, 2332, 1, 4),
(7, 1481, 1, 10),
(9, 271, 1, 1),
(9, 2503, 1, 2),
(11, 2303, 2, 8),
(11, 2068, 1, 8),
(12, 2503, 2, 4),
(16, 524, 1, 4),
(19, 443, 1, 4),
(19, 2345, 1, 4),
(21, 2345, 1, 4),
(24, 1202, 1, 3),
(25, 2306, 1, 5),
(26, 2503, 1, 2),
(27, 2336, 2, 30),
(27, 2261, 1, 12),
(28, 25, 1, 10),
(28, 24, 1, 5),
(29, 2332, 1, 4),
(30, 2333, 1, 5),
(31, 18, 1, 1),
(32, 18, 1, 1),
(36, 18, 1, 1),
(37, 2503, 1, 2),
(38, 2261, 1, 12),
(39, 2332, 1, 4),
(40, 2503, 1, 2),
(41, 2338, 1, 3),
(42, 598, 1, 15),
(42, 1612, 1, 6),
(42, 337, 1, 1),
(43, 2068, 1, 8),
(44, 331, 1, 10),
(45, 1749, 1, 4),
(45, 2332, 1, 4),
(45, 24, 1, 5),
(45, 23, 1, 2),
(45, 22, 1, 1),
(46, 796, 2, 8),
(54, 2342, 1, 12),
(53, 1749, 2, 8),
(54, 982, 1, 9),
(54, 1475, 1, 4),
(55, 2268, 2, 36),
(55, 2341, 1, 4),
(56, 1206, 1, 3),
(56, 2346, 1, 4),
(56, 1322, 1, 9),
(58, 2333, 1, 5),
(70, 4, 1, 39),
(60, 15, 1, 5),
(60, 23, 1, 2),
(61, 1677, 1, 7),
(61, 2346, 1, 4),
(61, 796, 1, 4),
(62, 2338, 1, 3),
(63, 2338, 2, 6),
(64, 2503, 1, 2),
(65, 2501, 1, 7),
(66, 651, 1, 15),
(67, 14, 1, 3),
(68, 2338, 1, 3),
(68, 1013, 1, 2),
(68, 336, 1, 1),
(68, 2280, 1, 2),
(68, 2503, 1, 2),
(69, 2306, 1, 5),
(71, 225, 1, 3),
(72, 2284, 1, 5),
(73, 2068, 1, 8),
(74, 2305, 1, 5),
(75, 1677, 1, 7),
(76, 1013, 2, 4),
(77, 734, 1, 3),
(78, 2285, 1, 23),
(78, 2287, 1, 17),
(79, 29, 1, 10),
(80, 1308, 1, 15),
(81, 2503, 1, 2),
(82, 3, 1, 12),
(83, 510, 1, 3),
(84, 2332, 1, 4),
(85, 331, 1, 10),
(85, 796, 1, 4),
(86, 278, 1, 5),
(86, 2335, 1, 12),
(87, 2342, 1, 12),
(88, 2503, 1, 2),
(89, 16, 1, 10),
(90, 651, 2, 30),
(90, 2261, 1, 12),
(91, 24, 1, 5),
(91, 796, 1, 4),
(92, 2344, 2, 8),
(93, 11, 1, 22),
(94, 24, 1, 5),
(96, 2068, 1, 8),
(97, 10, 1, 26),
(98, 2332, 1, 4),
(99, 1013, 1, 2),
(100, 2503, 1, 2),
(100, 2338, 1, 3),
(101, 2342, 1, 12),
(101, 3, 1, 12),
(102, 2335, 1, 12),
(103, 1677, 1, 7),
(104, 2338, 1, 3),
(105, 15, 1, 5),
(106, 5, 1, 30),
(107, 2338, 2, 6),
(109, 2346, 1, 4),
(109, 1202, 1, 3),
(110, 598, 1, 15),
(111, 3, 1, 12),
(112, 2342, 1, 12),
(113, 2506, 1, 4),
(113, 2344, 1, 4),
(116, 17, 1, 5),
(116, 323, 1, 3),
(116, 2342, 1, 12),
(117, 16, 1, 10),
(118, 2068, 1, 8),
(118, 2319, 1, 3),
(121, 1118, 1, 12),
(122, 2338, 3, 9),
(123, 11, 1, 22),
(124, 2320, 1, 8),
(125, 2503, 2, 4),
(125, 268, 2, 2),
(126, 2508, 1, 4),
(128, 25, 1, 10),
(128, 2514, 1, 9),
(128, 24, 1, 5),
(129, 2078, 1, 13),
(129, 3, 1, 12),
(132, 565, 1, 6),
(131, 2306, 1, 5),
(132, 2196, 1, 6),
(132, 2508, 1, 4),
(133, 796, 2, 8),
(134, 270, 3, 3),
(135, 12, 1, 40),
(136, 2509, 1, 4),
(136, 2510, 1, 4),
(137, 336, 6, 6),
(139, 2319, 1, 3),
(139, 2503, 1, 2),
(140, 2503, 1, 2),
(141, 29, 2, 20),
(142, 2342, 1, 12),
(143, 2068, 1, 8),
(144, 2511, 1, 9),
(145, 27, 1, 2),
(146, 10, 1, 26),
(147, 16, 1, 10),
(148, 1749, 1, 4),
(148, 225, 1, 3),
(149, 3, 1, 12),
(150, 12, 1, 40),
(150, 3, 1, 12),
(151, 3, 1, 12),
(151, 11, 1, 22),
(152, 24, 1, 5),
(153, 2285, 1, 23),
(153, 1013, 1, 2),
(154, 1013, 1, 2),
(155, 2503, 1, 2),
(156, 2333, 1, 5),
(157, 29, 1, 10),
(157, 27, 1, 2),
(158, 2338, 1, 3),
(159, 2505, 1, 4),
(160, 24, 1, 5),
(161, 3, 1, 12),
(162, 2261, 1, 12),
(163, 1749, 1, 4),
(165, 24, 1, 5),
(165, 23, 1, 2),
(166, 29, 2, 20),
(166, 27, 2, 4),
(167, 23, 2, 4),
(168, 2284, 1, 4),
(169, 2306, 1, 5),
(169, 2320, 1, 8),
(170, 25, 2, 20),
(171, 331, 1, 10),
(172, 29, 3, 30),
(173, 2342, 1, 12),
(174, 2519, 1, 10),
(175, 598, 3, 45),
(176, 734, 2, 6),
(177, 2305, 1, 5),
(178, 2332, 2, 8),
(179, 1, 2, 16),
(179, 2505, 1, 4),
(179, 2319, 2, 6),
(179, 17, 1, 5),
(181, 29, 1, 10),
(181, 28, 1, 5),
(183, 2338, 2, 6),
(185, 2261, 1, 12),
(185, 2342, 1, 12),
(186, 2335, 1, 12),
(187, 17, 1, 5),
(188, 2068, 1, 8),
(189, 2333, 1, 5),
(190, 3, 1, 12),
(191, 524, 1, 4),
(191, 1397, 1, 5),
(191, 225, 1, 3),
(191, 2505, 1, 4),
(192, 2305, 1, 5),
(192, 2304, 1, 2),
(192, 18, 1, 1),
(193, 2503, 1, 2),
(195, 29, 1, 10),
(195, 28, 1, 5),
(195, 26, 1, 1),
(196, 1749, 1, 4),
(196, 2506, 1, 4),
(196, 510, 1, 3),
(196, 796, 1, 4),
(197, 2333, 2, 10),
(198, 2332, 1, 4),
(198, 29, 1, 10),
(198, 23, 1, 2),
(199, 1694, 1, 5),
(200, 27, 2, 4),
(201, 3, 2, 24),
(202, 2346, 1, 4),
(202, 1818, 1, 4),
(203, 510, 1, 3),
(203, 511, 1, 4),
(203, 2332, 1, 4),
(204, 2305, 1, 5),
(205, 2517, 1, 5),
(205, 2511, 1, 9),
(206, 1818, 1, 4),
(206, 1322, 1, 9),
(206, 1206, 1, 3),
(206, 982, 1, 9),
(206, 2332, 1, 4),
(206, 443, 1, 4),
(206, 12, 1, 40),
(207, 1322, 1, 9),
(207, 1206, 1, 3),
(207, 2332, 1, 4),
(207, 1818, 1, 4),
(208, 2502, 2, 14),
(209, 2503, 1, 2),
(210, 2501, 1, 7),
(210, 2506, 1, 4),
(211, 2504, 1, 30),
(212, 6, 1, 16),
(213, 16, 1, 10),
(214, 2506, 1, 4),
(215, 2506, 1, 4),
(216, 15, 1, 5),
(217, 11, 2, 44),
(218, 2507, 1, 8),
(220, 24, 1, 5),
(220, 78, 1, 2),
(220, 23, 1, 2),
(220, 22, 1, 1),
(220, 331, 1, 10),
(221, 982, 1, 9),
(222, 28, 3, 15),
(222, 26, 1, 1),
(223, 16, 1, 10),
(224, 2509, 1, 4),
(226, 796, 1, 4),
(226, 443, 1, 4),
(227, 1322, 1, 9),
(228, 2307, 1, 8),
(229, 25, 1, 10),
(229, 23, 1, 2),
(230, 25, 1, 10),
(232, 2303, 2, 10),
(233, 734, 1, 3),
(234, 443, 1, 4),
(235, 651, 1, 15),
(241, 2527, 1, 5),
(241, 2526, 1, 12);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indexes for table `contatori`
--
ALTER TABLE `contatori`
  ADD PRIMARY KEY (`id_contatore`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ordini`
--
ALTER TABLE `ordini`
  ADD PRIMARY KEY (`id_ordine`);

--
-- Indexes for table `prodotti`
--
ALTER TABLE `prodotti`
  ADD PRIMARY KEY (`id_prodotto`);

--
-- Indexes for table `prodotti_categorie`
--
ALTER TABLE `prodotti_categorie`
  ADD PRIMARY KEY (`id_prodotto`,`id_categoria`,`posizione`);

--
-- Indexes for table `prodotti_contatori`
--
ALTER TABLE `prodotti_contatori`
  ADD PRIMARY KEY (`id_contatore`,`id_prodotto`);

--
-- Indexes for table `righe_ordini`
--
ALTER TABLE `righe_ordini`
  ADD PRIMARY KEY (`id_ordine`,`id_prodotto`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categoria` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `contatori`
--
ALTER TABLE `contatori`
  MODIFY `id_contatore` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ordini`
--
ALTER TABLE `ordini`
  MODIFY `id_ordine` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT for table `prodotti`
--
ALTER TABLE `prodotti`
  MODIFY `id_prodotto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2556;

--
-- AUTO_INCREMENT for table `prodotti_categorie`
--
ALTER TABLE `prodotti_categorie`
  MODIFY `id_prodotto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2553;

--
-- AUTO_INCREMENT for table `righe_ordini`
--
ALTER TABLE `righe_ordini`
  MODIFY `id_ordine` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
