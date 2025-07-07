-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 07, 2025 at 11:08 AM
-- Server version: 10.6.22-MariaDB-cll-lve-log
-- PHP Version: 8.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `czkugpvg_league`
--

-- --------------------------------------------------------

--
-- Table structure for table `leagues`
--

CREATE TABLE `leagues` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_public` tinyint(1) DEFAULT 0,
  `code` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `leagues`
--

INSERT INTO `leagues` (`id`, `user_id`, `name`, `created_by`, `created_at`, `is_public`, `code`) VALUES
(1, 1, 'Malam Jumat ori', NULL, '2025-07-03 09:26:10', 0, 'f5a2fd519a'),
(9, 3, 'LIGA BELGI', NULL, '2025-07-04 02:21:22', 0, 'a40bf295b1'),
(10, 3, 'LIGA BELGI ORI MENTENG', NULL, '2025-07-04 06:28:15', 1, '5cbc65a259'),
(11, 3, 'WENNY ASEPLOLE', NULL, '2025-07-04 06:59:06', 1, '90c3887441'),
(12, 3, 'ANCURSAT', NULL, '2025-07-04 08:28:23', 1, '7a631f6a41'),
(13, 4, 'Liga Malem Jumat', NULL, '2025-07-04 08:38:09', 1, '3c0e7177fc');

-- --------------------------------------------------------

--
-- Table structure for table `league_fixtures`
--

CREATE TABLE `league_fixtures` (
  `id` int(11) NOT NULL,
  `league_id` int(11) NOT NULL,
  `round` int(11) NOT NULL,
  `home_team` varchar(50) DEFAULT NULL,
  `away_team` varchar(50) DEFAULT NULL,
  `home_score` int(11) DEFAULT NULL,
  `away_score` int(11) DEFAULT NULL,
  `is_played` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `league_fixtures`
--

INSERT INTO `league_fixtures` (`id`, `league_id`, `round`, `home_team`, `away_team`, `home_score`, `away_score`, `is_played`) VALUES
(1, 1, 1, 'Telaso', 'Ngeru', NULL, NULL, 0),
(2, 1, 2, 'Telaso', 'Londs Ori', NULL, NULL, 0),
(3, 1, 3, 'Telaso', 'Aselole', NULL, NULL, 0),
(4, 1, 4, 'Telaso', 'Jos', NULL, NULL, 0),
(5, 1, 5, 'Telaso', 'Jon', NULL, NULL, 0),
(6, 1, 6, 'Telaso', 'Asepanj', NULL, NULL, 0),
(7, 1, 7, 'Telaso', 'asep mememekk', NULL, NULL, 0),
(8, 1, 8, 'Telaso', 'ngentot lu sep', NULL, NULL, 0),
(9, 1, 9, 'Telaso', 'ori gila', NULL, NULL, 0),
(10, 1, 10, 'Ngeru', 'Londs Ori', NULL, NULL, 0),
(11, 1, 11, 'Ngeru', 'Aselole', NULL, NULL, 0),
(12, 1, 12, 'Ngeru', 'Jos', NULL, NULL, 0),
(13, 1, 13, 'Ngeru', 'Jon', NULL, NULL, 0),
(14, 1, 14, 'Ngeru', 'Asepanj', NULL, NULL, 0),
(15, 1, 15, 'Ngeru', 'asep mememekk', NULL, NULL, 0),
(16, 1, 16, 'Ngeru', 'ngentot lu sep', NULL, NULL, 0),
(17, 1, 17, 'Ngeru', 'ori gila', NULL, NULL, 0),
(18, 1, 18, 'Londs Ori', 'Aselole', NULL, NULL, 0),
(19, 1, 19, 'Londs Ori', 'Jos', NULL, NULL, 0),
(20, 1, 20, 'Londs Ori', 'Jon', NULL, NULL, 0),
(21, 1, 21, 'Londs Ori', 'Asepanj', NULL, NULL, 0),
(22, 1, 22, 'Londs Ori', 'asep mememekk', NULL, NULL, 0),
(23, 1, 23, 'Londs Ori', 'ngentot lu sep', NULL, NULL, 0),
(24, 1, 24, 'Londs Ori', 'ori gila', NULL, NULL, 0),
(25, 1, 25, 'Aselole', 'Jos', NULL, NULL, 0),
(26, 1, 26, 'Aselole', 'Jon', NULL, NULL, 0),
(27, 1, 27, 'Aselole', 'Asepanj', NULL, NULL, 0),
(28, 1, 28, 'Aselole', 'asep mememekk', NULL, NULL, 0),
(29, 1, 29, 'Aselole', 'ngentot lu sep', NULL, NULL, 0),
(30, 1, 30, 'Aselole', 'ori gila', NULL, NULL, 0),
(31, 1, 31, 'Jos', 'Jon', NULL, NULL, 0),
(32, 1, 32, 'Jos', 'Asepanj', NULL, NULL, 0),
(33, 1, 33, 'Jos', 'asep mememekk', NULL, NULL, 0),
(34, 1, 34, 'Jos', 'ngentot lu sep', NULL, NULL, 0),
(35, 1, 35, 'Jos', 'ori gila', NULL, NULL, 0),
(36, 1, 36, 'Jon', 'Asepanj', NULL, NULL, 0),
(37, 1, 37, 'Jon', 'asep mememekk', NULL, NULL, 0),
(38, 1, 38, 'Jon', 'ngentot lu sep', NULL, NULL, 0),
(39, 1, 39, 'Jon', 'ori gila', NULL, NULL, 0),
(40, 1, 40, 'Asepanj', 'asep mememekk', NULL, NULL, 0),
(41, 1, 41, 'Asepanj', 'ngentot lu sep', NULL, NULL, 0),
(42, 1, 42, 'Asepanj', 'ori gila', NULL, NULL, 0),
(43, 1, 43, 'asep mememekk', 'ngentot lu sep', NULL, NULL, 0),
(44, 1, 44, 'asep mememekk', 'ori gila', NULL, NULL, 0),
(45, 1, 45, 'ngentot lu sep', 'ori gila', NULL, NULL, 0),
(132, 9, 1, 'Asep', 'Jon', NULL, NULL, 0),
(133, 9, 2, 'Asep', 'FBR Ori', NULL, NULL, 0),
(134, 9, 3, 'Asep', 'Sleeping Beauty', NULL, NULL, 0),
(135, 9, 4, 'Asep', 'Robert Sanchez', NULL, NULL, 0),
(136, 9, 5, 'Asep', 'Darby', NULL, NULL, 0),
(137, 9, 6, 'Asep', 'Wenny cagur', NULL, NULL, 0),
(138, 9, 7, 'Asep', 'Erik.ai', NULL, NULL, 0),
(139, 9, 8, 'Jon', 'FBR Ori', NULL, NULL, 0),
(140, 9, 9, 'Jon', 'Sleeping Beauty', NULL, NULL, 0),
(141, 9, 10, 'Jon', 'Robert Sanchez', NULL, NULL, 0),
(142, 9, 11, 'Jon', 'Darby', NULL, NULL, 0),
(143, 9, 12, 'Jon', 'Wenny cagur', NULL, NULL, 0),
(144, 9, 13, 'Jon', 'Erik.ai', NULL, NULL, 0),
(145, 9, 14, 'FBR Ori', 'Sleeping Beauty', NULL, NULL, 0),
(146, 9, 15, 'FBR Ori', 'Robert Sanchez', NULL, NULL, 0),
(147, 9, 16, 'FBR Ori', 'Darby', NULL, NULL, 0),
(148, 9, 17, 'FBR Ori', 'Wenny cagur', NULL, NULL, 0),
(149, 9, 18, 'FBR Ori', 'Erik.ai', NULL, NULL, 0),
(150, 9, 19, 'Sleeping Beauty', 'Robert Sanchez', NULL, NULL, 0),
(151, 9, 20, 'Sleeping Beauty', 'Darby', NULL, NULL, 0),
(152, 9, 21, 'Sleeping Beauty', 'Wenny cagur', NULL, NULL, 0),
(153, 9, 22, 'Sleeping Beauty', 'Erik.ai', NULL, NULL, 0),
(154, 9, 23, 'Robert Sanchez', 'Darby', NULL, NULL, 0),
(155, 9, 24, 'Robert Sanchez', 'Wenny cagur', NULL, NULL, 0),
(156, 9, 25, 'Robert Sanchez', 'Erik.ai', NULL, NULL, 0),
(157, 9, 26, 'Darby', 'Wenny cagur', NULL, NULL, 0),
(158, 9, 27, 'Darby', 'Erik.ai', NULL, NULL, 0),
(159, 9, 28, 'Wenny cagur', 'Erik.ai', NULL, NULL, 0),
(160, 9, 28, 'Jon', 'Asep', 0, 0, 0),
(161, 9, 29, 'FBR Ori', 'Asep', 0, 0, 0),
(162, 9, 30, 'FBR Ori', 'Jon', 0, 0, 0),
(163, 9, 31, 'Sleeping Beauty', 'Asep', 0, 0, 0),
(164, 9, 32, 'Sleeping Beauty', 'Jon', 0, 0, 0),
(165, 9, 33, 'Sleeping Beauty', 'FBR Ori', 0, 0, 0),
(166, 9, 34, 'Robert Sanchez', 'Asep', 0, 0, 0),
(167, 9, 35, 'Robert Sanchez', 'Jon', 0, 0, 0),
(168, 9, 36, 'Robert Sanchez', 'FBR Ori', 0, 0, 0),
(169, 9, 37, 'Robert Sanchez', 'Sleeping Beauty', 0, 0, 0),
(170, 9, 38, 'Darby', 'Asep', 0, 0, 0),
(171, 9, 39, 'Darby', 'Jon', 0, 0, 0),
(172, 9, 40, 'Darby', 'FBR Ori', 0, 0, 0),
(173, 9, 41, 'Darby', 'Sleeping Beauty', 0, 0, 0),
(174, 9, 42, 'Darby', 'Robert Sanchez', 0, 0, 0),
(175, 9, 43, 'Wenny cagur', 'Asep', 0, 0, 0),
(176, 9, 44, 'Wenny cagur', 'Jon', 0, 0, 0),
(177, 9, 45, 'Wenny cagur', 'FBR Ori', 0, 0, 0),
(178, 9, 46, 'Wenny cagur', 'Sleeping Beauty', 0, 0, 0),
(179, 9, 47, 'Wenny cagur', 'Robert Sanchez', 0, 0, 0),
(180, 9, 48, 'Wenny cagur', 'Darby', 0, 0, 0),
(181, 9, 49, 'Erik.ai', 'Asep', 0, 0, 0),
(182, 9, 50, 'Erik.ai', 'Jon', 0, 0, 0),
(183, 9, 51, 'Erik.ai', 'FBR Ori', 0, 0, 0),
(184, 9, 52, 'Erik.ai', 'Sleeping Beauty', 0, 0, 0),
(185, 9, 53, 'Erik.ai', 'Robert Sanchez', 0, 0, 0),
(186, 9, 54, 'Erik.ai', 'Darby', 0, 0, 0),
(187, 9, 55, 'Erik.ai', 'Wenny cagur', 0, 0, 0),
(218, 10, 1, 'Lip Balm', 'Jontol', NULL, NULL, 0),
(219, 10, 2, 'Lip Balm', 'FBR Ori', NULL, NULL, 0),
(220, 10, 3, 'Lip Balm', 'Robert Sanchez', NULL, NULL, 0),
(221, 10, 4, 'Lip Balm', 'Sleeping Beauty', NULL, NULL, 0),
(222, 10, 5, 'Lip Balm', 'Wenny Cagur', NULL, NULL, 0),
(223, 10, 6, 'Jontol', 'FBR Ori', NULL, NULL, 0),
(224, 10, 7, 'Jontol', 'Robert Sanchez', NULL, NULL, 0),
(225, 10, 8, 'Jontol', 'Sleeping Beauty', NULL, NULL, 0),
(226, 10, 9, 'Jontol', 'Wenny Cagur', NULL, NULL, 0),
(227, 10, 10, 'FBR Ori', 'Robert Sanchez', NULL, NULL, 0),
(228, 10, 11, 'FBR Ori', 'Sleeping Beauty', NULL, NULL, 0),
(229, 10, 12, 'FBR Ori', 'Wenny Cagur', NULL, NULL, 0),
(230, 10, 13, 'Robert Sanchez', 'Sleeping Beauty', NULL, NULL, 0),
(231, 10, 14, 'Robert Sanchez', 'Wenny Cagur', NULL, NULL, 0),
(232, 10, 15, 'Sleeping Beauty', 'Wenny Cagur', NULL, NULL, 0),
(249, 11, 1, 'Wenny Cagur', 'ASEP ORI', NULL, NULL, 0),
(250, 11, 2, 'Wenny Cagur', 'JENGGOT', NULL, NULL, 0),
(251, 11, 3, 'Wenny Cagur', 'PELER', NULL, NULL, 0),
(252, 11, 4, 'Wenny Cagur', 'KONT MASUK', NULL, NULL, 0),
(253, 11, 5, 'ASEP ORI', 'JENGGOT', NULL, NULL, 0),
(254, 11, 6, 'ASEP ORI', 'PELER', NULL, NULL, 0),
(255, 11, 7, 'ASEP ORI', 'KONT MASUK', NULL, NULL, 0),
(256, 11, 8, 'JENGGOT', 'PELER', NULL, NULL, 0),
(257, 11, 9, 'JENGGOT', 'KONT MASUK', NULL, NULL, 0),
(258, 11, 10, 'PELER', 'KONT MASUK', NULL, NULL, 0),
(259, 11, 11, 'ASEP ORI', 'Wenny Cagur', 0, 0, 0),
(260, 11, 12, 'JENGGOT', 'Wenny Cagur', 0, 0, 0),
(261, 11, 13, 'PELER', 'Wenny Cagur', 0, 0, 0),
(262, 11, 14, 'KONT MASUK', 'Wenny Cagur', 0, 0, 0),
(263, 11, 15, 'JENGGOT', 'ASEP ORI', 0, 0, 0),
(264, 11, 16, 'PELER', 'ASEP ORI', 0, 0, 0),
(265, 11, 17, 'KONT MASUK', 'ASEP ORI', 0, 0, 0),
(266, 11, 18, 'PELER', 'JENGGOT', 0, 0, 0),
(267, 11, 19, 'KONT MASUK', 'JENGGOT', 0, 0, 0),
(268, 11, 20, 'KONT MASUK', 'PELER', 0, 0, 0),
(269, 12, 1, 'Londs Ori', 'Ngeru', 1, 5, 1),
(270, 12, 2, 'Ngeru', 'Londs Ori', 1, 5, 1),
(271, 12, 3, 'Jos', 'Ngeru', 1, 1, 1),
(272, 12, 4, 'Ngeru', 'Jos', NULL, NULL, 0),
(273, 12, 5, 'Jos', 'Londs Ori', NULL, NULL, 0),
(274, 12, 6, 'Londs Ori', 'Jos', NULL, NULL, 0),
(275, 12, 7, 'Jon', 'Ngeru', NULL, NULL, 0),
(276, 12, 8, 'Ngeru', 'Jon', NULL, NULL, 0),
(277, 12, 9, 'Jon', 'Londs Ori', NULL, NULL, 0),
(278, 12, 10, 'Londs Ori', 'Jon', NULL, NULL, 0),
(279, 12, 11, 'Jon', 'Jos', NULL, NULL, 0),
(280, 12, 12, 'Jos', 'Jon', NULL, NULL, 0),
(281, 12, 13, 'KONT MASUK', 'Ngeru', NULL, NULL, 0),
(282, 12, 14, 'Ngeru', 'KONT MASUK', NULL, NULL, 0),
(283, 12, 15, 'KONT MASUK', 'Londs Ori', NULL, NULL, 0),
(284, 12, 16, 'Londs Ori', 'KONT MASUK', NULL, NULL, 0),
(285, 12, 17, 'KONT MASUK', 'Jos', NULL, NULL, 0),
(286, 12, 18, 'Jos', 'KONT MASUK', NULL, NULL, 0),
(287, 12, 19, 'KONT MASUK', 'Jon', NULL, NULL, 0),
(288, 12, 20, 'Jon', 'KONT MASUK', NULL, NULL, 0),
(289, 13, 1, 'Febray', 'Apid', 2, 6, 1),
(290, 13, 2, 'Apid', 'Febray', NULL, NULL, 0),
(291, 13, 3, 'Adam', 'Apid', 0, 7, 1),
(292, 13, 4, 'Apid', 'Adam', NULL, NULL, 0),
(293, 13, 5, 'Adam', 'Febray', 2, 2, 1),
(294, 13, 6, 'Febray', 'Adam', NULL, NULL, 0),
(295, 13, 7, 'Ino', 'Apid', 1, 8, 1),
(296, 13, 8, 'Apid', 'Ino', NULL, NULL, 0),
(297, 13, 9, 'Ino', 'Febray', 0, 2, 1),
(298, 13, 10, 'Febray', 'Ino', NULL, NULL, 0),
(299, 13, 11, 'Ino', 'Adam', NULL, NULL, 0),
(300, 13, 12, 'Adam', 'Ino', 2, 0, 1),
(301, 13, 13, 'Fatah', 'Apid', 1, 6, 1),
(302, 13, 14, 'Apid', 'Fatah', NULL, NULL, 0),
(303, 13, 15, 'Fatah', 'Febray', 1, 2, 1),
(304, 13, 16, 'Febray', 'Fatah', NULL, NULL, 0),
(305, 13, 17, 'Fatah', 'Adam', NULL, NULL, 0),
(306, 13, 18, 'Adam', 'Fatah', 0, 2, 1),
(307, 13, 19, 'Fatah', 'Ino', NULL, NULL, 0),
(308, 13, 20, 'Ino', 'Fatah', 2, 0, 1),
(309, 13, 21, 'Ridwans', 'Apid', 2, 9, 1),
(310, 13, 22, 'Apid', 'Ridwans', NULL, NULL, 0),
(311, 13, 23, 'Ridwans', 'Febray', NULL, NULL, 0),
(312, 13, 24, 'Febray', 'Ridwans', 3, 5, 1),
(313, 13, 25, 'Ridwans', 'Adam', NULL, NULL, 0),
(314, 13, 26, 'Adam', 'Ridwans', 3, 2, 1),
(315, 13, 27, 'Ridwans', 'Ino', 4, 3, 1),
(316, 13, 28, 'Ino', 'Ridwans', 4, 3, 1),
(317, 13, 29, 'Ridwans', 'Fatah', 2, 1, 1),
(318, 13, 30, 'Fatah', 'Ridwans', NULL, NULL, 0),
(319, 13, 31, 'Aan', 'Apid', NULL, NULL, 0),
(320, 13, 32, 'Apid', 'Aan', 5, 0, 1),
(321, 13, 33, 'Aan', 'Febray', NULL, NULL, 0),
(322, 13, 34, 'Febray', 'Aan', 3, 2, 1),
(323, 13, 35, 'Aan', 'Adam', 3, 2, 1),
(324, 13, 36, 'Adam', 'Aan', NULL, NULL, 0),
(325, 13, 37, 'Aan', 'Ino', NULL, NULL, 0),
(326, 13, 38, 'Ino', 'Aan', 2, 1, 1),
(327, 13, 39, 'Aan', 'Fatah', 7, 0, 1),
(328, 13, 40, 'Fatah', 'Aan', NULL, NULL, 0),
(329, 13, 41, 'Aan', 'Ridwans', NULL, NULL, 0),
(330, 13, 42, 'Ridwans', 'Aan', NULL, NULL, 0),
(331, 13, 43, 'Dafi', 'Apid', 0, 9, 1),
(332, 13, 44, 'Apid', 'Dafi', NULL, NULL, 0),
(333, 13, 45, 'Dafi', 'Febray', 2, 6, 1),
(334, 13, 46, 'Febray', 'Dafi', NULL, NULL, 0),
(335, 13, 47, 'Dafi', 'Adam', NULL, NULL, 0),
(336, 13, 48, 'Adam', 'Dafi', 2, 2, 1),
(337, 13, 49, 'Dafi', 'Ino', 2, 2, 1),
(338, 13, 50, 'Ino', 'Dafi', NULL, NULL, 0),
(339, 13, 51, 'Dafi', 'Fatah', 1, 2, 1),
(340, 13, 52, 'Fatah', 'Dafi', NULL, NULL, 0),
(341, 13, 53, 'Dafi', 'Ridwans', NULL, NULL, 0),
(342, 13, 54, 'Ridwans', 'Dafi', NULL, NULL, 0),
(343, 13, 55, 'Dafi', 'Aan', 2, 9, 1),
(344, 13, 56, 'Aan', 'Dafi', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `league_match_log`
--

CREATE TABLE `league_match_log` (
  `id` int(11) NOT NULL,
  `league_id` int(11) NOT NULL,
  `match_date` datetime DEFAULT current_timestamp(),
  `round` int(11) DEFAULT NULL,
  `home_team` varchar(50) DEFAULT NULL,
  `away_team` varchar(50) DEFAULT NULL,
  `home_score` int(11) DEFAULT NULL,
  `away_score` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `league_match_log`
--

INSERT INTO `league_match_log` (`id`, `league_id`, `match_date`, `round`, `home_team`, `away_team`, `home_score`, `away_score`) VALUES
(1, 1, '2025-07-03 19:27:38', 0, 'Aselole', 'Jon', 3, 2),
(3, 9, '2025-07-04 11:11:08', 1, 'Asep', 'Jon', 1, 4),
(4, 9, '2025-07-04 11:11:32', 3, 'Asep', 'FBR Ori', 1, 5),
(5, 9, '2025-07-04 11:13:24', 2, 'Jon', 'Asep', 1, 1),
(6, 9, '2025-07-04 11:13:39', 4, 'FBR Ori', 'Asep', 2, 0),
(7, 9, '2025-07-04 11:13:47', 5, 'Asep', 'Sleeping Beauty', 5, 3),
(8, 9, '2025-07-04 11:13:55', 6, 'Sleeping Beauty', 'Asep', 0, 2),
(9, 9, '2025-07-04 11:14:03', 7, 'Asep', 'Robert Sanchez', 4, 3),
(10, 9, '2025-07-04 11:14:12', 8, 'Robert Sanchez', 'Asep', 7, 6),
(11, 9, '2025-07-04 11:14:18', 9, 'Asep', 'Darby', 2, 7),
(12, 9, '2025-07-04 11:14:24', 10, 'Darby', 'Asep', 3, 3),
(13, 9, '2025-07-04 11:14:30', 11, 'Jon', 'FBR Ori', 2, 3),
(14, 9, '2025-07-04 11:14:38', 30, 'Darby', 'Robert Sanchez', 10, 1),
(15, 9, '2025-07-04 11:14:45', 29, 'Robert Sanchez', 'Darby', 2, 3),
(16, 9, '2025-07-04 11:14:51', 28, 'Darby', 'Sleeping Beauty', 4, 4),
(17, 9, '2025-07-04 11:14:59', 27, 'Sleeping Beauty', 'Darby', 2, 3),
(18, 9, '2025-07-04 11:17:14', 12, 'FBR Ori', 'Jon', 2, 3),
(19, 9, '2025-07-04 11:17:19', 13, 'Jon', 'Sleeping Beauty', 9, 2),
(20, 9, '2025-07-04 11:17:23', 14, 'Sleeping Beauty', 'Jon', 3, 4),
(21, 9, '2025-07-04 11:17:29', 15, 'Jon', 'Robert Sanchez', 8, 8),
(22, 9, '2025-07-04 11:17:34', 16, 'Robert Sanchez', 'Jon', 0, 0),
(23, 9, '2025-07-04 11:17:37', 17, 'Jon', 'Darby', 2, 2),
(24, 9, '2025-07-04 11:17:42', 18, 'Darby', 'Jon', 0, 0),
(25, 9, '2025-07-04 11:17:53', 19, 'FBR Ori', 'Sleeping Beauty', 14, 2),
(26, 9, '2025-07-04 11:17:58', 20, 'Sleeping Beauty', 'FBR Ori', 2, 9),
(27, 9, '2025-07-04 11:18:05', 21, 'FBR Ori', 'Robert Sanchez', 20, 2),
(28, 9, '2025-07-04 11:18:13', 22, 'Robert Sanchez', 'FBR Ori', 0, 9),
(29, 9, '2025-07-04 11:18:18', 23, 'FBR Ori', 'Darby', 9, 0),
(30, 9, '2025-07-04 11:18:23', 24, 'Darby', 'FBR Ori', 2, 2),
(31, 9, '2025-07-04 11:18:33', 25, 'Sleeping Beauty', 'Robert Sanchez', 3, 1),
(32, 9, '2025-07-04 11:18:38', 26, 'Robert Sanchez', 'Sleeping Beauty', 0, 10),
(33, 10, '2025-07-04 13:35:04', 1, 'Lip Balm', 'Jontol', 0, 0),
(34, 10, '2025-07-04 13:35:08', 2, 'Lip Balm', 'FBR Ori', 1, 0),
(35, 10, '2025-07-04 13:35:12', 3, 'Lip Balm', 'Robert Sanchez', 0, 1),
(36, 10, '2025-07-04 13:35:17', 4, 'Lip Balm', 'Sleeping Beauty', 10, 1),
(37, 10, '2025-07-04 13:35:21', 5, 'Jontol', 'FBR Ori', 6, 9),
(38, 10, '2025-07-04 13:35:26', 6, 'Jontol', 'Robert Sanchez', 9, 8),
(39, 10, '2025-07-04 13:35:29', 7, 'Jontol', 'Sleeping Beauty', 1, 1),
(40, 10, '2025-07-04 13:35:33', 8, 'FBR Ori', 'Robert Sanchez', 1, 1),
(41, 10, '2025-07-04 13:35:39', 9, 'FBR Ori', 'Sleeping Beauty', 1, 18),
(42, 10, '2025-07-04 13:35:53', 10, 'Robert Sanchez', 'Sleeping Beauty', 1, 1),
(43, 10, '2025-07-04 13:35:53', 10, 'Jontol', 'Lip Balm', 19, 91),
(44, 10, '2025-07-04 13:36:00', 11, 'FBR Ori', 'Lip Balm', 100, 1),
(45, 10, '2025-07-04 13:36:05', 12, 'Robert Sanchez', 'Lip Balm', 1, 1),
(46, 10, '2025-07-04 13:36:11', 19, 'Sleeping Beauty', 'Robert Sanchez', 10, 10),
(47, 10, '2025-07-04 13:36:15', 18, 'Sleeping Beauty', 'FBR Ori', 10, 10),
(48, 10, '2025-07-04 13:36:19', 17, 'Robert Sanchez', 'FBR Ori', 10, 10),
(49, 10, '2025-07-04 13:36:23', 16, 'Sleeping Beauty', 'Jontol', 10, 10),
(50, 10, '2025-07-04 13:36:26', 15, 'Robert Sanchez', 'Jontol', 10, 10),
(51, 10, '2025-07-04 13:36:30', 14, 'FBR Ori', 'Jontol', 5, 5),
(52, 10, '2025-07-04 13:36:34', 13, 'Sleeping Beauty', 'Lip Balm', 1, 1),
(53, 11, '2025-07-04 14:01:49', 1, 'Wenny Cagur', 'ASEP ORI', 1, 1),
(54, 11, '2025-07-04 14:01:52', 2, 'Wenny Cagur', 'JENGGOT', 1, 1),
(55, 11, '2025-07-04 14:01:55', 3, 'Wenny Cagur', 'PELER', 1, 1),
(56, 11, '2025-07-04 14:01:59', 4, 'ASEP ORI', 'JENGGOT', 1, 1),
(57, 11, '2025-07-04 14:02:02', 5, 'ASEP ORI', 'PELER', 1, 1),
(58, 11, '2025-07-04 14:02:06', 6, 'JENGGOT', 'PELER', 1, 1),
(59, 11, '2025-07-04 14:02:09', 7, 'ASEP ORI', 'Wenny Cagur', 1, 1),
(60, 12, '2025-07-04 15:29:01', 1, 'Londs Ori', 'Ngeru', 1, 5),
(61, 12, '2025-07-04 15:29:38', 2, 'Ngeru', 'Londs Ori', 1, 5),
(62, 12, '2025-07-04 15:29:45', 3, 'Jos', 'Ngeru', 1, 1),
(63, 13, '2025-07-04 15:40:03', 1, 'Febray', 'Apid', 2, 6),
(64, 13, '2025-07-04 15:42:18', 48, 'Adam', 'Dafi', 2, 2),
(65, 13, '2025-07-04 15:43:24', 49, 'Dafi', 'Ino', 2, 2),
(66, 13, '2025-07-04 15:44:28', 51, 'Dafi', 'Fatah', 1, 2),
(67, 13, '2025-07-04 15:44:43', 5, 'Adam', 'Febray', 2, 2),
(68, 13, '2025-07-04 15:45:07', 15, 'Fatah', 'Febray', 1, 2),
(69, 13, '2025-07-04 15:45:23', 45, 'Dafi', 'Febray', 2, 6),
(70, 13, '2025-07-04 15:54:19', 9, 'Ino', 'Febray', 0, 2),
(71, 13, '2025-07-04 15:54:56', 26, 'Adam', 'Ridwans', 3, 2),
(72, 13, '2025-07-04 15:55:07', 28, 'Ino', 'Ridwans', 4, 3),
(73, 13, '2025-07-04 15:55:44', 3, 'Adam', 'Apid', 0, 7),
(74, 13, '2025-07-04 15:55:55', 7, 'Ino', 'Apid', 1, 8),
(75, 13, '2025-07-04 15:56:15', 13, 'Fatah', 'Apid', 1, 6),
(76, 13, '2025-07-04 15:56:28', 43, 'Dafi', 'Apid', 0, 9),
(77, 13, '2025-07-04 15:56:52', 38, 'Ino', 'Aan', 2, 1),
(78, 13, '2025-07-04 15:57:11', 55, 'Dafi', 'Aan', 2, 9),
(79, 13, '2025-07-04 15:57:32', 24, 'Febray', 'Ridwans', 3, 5),
(80, 13, '2025-07-04 15:57:43', 34, 'Febray', 'Aan', 3, 2),
(81, 13, '2025-07-04 15:58:56', 21, 'Ridwans', 'Apid', 2, 9),
(82, 13, '2025-07-04 15:59:12', 32, 'Apid', 'Aan', 5, 0),
(83, 13, '2025-07-04 16:03:01', 18, 'Adam', 'Fatah', 0, 2),
(84, 13, '2025-07-04 16:03:28', 12, 'Adam', 'Ino', 2, 0),
(85, 13, '2025-07-04 16:03:42', 20, 'Ino', 'Fatah', 2, 0),
(86, 13, '2025-07-05 12:19:24', 35, 'Aan', 'Adam', 3, 2),
(87, 13, '2025-07-05 12:20:07', 27, 'Ridwans', 'Ino', 4, 3),
(88, 13, '2025-07-06 14:53:10', 29, 'Ridwans', 'Fatah', 2, 1),
(89, 13, '2025-07-06 14:53:26', 39, 'Aan', 'Fatah', 7, 0);

-- --------------------------------------------------------

--
-- Table structure for table `league_standings`
--

CREATE TABLE `league_standings` (
  `id` int(11) NOT NULL,
  `league_id` int(11) NOT NULL,
  `team_name` varchar(50) NOT NULL,
  `matches_played` int(11) DEFAULT 0,
  `wins` int(11) DEFAULT 0,
  `draws` int(11) DEFAULT 0,
  `losses` int(11) DEFAULT 0,
  `goals_for` int(11) DEFAULT 0,
  `goals_against` int(11) DEFAULT 0,
  `goal_difference` int(11) DEFAULT 0,
  `points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `league_standings`
--

INSERT INTO `league_standings` (`id`, `league_id`, `team_name`, `matches_played`, `wins`, `draws`, `losses`, `goals_for`, `goals_against`, `goal_difference`, `points`) VALUES
(1, 1, 'Telaso', 0, 0, 0, 0, 0, 0, 0, 0),
(2, 1, 'Ngeru', 0, 0, 0, 0, 0, 0, 0, 0),
(3, 1, 'Londs Ori', 0, 0, 0, 0, 0, 0, 0, 0),
(4, 1, 'Aselole', 1, 1, 0, 0, 3, 2, 1, 3),
(5, 1, 'Jos', 0, 0, 0, 0, 0, 0, 0, 0),
(6, 1, 'Jon', 1, 0, 0, 1, 2, 3, -1, 0),
(7, 1, 'Asepanj', 0, 0, 0, 0, 0, 0, 0, 0),
(8, 1, 'asep mememekk', 0, 0, 0, 0, 0, 0, 0, 0),
(9, 1, 'ngentot lu sep', 0, 0, 0, 0, 0, 0, 0, 0),
(10, 1, 'ori gila', 0, 0, 0, 0, 0, 0, 0, 0),
(11, 9, 'Asep', 11, 3, 2, 6, 27, 38, -11, 11),
(12, 9, 'Jon', 11, 5, 5, 1, 36, 24, 12, 20),
(13, 9, 'FBR Ori', 10, 8, 1, 1, 75, 14, 61, 25),
(14, 9, 'Sleeping Beauty', 10, 2, 1, 7, 31, 51, -20, 7),
(15, 9, 'Robert Sanchez', 10, 1, 2, 7, 24, 73, -49, 5),
(16, 9, 'Darby', 10, 4, 5, 1, 34, 27, 7, 17),
(17, 9, 'Wenny cagur', 0, 0, 0, 0, 0, 0, 0, 0),
(18, 9, 'Erik.ai', 0, 0, 0, 0, 0, 0, 0, 0),
(19, 10, 'Lip Balm', 8, 3, 3, 2, 105, 123, -18, 12),
(20, 10, 'Jontol', 8, 1, 5, 2, 60, 134, -74, 8),
(21, 10, 'FBR Ori', 8, 2, 4, 2, 136, 52, 84, 10),
(22, 10, 'Robert Sanchez', 8, 1, 6, 1, 42, 42, 0, 9),
(23, 10, 'Sleeping Beauty', 8, 1, 6, 1, 52, 44, 8, 9),
(24, 10, 'Wenny Cagur', 0, 0, 0, 0, 0, 0, 0, 0),
(25, 11, 'Wenny Cagur', 4, 0, 4, 0, 4, 4, 0, 4),
(26, 11, 'ASEP ORI', 4, 0, 4, 0, 4, 4, 0, 4),
(27, 11, 'JENGGOT', 3, 0, 3, 0, 3, 3, 0, 3),
(28, 11, 'PELER', 3, 0, 3, 0, 3, 3, 0, 3),
(29, 11, 'KONT MASUK', 0, 0, 0, 0, 0, 0, 0, 0),
(30, 12, 'Ngeru', 3, 1, 1, 1, 7, 7, 0, 4),
(31, 12, 'Londs Ori', 2, 1, 0, 1, 6, 6, 0, 3),
(32, 12, 'Jos', 1, 0, 1, 0, 1, 1, 0, 1),
(33, 12, 'Jon', 0, 0, 0, 0, 0, 0, 0, 0),
(34, 12, 'KONT MASUK', 0, 0, 0, 0, 0, 0, 0, 0),
(35, 13, 'Apid', 7, 7, 0, 0, 50, 6, 44, 21),
(36, 13, 'Febray', 7, 4, 1, 2, 20, 18, 2, 13),
(37, 13, 'Adam', 7, 2, 2, 3, 11, 18, -7, 8),
(38, 13, 'Ino', 8, 3, 1, 4, 14, 22, -8, 10),
(39, 13, 'Fatah', 7, 2, 0, 5, 7, 20, -13, 6),
(40, 13, 'Ridwans', 6, 3, 0, 3, 18, 23, -5, 9),
(41, 13, 'Aan', 6, 3, 0, 3, 22, 14, 8, 9),
(42, 13, 'Dafi', 6, 0, 2, 4, 9, 30, -21, 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `created_at`) VALUES
(1, 'jon', 'muslimj@asia1health.com', '$2y$10$c2t9SvI713irqxV7a7Cpaedq97oPYWft/uGScCcX.rxsKHL9ckHc.', '2025-07-03 08:54:01'),
(2, 'kur jancuk', 'jembut@gmail.com', '$2y$10$uB2EqWH0tLyaP0/kA7iCseIfa4ETmK0PXjAYPXmL8peCcT2BZ6NiC', '2025-07-03 09:16:24'),
(3, 'honduras', 'hondz@njing.mam', '$2y$10$il9YKEg/JkAxz1SOIYxcEOJH.cOFzv8/zOo0CtxuJD4zdmshMHgti', '2025-07-04 02:10:17'),
(4, 'lensajon', 'lensajon@lensajon.my.id', '$2y$10$oALH0VhhXMXcIqGAz2JNDOJues8dOgAjqG5CyJob4P0sWrpJd/Y9G', '2025-07-04 08:37:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `leagues`
--
ALTER TABLE `leagues`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `league_fixtures`
--
ALTER TABLE `league_fixtures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `league_id` (`league_id`);

--
-- Indexes for table `league_match_log`
--
ALTER TABLE `league_match_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `league_id` (`league_id`);

--
-- Indexes for table `league_standings`
--
ALTER TABLE `league_standings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `league_id` (`league_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leagues`
--
ALTER TABLE `leagues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `league_fixtures`
--
ALTER TABLE `league_fixtures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=345;

--
-- AUTO_INCREMENT for table `league_match_log`
--
ALTER TABLE `league_match_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `league_standings`
--
ALTER TABLE `league_standings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `leagues`
--
ALTER TABLE `leagues`
  ADD CONSTRAINT `leagues_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `league_fixtures`
--
ALTER TABLE `league_fixtures`
  ADD CONSTRAINT `league_fixtures_ibfk_1` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `league_match_log`
--
ALTER TABLE `league_match_log`
  ADD CONSTRAINT `league_match_log_ibfk_1` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `league_standings`
--
ALTER TABLE `league_standings`
  ADD CONSTRAINT `league_standings_ibfk_1` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
