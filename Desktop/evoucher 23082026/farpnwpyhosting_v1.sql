-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 23, 2026 at 01:25 PM
-- Server version: 10.6.24-MariaDB-cll-lve-log
-- PHP Version: 8.2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `farpnwpyhosting_v1`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `entity` varchar(50) NOT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `entity`, `entity_id`, `details`, `ip_address`, `created_at`) VALUES
(1, NULL, 'use', 'free_voucher', 43, 'Voucher used: FREECK20OVKUA', '116.98.253.95', '2026-04-25 04:29:43'),
(2, 4, 'login', 'session', 4, 'Login successful', '116.98.253.95', '2026-04-25 04:31:29'),
(3, 4, 'restore', 'free_voucher', 43, 'Restored voucher', '116.98.253.95', '2026-04-25 04:31:39'),
(4, NULL, 'use', 'free_voucher', 43, 'Voucher used: FREECK20OVKUA', '116.98.253.95', '2026-04-25 04:32:14'),
(5, 4, 'restore', 'free_voucher', 43, 'Restored voucher', '116.98.253.95', '2026-04-25 04:32:58'),
(6, NULL, 'use', 'free_voucher', 43, 'Voucher used: FREECK20OVKUA', '116.98.253.95', '2026-04-25 04:36:03'),
(7, 4, 'restore', 'free_voucher', 43, 'Restored voucher', '116.98.253.95', '2026-04-25 04:37:12'),
(8, NULL, 'use', 'free_voucher', 43, 'Voucher used: FREECK20OVKUA', '116.98.253.95', '2026-04-25 04:38:42'),
(9, 4, 'restore', 'free_voucher', 43, 'Restored voucher', '116.98.253.95', '2026-04-25 04:39:12'),
(10, 4, 'login', 'session', 4, 'Login successful', '116.98.253.95', '2026-04-25 04:40:33'),
(11, 4, 'login', 'session', 4, 'Login successful', '116.98.253.95', '2026-04-25 04:45:46'),
(12, 4, 'login', 'session', 4, 'Login successful', '116.98.253.95', '2026-04-25 04:50:57'),
(13, NULL, 'login_failed', 'session', NULL, 'Failed login for: Admin', '116.98.253.95', '2026-04-25 05:05:51'),
(14, 4, 'login', 'session', 4, 'Login successful', '116.98.253.95', '2026-04-25 05:06:07'),
(15, 4, 'login', 'session', 4, 'Login successful', '116.98.253.95', '2026-04-25 05:11:26'),
(16, 4, 'login', 'session', 4, 'Login successful', '1.54.113.168', '2026-04-25 06:42:35'),
(17, 4, 'login', 'session', 4, 'Login successful', '116.98.253.95', '2026-04-25 17:14:06'),
(18, 4, 'login', 'session', 4, 'Login successful', '116.98.252.210', '2026-04-27 06:33:37'),
(19, 4, 'use', 'free_voucher', 43, 'Voucher used: FREECK20OVKUA', '116.98.252.210', '2026-04-27 07:14:42'),
(20, 4, 'restore', 'free_voucher', 43, 'Restored voucher', '116.98.252.210', '2026-04-27 07:14:50'),
(21, 4, 'login', 'session', 4, 'Login successful', '1.54.113.168', '2026-04-27 08:54:03'),
(22, 4, 'use', 'free_voucher', 43, 'Voucher used: FREECK20OVKUA', '1.54.113.168', '2026-04-27 10:04:43'),
(23, 4, 'restore', 'free_voucher', 43, 'Restored voucher', '1.54.113.168', '2026-04-27 10:05:02'),
(24, 4, 'create', 'campaign', 24, 'Created campaign: Còng café', '1.54.113.168', '2026-04-27 10:10:59'),
(25, 4, 'update', 'campaign', 24, 'Updated campaign', '1.54.113.168', '2026-04-27 10:12:38'),
(26, 4, 'update', 'campaign', 24, 'Updated campaign', '1.54.113.168', '2026-04-27 10:13:05'),
(27, 4, 'login', 'session', 4, 'Login successful', '1.54.113.168', '2026-04-27 10:20:33'),
(28, 4, 'update', 'campaign', 24, 'Updated campaign', '1.54.113.168', '2026-04-27 10:46:13'),
(29, 4, 'update', 'campaign', 24, 'Updated campaign', '1.54.113.168', '2026-04-27 10:57:02'),
(30, 4, 'login', 'session', 4, 'Login successful', '1.54.113.168', '2026-04-27 10:57:53'),
(31, 4, 'create', 'campaign', 25, 'Created campaign: 𝟐𝐂𝐄 𝐓𝐢𝐞̣̂𝐦 𝐓𝐫𝐚̀', '1.54.113.168', '2026-04-27 12:29:36'),
(32, 4, 'create', 'campaign', 26, 'Created campaign: Tiệm nhà Bi', '1.54.113.168', '2026-04-27 12:33:46'),
(33, 4, 'create', 'campaign', 27, 'Created campaign: Sakura Cafe', '1.54.113.168', '2026-04-27 12:39:02'),
(34, 4, 'login', 'session', 4, 'Login successful', '1.54.113.168', '2026-04-27 13:00:35'),
(35, 4, 'login', 'session', 4, 'Login successful', '1.54.113.168', '2026-04-27 14:07:17'),
(36, 4, 'login', 'session', 4, 'Login successful', '116.98.252.210', '2026-04-27 15:02:03'),
(37, 4, 'login', 'session', 4, 'Login successful', '116.98.252.210', '2026-04-27 15:03:18'),
(38, 4, 'logout', 'session', 4, 'User logged out explicitly: admin', '116.98.252.210', '2026-04-27 15:23:07'),
(39, 4, 'login', 'session', 4, 'Login successful', '116.98.252.210', '2026-04-27 15:23:19'),
(40, 4, 'logout', 'session', 4, 'User logged out explicitly: admin', '116.98.252.210', '2026-04-27 15:23:26'),
(41, NULL, 'use', 'voucher', 1114, 'Voucher used: CF21GSBLL', '171.244.211.47', '2026-04-28 00:28:26'),
(42, NULL, 'use', 'voucher', 1099, 'Voucher used: CF21RFIP3', '27.67.73.208', '2026-04-28 00:44:54'),
(43, 4, 'login', 'session', 4, 'Login successful', '116.98.252.210', '2026-04-28 01:37:25'),
(44, NULL, 'use', 'voucher', 1160, 'Voucher used: NB21MWNJB', '171.254.179.202', '2026-04-28 03:29:42'),
(45, NULL, 'use', 'voucher', 1123, 'Voucher used: CE21H9B4N', '171.255.180.128', '2026-04-28 04:03:41'),
(46, NULL, 'use', 'voucher', 1180, 'Voucher used: SK21Z4R14', '171.254.177.163', '2026-04-28 04:13:18'),
(47, NULL, 'use', 'voucher', 1148, 'Voucher used: NB21INBZQ', '171.254.179.33', '2026-04-28 05:05:04'),
(48, NULL, 'use', 'voucher', 1159, 'Voucher used: NB21XIRLJ', '171.254.181.75', '2026-04-28 05:59:06'),
(49, NULL, 'use', 'voucher', 1177, 'Voucher used: SK21MDQGL', '116.98.253.106', '2026-04-28 06:25:39'),
(50, NULL, 'use', 'voucher', 1106, 'Voucher used: CF21CDCNG', '171.255.166.99', '2026-04-28 08:37:36'),
(51, NULL, 'use', 'voucher', 1120, 'Voucher used: CE21CFRNF', '116.98.252.203', '2026-04-28 10:31:39'),
(52, NULL, 'use', 'voucher', 1154, 'Voucher used: NB21R2V7G', '116.98.252.130', '2026-04-28 10:34:00'),
(53, NULL, 'use', 'voucher', 1153, 'Voucher used: NB21GHMZX', '116.98.252.130', '2026-04-28 10:34:15'),
(54, NULL, 'use', 'voucher', 1116, 'Voucher used: CE21CEL5D', '116.98.252.203', '2026-04-28 10:51:18'),
(55, NULL, 'use', 'voucher', 1098, 'Voucher used: CF21YR0GY', '116.98.253.230', '2026-04-28 11:09:26'),
(56, NULL, 'use', 'voucher', 1145, 'Voucher used: NB21ABSJ3', '116.98.252.130', '2026-04-28 11:37:36'),
(57, NULL, 'use', 'voucher', 1137, 'Voucher used: CE21XS1LR', '42.115.218.123', '2026-04-28 11:52:53'),
(58, NULL, 'use', 'voucher', 1166, 'Voucher used: SK21MBUUN', '42.116.122.254', '2026-04-28 13:03:24'),
(59, NULL, 'use', 'voucher', 1132, 'Voucher used: CE21N32VZ', '116.98.252.203', '2026-04-28 13:03:27'),
(60, NULL, 'use', 'voucher', 1174, 'Voucher used: SK21QNBKK', '116.98.253.184', '2026-04-28 13:06:59'),
(61, NULL, 'use', 'voucher', 1095, 'Voucher used: CF21AUIN8', '117.3.123.76', '2026-04-28 13:25:28'),
(62, NULL, 'use', 'voucher', 1115, 'Voucher used: CF21C31DX', '116.98.253.230', '2026-04-28 13:25:54'),
(63, NULL, 'use', 'voucher', 1144, 'Voucher used: NB21QQ0DG', '116.98.252.130', '2026-04-28 13:40:55'),
(64, NULL, 'use', 'voucher', 1092, 'Voucher used: CF21LD8V8', '171.237.228.42', '2026-04-28 14:40:38'),
(65, NULL, 'use', 'voucher', 1176, 'Voucher used: SK214DXO6', '116.98.253.106', '2026-04-29 01:43:20'),
(66, NULL, 'use', 'voucher', 1158, 'Voucher used: NB21HOJU3', '116.98.252.130', '2026-04-29 03:36:05'),
(67, NULL, 'use', 'voucher', 1101, 'Voucher used: CF21RP48A', '113.189.0.201', '2026-04-29 03:38:43'),
(68, NULL, 'use', 'voucher', 1185, 'Voucher used: SK212ZLV5', '171.254.186.103', '2026-04-29 04:11:27'),
(69, 4, 'login', 'session', 4, 'Login successful', '42.112.73.96', '2026-04-29 06:22:57'),
(70, NULL, 'use', 'voucher', 1102, 'Voucher used: CF21U02TB', '113.185.104.167', '2026-04-29 06:26:06'),
(71, NULL, 'use', 'voucher', 1169, 'Voucher used: SK21UGAHI', '42.116.122.189', '2026-04-29 08:21:44'),
(72, NULL, 'use', 'voucher', 1091, 'Voucher used: CF21SV248', '113.189.0.201', '2026-04-29 08:24:38'),
(73, NULL, 'use', 'voucher', 1110, 'Voucher used: CF21R8OF4', '42.116.122.43', '2026-04-29 08:26:32'),
(74, NULL, 'use', 'voucher', 1189, 'Voucher used: SK21LXDYA', '171.255.164.201', '2026-04-29 08:29:04'),
(75, NULL, 'use', 'voucher', 1165, 'Voucher used: NB21S4IUD', '116.98.252.130', '2026-04-29 08:59:26'),
(76, NULL, 'use', 'voucher', 1107, 'Voucher used: CF218V1E4', '171.254.137.192', '2026-04-29 09:15:55'),
(77, NULL, 'use', 'voucher', 1184, 'Voucher used: SK21VSS8N', '171.254.176.196', '2026-04-29 09:25:15'),
(78, NULL, 'use', 'voucher', 1118, 'Voucher used: CE21SKKTP', '171.254.176.196', '2026-04-29 09:33:46'),
(79, NULL, 'use', 'voucher', 1179, 'Voucher used: SK21S7CY0', '116.98.253.184', '2026-04-29 09:35:00'),
(80, NULL, 'use', 'voucher', 1096, 'Voucher used: CF21TZMZB', '42.115.218.194', '2026-04-29 09:38:52'),
(81, NULL, 'use', 'voucher', 1150, 'Voucher used: NB21O6CZ3', '171.254.139.67', '2026-04-29 09:49:34'),
(82, NULL, 'use', 'voucher', 1108, 'Voucher used: CF211XJB9', '113.189.0.201', '2026-04-29 09:53:40'),
(83, NULL, 'use', 'voucher', 1122, 'Voucher used: CE21FOFHC', '116.98.252.203', '2026-04-29 10:41:22'),
(84, NULL, 'use', 'voucher', 1178, 'Voucher used: SK21H8MKG', '125.234.229.73', '2026-04-29 11:02:34'),
(85, NULL, 'use', 'voucher', 1142, 'Voucher used: NB21HHUE7', '42.115.225.61', '2026-04-29 11:10:04'),
(86, NULL, 'use', 'voucher', 1138, 'Voucher used: CE21IVS84', '116.98.252.203', '2026-04-29 12:03:45'),
(87, NULL, 'use', 'voucher', 1188, 'Voucher used: SK21WE863', '27.66.61.23', '2026-04-29 12:05:22'),
(88, NULL, 'use', 'voucher', 1105, 'Voucher used: CF21OX3TI', '42.115.225.249', '2026-04-29 12:18:48'),
(89, NULL, 'use', 'voucher', 1183, 'Voucher used: SK21NG58J', '42.115.225.249', '2026-04-29 12:32:28'),
(90, NULL, 'use', 'voucher', 1167, 'Voucher used: SK218R0H4', '42.115.218.37', '2026-04-29 12:34:08'),
(91, NULL, 'use', 'voucher', 1109, 'Voucher used: CF21YAPUO', '42.116.96.57', '2026-04-29 12:54:18'),
(92, NULL, 'use', 'voucher', 1125, 'Voucher used: CE21AEROX', '117.3.123.107', '2026-04-29 13:00:59'),
(93, NULL, 'use', 'voucher', 1186, 'Voucher used: SK21DI45J', '116.98.253.184', '2026-04-29 13:45:43'),
(94, 4, 'login', 'session', 4, 'Login successful', '116.98.252.210', '2026-04-30 00:32:31'),
(95, NULL, 'use', 'voucher', 1190, 'Voucher used: SK21E00Z3', '116.98.253.184', '2026-04-30 00:34:21'),
(96, NULL, 'use', 'voucher', 1133, 'Voucher used: CE21NK3O0', '171.254.187.208', '2026-04-30 01:09:14'),
(97, NULL, 'use', 'voucher', 1119, 'Voucher used: CE21T2HM9', '116.98.252.203', '2026-04-30 01:20:48'),
(98, NULL, 'use', 'voucher', 1168, 'Voucher used: SK21Q7XUW', '116.98.253.184', '2026-04-30 01:24:18'),
(99, NULL, 'use', 'voucher', 1097, 'Voucher used: CF2117SSK', '117.3.226.86', '2026-04-30 03:09:10'),
(100, NULL, 'use', 'voucher', 1094, 'Voucher used: CF21KHMUL', '116.98.253.181', '2026-04-30 03:21:40'),
(101, NULL, 'use', 'voucher', 1121, 'Voucher used: CE21EM08W', '117.3.123.152', '2026-04-30 03:23:55'),
(102, NULL, 'use', 'voucher', 1161, 'Voucher used: NB21NU2OL', '113.185.109.14', '2026-04-30 03:25:58'),
(103, NULL, 'use', 'voucher', 1130, 'Voucher used: CE21N40LB', '116.98.252.203', '2026-04-30 03:31:11'),
(104, NULL, 'use', 'voucher', 1187, 'Voucher used: SK216LTNO', '117.3.123.152', '2026-04-30 03:33:30'),
(105, NULL, 'use', 'voucher', 1139, 'Voucher used: CE21HBFIR', '116.98.252.203', '2026-04-30 03:39:29'),
(106, NULL, 'use', 'voucher', 1128, 'Voucher used: CE21LWCWX', '116.98.252.203', '2026-04-30 03:43:23'),
(107, NULL, 'use', 'voucher', 1164, 'Voucher used: NB21EB5QH', '14.243.25.16', '2026-04-30 04:20:06'),
(108, NULL, 'use', 'voucher', 1175, 'Voucher used: SK216L2AW', '116.98.253.184', '2026-04-30 04:35:37'),
(109, NULL, 'use', 'voucher', 1111, 'Voucher used: CF21KLQXV', '42.116.96.99', '2026-04-30 05:14:16'),
(110, NULL, 'use', 'voucher', 1112, 'Voucher used: CF21HKCIS', '116.98.253.181', '2026-04-30 06:15:50'),
(111, NULL, 'use', 'voucher', 1113, 'Voucher used: CF2125IV0', '113.189.0.201', '2026-04-30 07:15:53'),
(112, NULL, 'use', 'voucher', 1157, 'Voucher used: NB216XCPQ', '42.116.96.219', '2026-04-30 09:35:14'),
(113, NULL, 'use', 'voucher', 1134, 'Voucher used: CE21RYWOG', '116.98.252.203', '2026-04-30 09:51:25'),
(114, NULL, 'use', 'voucher', 1117, 'Voucher used: CE21YMQJI', '116.98.252.203', '2026-04-30 10:29:53'),
(115, NULL, 'use', 'voucher', 1129, 'Voucher used: CE214XPZZ', '171.254.188.188', '2026-04-30 10:39:17'),
(116, NULL, 'use', 'voucher', 1181, 'Voucher used: SK21J10H9', '171.255.145.31', '2026-04-30 11:03:06'),
(117, NULL, 'use', 'voucher', 1182, 'Voucher used: SK21KDD7O', '116.98.253.106', '2026-04-30 11:21:27'),
(118, NULL, 'use', 'voucher', 1140, 'Voucher used: CE21U7Z04', '116.98.252.203', '2026-04-30 11:23:25'),
(119, NULL, 'use', 'voucher', 1149, 'Voucher used: NB21RH4ZN', '117.3.123.13', '2026-04-30 11:29:25'),
(120, NULL, 'use', 'voucher', 1151, 'Voucher used: NB21RQ2OV', '116.98.252.130', '2026-04-30 11:29:30'),
(121, NULL, 'use', 'voucher', 1131, 'Voucher used: CE21FRGSJ', '116.98.252.203', '2026-04-30 12:31:46'),
(122, NULL, 'use', 'voucher', 1172, 'Voucher used: SK2190QQW', '116.98.253.184', '2026-04-30 12:40:24'),
(123, NULL, 'use', 'voucher', 1173, 'Voucher used: SK21EV8HY', '116.98.253.184', '2026-04-30 12:44:04'),
(124, NULL, 'use', 'voucher', 1170, 'Voucher used: SK21FCLYJ', '113.185.106.127', '2026-04-30 12:48:22'),
(125, NULL, 'use', 'voucher', 1141, 'Voucher used: NB21WTN05', '171.254.175.216', '2026-04-30 13:09:35'),
(126, NULL, 'use', 'voucher', 1171, 'Voucher used: SK21YNKFP', '171.226.25.175', '2026-04-30 13:22:08'),
(127, NULL, 'use', 'voucher', 1163, 'Voucher used: NB21DE43X', '116.98.252.130', '2026-04-30 13:25:39'),
(128, NULL, 'use', 'voucher', 1136, 'Voucher used: CE21J8VXG', '116.98.252.203', '2026-04-30 13:33:00'),
(129, NULL, 'use', 'voucher', 1127, 'Voucher used: CE213Y8E0', '27.66.60.49', '2026-04-30 13:38:33'),
(130, NULL, 'use', 'voucher', 1156, 'Voucher used: NB217W4MT', '171.254.180.221', '2026-04-30 14:17:18'),
(131, 4, 'login', 'session', 4, 'Login successful', '42.112.73.96', '2026-04-30 14:51:57'),
(132, 4, 'login', 'session', 4, 'Login successful', '116.98.252.210', '2026-04-30 17:43:45'),
(133, 4, 'login', 'session', 4, 'Login successful', '116.98.252.210', '2026-04-30 17:44:41'),
(134, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-05-29 18:48:41'),
(135, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-05-30 09:21:58'),
(136, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-05-30 15:52:46'),
(137, NULL, 'login_failed', 'session', NULL, 'Failed login for: admin', '42.118.186.160', '2026-05-30 16:01:24'),
(138, NULL, 'login_failed', 'session', NULL, 'Failed login for: admin', '42.118.186.160', '2026-05-30 16:01:46'),
(139, NULL, 'login_failed', 'session', NULL, 'Failed login for: admin', '42.118.186.160', '2026-05-30 16:03:29'),
(140, NULL, 'login_failed', 'session', NULL, 'Failed login for: admin', '42.118.186.160', '2026-05-30 16:04:47'),
(141, NULL, 'login_failed', 'session', NULL, 'Failed login for: admin', '42.118.186.160', '2026-05-30 16:05:56'),
(142, NULL, 'login_failed', 'session', NULL, 'Failed login for: admin', '42.118.186.160', '2026-05-30 16:06:22'),
(143, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-06-01 11:08:28'),
(144, 4, 'create', 'campaign', 28, 'Created campaign: Cái Tiệm Cà Kê', '42.118.186.160', '2026-06-01 11:12:18'),
(145, 4, 'update', 'campaign', 28, 'Updated campaign', '42.118.186.160', '2026-06-01 11:13:00'),
(146, 4, 'create', 'campaign', 29, 'Created campaign: 2/9 Coffee Shop', '42.118.186.160', '2026-06-01 11:20:25'),
(147, 4, 'create', 'campaign', 30, 'Created campaign: Chip Chip Coffee KonTum', '42.118.186.160', '2026-06-01 11:47:39'),
(148, 4, 'update', 'campaign', 30, 'Updated campaign', '42.118.186.160', '2026-06-01 11:51:43'),
(149, 4, 'create', 'campaign', 31, 'Created campaign: Mộc Coffee', '42.118.186.160', '2026-06-01 11:59:22'),
(150, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-06-01 12:01:04'),
(151, 4, 'login', 'session', 4, 'Login successful', '27.66.22.36', '2026-06-01 13:05:17'),
(152, 4, 'login', 'session', 4, 'Login successful', '27.66.22.36', '2026-06-01 13:23:56'),
(153, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-06-01 15:44:55'),
(154, 4, 'update', 'campaign', 30, 'Updated campaign', '42.118.186.160', '2026-06-01 16:01:39'),
(155, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-06-01 16:10:28'),
(156, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-06-01 16:53:40'),
(157, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-06-01 17:07:09'),
(158, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-06-01 17:27:07'),
(159, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-06-01 17:33:01'),
(160, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-06-01 17:33:46'),
(161, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-06-01 17:45:26'),
(162, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-06-01 17:49:40'),
(163, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-06-01 17:49:44'),
(164, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-06-01 17:51:28'),
(165, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-06-01 18:02:03'),
(166, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-06-01 18:04:22'),
(167, 4, 'login', 'session', 4, 'Login successful', '42.118.186.160', '2026-06-01 18:23:24'),
(168, 4, 'update', 'campaign', 30, 'Updated campaign', '42.118.186.160', '2026-06-01 18:50:26'),
(169, 4, 'update', 'campaign', 28, 'Updated campaign', '42.118.186.160', '2026-06-01 19:00:30'),
(170, 4, 'update', 'campaign', 28, 'Updated campaign', '42.118.186.160', '2026-06-01 19:01:10'),
(171, 4, 'update', 'campaign', 31, 'Updated campaign', '42.118.186.160', '2026-06-01 19:48:09'),
(172, 4, 'update', 'campaign', 31, 'Updated campaign', '42.118.186.160', '2026-06-01 19:49:32'),
(173, NULL, 'use', 'voucher', 1439, 'Voucher used: CK22VW2XD', '14.236.237.226', '2026-06-02 01:12:40'),
(174, 4, 'update', 'campaign', 31, 'Updated campaign', '42.118.186.160', '2026-06-02 01:36:50'),
(175, NULL, 'use', 'voucher', 1390, 'Voucher used: CC22WZU0E', '171.226.26.247', '2026-06-02 02:08:45'),
(176, NULL, 'use', 'voucher', 1373, 'Voucher used: CC22XLELA', '1.55.41.244', '2026-06-02 02:46:48'),
(177, NULL, 'use', 'voucher', 1257, 'Voucher used: HC22O2G0M', '171.252.162.211', '2026-06-02 03:16:08'),
(178, 4, 'restore', 'voucher', 1439, 'Restored voucher', '42.118.186.160', '2026-06-02 03:17:18'),
(179, NULL, 'use', 'voucher', 1493, 'Voucher used: MC2221CGG', '117.3.226.118', '2026-06-02 04:05:33'),
(180, NULL, 'use', 'voucher', 1420, 'Voucher used: CK22TE8JK', '171.254.139.126', '2026-06-02 04:27:34'),
(181, NULL, 'use', 'voucher', 1432, 'Voucher used: CK224RI0V', '171.254.139.126', '2026-06-02 04:27:50'),
(182, NULL, 'use', 'voucher', 1419, 'Voucher used: CK22353B5', '118.68.1.189', '2026-06-02 04:31:37'),
(183, NULL, 'use', 'voucher', 1261, 'Voucher used: HC22702ZC', '42.116.122.123', '2026-06-02 06:29:50'),
(184, NULL, 'use', 'voucher', 1388, 'Voucher used: CC22YBW92', '113.185.111.154', '2026-06-02 06:56:30'),
(185, NULL, 'use', 'voucher', 1376, 'Voucher used: CC227VREX', '171.244.209.105', '2026-06-02 07:38:18'),
(186, NULL, 'use', 'voucher', 1503, 'Voucher used: MC22IG4IE', '171.252.162.39', '2026-06-02 08:19:29'),
(187, NULL, 'use', 'voucher', 1435, 'Voucher used: CK2227AMW', '171.226.24.179', '2026-06-02 08:21:10'),
(188, NULL, 'use', 'voucher', 1421, 'Voucher used: CK22GG2N3', '118.68.1.189', '2026-06-02 08:24:26'),
(189, NULL, 'use', 'voucher', 1372, 'Voucher used: CC22892OL', '171.254.138.174', '2026-06-02 08:44:31'),
(190, NULL, 'use', 'voucher', 1248, 'Voucher used: HC22LFHAN', '42.115.218.181', '2026-06-02 08:53:56'),
(191, NULL, 'use', 'voucher', 1496, 'Voucher used: MC22UPREW', '171.252.162.39', '2026-06-02 08:56:02'),
(192, 4, 'restore', 'voucher', 1420, 'Restored voucher', '42.118.186.160', '2026-06-02 09:05:35'),
(193, 4, 'restore', 'voucher', 1372, 'Restored voucher', '42.118.186.160', '2026-06-02 09:06:16'),
(194, 4, 'update', 'campaign', 30, 'Updated campaign', '42.118.186.160', '2026-06-02 09:08:16'),
(195, NULL, 'use', 'voucher', 1532, 'Voucher used: CC22IMYSL', '1.55.41.244', '2026-06-02 09:24:54'),
(196, NULL, 'use', 'voucher', 1438, 'Voucher used: CK222F25K', '42.116.96.137', '2026-06-02 09:42:38'),
(197, NULL, 'use', 'voucher', 1506, 'Voucher used: MC2207WOI', '171.252.162.39', '2026-06-02 10:19:56'),
(198, NULL, 'use', 'voucher', 1247, 'Voucher used: HC22W87XX', '171.252.162.211', '2026-06-02 10:21:06'),
(199, NULL, 'use', 'voucher', 1522, 'Voucher used: CC22DB6I7', '1.55.41.244', '2026-06-02 10:31:09'),
(200, NULL, 'use', 'voucher', 1540, 'Voucher used: CC223TJC9', '1.55.41.244', '2026-06-02 10:31:30'),
(201, NULL, 'use', 'voucher', 1520, 'Voucher used: CC22VST6S', '1.55.41.244', '2026-06-02 10:31:53'),
(202, NULL, 'use', 'voucher', 1431, 'Voucher used: CK22ES1BA', '113.185.104.53', '2026-06-02 10:48:12'),
(203, NULL, 'use', 'voucher', 1262, 'Voucher used: HC22PI1DP', '171.252.162.211', '2026-06-02 11:12:40'),
(204, NULL, 'use', 'voucher', 1434, 'Voucher used: CK22WUHRN', '113.185.110.237', '2026-06-02 11:16:27'),
(205, NULL, 'use', 'voucher', 1425, 'Voucher used: CK2215MYB', '118.68.1.189', '2026-06-02 11:23:30'),
(206, NULL, 'use', 'voucher', 1243, 'Voucher used: HC22JV6CM', '27.66.48.239', '2026-06-02 11:25:52'),
(207, NULL, 'use', 'voucher', 1539, 'Voucher used: CC22FRP9T', '27.66.48.239', '2026-06-02 11:37:07'),
(208, NULL, 'use', 'voucher', 1265, 'Voucher used: HC22GEZEN', '27.71.55.38', '2026-06-02 12:03:46'),
(209, 4, 'update', 'campaign', 31, 'Updated campaign', '171.253.24.163', '2026-06-02 12:14:38'),
(210, 4, 'update', 'campaign', 30, 'Updated campaign', '171.253.24.163', '2026-06-02 12:14:55'),
(211, 4, 'update', 'campaign', 29, 'Updated campaign', '171.253.24.163', '2026-06-02 12:15:07'),
(212, 4, 'update', 'campaign', 28, 'Updated campaign', '171.253.24.163', '2026-06-02 12:15:14'),
(213, NULL, 'use', 'voucher', 1559, 'Voucher used: MC22QT2P1', '171.252.162.39', '2026-06-02 12:19:34'),
(214, NULL, 'use', 'voucher', 1601, 'Voucher used: HC22AZO0K', '171.252.162.211', '2026-06-02 12:50:55'),
(215, NULL, 'use', 'voucher', 1583, 'Voucher used: CC22KRRA7', '1.55.41.244', '2026-06-02 13:11:03'),
(216, NULL, 'use', 'voucher', 1636, 'Voucher used: CK22TE8JK', '118.68.1.189', '2026-06-02 13:53:49'),
(217, NULL, 'use', 'voucher', 1620, 'Voucher used: CK22E08H7', '118.68.1.189', '2026-06-02 13:56:19'),
(218, NULL, 'use', 'voucher', 1622, 'Voucher used: CK22WUHRN', '171.254.176.159', '2026-06-02 22:47:19'),
(219, NULL, 'use', 'voucher', 1631, 'Voucher used: CK2215MYB', '171.254.176.159', '2026-06-02 22:48:08'),
(220, NULL, 'use', 'voucher', 1618, 'Voucher used: CK222F25K', '171.254.176.159', '2026-06-02 22:48:20'),
(221, NULL, 'use', 'voucher', 1598, 'Voucher used: HC22LFHAN', '171.254.176.159', '2026-06-02 22:48:36'),
(222, NULL, 'use', 'voucher', 1635, 'Voucher used: CK22GG2N3', '171.254.176.159', '2026-06-02 22:49:21'),
(223, NULL, 'use', 'voucher', 1592, 'Voucher used: HC22JK3RO', '42.116.96.41', '2026-06-03 00:32:57'),
(224, NULL, 'use', 'voucher', 1570, 'Voucher used: CC22892OL', '1.55.41.244', '2026-06-03 01:10:20'),
(225, NULL, 'use', 'voucher', 1581, 'Voucher used: CC22N9UP5', '125.234.229.31', '2026-06-03 02:39:40'),
(226, NULL, 'use', 'voucher', 1548, 'Voucher used: MC22BU3EO', '171.254.130.198', '2026-06-03 03:02:32'),
(227, NULL, 'use', 'voucher', 1578, 'Voucher used: CC22JRQ4N', '171.244.212.27', '2026-06-03 03:10:51'),
(228, NULL, 'use', 'voucher', 1554, 'Voucher used: MC22U6WKR', '171.255.147.142', '2026-06-03 03:13:12'),
(229, NULL, 'use', 'voucher', 1630, 'Voucher used: CK22W8QID', '118.68.1.189', '2026-06-03 03:39:12'),
(230, NULL, 'use', 'voucher', 1606, 'Voucher used: HC226DF7X', '42.115.218.11', '2026-06-03 03:53:43'),
(231, NULL, 'use', 'voucher', 1603, 'Voucher used: HC22EU8EP', '171.254.136.53', '2026-06-03 04:35:35'),
(232, NULL, 'use', 'voucher', 1572, 'Voucher used: CC22IMYSL', '171.254.176.105', '2026-06-03 04:57:10'),
(233, NULL, 'use', 'voucher', 1587, 'Voucher used: CC22IB7QM', '171.226.24.125', '2026-06-03 04:58:07'),
(234, NULL, 'use', 'voucher', 1573, 'Voucher used: CC22ZEMG8', '42.116.96.183', '2026-06-03 05:01:29'),
(235, NULL, 'use', 'voucher', 1591, 'Voucher used: HC22U448D', '42.116.96.108', '2026-06-03 05:07:36'),
(236, NULL, 'use', 'voucher', 1604, 'Voucher used: HC22Y830I', '171.244.208.160', '2026-06-03 06:25:40'),
(237, NULL, 'use', 'voucher', 1628, 'Voucher used: CK221BB01', '171.255.152.20', '2026-06-03 06:28:36'),
(238, NULL, 'use', 'voucher', 1586, 'Voucher used: CC22VST6S', '171.252.163.39', '2026-06-03 07:58:53'),
(239, NULL, 'use', 'voucher', 1634, 'Voucher used: CK222O50K', '118.68.1.189', '2026-06-03 08:14:48'),
(240, 4, 'create', 'campaign', 32, 'Created campaign: aaaaaaaaaChip Chip Coffee KonTum', '171.252.163.39', '2026-06-03 08:40:18'),
(241, NULL, 'use', 'voucher', 1553, 'Voucher used: MC22IG4IE', '171.252.163.39', '2026-06-03 08:45:32'),
(242, NULL, 'use', 'voucher', 1614, 'Voucher used: HC22MLEGL', '171.252.162.211', '2026-06-03 08:49:07'),
(243, NULL, 'use', 'voucher', 1621, 'Voucher used: CK2227AMW', '171.252.163.39', '2026-06-03 09:02:59'),
(244, NULL, 'use', 'voucher', 1640, 'Voucher used: CK22CPTUW', '118.68.1.189', '2026-06-03 09:05:03'),
(245, NULL, 'use', 'voucher', 1589, 'Voucher used: CC22FRP9T', '14.224.233.157', '2026-06-03 09:08:07'),
(246, 4, 'delete', 'campaign', 32, 'Deleted campaign', '171.252.163.39', '2026-06-03 09:15:09'),
(247, NULL, 'use', 'voucher', 1593, 'Voucher used: HC22JV6CM', '14.224.233.157', '2026-06-03 09:16:22'),
(248, NULL, 'use', 'voucher', 1616, 'Voucher used: CK222ZI9R', '125.234.231.75', '2026-06-03 09:21:15'),
(249, NULL, 'use', 'voucher', 1549, 'Voucher used: MC22W7P6N', '171.252.162.39', '2026-06-03 09:40:20'),
(250, NULL, 'use', 'voucher', 1610, 'Voucher used: HC22F7YDD', '42.112.230.134', '2026-06-03 09:47:16'),
(251, NULL, 'use', 'voucher', 1545, 'Voucher used: MC22IJCXS', '171.244.208.147', '2026-06-03 10:05:50'),
(252, NULL, 'use', 'voucher', 1596, 'Voucher used: HC22HNTNW', '171.252.162.211', '2026-06-03 10:06:20'),
(253, NULL, 'use', 'voucher', 1602, 'Voucher used: HC22RADFA', '171.252.162.211', '2026-06-03 10:07:00'),
(254, NULL, 'use', 'voucher', 1556, 'Voucher used: MC22TMRKY', '113.185.111.155', '2026-06-03 10:17:24'),
(255, NULL, 'use', 'voucher', 1551, 'Voucher used: MC22VFZ4G', '171.252.162.39', '2026-06-03 10:21:24'),
(256, NULL, 'use', 'voucher', 1624, 'Voucher used: CK224RI0V', '171.254.188.16', '2026-06-03 10:21:33'),
(257, NULL, 'use', 'voucher', 1546, 'Voucher used: MC220UYV9', '171.252.162.39', '2026-06-03 10:29:10'),
(258, NULL, 'use', 'voucher', 1617, 'Voucher used: CK22VW2XD', '113.185.104.4', '2026-06-03 10:31:15'),
(259, NULL, 'use', 'voucher', 1595, 'Voucher used: HC220B1GQ', '42.115.225.136', '2026-06-03 10:36:01'),
(260, NULL, 'use', 'voucher', 1557, 'Voucher used: MC22V2M9W', '113.185.110.26', '2026-06-03 10:41:41'),
(261, NULL, 'use', 'voucher', 1600, 'Voucher used: HC22Z5NKX', '42.115.218.252', '2026-06-03 11:13:36'),
(262, NULL, 'use', 'voucher', 1561, 'Voucher used: MC22SUFK0', '171.252.162.39', '2026-06-03 11:20:13'),
(263, NULL, 'use', 'voucher', 1569, 'Voucher used: CC227HWFF', '113.185.109.35', '2026-06-03 11:32:52'),
(264, NULL, 'use', 'voucher', 1629, 'Voucher used: CK2220P1N', '118.68.1.189', '2026-06-03 11:41:29'),
(265, NULL, 'use', 'voucher', 1558, 'Voucher used: MC22JJGRR', '171.254.130.65', '2026-06-03 12:26:16'),
(266, NULL, 'use', 'voucher', 1579, 'Voucher used: CC22UOUK1', '42.116.96.140', '2026-06-03 12:44:38'),
(267, NULL, 'use', 'voucher', 1632, 'Voucher used: CK22PFCIS', '118.68.1.189', '2026-06-03 13:04:09'),
(268, NULL, 'use', 'voucher', 1544, 'Voucher used: MC22HLM5P', '171.252.162.39', '2026-06-03 13:05:30'),
(269, NULL, 'use', 'voucher', 1599, 'Voucher used: HC22G7ZMG', '171.252.162.211', '2026-06-03 13:06:46'),
(270, NULL, 'use', 'free_voucher', 74, 'Voucher used: CK22WUHRM', '118.68.1.189', '2026-06-03 13:16:00'),
(271, NULL, 'use', 'voucher', 1619, 'Voucher used: CK224WTPX', '171.252.163.176', '2026-06-03 13:31:46'),
(272, NULL, 'use', 'voucher', 1576, 'Voucher used: CC228TNY9', '1.55.41.244', '2026-06-03 13:32:11'),
(273, NULL, 'use', 'voucher', 1565, 'Voucher used: MC22N5R3Q', '171.252.162.39', '2026-06-03 13:42:40'),
(274, NULL, 'use', 'voucher', 1566, 'Voucher used: CC22LTTFY', '1.55.41.244', '2026-06-03 13:56:06'),
(275, NULL, 'use', 'voucher', 1568, 'Voucher used: CC22JL6J1', '1.55.41.244', '2026-06-03 13:56:39'),
(276, NULL, 'use', 'voucher', 1575, 'Voucher used: CC22R3RL9', '1.55.41.244', '2026-06-03 13:57:07'),
(277, NULL, 'use', 'voucher', 1542, 'Voucher used: MC221JGFQ', '171.252.162.39', '2026-06-03 14:21:56'),
(278, 4, 'login', 'session', 4, 'Login successful', '171.252.163.39', '2026-06-03 15:10:29'),
(279, 4, 'login', 'session', 4, 'Login successful', '171.252.163.39', '2026-06-03 17:27:44'),
(280, NULL, 'use', 'voucher', 1615, 'Voucher used: HC22GEZEN', '27.71.55.38', '2026-06-03 23:52:07'),
(281, NULL, 'use', 'voucher', 1541, 'Voucher used: MC22L78H7', '171.252.162.177', '2026-06-04 01:27:55'),
(282, NULL, 'use', 'voucher', 1608, 'Voucher used: HC22KRNJK', '171.255.144.4', '2026-06-04 03:35:48'),
(283, NULL, 'use', 'voucher', 1582, 'Voucher used: CC222T44C', '171.254.140.189', '2026-06-04 04:55:57'),
(284, NULL, 'use', 'voucher', 1547, 'Voucher used: MC22N4E0B', '171.252.162.39', '2026-06-04 06:18:44'),
(285, NULL, 'use', 'voucher', 1638, 'Voucher used: CK22V7DRL', '116.110.227.62', '2026-06-04 06:49:05'),
(286, NULL, 'use', 'voucher', 1612, 'Voucher used: HC22PI1DP', '171.252.163.39', '2026-06-04 06:58:09'),
(287, NULL, 'use', 'voucher', 1571, 'Voucher used: CC22XLELA', '171.252.163.39', '2026-06-04 07:07:53'),
(288, NULL, 'use', 'voucher', 1607, 'Voucher used: HC22O2G0M', '171.252.163.39', '2026-06-04 07:08:06'),
(289, NULL, 'use', 'voucher', 1623, 'Voucher used: CK22FQN21', '42.115.218.200', '2026-06-04 09:11:57'),
(290, NULL, 'use', 'voucher', 1597, 'Voucher used: HC22W87XX', '117.2.230.203', '2026-06-04 09:21:03'),
(291, NULL, 'use', 'voucher', 1605, 'Voucher used: HC22N0HCU', '171.252.162.211', '2026-06-04 09:49:42'),
(292, NULL, 'use', 'voucher', 1564, 'Voucher used: MC22C8HHL', '171.252.162.39', '2026-06-04 09:58:09'),
(293, NULL, 'use', 'voucher', 1639, 'Voucher used: CK22MIB5N', '171.254.171.213', '2026-06-04 10:09:43'),
(294, NULL, 'use', 'voucher', 1585, 'Voucher used: CC22OVC5R', '1.55.50.190', '2026-06-04 10:19:46'),
(295, NULL, 'use', 'voucher', 1626, 'Voucher used: CK2241UOB', '118.68.1.189', '2026-06-04 11:02:59'),
(296, NULL, 'use', 'free_voucher', 68, 'Voucher used: CC22IB7Q4', '1.55.50.190', '2026-06-04 11:08:50'),
(297, NULL, 'use', 'free_voucher', 76, 'Voucher used: CK224WTPS', '117.3.120.173', '2026-06-04 11:14:15'),
(298, NULL, 'use', 'voucher', 1563, 'Voucher used: MC2221CGG', '171.254.172.154', '2026-06-04 11:14:33'),
(299, NULL, 'use', 'free_voucher', 75, 'Voucher used: CK221BB00', '117.3.120.173', '2026-06-04 11:14:36'),
(300, NULL, 'use', 'voucher', 1594, 'Voucher used: HC22ZO5T0', '117.3.120.173', '2026-06-04 11:24:50'),
(301, NULL, 'use', 'voucher', 1562, 'Voucher used: MC226L7WW', '113.185.109.24', '2026-06-04 11:28:59'),
(302, NULL, 'use', 'voucher', 1543, 'Voucher used: MC222UTWY', '113.185.109.24', '2026-06-04 11:29:14'),
(303, NULL, 'use', 'voucher', 1552, 'Voucher used: MC22X79LT', '171.254.140.151', '2026-06-04 12:05:45'),
(304, NULL, 'use', 'voucher', 1588, 'Voucher used: CC22F2R4Q', '27.67.72.217', '2026-06-04 12:16:04'),
(305, NULL, 'use', 'voucher', 1633, 'Voucher used: CK22YY07K', '118.68.1.189', '2026-06-04 12:22:19'),
(306, NULL, 'use', 'voucher', 1577, 'Voucher used: CC22I328S', '1.55.50.190', '2026-06-04 12:23:38'),
(307, NULL, 'use', 'voucher', 1627, 'Voucher used: CK22AQTBD', '171.252.163.176', '2026-06-04 12:35:59'),
(308, NULL, 'use', 'free_voucher', 78, 'Voucher used: CK22V7DRI', '118.68.1.189', '2026-06-04 12:40:15'),
(309, NULL, 'use', 'voucher', 1613, 'Voucher used: HC221874T', '171.252.162.211', '2026-06-04 12:41:43'),
(310, NULL, 'use', 'voucher', 1567, 'Voucher used: CC22YBW92', '171.254.184.135', '2026-06-04 13:16:18'),
(311, NULL, 'use', 'free_voucher', 73, 'Voucher used: HC22AZOOK', '42.115.218.40', '2026-06-04 13:35:59');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL,
  `sponsor_name` varchar(100) NOT NULL,
  `sponsor_short` varchar(20) NOT NULL,
  `description` varchar(200) DEFAULT 'TẶNG 1 LY NƯỚC',
  `logo` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `guide_content` text DEFAULT NULL,
  `menu_content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `sponsor_name`, `sponsor_short`, `description`, `logo`, `start_date`, `end_date`, `guide_content`, `menu_content`, `created_at`) VALUES
(5, 'the Dé', 'TD', 'TẶNG 1 LY NƯỚC', '69007c6e2a8f6_1761639534.jpg', '2025-10-29', '2025-12-01', '<div style=\"font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\">\r\n\r\n    <div style=\"display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\">\r\n        <div style=\"margin-right: 15px; min-width: 40px;\">\r\n            <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">\r\n                <path d=\"M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\" fill=\"#FF5C00\"/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\"margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\">Địa chỉ sử dụng voucher:</p>\r\n            <h3 style=\"margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\">the Dé</h3>\r\n            <p style=\"margin: 0; color: #555; font-size: 16px; line-height: 1.6;\">\r\n                📍 349 Trần Phú, P.Kon Tum<br>\r\n                ⏰ 08h00 - 22h00<br>\r\n                📞 0935.935.263\r\n            </p>\r\n        </div>\r\n    </div>\r\n\r\n    <div>\r\n        <h4 style=\"margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\">Hướng dẫn sử dụng voucher:</h4>\r\n        <div style=\"color: #333; font-size: 16px; line-height: 1.7;\">\r\n            <p style=\"margin: 0 0 10px 0;\"><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\"margin: 0 0 10px 0;\"><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \"Xác nhận sử dụng\".</p>\r\n            <p style=\"margin: 0 0 15px 0;\"><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\"margin: 0; font-style: italic; color: #777;\">Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n\r\n</div>', '<div style=\" padding: 20px; text-align: center;\">\r\n        <p style=\"margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\">\r\n            💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n        </p>\r\n        <img src=\"https://i.ibb.co/FthMLpm/menu-the-de.jpg\" alt=\"menu-the-de\" border=\"0\" style=\"max-width: 100%; height: auto; border-radius: 8px;\">\r\n    </div>', '2025-10-28 08:12:04'),
(9, 'Trà Sữa Cà Phê Më Chà', 'MC', 'TẶNG 1 LY NƯỚC', '69008b3023552_1761643312.jpg', '2025-10-29', '2025-11-01', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>Trà Sữa Cà Phê Më Chà</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 257 Trần Khánh Dư, P.Kon Tum<br>\r\n                ⏰ 10h00 - 21h00<br>\r\n                📞 0775.49.48.49\r\n            </p>\r\n        </div>\r\n    </div>\r\n\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n\r\n</div>', '<div style=\' padding: 20px; text-align: center;\'>\r\n        <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n            💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n        </p>\r\n        <img src=\'https://i.ibb.co/G4C3Tdzf/menu-me-cha.jpg\' alt=\'menu-me-cha\' border=\'0\' style=\'max-width: 100%; height: auto; border-radius: 8px;\'>\r\n    </div>', '2025-10-28 09:20:29'),
(10, 'Chip Chip Coffee KonTum ', 'CC', 'TẶNG 1 LY NƯỚC', '69008c1e7defe_1761643550.jpg', '2025-10-29', '2025-11-01', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>Chip Chip Coffee KonTum</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 32 Nguyễn Đình Chiểu, P.Kon Tum<br>\r\n                ⏰ 06h00 - 21h00<br>\r\n                📞 0961.964.858\r\n            </p>\r\n        </div>\r\n    </div>\r\n\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n\r\n</div>', '<div style=\' padding: 20px; text-align: center;\'>\r\n        <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n            💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n        </p>\r\n        <img src=\'https://i.ibb.co/cSHmZqQb/IMG-4386.jpg\' alt=\'menu-chip-chip\' border=\'0\' style=\'max-width: 100%; height: auto; border-radius: 8px;\'>\r\n    </div>', '2025-10-28 09:25:50'),
(11, 'Trà Sữa Garlic', 'GL', 'TẶNG 1 LY NƯỚC', '69008d0d0d7df_1761643789.jpg', '2025-10-29', '2025-11-01', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>Trà Sữa Garlic</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 18 Trần Quang Khải, P.Kon Tum<br>\r\n                ⏰ 08h00 - 20h00<br>\r\n                📞 0935.273.721\r\n            </p>\r\n        </div>\r\n    </div>\r\n\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n\r\n</div>', '<div style=\' padding: 20px; text-align: center;\'>\r\n        <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n            💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n        </p>\r\n        <img src=\'https://i.ibb.co/gZQLp9bf/menu-garlic.jpg\' alt=\'menu-garlic\' border=\'0\' style=\'max-width: 100%; height: auto; border-radius: 8px;\'>\r\n    </div>', '2025-10-28 09:28:12'),
(16, 'Trà Sữa Garlic', 'GL19', 'TẶNG 1 LY NƯỚC', '6979c92369b4f_1769589027.jpg', '2026-01-29', '2026-02-01', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>Trà Sữa Garlic</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 18 Trần Quang Khải, P.Kon Tum<br>\r\n                ⏰ 08h00 - 20h00<br>\r\n                📞 0935.273.721\r\n            </p>\r\n        </div>\r\n    </div>\r\n\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n\r\n</div>', '<div style=\' padding: 20px; text-align: center;\'>\r\n        <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n            💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n        </p>\r\n        <img src=\'https://i.ibb.co/gZQLp9bf/menu-garlic.jpg\' alt=\'menu-garlic\' border=\'0\' style=\'max-width: 100%; height: auto; border-radius: 8px;\'>\r\n    </div>', '2026-01-28 08:30:27'),
(17, 'the Dé', 'TD19', 'TẶNG 1 LY NƯỚC', '6979ca38600d7_1769589304.jpg', '2026-01-29', '2026-02-01', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng e-voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>the Dé</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 349 Trần Phú, P.Kon Tum<br>\r\n                ⏰ 08h00 - 22h00<br>\r\n                📞 0935.935.263\r\n            </p>\r\n        </div>\r\n    </div>\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng e-voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n</div>\r\n', '<div style=\' padding: 20px; text-align: center;\'>\r\n        <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n            💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n        </p>\r\n        <img src=\'https://i.ibb.co/FthMLpm/menu-the-de.jpg\' alt=\'menu-the-de\' border=\'0\' style=\'max-width: 100%; height: auto; border-radius: 8px;\'>\r\n    </div>', '2026-01-28 08:35:04'),
(18, '% 𝘾𝙤𝙛𝙛𝙚𝙚 & 𝙏𝙚𝙖', 'PT19', 'TẶNG 1 LY NƯỚC', '6979d0f407bec_1769591028.jpg', '2026-01-29', '2026-02-01', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng e-voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>% 𝘾𝙤𝙛𝙛𝙚𝙚 & 𝙏𝙚𝙖</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 10 Triệu Việt Vương, P.Kon Tum<br>\r\n                ⏰ 11h00 - 22h00<br>\r\n                📞 079.668.7777\r\n            </p>\r\n        </div>\r\n    </div>\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng e-voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n</div>\r\n', '<div style=\' padding: 20px; text-align: center;\'>\r\n        <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n            💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n        </p>\r\n        <img src=\'https://i.ibb.co/1f7Mrfbs/menuphantram.jpg\' alt=\'menu\' border=\'0\' style=\'max-width: 100%; height: auto; border-radius: 8px;\'>\r\n    </div>', '2026-01-28 09:03:48'),
(19, 'Trà Sữa Nhà Làm Mẹ Bối', 'MB19', 'TẶNG 1 LY NƯỚC', '6979d3ebb557b_1769591787.jpg', '2026-01-29', '2026-02-01', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng e-voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>Trà Sữa Nhà Làm Mẹ Bối</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 98 Nguyễn Viết Xuân, P.Kon Tum<br>\r\n                ⏰ 11h00 - 20h00<br>\r\n                📞 0935.626.720\r\n            </p>\r\n        </div>\r\n    </div>\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng e-voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n</div>\r\n', '<div style=\' padding: 20px; text-align: center;\'>\r\n        <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n            💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n        </p>\r\n        <p style=\'margin: 0 0 15px 0;\'><b>1.</b> Houjicha Latte</p>\r\n<p style=\'margin: 0 0 15px 0;\'><b>2.</b> Trà sữa thái đỏ</p>\r\n<p style=\'margin: 0 0 15px 0;\'><b>3.</b> Khoai môn latte sữa dừa</p>\r\n<p style=\'margin: 0 0 15px 0;\'><b>4.</b> Trà sữa ôlong</p>\r\n<p style=\'margin: 0 0 15px 0;\'><b>5.</b> Trà mơ má đào</p>\r\n    </div>', '2026-01-28 09:16:27'),
(20, 'the Dé', 'TD20', 'TẶNG 1 LY NƯỚC', '69c7c1aec2b51_1774698926.jpg', '2026-03-29', '2026-04-01', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng e-voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>the Dé</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 349 Trần Phú, P.Kon Tum<br>\r\n                ⏰ 08h00 - 22h00<br>\r\n                📞 0935.935.263\r\n            </p>\r\n        </div>\r\n    </div>\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng e-voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n</div>\r\n', '<div style=\' padding: 20px; text-align: center;\'>\r\n        <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n            💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n        </p>\r\n        <img src=\'https://i.ibb.co/FthMLpm/menu-the-de.jpg\' alt=\'menu-the-de\' border=\'0\' style=\'max-width: 100%; height: auto; border-radius: 8px;\'>\r\n    </div>', '2026-03-28 11:55:26'),
(21, 'Cái Tiệm Cà Kê', 'CK20', 'TẶNG 1 LY NƯỚC', '69c7c357b84f5_1774699351.jpg', '2026-03-30', '2026-04-02', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng e-voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>Cái Tiệm Cà Kê</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 \r\n407 U Rê, Kon Tum<br>\r\n                ⏰ 09h00 - 21h30<br>\r\n                📞 037 211 3667\r\n            </p>\r\n        </div>\r\n    </div>\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng e-voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n</div>\r\n', '<div style=\'padding: 20px; text-align: center; font-family: sans-serif; line-height: 1.6;\'>\r\n    <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n        💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n    </p>\r\n\r\n    <!-- Nhóm Trà Sữa (Size M) -->\r\n    <p style=\'margin: 5px 0; color: #555; font-style: italic;\'>--- Dòng Trà Sữa (Size M) ---</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>1.</b> Trà sữa cà kê</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>2.</b> Trà sữa phô mai tươi</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>3.</b> Trà sữa phô mai mặn</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>4.</b> Trà sữa cheese ball</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>5.</b> Trà sữa phô mai viên</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>6.</b> Trà sữa phô mai mix</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>7.</b> Trà sữa phô mai muối</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>8.</b> Trà sữa váng vàng pudding đậu đỏ</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>9.</b> Trà sữa đậu đỏ kem trứng</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>10.</b> Trà sữa trân châu đường đen</p>\r\n    <p style=\'margin: 0 0 15px 0;\'><b>11.</b> Trà sữa trân châu hoàng kim mini</p>\r\n\r\n    <!-- Nhóm Trà Trái Cây (Size L) -->\r\n    <p style=\'margin: 5px 0; color: #555; font-style: italic;\'>--- Dòng Trà Trái Cây (Size L) ---</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>12.</b> Trà Táo xanh</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>13.</b> Trà Nho đen</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>14.</b> Trà lựu thạch dừa</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>15.</b> Trà quýt mật ong</p>\r\n</div>', '2026-03-28 12:01:41'),
(22, 'Trà Sữa Cà Phê Më Chà', 'MC20', 'TẶNG 1 LY NƯỚC', '69c7c60f98dbf_1774700047.jpg', '2026-03-29', '2026-04-01', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>Trà Sữa Cà Phê Më Chà</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 257 Trần Khánh Dư, P.Kon Tum<br>\r\n                ⏰ 10h00 - 21h00<br>\r\n                📞 0775.49.48.49\r\n            </p>\r\n        </div>\r\n    </div>\r\n\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n\r\n</div>', '<div style=\'padding: 20px; text-align: center; font-family: sans-serif;\'>\r\n    <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n        💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n    </p>\r\n    \r\n    <p style=\'margin: 0 0 15px 0;\'><b>1.</b> Trà sữa full topping</p>\r\n    <p style=\'margin: 0 0 15px 0;\'><b>2.</b> Hồng Trà Sủi Bọt T.Châu</p>\r\n    <p style=\'margin: 0 0 15px 0;\'><b>3.</b> Hồng Trà Tắc Mật Ong</p>\r\n    <p style=\'margin: 0 0 15px 0;\'><b>4.</b> Trà Me Đác Rim</p>\r\n    <p style=\'margin: 0 0 15px 0;\'><b>5.</b> Trà Mãng Cầu</p>\r\n    <p style=\'margin: 0 0 15px 0;\'><b>6.</b> Trà Nhiệt Đới</p>\r\n    <p style=\'margin: 0 0 15px 0;\'><b>7.</b> Trà Việt Quốc</p>\r\n    <p style=\'margin: 0 0 15px 0;\'><b>8.</b> Trà Thơm</p>\r\n    <p style=\'margin: 0 0 15px 0;\'><b>9.</b> Trà Xoài</p>\r\n    <p style=\'margin: 0 0 15px 0;\'><b>10.</b> Trà Đào</p>\r\n    <p style=\'margin: 0 0 15px 0;\'><b>11.</b> Tàu Hủ T.Châu Đ.Đen</p>\r\n    <p style=\'margin: 0 0 15px 0;\'><b>12.</b> Tàu Hủ Full Topping Kem</p>\r\n</div>', '2026-03-28 12:13:46'),
(23, 'nâu đá café', 'ND20', 'TẶNG 1 LY NƯỚC', '69c7c721e351d_1774700321.jpg', '2026-03-29', '2026-04-01', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng e-voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>nâu đá café</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 1013 Phan Đình Phùng (Ngã tư Phan Chu Trinh - Phan Đình Phùng)<br>\r\n                ⏰ 06h00 - 22h00<br>\r\n                📞 0963.445.551\r\n            </p>\r\n        </div>\r\n    </div>\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng e-voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n</div>\r\n', '<div style=\'padding: 20px; text-align: center; font-family: sans-serif;\'>\r\n    <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n        💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n    </p>\r\n    \r\n    <p style=\'margin: 0 0 15px 0;\'><b>1.</b> Trà sữa thái xanh</p>\r\n    <p style=\'margin: 0 0 15px 0;\'><b>2.</b> Trà sữa phô mai</p>\r\n\r\n</div>', '2026-03-28 12:18:23'),
(24, 'Còng café', 'CF21', 'TẶNG 1 LY NƯỚC', '14b39dd10a44fc3d_1777284758.jpg', '2026-04-28', '2026-05-01', '{\"address\":\"193 Phan Chu Trinh, P.Kon Tum\",\"time\":\"06h00 - 22h00\",\"phone\":\"096 781 79 21\"}', '{\"items\":\"Cf tr\\u1ee9ng\\r\\nCf s\\u1eefa h\\u1ea1nh nh\\u00e2n\\r\\nMatcha late \\u0111\\u00e0i loan\\r\\nTr\\u00e0 hibicus\\r\\nTr\\u00e0 \\u0111\\u00e0o\\r\\nTr\\u00e0 v\\u1ea3i\\r\\nTr\\u00e0 s\\u1eefa @\\r\\nTr\\u00e0 s\\u1eefa g\\u1ea1o rang\\r\\nTr\\u00e0 s\\u1eefa shan tuy\\u1ebft\",\"image\":\"\"}', '2026-04-27 10:10:59'),
(25, '𝟐𝐂𝐄 𝐓𝐢𝐞̣̂𝐦 𝐓𝐫𝐚̀', 'CE21', 'TẶNG 1 LY NƯỚC', '12b72138627ed9be_1777292976.jpg', '2026-04-28', '2026-05-01', '{\"address\":\"541 H\\u00f9ng V\\u01b0\\u01a1ng, P.Kon Tum \",\"time\":\"06h30 - 11h00 v\\u00e0 14h00 - 22h00\",\"phone\":\"0363 975 487\"}', '{\"items\":\"Tr\\u00e0 d\\u00e2u t\\u1eb1m tr\\u00e0\\r\\nTr\\u00e0 t\\u1eafc x\\u00ed mu\\u1ed9i\\r\\nTr\\u00e0 t\\u00e1o b\\u1ea1c h\\u00e0\\r\\nTr\\u00e0 t\\u00e1o h\\u01b0\\u01a1ng l\\u00e0i\\r\\nTr\\u00e0 \\u1ed5i h\\u1ed3ng\",\"image\":\"\"}', '2026-04-27 12:29:36'),
(26, 'Tiệm nhà Bi', 'NB21', 'TẶNG 1 LY NƯỚC', '0ef0c0ec654e8260_1777293226.jpg', '2026-04-28', '2026-05-01', '{\"address\":\"515 Duy T\\u00e2n, P.\\u0110\\u1eafk C\\u1ea5m\",\"time\":\"09h00 - 21h00\",\"phone\":\"0968 952 779\"}', '{\"items\":\"Tr\\u00e0 s\\u1eefa truy\\u1ec1n th\\u1ed1ng\\r\\nTr\\u00e0 s\\u1eefa th\\u00e1i xanh\\r\\nTr\\u00e0 tr\\u00e1i c\\u00e2y\\r\\nMilo d\\u1ea7m b\\u00e1nh plan\",\"image\":\"\"}', '2026-04-27 12:33:46'),
(27, 'Sakura Cafe', 'SK21', 'TẶNG 1 LY NƯỚC', 'da567502847896c0_1777293542.jpg', '2026-04-28', '2026-05-01', '{\"address\":\"381 Tr\\u1ea7n Nh\\u00e2n T\\u00f4ng, P.Kon Tum \",\"time\":\"06h00 - 22h00\",\"phone\":\"0907 488 133\"}', '{\"items\":\"Tr\\u00e0 s\\u1eefa truy\\u1ec1n th\\u1ed1ng\\r\\nTr\\u00e0 d\\u00e2u t\\u1eb1m\\r\\nTr\\u00e0 l\\u1ef1u \\u0111\\u1ecf th\\u1ea1ch d\\u1eeba\",\"image\":\"\"}', '2026-04-27 12:39:02'),
(28, 'Cái Tiệm Cà Kê', 'CK22', 'TẶNG 1 LY NƯỚC', '4d0ebe74d8818150_1780312380.jpg', '2026-06-02', '2026-06-05', '{\"address\":\"407 U R\\u00ea, P.Kon Tum\",\"time\":\"09:30 - 21:30\",\"phone\":\"0372113667\"}', '{\"note\":\"\",\"items\":\"\\u00c1P D\\u1ee4NG T\\u1ea4T C\\u1ea2 SIZE M\\r\\nTr\\u00e0 d\\u00e2u\\r\\nTr\\u00e0 t\\u00e1o\\r\\nTr\\u00e0 \\u0111\\u00e0o\\r\\nTr\\u00e0 v\\u1ea3i\\r\\nTr\\u00e0 nho\\r\\nTr\\u00e0 \\u1ed5i h\\u1ed3ng\\r\\nTr\\u00e0 s\\u1eefa (truy\\u1ec1n th\\u1ed1ng, th\\u00e1i xanh, th\\u00e1i \\u0111\\u1ecf)\\r\\nTr\\u00e0 s\\u1eefa C\\u00e0 k\\u00ea\\r\\nTr\\u00e0 s\\u1eefa Tr\\u00e2n ch\\u00e2u ho\\u00e0ng kim mini\\r\\nTr\\u00e0 s\\u1eefa Phomai vi\\u00ean \\r\\nTr\\u00e0 s\\u1eefa Phomai m\\u1eb7n\\r\\nTr\\u00e0 s\\u1eefa Cheeseball \\r\\nTr\\u00e0 s\\u1eefa Phomai t\\u01b0\\u01a1i\\r\\nTr\\u00e0 s\\u1eefa Full topping \\r\\nTr\\u00e0 s\\u1eefa Phomai mix\",\"image\":\"\"}', '2026-06-01 11:12:18'),
(29, '2/9 Coffee Shop', 'HC22', 'TẶNG 1 LY NƯỚC', '77c3c7a0cfe4783b_1780312825.jpg', '2026-06-02', '2026-06-05', '{\"address\":\"55 L\\u00ea L\\u1ee3i, P.Kon Tum\",\"time\":\"09:00 - 22:00\",\"phone\":\"086.666.7581\"}', '{\"note\":\"\",\"items\":\"C\\u00e0 ph\\u00ea \\u0111en S\\r\\nC\\u00e0 ph\\u00ea mu\\u1ed1i S\\r\\nC\\u00e0 ph\\u00ea s\\u1eefa S\\r\\nMatcha Nh\\u1eadt\\/\\u0110\\u00e0i S\\r\\nTr\\u00e0 nh\\u00e3n l\\u00e0i\",\"image\":\"\"}', '2026-06-01 11:20:25'),
(30, 'Chip Chip Coffee KonTum', 'CC22', 'TẶNG 1 LY NƯỚC', '45fc8f9087ded084_1780339826.jpg', '2026-06-02', '2026-06-05', '{\"address\":\"32 Nguy\\u1ec5n \\u0110\\u00ecnh Chi\\u1ec3u, P.Kon Tum\",\"time\":\"06h00 - 21h00\",\"phone\":\"0961964858\"}', '{\"note\":\"M\\u1eddi b\\u1ea1n ch\\u1ecdn 1 m\\u00f3n b\\u1ea5t k\\u1ef3 trong menu b\\u00ean d\\u01b0\\u1edbi\\ud83d\\udc47\",\"items\":\"\",\"image\":\"1fe727c80696ad7a_1780314459.jpg\"}', '2026-06-01 11:47:39'),
(31, 'Mộc Coffee', 'MC22', 'TẶNG 1 LY NƯỚC', '8c42600372f4c596_1780315162.jpg', '2026-06-02', '2026-06-05', '{\"address\":\"371 Tr\\u1ea7n Nh\\u00e2n T\\u00f4ng, P. Kon Tum\",\"time\":\"06:00 - 22:00\",\"phone\":\"0379 526 262\"}', '{\"note\":\"\",\"items\":\"Sinh t\\u1ed1 b\\u01a1 d\\u1eeba non:30 \\r\\nSinh t\\u1ed1 xo\\u00e0i :30\\r\\n\\r\\nTr\\u00e0 nh\\u00e3n kh\\u00fac b\\u1ea1ch :35\\r\\nTr\\u00e0 m\\u0103ng c\\u1ee5t :30( theo m\\u00f9a)\\r\\nTr\\u00e0 m\\u0103ng c\\u1ee5t chanh leo:30( theo m\\u00f9a) \\r\\nTr\\u00e0 m\\u00e3ng c\\u1ea7u chanh leo:30\\r\\nTr\\u00e0 m\\u00e3ng c\\u1ea7u atiso:30\\r\\nTr\\u00e0 thanh xo\\u00e0i nhi\\u1ec7t \\u0111\\u1edbi :30\\r\\nTr\\u00e0 cam x\\u00ed mu\\u1ed9i: 30 ( hot) \\r\\nTr\\u00e0 t\\u1eafc x\\u00ed mu\\u1ed9i: 25\\r\\nTr\\u00e0 \\u0111\\u00e0o:30\\r\\nTr\\u00e0 d\\u00e2u t\\u00e2y:30\\r\\nTr\\u00e0 m\\u00e3ng c\\u1ea7u:30\\r\\nTr\\u00e0 nho baby:30\\r\\nTr\\u00e0 l\\u1ef1u:30( theo m\\u00f9a) \\r\\nTr\\u00e0 nhi\\u00eat \\u0111\\u1edbi trai c\\u00e2y t\\u01b0\\u01a1i:30\\r\\nTr\\u00e0 d\\u00e2u t\\u1eb1m 30\\r\\nTr\\u00e0 d\\u00e2u t\\u1eb1m mix atiso:30\\r\\nTr\\u00e0 m\\u00e3ng c\\u1ea7u mix d\\u00e2u t\\u1eb1m :30\\r\\nTr\\u00e0 m\\u00e3ng c\\u1ea7u me :30\\r\\nTr\\u00e0 m\\u1eadn :30\\r\\nTr\\u00e0 atiso :30\\r\\nTr\\u00e0 \\u1ed5i:30\\r\\nTr\\u00e0 me:30\\r\\nTr\\u00e0 h\\u1ea1t sen:30\\r\\nTr\\u00e0 chanh x\\u00ed mu\\u1ed9i:25\\r\\nTr\\u00e0 lipton \\u0111\\u00e1:30\\r\\nTr\\u00e0 qu\\u00fdt x\\u00ed mu\\u1ed9i :30\\r\\nTr\\u00e0 nh\\u00e3n th\\u1ea3o m\\u1ed9c :30\\r\\nTr\\u00e0 v\\u1ea3i:30\\r\\nTr\\u00e0 sen nh\\u00e3n:35\\r\\n\\r\\n\\r\\nTr\\u00e0 hoa c\\u00fac h\\u1ea3i ph\\u00f2ng:30\\r\\nTr\\u00e0 lipton:30\\r\\nTr\\u00e0 g\\u1eebng:30\\r\\nCa cao n\\u00f3ng:25\\r\\nB\\u1ea1c x\\u1ec9u n\\u00f3ng 25\\r\\nTr\\u00e0 chanh m\\u1eadt ong x\\u00ed mu\\u1ed9i  :25\\r\\nS\\u1eefa n\\u00f3ng 20\\r\\n\\r\\n\\r\\nS\\u1eefa chua d\\u00e2u t\\u00e2y h\\u1ea1t chia:30\\r\\nS\\u1eefa chua vi\\u00eat qu\\u1ea5t:30\\r\\nS chua \\u0111\\u00e0o:30\\r\\nS chua nho:30\\r\\nS chua kiwi:30\\r\\nSua chua h\\u1ea1c \\u0111\\u00e1t mix th\\u01a1m:30\\r\\nS\\u01b0a chua \\u0111\\u00e1c d\\u00e2u t\\u1eb1m:30\\r\\nS\\u1eefa chua chanh leo:30 \\r\\nS\\u1eefa chua d\\u00e2u t\\u1eb1m :30\\r\\nS\\u01b0a chua xo\\u00e0i:30\\r\\nS\\u1eefa chua c\\u1ed1m d\\u1ebbo :30 \\r\\nS\\u1eefa chua \\u0111\\u00e1 28\\r\\nS\\u1eefa chua h\\u1ee7 :10\\r\\nS\\u1eefa chua kem phomai +d\\u00e2u s\\u1ea5y :35 ( hot) \\r\\n\\r\\nCf phin : \\u0111en 17. Cf  s\\u1eefa 18 \\r\\nCf m\\u00e1y \\u0111en 20.s\\u1eefa:20\\r\\nCf mu\\u1ed1i:25\\r\\nCf kem d\\u1ebbo bu\\u00f4n m\\u00ea:28\\r\\nCf kem tr\\u1ee9ng:28\\r\\nCf caramen kem mu\\u1ed1i 25\\r\\nB\\u1ea1c x\\u1ec9u:25\\r\\nS\\u1eefa t\\u01b0\\u01a1i s\\u01b0\\u01a1ng s\\u00e1o cf h\\u1ea1t chia:30\\r\\nS\\u1eefa t\\u01b0\\u01a1i tr\\u00e2n ch\\u00e2u \\u0111\\u01b0\\u1eddng \\u0111en: 28\\r\\nCa cao \\u0111\\u00e1 28\\r\\nPhin \\u0111i choco k\\u00e8m th\\u1ea1ch :30\\r\\n \\r\\nMatcha latte: 30\\r\\nMatcha latte kem mu\\u1ed1i :30\\r\\n\\r\\nTr\\u00e0 s\\u1eefa truy\\u1ec1n th\\u1ed1ng:30 k\\u00e8m topping size  \\r\\nTr\\u00e0 s\\u1eefa th\\u00e1i \\u0111\\u1ecf 30\\r\\nTr\\u00e0 s\\u1eefa th\\u00e1i xanh 30\\r\\nTr\\u00e0 s\\u1eefa  khoai m\\u00f4n :30\\r\\nTr\\u00e0 s\\u1eefa c\\u1ed1m:30\\r\\nTr\\u00e0 s\\u1eefa olong :30\\r\\n\\r\\nN\\u01b0\\u1edbc \\u00e9p d\\u01b0a h\\u1ea5u:30\\r\\nN\\u01b0\\u1edbc \\u00e9p cam t\\u00e1o:30\\r\\nN\\u01b0\\u1edbc \\u00e9p cam:30\\r\\nN\\u01b0\\u1edbc \\u00e9p \\u1ed5i 30\\r\\nN\\u01b0\\u1edbc \\u00e9p t\\u00e1o mix th\\u01a1m :30\\r\\nN\\u01b0\\u1edbc \\u00e9p th\\u01a1m :30\\r\\nN\\u01b0\\u1edbc \\u00e9p d\\u01b0a h\\u1ea5u mix \\u1ed5i :30\\r\\nN\\u01b0\\u1edbc \\u00e9p cam th\\u01a1m :30\",\"image\":\"\"}', '2026-06-01 11:59:22');

-- --------------------------------------------------------

--
-- Table structure for table `free_vouchers`
--

CREATE TABLE `free_vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `sponsor_name` varchar(100) DEFAULT NULL,
  `description` varchar(200) DEFAULT 'TẶNG 1 LY NƯỚC',
  `logo` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `guide_content` text DEFAULT NULL,
  `menu_content` text DEFAULT NULL,
  `status` enum('unused','used','expired') DEFAULT 'unused',
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `free_vouchers`
--

INSERT INTO `free_vouchers` (`id`, `code`, `sponsor_name`, `description`, `logo`, `start_date`, `end_date`, `guide_content`, `menu_content`, `status`, `used_at`, `created_at`) VALUES
(37, 'PT19TEST', '% 𝘾𝙤𝙛𝙛𝙚𝙚 & 𝙏𝙚𝙖', 'TẶNG 1 LY NƯỚC', '6979d62af3e87_1769592362.jpg', '2026-01-28', '2026-02-01', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng e-voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>% 𝘾𝙤𝙛𝙛𝙚𝙚 & 𝙏𝙚𝙖</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 10 Triệu Việt Vương, P.Kon Tum<br>\r\n                ⏰ 11h00 - 22h00<br>\r\n                📞 079.668.7777\r\n            </p>\r\n        </div>\r\n    </div>\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng e-voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n</div>\r\n', '<div style=\' padding: 20px; text-align: center;\'>\r\n        <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n            💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n        </p>\r\n        <img src=\'https://i.ibb.co/1f7Mrfbs/menuphantram.jpg\' alt=\'menu\' border=\'0\' style=\'max-width: 100%; height: auto; border-radius: 8px;\'>\r\n    </div>', 'unused', NULL, '2026-01-28 09:24:13'),
(38, 'TD19TEST', 'the Dé', 'TẶNG 1 LY NƯỚC', '6979d6090ce15_1769592329.jpg', '2026-01-28', '2026-02-01', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng e-voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>the Dé</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 349 Trần Phú, P.Kon Tum<br>\r\n                ⏰ 08h00 - 22h00<br>\r\n                📞 0935.935.263\r\n            </p>\r\n        </div>\r\n    </div>\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng e-voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n</div>\r\n', '<div style=\' padding: 20px; text-align: center;\'>\r\n        <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n            💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n        </p>\r\n        <img src=\'https://i.ibb.co/FthMLpm/menu-the-de.jpg\' alt=\'menu-the-de\' border=\'0\' style=\'max-width: 100%; height: auto; border-radius: 8px;\'>\r\n    </div>', 'used', '2026-01-31 06:55:13', '2026-01-28 09:24:19'),
(36, 'MB19TEST', 'Trà Sữa Nhà Làm Mẹ Bối', 'TẶNG 1 LY NƯỚC', '6979d63976dba_1769592377.jpg', '2026-01-28', '2026-02-01', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng e-voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>Trà Sữa Nhà Làm Mẹ Bối</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 98 Nguyễn Viết Xuân, P.Kon Tum<br>\r\n                ⏰ 11h00 - 20h00<br>\r\n                📞 0935.626.720\r\n            </p>\r\n        </div>\r\n    </div>\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng e-voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n</div>\r\n', '<div style=\' padding: 20px; text-align: center;\'>\r\n        <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n            💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n        </p>\r\n        <p style=\'margin: 0 0 15px 0;\'><b>1.</b> Houjicha Latte</p>\r\n<p style=\'margin: 0 0 15px 0;\'><b>2.</b> Trà sữa thái đỏ</p>\r\n<p style=\'margin: 0 0 15px 0;\'><b>3.</b> Khoai môn latte sữa dừa</p>\r\n<p style=\'margin: 0 0 15px 0;\'><b>4.</b> Trà sữa ôlong</p>\r\n<p style=\'margin: 0 0 15px 0;\'><b>5.</b> Trà mơ má đào</p>\r\n    </div>', 'unused', NULL, '2026-01-28 09:24:09'),
(39, 'GL19TEST', 'Trà Sữa Garlic', 'TẶNG 1 LY NƯỚC', '6979d5f47ac26_1769592308.jpg', '2026-01-28', '2026-02-01', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>Trà Sữa Garlic</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 18 Trần Quang Khải, P.Kon Tum<br>\r\n                ⏰ 08h00 - 20h00<br>\r\n                📞 0935.273.721\r\n            </p>\r\n        </div>\r\n    </div>\r\n\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n\r\n</div>', '<div style=\' padding: 20px; text-align: center;\'>\r\n        <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n            💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n        </p>\r\n        <img src=\'https://i.ibb.co/gZQLp9bf/menu-garlic.jpg\' alt=\'menu-garlic\' border=\'0\' style=\'max-width: 100%; height: auto; border-radius: 8px;\'>\r\n    </div>', 'unused', NULL, '2026-01-28 09:24:24'),
(76, 'CK224WTPS', 'Cái Tiệm Cà Kê', 'TẶNG 1 LY NƯỚC', '4d0ebe74d8818150_1780312380.jpg', '2026-06-02', '2026-06-05', '{\"address\":\"407 U R\\u00ea, P.Kon Tum\",\"time\":\"09:30 - 21:30\",\"phone\":\"0372113667\"}', '{\"note\":\"\",\"items\":\"\\u00c1P D\\u1ee4NG T\\u1ea4T C\\u1ea2 SIZE M\\r\\nTr\\u00e0 d\\u00e2u\\r\\nTr\\u00e0 t\\u00e1o\\r\\nTr\\u00e0 \\u0111\\u00e0o\\r\\nTr\\u00e0 v\\u1ea3i\\r\\nTr\\u00e0 nho\\r\\nTr\\u00e0 \\u1ed5i h\\u1ed3ng\\r\\nTr\\u00e0 s\\u1eefa (truy\\u1ec1n th\\u1ed1ng, th\\u00e1i xanh, th\\u00e1i \\u0111\\u1ecf)\\r\\nTr\\u00e0 s\\u1eefa C\\u00e0 k\\u00ea\\r\\nTr\\u00e0 s\\u1eefa Tr\\u00e2n ch\\u00e2u ho\\u00e0ng kim mini\\r\\nTr\\u00e0 s\\u1eefa Phomai vi\\u00ean \\r\\nTr\\u00e0 s\\u1eefa Phomai m\\u1eb7n\\r\\nTr\\u00e0 s\\u1eefa Cheeseball \\r\\nTr\\u00e0 s\\u1eefa Phomai t\\u01b0\\u01a1i\\r\\nTr\\u00e0 s\\u1eefa Full topping \\r\\nTr\\u00e0 s\\u1eefa Phomai mix\",\"image\":\"\"}', 'used', '2026-06-04 11:14:15', '2026-06-04 07:22:26'),
(77, 'MC220UYV8', 'Mộc Coffee', 'TẶNG 1 LY NƯỚC', '8c42600372f4c596_1780315162.jpg', '2026-06-02', '2026-06-05', '{\"address\":\"371 Tr\\u1ea7n Nh\\u00e2n T\\u00f4ng, P. Kon Tum\",\"time\":\"06:00 - 22:00\",\"phone\":\"0379 526 262\"}', '{\"note\":\"\",\"items\":\"Sinh t\\u1ed1 b\\u01a1 d\\u1eeba non:30 \\r\\nSinh t\\u1ed1 xo\\u00e0i :30\\r\\n\\r\\nTr\\u00e0 nh\\u00e3n kh\\u00fac b\\u1ea1ch :35\\r\\nTr\\u00e0 m\\u0103ng c\\u1ee5t :30( theo m\\u00f9a)\\r\\nTr\\u00e0 m\\u0103ng c\\u1ee5t chanh leo:30( theo m\\u00f9a) \\r\\nTr\\u00e0 m\\u00e3ng c\\u1ea7u chanh leo:30\\r\\nTr\\u00e0 m\\u00e3ng c\\u1ea7u atiso:30\\r\\nTr\\u00e0 thanh xo\\u00e0i nhi\\u1ec7t \\u0111\\u1edbi :30\\r\\nTr\\u00e0 cam x\\u00ed mu\\u1ed9i: 30 ( hot) \\r\\nTr\\u00e0 t\\u1eafc x\\u00ed mu\\u1ed9i: 25\\r\\nTr\\u00e0 \\u0111\\u00e0o:30\\r\\nTr\\u00e0 d\\u00e2u t\\u00e2y:30\\r\\nTr\\u00e0 m\\u00e3ng c\\u1ea7u:30\\r\\nTr\\u00e0 nho baby:30\\r\\nTr\\u00e0 l\\u1ef1u:30( theo m\\u00f9a) \\r\\nTr\\u00e0 nhi\\u00eat \\u0111\\u1edbi trai c\\u00e2y t\\u01b0\\u01a1i:30\\r\\nTr\\u00e0 d\\u00e2u t\\u1eb1m 30\\r\\nTr\\u00e0 d\\u00e2u t\\u1eb1m mix atiso:30\\r\\nTr\\u00e0 m\\u00e3ng c\\u1ea7u mix d\\u00e2u t\\u1eb1m :30\\r\\nTr\\u00e0 m\\u00e3ng c\\u1ea7u me :30\\r\\nTr\\u00e0 m\\u1eadn :30\\r\\nTr\\u00e0 atiso :30\\r\\nTr\\u00e0 \\u1ed5i:30\\r\\nTr\\u00e0 me:30\\r\\nTr\\u00e0 h\\u1ea1t sen:30\\r\\nTr\\u00e0 chanh x\\u00ed mu\\u1ed9i:25\\r\\nTr\\u00e0 lipton \\u0111\\u00e1:30\\r\\nTr\\u00e0 qu\\u00fdt x\\u00ed mu\\u1ed9i :30\\r\\nTr\\u00e0 nh\\u00e3n th\\u1ea3o m\\u1ed9c :30\\r\\nTr\\u00e0 v\\u1ea3i:30\\r\\nTr\\u00e0 sen nh\\u00e3n:35\\r\\n\\r\\n\\r\\nTr\\u00e0 hoa c\\u00fac h\\u1ea3i ph\\u00f2ng:30\\r\\nTr\\u00e0 lipton:30\\r\\nTr\\u00e0 g\\u1eebng:30\\r\\nCa cao n\\u00f3ng:25\\r\\nB\\u1ea1c x\\u1ec9u n\\u00f3ng 25\\r\\nTr\\u00e0 chanh m\\u1eadt ong x\\u00ed mu\\u1ed9i  :25\\r\\nS\\u1eefa n\\u00f3ng 20\\r\\n\\r\\n\\r\\nS\\u1eefa chua d\\u00e2u t\\u00e2y h\\u1ea1t chia:30\\r\\nS\\u1eefa chua vi\\u00eat qu\\u1ea5t:30\\r\\nS chua \\u0111\\u00e0o:30\\r\\nS chua nho:30\\r\\nS chua kiwi:30\\r\\nSua chua h\\u1ea1c \\u0111\\u00e1t mix th\\u01a1m:30\\r\\nS\\u01b0a chua \\u0111\\u00e1c d\\u00e2u t\\u1eb1m:30\\r\\nS\\u1eefa chua chanh leo:30 \\r\\nS\\u1eefa chua d\\u00e2u t\\u1eb1m :30\\r\\nS\\u01b0a chua xo\\u00e0i:30\\r\\nS\\u1eefa chua c\\u1ed1m d\\u1ebbo :30 \\r\\nS\\u1eefa chua \\u0111\\u00e1 28\\r\\nS\\u1eefa chua h\\u1ee7 :10\\r\\nS\\u1eefa chua kem phomai +d\\u00e2u s\\u1ea5y :35 ( hot) \\r\\n\\r\\nCf phin : \\u0111en 17. Cf  s\\u1eefa 18 \\r\\nCf m\\u00e1y \\u0111en 20.s\\u1eefa:20\\r\\nCf mu\\u1ed1i:25\\r\\nCf kem d\\u1ebbo bu\\u00f4n m\\u00ea:28\\r\\nCf kem tr\\u1ee9ng:28\\r\\nCf caramen kem mu\\u1ed1i 25\\r\\nB\\u1ea1c x\\u1ec9u:25\\r\\nS\\u1eefa t\\u01b0\\u01a1i s\\u01b0\\u01a1ng s\\u00e1o cf h\\u1ea1t chia:30\\r\\nS\\u1eefa t\\u01b0\\u01a1i tr\\u00e2n ch\\u00e2u \\u0111\\u01b0\\u1eddng \\u0111en: 28\\r\\nCa cao \\u0111\\u00e1 28\\r\\nPhin \\u0111i choco k\\u00e8m th\\u1ea1ch :30\\r\\n \\r\\nMatcha latte: 30\\r\\nMatcha latte kem mu\\u1ed1i :30\\r\\n\\r\\nTr\\u00e0 s\\u1eefa truy\\u1ec1n th\\u1ed1ng:30 k\\u00e8m topping size  \\r\\nTr\\u00e0 s\\u1eefa th\\u00e1i \\u0111\\u1ecf 30\\r\\nTr\\u00e0 s\\u1eefa th\\u00e1i xanh 30\\r\\nTr\\u00e0 s\\u1eefa  khoai m\\u00f4n :30\\r\\nTr\\u00e0 s\\u1eefa c\\u1ed1m:30\\r\\nTr\\u00e0 s\\u1eefa olong :30\\r\\n\\r\\nN\\u01b0\\u1edbc \\u00e9p d\\u01b0a h\\u1ea5u:30\\r\\nN\\u01b0\\u1edbc \\u00e9p cam t\\u00e1o:30\\r\\nN\\u01b0\\u1edbc \\u00e9p cam:30\\r\\nN\\u01b0\\u1edbc \\u00e9p \\u1ed5i 30\\r\\nN\\u01b0\\u1edbc \\u00e9p t\\u00e1o mix th\\u01a1m :30\\r\\nN\\u01b0\\u1edbc \\u00e9p th\\u01a1m :30\\r\\nN\\u01b0\\u1edbc \\u00e9p d\\u01b0a h\\u1ea5u mix \\u1ed5i :30\\r\\nN\\u01b0\\u1edbc \\u00e9p cam th\\u01a1m :30\",\"image\":\"\"}', 'expired', NULL, '2026-06-04 11:11:00'),
(41, 'FREEND20FHSIW', 'nâu đá café', 'TẶNG 1 LY NƯỚC', '69c7cae74535d_1774701287.jpg', '2026-03-29', '2026-04-01', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng e-voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>nâu đá café</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 1013 Phan Đình Phùng (Ngã tư Phan Chu Trinh - Phan Đình Phùng)<br>\r\n                ⏰ 06h00 - 22h00<br>\r\n                📞 0963.445.551\r\n            </p>\r\n        </div>\r\n    </div>\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng e-voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n</div>\r\n', '<div style=\'padding: 20px; text-align: center; font-family: sans-serif;\'>\r\n    <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n        💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n    </p>\r\n    \r\n    <p style=\'margin: 0 0 15px 0;\'><b>1.</b> Trà sữa thái xanh</p>\r\n    <p style=\'margin: 0 0 15px 0;\'><b>2.</b> Trà sữa phô mai</p>\r\n\r\n</div>', 'used', '2026-03-30 16:22:18', '2026-03-28 12:20:16'),
(73, 'HC22AZOOK', '2/9 Coffee Shop', 'TẶNG 1 LY NƯỚC', '77c3c7a0cfe4783b_1780312825.jpg', '2026-06-02', '2026-06-05', '{\"address\":\"55 L\\u00ea L\\u1ee3i, P.Kon Tum\",\"time\":\"09:00 - 22:00\",\"phone\":\"086.666.7581\"}', '{\"note\":\"\",\"items\":\"C\\u00e0 ph\\u00ea \\u0111en S\\r\\nC\\u00e0 ph\\u00ea mu\\u1ed1i S\\r\\nC\\u00e0 ph\\u00ea s\\u1eefa S\\r\\nMatcha Nh\\u1eadt\\/\\u0110\\u00e0i S\\r\\nTr\\u00e0 nh\\u00e3n l\\u00e0i\",\"image\":\"\"}', 'used', '2026-06-04 13:35:59', '2026-06-03 11:50:26'),
(74, 'CK22WUHRM', 'Cái Tiệm Cà Kê', 'TẶNG 1 LY NƯỚC', '4d0ebe74d8818150_1780312380.jpg', '2026-06-02', '2026-06-05', '{\"address\":\"407 U R\\u00ea, P.Kon Tum\",\"time\":\"09:30 - 21:30\",\"phone\":\"0372113667\"}', '{\"note\":\"\",\"items\":\"\\u00c1P D\\u1ee4NG T\\u1ea4T C\\u1ea2 SIZE M\\r\\nTr\\u00e0 d\\u00e2u\\r\\nTr\\u00e0 t\\u00e1o\\r\\nTr\\u00e0 \\u0111\\u00e0o\\r\\nTr\\u00e0 v\\u1ea3i\\r\\nTr\\u00e0 nho\\r\\nTr\\u00e0 \\u1ed5i h\\u1ed3ng\\r\\nTr\\u00e0 s\\u1eefa (truy\\u1ec1n th\\u1ed1ng, th\\u00e1i xanh, th\\u00e1i \\u0111\\u1ecf)\\r\\nTr\\u00e0 s\\u1eefa C\\u00e0 k\\u00ea\\r\\nTr\\u00e0 s\\u1eefa Tr\\u00e2n ch\\u00e2u ho\\u00e0ng kim mini\\r\\nTr\\u00e0 s\\u1eefa Phomai vi\\u00ean \\r\\nTr\\u00e0 s\\u1eefa Phomai m\\u1eb7n\\r\\nTr\\u00e0 s\\u1eefa Cheeseball \\r\\nTr\\u00e0 s\\u1eefa Phomai t\\u01b0\\u01a1i\\r\\nTr\\u00e0 s\\u1eefa Full topping \\r\\nTr\\u00e0 s\\u1eefa Phomai mix\",\"image\":\"\"}', 'used', '2026-06-03 13:16:00', '2026-06-03 11:53:21'),
(68, 'CC22IB7Q4', 'Chip Chip Coffee KonTum', 'TẶNG 1 LY NƯỚC', '45fc8f9087ded084_1780339826.jpg', '2026-06-02', '2026-06-05', '{\"address\":\"32 Nguy\\u1ec5n \\u0110\\u00ecnh Chi\\u1ec3u, P.Kon Tum\",\"time\":\"06h00 - 21h00\",\"phone\":\"0961964858\"}', '{\"note\":\"M\\u1eddi b\\u1ea1n ch\\u1ecdn 1 m\\u00f3n b\\u1ea5t k\\u1ef3 trong menu b\\u00ean d\\u01b0\\u1edbi\\ud83d\\udc47\",\"items\":\"\",\"image\":\"1fe727c80696ad7a_1780314459.jpg\"}', 'used', '2026-06-04 11:08:50', '2026-06-03 08:39:20'),
(75, 'CK221BB00', 'Cái Tiệm Cà Kê', 'TẶNG 1 LY NƯỚC', '4d0ebe74d8818150_1780312380.jpg', '2026-06-02', '2026-06-05', '{\"address\":\"407 U R\\u00ea, P.Kon Tum\",\"time\":\"09:30 - 21:30\",\"phone\":\"0372113667\"}', '{\"note\":\"\",\"items\":\"\\u00c1P D\\u1ee4NG T\\u1ea4T C\\u1ea2 SIZE M\\r\\nTr\\u00e0 d\\u00e2u\\r\\nTr\\u00e0 t\\u00e1o\\r\\nTr\\u00e0 \\u0111\\u00e0o\\r\\nTr\\u00e0 v\\u1ea3i\\r\\nTr\\u00e0 nho\\r\\nTr\\u00e0 \\u1ed5i h\\u1ed3ng\\r\\nTr\\u00e0 s\\u1eefa (truy\\u1ec1n th\\u1ed1ng, th\\u00e1i xanh, th\\u00e1i \\u0111\\u1ecf)\\r\\nTr\\u00e0 s\\u1eefa C\\u00e0 k\\u00ea\\r\\nTr\\u00e0 s\\u1eefa Tr\\u00e2n ch\\u00e2u ho\\u00e0ng kim mini\\r\\nTr\\u00e0 s\\u1eefa Phomai vi\\u00ean \\r\\nTr\\u00e0 s\\u1eefa Phomai m\\u1eb7n\\r\\nTr\\u00e0 s\\u1eefa Cheeseball \\r\\nTr\\u00e0 s\\u1eefa Phomai t\\u01b0\\u01a1i\\r\\nTr\\u00e0 s\\u1eefa Full topping \\r\\nTr\\u00e0 s\\u1eefa Phomai mix\",\"image\":\"\"}', 'used', '2026-06-04 11:14:36', '2026-06-04 07:18:34'),
(43, 'FREECK20OVKUA', 'Cái Tiệm Cà Kê', 'TẶNG 1 LY NƯỚC', '69ea085cb68be_1776945244.JPG', '2026-03-30', '2026-05-03', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng e-voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>Cái Tiệm Cà Kê</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 \r\n407 U Rê, Kon Tum<br>\r\n                ⏰ 09h00 - 21h30<br>\r\n                📞 037 211 3667\r\n            </p>\r\n        </div>\r\n    </div>\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng e-voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n</div>\r\n', '<div style=\'padding: 20px; text-align: center; font-family: sans-serif; line-height: 1.6;\'>\r\n    <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n        💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n    </p>\r\n\r\n    <!-- Nhóm Trà Sữa (Size M) -->\r\n    <p style=\'margin: 5px 0; color: #555; font-style: italic;\'>--- Dòng Trà Sữa (Size M) ---</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>1.</b> Trà sữa cà kê</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>2.</b> Trà sữa phô mai tươi</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>3.</b> Trà sữa phô mai mặn</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>4.</b> Trà sữa cheese ball</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>5.</b> Trà sữa phô mai viên</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>6.</b> Trà sữa phô mai mix</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>7.</b> Trà sữa phô mai muối</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>8.</b> Trà sữa váng vàng pudding đậu đỏ</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>9.</b> Trà sữa đậu đỏ kem trứng</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>10.</b> Trà sữa trân châu đường đen</p>\r\n    <p style=\'margin: 0 0 15px 0;\'><b>11.</b> Trà sữa trân châu hoàng kim mini</p>\r\n\r\n    <!-- Nhóm Trà Trái Cây (Size L) -->\r\n    <p style=\'margin: 5px 0; color: #555; font-style: italic;\'>--- Dòng Trà Trái Cây (Size L) ---</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>12.</b> Trà Táo xanh</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>13.</b> Trà Nho đen</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>14.</b> Trà lựu thạch dừa</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>15.</b> Trà quýt mật ong</p>\r\n</div>', 'expired', NULL, '2026-04-23 11:53:26'),
(44, 'FREECK200VGY8', 'Cái Tiệm Cà Kê', 'TẶNG 1 LY NƯỚC', NULL, '2026-03-30', '2026-04-02', '<div style=\'font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; padding: 25px; background-color: #ffffff;\'>\r\n    <div style=\'display: flex; align-items: flex-start; border-bottom: 1px dashed #cccccc; padding-bottom: 20px; margin-bottom: 20px;\'>\r\n        <div style=\'margin-right: 15px; min-width: 40px;\'>\r\n            <svg width=\'24\' height=\'24\' viewBox=\'0 0 24 24\' fill=\'none\' xmlns=\'http://www.w3.org/2000/svg\'>\r\n                <path d=\'M12 0C7.31 0 3.5 3.81 3.5 8.5C3.5 14.87 12 24 12 24C12 24 20.5 14.87 20.5 8.5C20.5 3.81 16.69 0 12 0ZM12 11.5C10.34 11.5 9 10.16 9 8.5C9 6.84 10.34 5.5 12 5.5C13.66 5.5 15 6.84 15 8.5C15 10.16 13.66 11.5 12 11.5Z\' fill=\'#FF5C00\'/>\r\n            </svg>\r\n        </div>\r\n        <div>\r\n            <p style=\'margin: 0 0 10px 0; color: #333; font-size: 16px; font-weight: bold;\'>Địa chỉ sử dụng e-voucher:</p>\r\n            <h3 style=\'margin: 0 0 8px 0; color: #d9534f; font-size: 22px;\'>Cái Tiệm Cà Kê</h3>\r\n            <p style=\'margin: 0; color: #555; font-size: 16px; line-height: 1.6;\'>\r\n                📍 \r\n407 U Rê, Kon Tum<br>\r\n                ⏰ 09h00 - 21h30<br>\r\n                📞 037 211 3667\r\n            </p>\r\n        </div>\r\n    </div>\r\n    <div>\r\n        <h4 style=\'margin: 0 0 15px 0; color: #FF5C00; font-size: 18px; font-weight: bold;\'>Hướng dẫn sử dụng e-voucher:</h4>\r\n        <div style=\'color: #333; font-size: 16px; line-height: 1.7;\'>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 1:</b> Đến quán theo địa chỉ trên.</p>\r\n            <p style=\'margin: 0 0 10px 0;\'><b>Bước 2:</b> Mở trang này và đưa cho thu ngân bấm \'Xác nhận sử dụng\'.</p>\r\n            <p style=\'margin: 0 0 15px 0;\'><b>Bước 3:</b> Chọn 1 món trong menu đính kèm voucher.</p>\r\n            <p style=\'margin: 0; font-style: italic; color: #777;\'>Cảm ơn bạn đã đồng hành với Kon Tum +</p>\r\n        </div>\r\n    </div>\r\n</div>\r\n', '<div style=\'padding: 20px; text-align: center; font-family: sans-serif; line-height: 1.6;\'>\r\n    <p style=\'margin: 0 0 15px 0; font-size: 20px; font-weight: bold; color: #FF5C00;\'>\r\n        💟 Mời bạn chọn 1 ly BẤT KỲ trong menu bên dưới\r\n    </p>\r\n\r\n    <!-- Nhóm Trà Sữa (Size M) -->\r\n    <p style=\'margin: 5px 0; color: #555; font-style: italic;\'>--- Dòng Trà Sữa (Size M) ---</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>1.</b> Trà sữa cà kê</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>2.</b> Trà sữa phô mai tươi</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>3.</b> Trà sữa phô mai mặn</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>4.</b> Trà sữa cheese ball</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>5.</b> Trà sữa phô mai viên</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>6.</b> Trà sữa phô mai mix</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>7.</b> Trà sữa phô mai muối</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>8.</b> Trà sữa váng vàng pudding đậu đỏ</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>9.</b> Trà sữa đậu đỏ kem trứng</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>10.</b> Trà sữa trân châu đường đen</p>\r\n    <p style=\'margin: 0 0 15px 0;\'><b>11.</b> Trà sữa trân châu hoàng kim mini</p>\r\n\r\n    <!-- Nhóm Trà Trái Cây (Size L) -->\r\n    <p style=\'margin: 5px 0; color: #555; font-style: italic;\'>--- Dòng Trà Trái Cây (Size L) ---</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>12.</b> Trà Táo xanh</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>13.</b> Trà Nho đen</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>14.</b> Trà lựu thạch dừa</p>\r\n    <p style=\'margin: 0 0 10px 0;\'><b>15.</b> Trà quýt mật ong</p>\r\n</div>', 'expired', NULL, '2026-04-25 04:39:33'),
(45, 'FREECF21C31DX', 'Còng café', 'TẶNG 1 LY NƯỚC', NULL, '2026-04-27', '2026-05-01', '{\"address\":\"193 Phan Chu Trinh, P.Kon Tum\",\"time\":\"06h00 - 22h00\",\"phone\":\"096 781 79 21\"}', '{\"items\":\"Cf tr\\u1ee9ng\\r\\nCf s\\u1eefa h\\u1ea1nh nh\\u00e2n\\r\\nMatcha late \\u0111\\u00e0i loan\\r\\nTr\\u00e0 hibicus\\r\\nTr\\u00e0 \\u0111\\u00e0o\\r\\nTr\\u00e0 v\\u1ea3i\\r\\nTr\\u00e0 s\\u1eefa @\\r\\nTr\\u00e0 s\\u1eefa g\\u1ea1o rang\\r\\nTr\\u00e0 s\\u1eefa shan tuy\\u1ebft\",\"image\":\"\"}', 'unused', NULL, '2026-04-27 10:29:15'),
(46, 'FREESK21MBUUN', 'Sakura Cafe', 'TẶNG 1 LY NƯỚC', '680d29061277a5cd_1777302364.jpg', '2026-04-27', '2026-05-01', '{\"address\":\"381 Tr\\u1ea7n Nh\\u00e2n T\\u00f4ng, P.Kon Tum \",\"time\":\"06h00 - 22h00\",\"phone\":\"0907 488 133\"}', '{\"items\":\"Tr\\u00e0 s\\u1eefa truy\\u1ec1n th\\u1ed1ng\\r\\nTr\\u00e0 d\\u00e2u t\\u1eb1m\\r\\nTr\\u00e0 l\\u1ef1u \\u0111\\u1ecf th\\u1ea1ch d\\u1eeba\",\"image\":\"\"}', 'unused', NULL, '2026-04-27 12:40:16'),
(47, 'FREENB21WTN05', 'Tiệm nhà Bi', 'TẶNG 1 LY NƯỚC', 'aa485ad1446f6028_1777302351.jpg', '2026-04-27', '2026-05-01', '{\"address\":\"515 Duy T\\u00e2n, P.\\u0110\\u1eafk C\\u1ea5m\",\"time\":\"09h00 - 21h00\",\"phone\":\"0968 952 779\"}', '{\"items\":\"Tr\\u00e0 s\\u1eefa truy\\u1ec1n th\\u1ed1ng\\r\\nTr\\u00e0 s\\u1eefa th\\u00e1i xanh\\r\\nTr\\u00e0 tr\\u00e1i c\\u00e2y\\r\\nMilo d\\u1ea7m b\\u00e1nh plan\",\"image\":\"\"}', 'unused', NULL, '2026-04-27 12:40:22'),
(48, 'FREECE21CEL5D', '𝟐𝐂𝐄 𝐓𝐢𝐞̣̂𝐦 𝐓𝐫𝐚̀', 'TẶNG 1 LY NƯỚC', '3a5161ef71d49e67_1777302298.jpg', '2026-04-27', '2026-05-01', '{\"address\":\"541 H\\u00f9ng V\\u01b0\\u01a1ng, P.Kon Tum \",\"time\":\"06h30 - 11h00 v\\u00e0 14h00 - 22h00\",\"phone\":\"0363 975 487\"}', '{\"items\":\"Tr\\u00e0 d\\u00e2u t\\u1eb1m tr\\u00e0\\r\\nTr\\u00e0 t\\u1eafc x\\u00ed mu\\u1ed9i\\r\\nTr\\u00e0 t\\u00e1o b\\u1ea1c h\\u00e0\\r\\nTr\\u00e0 t\\u00e1o h\\u01b0\\u01a1ng l\\u00e0i\\r\\nTr\\u00e0 \\u1ed5i h\\u1ed3ng\",\"image\":\"\"}', 'expired', NULL, '2026-04-27 12:40:27'),
(49, 'FREECF21GSBLL', 'Còng café', 'TẶNG 1 LY NƯỚC', '61baf484b8cfe714_1777302275.jpg', '2026-04-27', '2026-06-04', '{\"address\":\"193 Phan Chu Trinh, P.Kon Tum\",\"time\":\"06h00 - 22h00\",\"phone\":\"096 781 79 21\"}', '{\"items\":\"Cf tr\\u1ee9ng\\r\\nCf s\\u1eefa h\\u1ea1nh nh\\u00e2n\\r\\nMatcha late \\u0111\\u00e0i loan\\r\\nTr\\u00e0 hibicus\\r\\nTr\\u00e0 \\u0111\\u00e0o\\r\\nTr\\u00e0 v\\u1ea3i\\r\\nTr\\u00e0 s\\u1eefa @\\r\\nTr\\u00e0 s\\u1eefa g\\u1ea1o rang\\r\\nTr\\u00e0 s\\u1eefa shan tuy\\u1ebft\",\"image\":\"\"}', 'unused', NULL, '2026-04-27 12:40:35'),
(62, 'FREESK21YNKFP', 'Sakura Cafe', 'TẶNG 1 LY NƯỚC', 'da567502847896c0_1777293542.jpg', '2026-04-28', '2026-06-07', '{\"address\":\"381 Tr\\u1ea7n Nh\\u00e2n T\\u00f4ng, P.Kon Tum \",\"time\":\"06h00 - 22h00\",\"phone\":\"0907 488 133\"}', '{\"items\":\"Tr\\u00e0 s\\u1eefa truy\\u1ec1n th\\u1ed1ng\\r\\nTr\\u00e0 d\\u00e2u t\\u1eb1m\\r\\nTr\\u00e0 l\\u1ef1u \\u0111\\u1ecf th\\u1ea1ch d\\u1eeba\",\"image\":\"\"}', 'unused', NULL, '2026-05-30 16:21:19'),
(63, 'FREEHC22U448D', '2/9 Coffee Shop', 'TẶNG 1 LY NƯỚC', '77c3c7a0cfe4783b_1780312825.jpg', '2026-06-01', '2026-06-04', '{\"address\":\"55 L\\u00ea L\\u1ee3i, P.Kon Tum\",\"time\":\"09:00 - 22:00\",\"phone\":\"086.666.7581\"}', '{\"items\":\"C\\u00e0 ph\\u00ea \\u0111en S\\r\\nC\\u00e0 ph\\u00ea mu\\u1ed1i S\\r\\nC\\u00e0 ph\\u00ea s\\u1eefa S\\r\\nMatcha Nh\\u1eadt\\/\\u0110\\u00e0i S\\r\\nTr\\u00e0 nh\\u00e3n l\\u00e0i\",\"image\":\"\"}', 'unused', NULL, '2026-06-01 11:30:19'),
(64, 'FREEMC22N5R3Q', 'Mộc Coffee', 'TẶNG 1 LY NƯỚC', '8c42600372f4c596_1780315162.jpg', '2026-06-01', '2026-06-04', '{\"address\":\"371 Tr\\u1ea7n Nh\\u00e2n T\\u00f4ng, P. Kon Tum\",\"time\":\"06:00 - 22:00\",\"phone\":\"0379 526 262\"}', '{\"items\":\"Sinh t\\u1ed1 b\\u01a1 d\\u1eeba non:30 \\r\\nSinh t\\u1ed1 xo\\u00e0i :30\\r\\n\\r\\nTr\\u00e0 nh\\u00e3n kh\\u00fac b\\u1ea1ch :35\\r\\nTr\\u00e0 m\\u0103ng c\\u1ee5t :30( theo m\\u00f9a)\\r\\nTr\\u00e0 m\\u0103ng c\\u1ee5t chanh leo:30( theo m\\u00f9a) \\r\\nTr\\u00e0 m\\u00e3ng c\\u1ea7u chanh leo:30\\r\\nTr\\u00e0 m\\u00e3ng c\\u1ea7u atiso:30\\r\\nTr\\u00e0 thanh xo\\u00e0i nhi\\u1ec7t \\u0111\\u1edbi :30\\r\\nTr\\u00e0 cam x\\u00ed mu\\u1ed9i: 30 ( hot) \\r\\nTr\\u00e0 t\\u1eafc x\\u00ed mu\\u1ed9i: 25\\r\\nTr\\u00e0 \\u0111\\u00e0o:30\\r\\nTr\\u00e0 d\\u00e2u t\\u00e2y:30\\r\\nTr\\u00e0 m\\u00e3ng c\\u1ea7u:30\\r\\nTr\\u00e0 nho baby:30\\r\\nTr\\u00e0 l\\u1ef1u:30( theo m\\u00f9a) \\r\\nTr\\u00e0 nhi\\u00eat \\u0111\\u1edbi trai c\\u00e2y t\\u01b0\\u01a1i:30\\r\\nTr\\u00e0 d\\u00e2u t\\u1eb1m 30\\r\\nTr\\u00e0 d\\u00e2u t\\u1eb1m mix atiso:30\\r\\nTr\\u00e0 m\\u00e3ng c\\u1ea7u mix d\\u00e2u t\\u1eb1m :30\\r\\nTr\\u00e0 m\\u00e3ng c\\u1ea7u me :30\\r\\nTr\\u00e0 m\\u1eadn :30\\r\\nTr\\u00e0 atiso :30\\r\\nTr\\u00e0 \\u1ed5i:30\\r\\nTr\\u00e0 me:30\\r\\nTr\\u00e0 h\\u1ea1t sen:30\\r\\nTr\\u00e0 chanh x\\u00ed mu\\u1ed9i:25\\r\\nTr\\u00e0 lipton \\u0111\\u00e1:30\\r\\nTr\\u00e0 qu\\u00fdt x\\u00ed mu\\u1ed9i :30\\r\\nTr\\u00e0 nh\\u00e3n th\\u1ea3o m\\u1ed9c :30\\r\\nTr\\u00e0 v\\u1ea3i:30\\r\\nTr\\u00e0 sen nh\\u00e3n:35\\r\\n\\r\\n\\r\\nTr\\u00e0 hoa c\\u00fac h\\u1ea3i ph\\u00f2ng:30\\r\\nTr\\u00e0 lipton:30\\r\\nTr\\u00e0 g\\u1eebng:30\\r\\nCa cao n\\u00f3ng:25\\r\\nB\\u1ea1c x\\u1ec9u n\\u00f3ng 25\\r\\nTr\\u00e0 chanh m\\u1eadt ong x\\u00ed mu\\u1ed9i  :25\\r\\nS\\u1eefa n\\u00f3ng 20\\r\\n\\r\\n\\r\\nS\\u1eefa chua d\\u00e2u t\\u00e2y h\\u1ea1t chia:30\\r\\nS\\u1eefa chua vi\\u00eat qu\\u1ea5t:30\\r\\nS chua \\u0111\\u00e0o:30\\r\\nS chua nho:30\\r\\nS chua kiwi:30\\r\\nSua chua h\\u1ea1c \\u0111\\u00e1t mix th\\u01a1m:30\\r\\nS\\u01b0a chua \\u0111\\u00e1c d\\u00e2u t\\u1eb1m:30\\r\\nS\\u1eefa chua chanh leo:30 \\r\\nS\\u1eefa chua d\\u00e2u t\\u1eb1m :30\\r\\nS\\u01b0a chua xo\\u00e0i:30\\r\\nS\\u1eefa chua c\\u1ed1m d\\u1ebbo :30 \\r\\nS\\u1eefa chua \\u0111\\u00e1 28\\r\\nS\\u1eefa chua h\\u1ee7 :10\\r\\nS\\u1eefa chua kem phomai +d\\u00e2u s\\u1ea5y :35 ( hot) \\r\\n\\r\\nCf phin : \\u0111en 17. Cf  s\\u1eefa 18 \\r\\nCf m\\u00e1y \\u0111en 20.s\\u1eefa:20\\r\\nCf mu\\u1ed1i:25\\r\\nCf kem d\\u1ebbo bu\\u00f4n m\\u00ea:28\\r\\nCf kem tr\\u1ee9ng:28\\r\\nCf caramen kem mu\\u1ed1i 25\\r\\nB\\u1ea1c x\\u1ec9u:25\\r\\nS\\u1eefa t\\u01b0\\u01a1i s\\u01b0\\u01a1ng s\\u00e1o cf h\\u1ea1t chia:30\\r\\nS\\u1eefa t\\u01b0\\u01a1i tr\\u00e2n ch\\u00e2u \\u0111\\u01b0\\u1eddng \\u0111en: 28\\r\\nCa cao \\u0111\\u00e1 28\\r\\nPhin \\u0111i choco k\\u00e8m th\\u1ea1ch :30\\r\\n \\r\\nMatcha latte: 30\\r\\nMatcha latte kem mu\\u1ed1i :30\\r\\n\\r\\nTr\\u00e0 s\\u1eefa truy\\u1ec1n th\\u1ed1ng:30 k\\u00e8m topping size  \\r\\nTr\\u00e0 s\\u1eefa th\\u00e1i \\u0111\\u1ecf 30\\r\\nTr\\u00e0 s\\u1eefa th\\u00e1i xanh 30\\r\\nTr\\u00e0 s\\u1eefa  khoai m\\u00f4n :30\\r\\nTr\\u00e0 s\\u1eefa c\\u1ed1m:30\\r\\nTr\\u00e0 s\\u1eefa olong :30\\r\\n\\r\\nN\\u01b0\\u1edbc \\u00e9p d\\u01b0a h\\u1ea5u:30\\r\\nN\\u01b0\\u1edbc \\u00e9p cam t\\u00e1o:30\\r\\nN\\u01b0\\u1edbc \\u00e9p cam:30\\r\\nN\\u01b0\\u1edbc \\u00e9p \\u1ed5i 30\\r\\nN\\u01b0\\u1edbc \\u00e9p t\\u00e1o mix th\\u01a1m :30\\r\\nN\\u01b0\\u1edbc \\u00e9p th\\u01a1m :30\\r\\nN\\u01b0\\u1edbc \\u00e9p d\\u01b0a h\\u1ea5u mix \\u1ed5i :30\\r\\nN\\u01b0\\u1edbc \\u00e9p cam th\\u01a1m :30\",\"image\":\"\"}', 'unused', NULL, '2026-06-01 11:59:28'),
(65, 'FREECC22WZU0E', 'Chip Chip Coffee KonTum', 'TẶNG 1 LY NƯỚC', 'f1aae998117567e0_1780314703.jpg', '2026-06-01', '2026-07-31', '{\"address\":\"32 Nguy\\u1ec5n \\u0110\\u00ecnh Chi\\u1ec3u, P.Kon Tum\",\"time\":\"06h00 - 21h00\",\"phone\":\"0961964858\"}', '{\"note\":\"\",\"items\":\"\",\"image\":\"1fe727c80696ad7a_1780314459.jpg\"}', 'unused', NULL, '2026-06-01 13:05:32'),
(78, 'CK22V7DRI', 'Cái Tiệm Cà Kê', 'TẶNG 1 LY NƯỚC', '4d0ebe74d8818150_1780312380.jpg', '2026-06-02', '2026-06-05', '{\"address\":\"407 U R\\u00ea, P.Kon Tum\",\"time\":\"09:30 - 21:30\",\"phone\":\"0372113667\"}', '{\"note\":\"\",\"items\":\"\\u00c1P D\\u1ee4NG T\\u1ea4T C\\u1ea2 SIZE M\\r\\nTr\\u00e0 d\\u00e2u\\r\\nTr\\u00e0 t\\u00e1o\\r\\nTr\\u00e0 \\u0111\\u00e0o\\r\\nTr\\u00e0 v\\u1ea3i\\r\\nTr\\u00e0 nho\\r\\nTr\\u00e0 \\u1ed5i h\\u1ed3ng\\r\\nTr\\u00e0 s\\u1eefa (truy\\u1ec1n th\\u1ed1ng, th\\u00e1i xanh, th\\u00e1i \\u0111\\u1ecf)\\r\\nTr\\u00e0 s\\u1eefa C\\u00e0 k\\u00ea\\r\\nTr\\u00e0 s\\u1eefa Tr\\u00e2n ch\\u00e2u ho\\u00e0ng kim mini\\r\\nTr\\u00e0 s\\u1eefa Phomai vi\\u00ean \\r\\nTr\\u00e0 s\\u1eefa Phomai m\\u1eb7n\\r\\nTr\\u00e0 s\\u1eefa Cheeseball \\r\\nTr\\u00e0 s\\u1eefa Phomai t\\u01b0\\u01a1i\\r\\nTr\\u00e0 s\\u1eefa Full topping \\r\\nTr\\u00e0 s\\u1eefa Phomai mix\",\"image\":\"\"}', 'used', '2026-06-04 12:40:15', '2026-06-04 12:38:44');

-- --------------------------------------------------------

--
-- Table structure for table `given_vouchers`
--

CREATE TABLE `given_vouchers` (
  `id` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  `given_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(11) NOT NULL,
  `migration` varchar(100) NOT NULL,
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `applied_at`) VALUES
(1, '001_create_core_tables', '2026-04-23 12:50:33'),
(2, '002_add_indexes', '2026-04-23 12:50:33'),
(3, '003_create_audit_logs', '2026-04-23 12:50:33'),
(4, '004_user_sessions', '2026-06-01 17:26:59'),
(5, '005_settings', '2026-06-01 18:19:25'),
(6, '006_sw_cache_version', '2026-06-01 18:40:14');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `key` varchar(100) NOT NULL,
  `value` text NOT NULL DEFAULT '',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`key`, `value`, `updated_at`) VALUES
('give_message', 'Gửi tặng bạn voucher đổi đồ uống đợt 22 🫰.\n        VUI LÒNG ĐỌC KỸ HƯỚNG DẪN SỬ DỤNG có trong voucher.\n        😍Mọi thắc mắc xin nhắn tin cho Kon Tum + để được hỗ trợ nhanh nhất.\n        ☺️Nếu được bạn hãy chụp 1 tấm check-in khi đến nhận đồ uống và gửi về Kon Tum + nhé. Cảm ơn bạn đã đồng hành.\n        Link voucher 👉', '2026-06-01 19:16:04'),
('sw_cache_version', '25', '2026-06-04 03:19:37');

-- --------------------------------------------------------

--
-- Table structure for table `taken_vouchers`
--

CREATE TABLE `taken_vouchers` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `taken_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taken_voucher_items`
--

CREATE TABLE `taken_voucher_items` (
  `id` int(11) NOT NULL,
  `voucher_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `taken_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('available','given','returned') NOT NULL DEFAULT 'available',
  `returned_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `taken_voucher_items`
--

INSERT INTO `taken_voucher_items` (`id`, `voucher_id`, `campaign_id`, `taken_at`, `status`, `returned_at`) VALUES
(31, 638, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(32, 637, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(33, 635, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(34, 636, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(35, 626, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(36, 627, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(37, 628, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(38, 629, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(39, 630, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(40, 631, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(41, 632, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(42, 634, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(43, 625, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(44, 622, 5, '2026-06-03 18:01:20', 'returned', '2026-06-03 18:10:19'),
(45, 623, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(46, 618, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(47, 619, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(48, 616, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(49, 617, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(50, 615, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50'),
(51, 639, 5, '2026-06-03 18:01:20', 'returned', '2026-06-04 03:20:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(4, 'admin', '$2y$10$Yu6tUEJbydl2kZmQB/mrJe4nLOVV5PaNNfy.eqqnWb.R.NhO7z9tu', '2026-03-27 08:30:57'),
(3, 'mon', '$2y$10$P7XpW9vG4kR2D6zN5mF1eO8jU0lH3sT9qA4cB2V1iY6zW5xK0uS3.', '2026-03-27 08:06:08');

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE `user_sessions` (
  `id` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  `device` varchar(200) DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `last_seen` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_sessions`
--

INSERT INTO `user_sessions` (`id`, `user_id`, `device`, `ip`, `last_seen`, `created_at`) VALUES
('1b0c0a63e82ddecee43580778960ff3b5ebfe04b559c917cf3784f5ffd230e23', 4, 'Mobile - Android', '42.118.186.160', '2026-06-05 01:21:36', '2026-06-01 17:27:07'),
('52eab7abe399232d60d97fb42eac8082826730fd9c7b537740197d09314a9032', 4, 'Desktop - Macintosh', '42.118.186.160', '2026-06-01 17:33:01', '2026-06-01 17:33:01'),
('08ea3fc3b14e852c5215d09018139a047a0145592769a82367754546526c3a00', 4, 'Desktop - Macintosh', '42.118.186.160', '2026-06-01 17:33:46', '2026-06-01 17:33:46'),
('b69eb0166d6a52109f0a3cad7d62abe227146409680d9d5b86475d033a51352c', 4, 'Desktop - Macintosh', '42.118.186.160', '2026-06-01 17:49:27', '2026-06-01 17:45:26'),
('0968ee4949edc275705c0e8383df3079737b4e78ba1c25c5b47585d959169e0c', 4, 'Desktop - Macintosh', '42.118.186.160', '2026-06-01 17:49:40', '2026-06-01 17:49:40'),
('5c0fca4a40e64f428acdd10c136cd8d1aa09107941c569f4867c2de59ce03b12', 4, 'Desktop - Macintosh', '42.118.186.160', '2026-06-01 18:11:04', '2026-06-01 17:49:44'),
('61fb7b6447606b4cc9ed71eb054988954b1263166a76de2cd9714f2186155ce6', 4, 'Mobile - iPhone', '42.118.186.160', '2026-06-02 03:51:57', '2026-06-01 17:51:28'),
('5eb1927bb385ed7c4542cd94df432c1950ff23694b55ec52f7471e76a9f378d9', 4, 'Mobile - iPhone', '42.118.186.160', '2026-06-11 18:32:05', '2026-06-01 18:02:03'),
('3e2fd6fee304c02488d7fa21274bc06a348560c1f95d76ccbd9939a5cf653952', 4, 'Mobile - iPhone', '42.118.186.160', '2026-07-01 15:41:39', '2026-06-01 18:04:22'),
('82be6a477113649fcc1b157543342cb6e85cbc9dbe31d2547e08cdb78f72f085', 4, 'Desktop - Macintosh', '42.118.186.160', '2026-06-01 19:19:50', '2026-06-01 18:23:24'),
('74607accce4fdda9af35e3cb68ea222a06218716dc70fce4c14fbb079aba6c8d', 4, 'Mobile - iPhone', '171.252.163.39', '2026-06-03 15:15:28', '2026-06-03 15:10:29'),
('d2f592b5d23bfb10be6c3086dc6d00f6f11e9542be87e4396254aed9ab09c25b', 4, 'Desktop - Macintosh', '171.252.163.39', '2026-06-03 17:27:46', '2026-06-03 17:27:44');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `code` varchar(5) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` enum('unused','used','expired') DEFAULT 'unused',
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `campaign_id`, `code`, `logo`, `status`, `used_at`, `created_at`) VALUES
(1, 1, 'ABC12', NULL, 'unused', NULL, '2025-10-25 17:41:13'),
(2, 1, 'XYZ34', NULL, 'unused', NULL, '2025-10-25 17:41:13'),
(3, 1, 'DEF56', NULL, 'unused', NULL, '2025-10-25 17:41:13'),
(4, 1, 'GHI78', NULL, 'unused', NULL, '2025-10-25 17:41:13'),
(5, 1, 'JKL90', NULL, 'unused', NULL, '2025-10-25 17:41:13'),
(6, 2, 'ZI184', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(7, 2, 'IOHQ0', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(8, 2, 'WVXU0', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(9, 2, '5SCME', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(10, 2, '5A6FQ', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(11, 2, '947ZU', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(12, 2, 'IJ3B1', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(13, 2, 'ZYE5P', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(14, 2, 'VA7HJ', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(15, 2, 'SD0DR', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(16, 2, 'LWUJ5', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(17, 2, 'G9U56', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(18, 2, 'OGMDG', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(19, 2, 'EIN4T', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(20, 2, 'WL150', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(21, 2, 'VTNRN', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(22, 2, '4JQNN', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(23, 2, '846BM', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(24, 2, '26GJ6', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(25, 2, 'SAF50', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(26, 2, 'MR28P', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(27, 2, 'J8UAF', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(28, 2, 'Q4701', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(29, 2, '2PTPN', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(30, 2, '037U2', NULL, 'unused', NULL, '2025-10-25 17:47:40'),
(105, 3, '7YSY8', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(104, 3, 'WCO3M', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(103, 3, 'QID8X', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(102, 3, 'M52V3', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(101, 3, 'V3207', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(100, 3, 'BDUXW', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(99, 3, '4IN2Z', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(98, 3, 'CQ4K5', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(97, 3, '5KTP3', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(96, 3, '6AVG1', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(95, 3, 'GHBKC', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(94, 3, 'HGW73', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(93, 3, 'RHBMD', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(92, 3, 'V3609', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(91, 3, 'MQG0E', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(90, 3, '0G7WU', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(89, 3, 'ZV04Y', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(88, 3, 'OVJS3', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(87, 3, '25ZD1', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(86, 3, 'JDLBS', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(85, 3, 'SKNVT', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(84, 3, 'KGYJW', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(83, 3, '3EHSC', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(82, 3, 'VE9GT', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(81, 3, '19ZVI', NULL, 'unused', NULL, '2025-10-26 16:03:58'),
(106, 4, 'CXURG', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(107, 4, '2XP5P', NULL, 'expired', NULL, '2025-10-28 08:00:18'),
(108, 4, 'Q0II8', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(109, 4, 'QRLWY', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(110, 4, 'KXJDY', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(111, 4, '514LZ', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(112, 4, 'L2QEV', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(113, 4, 'FEZ09', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(114, 4, '9RVVS', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(115, 4, 'ZNLFG', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(116, 4, 'MO8K5', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(117, 4, 'INKSF', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(118, 4, '3ERAN', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(119, 4, 'QL56I', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(120, 4, 'I1UDF', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(121, 4, 'BE91G', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(122, 4, 'AUSE3', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(123, 4, 'U64G9', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(124, 4, 'OT2CX', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(125, 4, 'MJBXS', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(126, 4, '5X64O', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(127, 4, 'D9JBC', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(128, 4, 'I5ZGS', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(129, 4, '9BTY4', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(130, 4, 'K66LS', NULL, 'unused', NULL, '2025-10-28 08:00:18'),
(638, 5, 'GNUHH', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(637, 5, 'ZNLFG', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(635, 5, 'U64G9', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(636, 5, 'FEZ09', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(626, 5, 'OT2CX', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(627, 5, '9RVVS', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(628, 5, 'AUSE3', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(629, 5, 'Q0II8', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(630, 5, 'BE91G', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(631, 5, 'QL56I', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(632, 5, 'CXURG', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(633, 5, 'QRLWY', NULL, 'expired', NULL, '2026-01-27 15:15:14'),
(634, 5, 'D9JBC', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(624, 5, 'NAUX1', NULL, 'expired', NULL, '2026-01-27 15:15:14'),
(625, 5, '9BTY4', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(622, 5, 'MO8K5', NULL, 'expired', NULL, '2026-01-27 15:15:14'),
(623, 5, '5X64O', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(620, 5, 'MJBXS', NULL, 'expired', NULL, '2026-01-27 15:15:14'),
(621, 5, '514LZ', NULL, 'expired', NULL, '2026-01-27 15:15:14'),
(618, 5, 'L2QEV', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(619, 5, '3ERAN', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(616, 5, 'KXJDY', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(617, 5, '2XP5P', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(614, 5, 'INKSF', NULL, 'expired', NULL, '2026-01-27 15:15:14'),
(615, 5, 'K66LS', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(330, 6, 'LDF76', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(329, 6, '1YDJB', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(328, 6, '914NF', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(327, 6, '1NSUL', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(326, 6, 'ABB4P', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(325, 6, 'GSILO', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(324, 6, 'W50R3', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(323, 6, '96CS7', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(322, 6, '3ALUH', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(321, 6, '1D58C', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(320, 6, 'F4GMU', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(319, 6, 'SU2KS', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(318, 6, '6ZL31', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(317, 6, 'UFXV1', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(316, 6, 'OZ3E7', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(315, 6, 'PJKYI', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(314, 6, 'Z2T91', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(313, 6, 'FMPBP', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(312, 6, 'KHVS9', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(311, 6, 'K4EPL', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(310, 6, 'ZOBHK', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(309, 6, '8GG3O', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(308, 6, 'Q5OH3', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(307, 6, 'FWG0X', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(306, 6, 'D3MBX', NULL, 'unused', NULL, '2025-10-28 08:43:08'),
(331, 7, '84TB2', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(332, 7, 'CHJ63', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(333, 7, 'LC9RA', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(334, 7, 'USK6W', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(335, 7, '6HJAU', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(336, 7, '3WAUW', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(337, 7, 'HF5C1', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(338, 7, 'C7F9Q', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(339, 7, 'ZEGK9', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(340, 7, '2UBX2', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(341, 7, 'Z3520', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(342, 7, 'RF6RQ', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(343, 7, 'ZK7BB', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(344, 7, 'NN82B', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(345, 7, 'C4LRR', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(346, 7, '6Q3H5', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(347, 7, 'E0W0X', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(348, 7, 'VRROI', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(349, 7, '7QZ2V', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(350, 7, 'EEH45', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(351, 7, 'JWCGK', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(352, 7, 'BD3TL', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(353, 7, 'F8O5J', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(354, 7, '2Q75R', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(355, 7, '7QD7Q', NULL, 'unused', NULL, '2025-10-28 08:45:17'),
(356, 8, '4G1WF', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(357, 8, '5FOZD', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(358, 8, 'PK8W5', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(359, 8, '9GXZN', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(360, 8, '2LSGW', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(361, 8, 'BWW67', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(362, 8, 'IZ802', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(363, 8, '570O0', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(364, 8, 'WVESY', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(365, 8, 'ME376', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(366, 8, '98Y1R', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(367, 8, '2Z134', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(368, 8, '6GN7I', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(369, 8, 'II12X', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(370, 8, 'UIK3K', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(371, 8, '9NTES', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(372, 8, '1YGDM', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(373, 8, 'OAZLZ', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(374, 8, 'P8VL8', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(375, 8, 'WATGD', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(376, 8, 'YOQ9Q', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(377, 8, 'YYQ6T', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(378, 8, 'FYKZE', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(379, 8, 'ABUZ1', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(380, 8, 'QEFOG', NULL, 'unused', NULL, '2025-10-28 09:05:31'),
(406, 9, 'PK2NB', NULL, 'unused', NULL, '2025-10-28 09:21:52'),
(407, 9, '36NCP', NULL, 'expired', NULL, '2025-10-28 09:21:52'),
(408, 9, 'QWIPG', NULL, 'unused', NULL, '2025-10-28 09:21:52'),
(409, 9, '3VFO3', NULL, 'unused', NULL, '2025-10-28 09:21:52'),
(410, 9, 'V2BT7', NULL, 'unused', NULL, '2025-10-28 09:21:52'),
(411, 9, '5TKBH', NULL, 'unused', NULL, '2025-10-28 09:21:52'),
(412, 9, 'YYGSU', NULL, 'unused', NULL, '2025-10-28 09:21:52'),
(413, 9, '5VFRP', NULL, 'unused', NULL, '2025-10-28 09:21:52'),
(414, 9, 'ZKTVW', NULL, 'used', '2025-10-31 08:54:38', '2025-10-28 09:21:52'),
(415, 9, '63TXV', NULL, 'expired', NULL, '2025-10-28 09:21:52'),
(416, 9, '7UNWL', NULL, 'unused', NULL, '2025-10-28 09:21:52'),
(417, 9, '7ZSGJ', NULL, 'unused', NULL, '2025-10-28 09:21:52'),
(418, 9, '8I43O', NULL, 'used', '2025-10-30 03:44:28', '2025-10-28 09:21:52'),
(419, 9, 'AEZIX', NULL, 'unused', NULL, '2025-10-28 09:21:52'),
(420, 9, 'BQ7RN', NULL, 'unused', NULL, '2025-10-28 09:21:52'),
(421, 9, 'EVGQM', NULL, 'used', '2025-10-29 10:10:44', '2025-10-28 09:21:52'),
(422, 9, 'J4M09', NULL, 'unused', NULL, '2025-10-28 09:21:52'),
(423, 9, 'JFQF1', NULL, 'expired', NULL, '2025-10-28 09:21:52'),
(424, 9, '013BO', NULL, 'unused', NULL, '2025-10-28 09:21:52'),
(425, 9, 'JJAFQ', NULL, 'expired', NULL, '2025-10-28 09:21:52'),
(426, 9, '0Q0EW', NULL, 'unused', NULL, '2025-10-28 09:21:52'),
(427, 9, 'K96W4', NULL, 'used', '2025-10-31 05:21:54', '2025-10-28 09:21:52'),
(428, 9, '2FAKY', NULL, 'used', '2025-10-30 12:27:17', '2025-10-28 09:21:52'),
(429, 9, 'MQ3E3', NULL, 'used', '2025-10-30 09:23:14', '2025-10-28 09:21:52'),
(430, 9, '2YRCZ', NULL, 'unused', NULL, '2025-10-28 09:21:52'),
(579, 10, 'TFMGX', NULL, 'used', '2025-10-29 09:44:45', '2025-10-28 19:20:28'),
(578, 10, '5GHKK', NULL, 'used', '2025-10-31 10:29:01', '2025-10-28 19:20:28'),
(561, 10, '4W6RR', NULL, 'expired', NULL, '2025-10-28 19:20:28'),
(562, 10, 'HLGZ9', NULL, 'used', '2025-10-31 08:53:30', '2025-10-28 19:20:28'),
(563, 10, 'ZVAIS', NULL, 'used', '2025-10-31 11:05:41', '2025-10-28 19:20:28'),
(564, 10, 'NBIY8', NULL, 'used', '2025-10-29 12:44:06', '2025-10-28 19:20:28'),
(565, 10, '9ZQRM', NULL, 'used', '2025-10-29 10:03:58', '2025-10-28 19:20:28'),
(566, 10, 'F23WP', NULL, 'used', '2025-10-29 12:36:24', '2025-10-28 19:20:28'),
(567, 10, 'N28IG', NULL, 'used', '2025-10-30 12:31:35', '2025-10-28 19:20:28'),
(568, 10, 'HLFDY', NULL, 'used', '2025-10-30 12:28:18', '2025-10-28 19:20:28'),
(569, 10, 'YWCA0', NULL, 'used', '2025-10-31 12:02:05', '2025-10-28 19:20:28'),
(570, 10, 'EBEAW', NULL, 'used', '2025-10-29 09:47:37', '2025-10-28 19:20:28'),
(571, 10, '24S5R', NULL, 'used', '2025-10-29 05:54:32', '2025-10-28 19:20:28'),
(572, 10, 'B4869', NULL, 'used', '2025-10-29 10:40:15', '2025-10-28 19:20:28'),
(573, 10, '4OWEV', NULL, 'used', '2025-10-30 10:51:26', '2025-10-28 19:20:28'),
(574, 10, 'B4R5O', NULL, 'used', '2025-10-29 10:36:25', '2025-10-28 19:20:28'),
(575, 10, 'QS96G', NULL, 'used', '2025-10-31 12:19:46', '2025-10-28 19:20:28'),
(576, 10, 'IB2ET', NULL, 'used', '2025-10-29 09:37:33', '2025-10-28 19:20:28'),
(577, 10, 'EOEJR', NULL, 'used', '2025-10-31 12:48:40', '2025-10-28 19:20:28'),
(522, 11, '2M0N0', NULL, 'expired', NULL, '2025-10-28 09:29:49'),
(523, 11, 'UUNB6', NULL, 'expired', NULL, '2025-10-28 09:29:49'),
(524, 11, '3BOTK', NULL, 'used', '2025-10-31 10:24:00', '2025-10-28 09:29:49'),
(525, 11, 'V0RRA', NULL, 'used', '2025-10-31 05:37:24', '2025-10-28 09:29:49'),
(526, 11, '4N7T5', NULL, 'unused', NULL, '2025-10-28 09:29:49'),
(527, 11, 'WDL3K', NULL, 'used', '2025-10-31 09:58:54', '2025-10-28 09:29:49'),
(528, 11, '64ETG', NULL, 'unused', NULL, '2025-10-28 09:29:49'),
(529, 11, '87POT', NULL, 'used', '2025-10-29 06:19:04', '2025-10-28 09:29:49'),
(506, 11, '9TPOL', NULL, 'expired', NULL, '2025-10-28 09:29:49'),
(507, 11, 'Z15E8', NULL, 'used', '2025-10-31 11:44:38', '2025-10-28 09:29:49'),
(508, 11, 'D366F', NULL, 'used', '2025-10-30 10:57:38', '2025-10-28 09:29:49'),
(509, 11, 'ZJL9C', NULL, 'unused', NULL, '2025-10-28 09:29:49'),
(510, 11, 'DU63G', NULL, 'used', '2025-10-29 08:29:49', '2025-10-28 09:29:49'),
(511, 11, 'ZSE5G', NULL, 'used', '2025-10-31 04:18:36', '2025-10-28 09:29:49'),
(512, 11, 'GPNRF', NULL, 'unused', NULL, '2025-10-28 09:29:49'),
(513, 11, 'ITX48', NULL, 'used', '2025-10-29 04:22:22', '2025-10-28 09:29:49'),
(514, 11, 'NZ2QN', NULL, 'used', '2025-10-31 06:19:59', '2025-10-28 09:29:49'),
(515, 11, 'PQXWW', NULL, 'used', '2025-10-29 09:59:14', '2025-10-28 09:29:49'),
(516, 11, 'RTAFG', NULL, 'unused', NULL, '2025-10-28 09:29:49'),
(517, 11, 'SO123', NULL, 'used', '2025-10-31 12:00:30', '2025-10-28 09:29:49'),
(518, 11, 'TOBYY', NULL, 'used', '2025-10-29 09:28:31', '2025-10-28 09:29:49'),
(519, 11, 'TT00A', NULL, 'used', '2025-10-30 07:38:35', '2025-10-28 09:29:49'),
(520, 11, '10RLA', NULL, 'expired', NULL, '2025-10-28 09:29:49'),
(521, 11, 'UPNO8', NULL, 'used', '2025-10-29 05:38:22', '2025-10-28 09:29:49'),
(530, 11, 'X0XBU', NULL, 'used', '2025-10-29 10:25:55', '2025-10-28 09:29:49'),
(531, 12, 'TEST', NULL, 'unused', NULL, '2025-10-28 10:16:02'),
(532, 13, 'TEST', NULL, 'unused', NULL, '2025-10-28 10:17:13'),
(533, 14, 'TEST', NULL, 'unused', NULL, '2025-10-28 10:18:21'),
(534, 15, 'TEST', NULL, 'used', '2025-10-28 11:48:15', '2025-10-28 10:19:27'),
(639, 5, 'I1UDF', NULL, 'unused', NULL, '2026-01-27 15:15:14'),
(580, 10, 'PLRZK', NULL, 'used', '2025-10-31 05:03:27', '2025-10-28 19:20:28'),
(581, 10, 'EEK3E', NULL, 'expired', NULL, '2025-10-28 19:20:28'),
(582, 10, 'BCVVC', NULL, 'used', '2025-10-30 09:33:33', '2025-10-28 19:20:28'),
(583, 10, '5OA2A', NULL, 'unused', NULL, '2025-10-28 19:20:28'),
(584, 10, 'XORVC', NULL, 'used', '2025-10-30 06:48:28', '2025-10-28 19:20:28'),
(585, 10, 'PB3QQ', NULL, 'used', '2025-10-30 09:43:36', '2025-10-28 19:20:28'),
(640, 5, 'I5ZGS', NULL, 'expired', NULL, '2026-01-27 15:15:14'),
(641, 16, 'PTCWR', NULL, 'used', '2026-01-29 08:40:34', '2026-01-28 08:30:27'),
(642, 16, '89S57', NULL, 'expired', NULL, '2026-01-28 08:30:27'),
(643, 16, 'E1QCA', NULL, 'used', '2026-01-29 11:57:19', '2026-01-28 08:30:27'),
(644, 16, 'IT7E8', NULL, 'used', '2026-01-29 10:26:54', '2026-01-28 08:30:27'),
(645, 16, 'SZLT9', NULL, 'used', '2026-01-30 08:21:54', '2026-01-28 08:30:27'),
(646, 16, 'T0X30', NULL, 'used', '2026-01-31 11:32:22', '2026-01-28 08:30:27'),
(647, 16, '7JB16', NULL, 'used', '2026-01-29 05:20:55', '2026-01-28 08:30:27'),
(648, 16, '7B9UT', NULL, 'used', '2026-01-31 11:37:32', '2026-01-28 08:30:27'),
(649, 16, 'JMEK8', NULL, 'used', '2026-01-31 07:22:15', '2026-01-28 08:30:27'),
(650, 16, '128PJ', NULL, 'used', '2026-01-29 05:20:27', '2026-01-28 08:30:27'),
(651, 16, 'NSBMD', NULL, 'used', '2026-01-29 09:41:45', '2026-01-28 08:30:27'),
(652, 16, '08PN3', NULL, 'used', '2026-01-30 04:09:43', '2026-01-28 08:30:27'),
(653, 16, 'OMKVI', NULL, 'used', '2026-01-31 12:09:49', '2026-01-28 08:30:27'),
(654, 16, 'B0ES2', NULL, 'used', '2026-01-31 08:52:55', '2026-01-28 08:30:27'),
(655, 16, 'VLMF8', NULL, 'used', '2026-01-31 10:11:30', '2026-01-28 08:30:27'),
(656, 16, 'NDXB4', NULL, 'unused', NULL, '2026-01-28 08:30:27'),
(657, 16, 'TO77M', NULL, 'used', '2026-01-31 11:15:12', '2026-01-28 08:30:27'),
(658, 16, '776T7', NULL, 'used', '2026-01-29 08:16:52', '2026-01-28 08:30:27'),
(659, 16, 'ZT6FX', NULL, 'used', '2026-01-29 08:57:04', '2026-01-28 08:30:27'),
(660, 16, '59S9U', NULL, 'used', '2026-01-31 11:44:10', '2026-01-28 08:30:27'),
(661, 16, 'PGWQH', NULL, 'used', '2026-01-29 09:32:38', '2026-01-28 08:30:27'),
(662, 16, 'IPQ9S', NULL, 'used', '2026-01-30 10:20:16', '2026-01-28 08:30:27'),
(663, 16, 'OTYG5', NULL, 'expired', NULL, '2026-01-28 08:30:27'),
(664, 16, '31KJN', NULL, 'unused', NULL, '2026-01-28 08:30:27'),
(665, 16, 'MHMK1', NULL, 'used', '2026-01-31 12:27:40', '2026-01-28 08:30:27'),
(666, 17, '5ZR4M', NULL, 'used', '2026-01-29 04:37:50', '2026-01-28 08:35:04'),
(667, 17, 'N4KUQ', NULL, 'used', '2026-01-31 11:05:45', '2026-01-28 08:35:04'),
(668, 17, 'KKXGI', NULL, 'used', '2026-01-29 12:12:37', '2026-01-28 08:35:04'),
(669, 17, 'K6WW2', NULL, 'used', '2026-01-29 12:31:12', '2026-01-28 08:35:04'),
(670, 17, '0F278', NULL, 'used', '2026-01-31 08:31:38', '2026-01-28 08:35:04'),
(671, 17, 'XDMW4', NULL, 'used', '2026-01-31 09:22:56', '2026-01-28 08:35:04'),
(672, 17, '8QRRV', NULL, 'used', '2026-01-31 09:48:52', '2026-01-28 08:35:04'),
(673, 17, '11F76', NULL, 'used', '2026-01-29 12:04:14', '2026-01-28 08:35:04'),
(674, 17, '4NFRD', NULL, 'used', '2026-01-31 09:19:23', '2026-01-28 08:35:04'),
(675, 17, '9931V', NULL, 'used', '2026-01-30 09:30:55', '2026-01-28 08:35:04'),
(676, 17, 'ZPOCZ', NULL, 'used', '2026-01-30 13:10:57', '2026-01-28 08:35:04'),
(677, 17, 'S5GR9', NULL, 'used', '2026-01-31 09:56:21', '2026-01-28 08:35:04'),
(678, 17, 'DNCIM', NULL, 'used', '2026-01-29 09:54:04', '2026-01-28 08:35:04'),
(679, 17, 'EOFC6', NULL, 'used', '2026-01-31 11:18:55', '2026-01-28 08:35:04'),
(680, 17, '8U349', NULL, 'expired', NULL, '2026-01-28 08:35:04'),
(681, 17, 'P0QJT', NULL, 'used', '2026-01-31 09:56:36', '2026-01-28 08:35:04'),
(682, 17, '7BUBY', NULL, 'used', '2026-01-31 08:38:34', '2026-01-28 08:35:04'),
(683, 17, 'TM8YN', NULL, 'used', '2026-01-30 07:32:25', '2026-01-28 08:35:04'),
(684, 17, 'H530P', NULL, 'used', '2026-01-31 08:32:24', '2026-01-28 08:35:04'),
(685, 17, 'ZPX8K', NULL, 'used', '2026-01-30 09:09:52', '2026-01-28 08:35:04'),
(686, 17, 'UH1IZ', NULL, 'used', '2026-01-29 04:41:50', '2026-01-28 08:35:04'),
(687, 17, '98G3U', NULL, 'used', '2026-01-30 08:33:00', '2026-01-28 08:35:04'),
(688, 17, 'DCNLX', NULL, 'expired', NULL, '2026-01-28 08:35:04'),
(689, 17, '67TLI', NULL, 'used', '2026-01-29 10:16:59', '2026-01-28 08:35:04'),
(690, 17, 'CIMY9', NULL, 'used', '2026-01-31 10:09:13', '2026-01-28 08:35:04'),
(691, 18, 'D775E', NULL, 'used', '2026-01-31 13:49:45', '2026-01-28 09:03:48'),
(692, 18, '52CEY', NULL, 'used', '2026-01-30 05:19:33', '2026-01-28 09:03:48'),
(693, 18, 'RLUWH', NULL, 'used', '2026-01-31 12:42:52', '2026-01-28 09:03:48'),
(694, 18, 'LZU25', NULL, 'used', '2026-01-30 10:17:07', '2026-01-28 09:03:48'),
(695, 18, '8G277', NULL, 'used', '2026-01-29 07:11:15', '2026-01-28 09:03:48'),
(696, 18, 'V99EM', NULL, 'used', '2026-01-31 13:44:06', '2026-01-28 09:03:48'),
(697, 18, 'NJNE2', NULL, 'used', '2026-01-29 12:30:37', '2026-01-28 09:03:48'),
(698, 18, 'U1NB2', NULL, 'used', '2026-01-31 09:31:07', '2026-01-28 09:03:48'),
(699, 18, 'T2QPK', NULL, 'used', '2026-01-31 11:20:33', '2026-01-28 09:03:48'),
(700, 18, 'RXASW', NULL, 'used', '2026-01-29 09:22:18', '2026-01-28 09:03:48'),
(701, 18, 'X7RAZ', NULL, 'used', '2026-01-29 08:34:50', '2026-01-28 09:03:48'),
(702, 18, 'LYHG1', NULL, 'used', '2026-01-31 12:19:09', '2026-01-28 09:03:48'),
(703, 18, 'UORDO', NULL, 'used', '2026-01-29 11:08:54', '2026-01-28 09:03:48'),
(704, 18, 'WNB34', NULL, 'used', '2026-01-31 13:59:30', '2026-01-28 09:03:48'),
(705, 18, 'YJWIS', NULL, 'used', '2026-01-30 09:45:18', '2026-01-28 09:03:48'),
(706, 18, '8Z0S9', NULL, 'used', '2026-01-31 07:50:51', '2026-01-28 09:03:48'),
(707, 18, 'N0Q2Q', NULL, 'used', '2026-01-31 14:02:47', '2026-01-28 09:03:48'),
(708, 18, 'D57E6', NULL, 'used', '2026-01-29 10:31:06', '2026-01-28 09:03:48'),
(709, 18, 'BJFKJ', NULL, 'used', '2026-01-29 10:51:41', '2026-01-28 09:03:48'),
(710, 18, 'NGS7V', NULL, 'used', '2026-01-31 12:56:23', '2026-01-28 09:03:48'),
(711, 18, 'ZW2XS', NULL, 'used', '2026-01-30 12:15:31', '2026-01-28 09:03:48'),
(712, 18, 'A525Q', NULL, 'used', '2026-01-30 11:15:13', '2026-01-28 09:03:48'),
(713, 18, 'Y6JOL', NULL, 'used', '2026-01-29 13:18:49', '2026-01-28 09:03:48'),
(714, 18, 'CMJ13', NULL, 'used', '2026-01-31 10:31:20', '2026-01-28 09:03:48'),
(715, 18, 'TNT7Y', NULL, 'used', '2026-01-29 07:25:47', '2026-01-28 09:03:48'),
(716, 19, 'GE5FI', NULL, 'expired', NULL, '2026-01-28 09:16:27'),
(717, 19, '4260A', NULL, 'used', '2026-01-31 11:00:08', '2026-01-28 09:16:27'),
(718, 19, 'Q980O', NULL, 'used', '2026-01-30 10:10:00', '2026-01-28 09:16:27'),
(719, 19, 'IBPYM', NULL, 'used', '2026-01-29 09:08:54', '2026-01-28 09:16:27'),
(720, 19, 'MZ6I9', NULL, 'used', '2026-01-30 07:17:28', '2026-01-28 09:16:27'),
(721, 19, 'X9L0R', NULL, 'used', '2026-01-31 09:21:48', '2026-01-28 09:16:27'),
(722, 19, 'Z3RQL', NULL, 'expired', NULL, '2026-01-28 09:16:27'),
(723, 19, 'N9F95', NULL, 'used', '2026-01-29 09:02:26', '2026-01-28 09:16:27'),
(724, 19, '5L45Z', NULL, 'used', '2026-01-31 12:55:14', '2026-01-28 09:16:27'),
(725, 19, '62GPL', NULL, 'used', '2026-01-30 09:43:24', '2026-01-28 09:16:27'),
(726, 19, '8V38G', NULL, 'used', '2026-01-31 09:19:09', '2026-01-28 09:16:27'),
(727, 19, '00X02', NULL, 'used', '2026-01-30 04:41:12', '2026-01-28 09:16:27'),
(728, 19, '023PX', NULL, 'used', '2026-01-29 10:12:55', '2026-01-28 09:16:27'),
(729, 19, '0CGND', NULL, 'used', '2026-01-30 06:45:16', '2026-01-28 09:16:27'),
(730, 19, 'F5DVR', NULL, 'used', '2026-01-29 12:41:41', '2026-01-28 09:16:27'),
(731, 19, '9VTE1', NULL, 'used', '2026-01-31 09:22:33', '2026-01-28 09:16:27'),
(732, 19, 'NP170', NULL, 'unused', NULL, '2026-01-28 09:16:27'),
(733, 19, 'Q3GIK', NULL, 'used', '2026-01-31 06:28:11', '2026-01-28 09:16:27'),
(734, 19, '19LRY', NULL, 'used', '2026-01-31 07:58:26', '2026-01-28 09:16:27'),
(735, 19, 'SVJK4', NULL, 'used', '2026-01-29 12:00:43', '2026-01-28 09:16:27'),
(736, 19, '56QVF', NULL, 'used', '2026-01-29 12:01:15', '2026-01-28 09:16:27'),
(737, 19, 'O9MPT', NULL, 'used', '2026-01-29 06:52:13', '2026-01-28 09:16:27'),
(738, 19, 'D4JSO', NULL, 'used', '2026-01-31 11:06:26', '2026-01-28 09:16:27'),
(739, 19, 'NXWSI', NULL, 'used', '2026-01-31 10:47:28', '2026-01-28 09:16:27'),
(740, 19, 'JGBIR', NULL, 'used', '2026-01-31 08:26:53', '2026-01-28 09:16:27'),
(741, 20, 'H9TVJ', NULL, 'used', '2026-03-29 10:40:45', '2026-03-28 11:55:26'),
(742, 20, 'XVRCN', NULL, 'used', '2026-03-29 03:33:13', '2026-03-28 11:55:26'),
(743, 20, 'EINOG', NULL, 'unused', NULL, '2026-03-28 11:55:26'),
(744, 20, 'A63VH', NULL, 'unused', NULL, '2026-03-28 11:55:26'),
(745, 20, 'N1YK6', NULL, 'used', '2026-03-31 14:47:49', '2026-03-28 11:55:26'),
(746, 20, '80LHO', NULL, 'unused', NULL, '2026-03-28 11:55:26'),
(747, 20, 'OWAPX', NULL, 'unused', NULL, '2026-03-28 11:55:26'),
(748, 20, 'OP87S', NULL, 'used', '2026-03-30 13:03:49', '2026-03-28 11:55:26'),
(749, 20, 'KJSV1', NULL, 'used', '2026-03-30 11:56:32', '2026-03-28 11:55:26'),
(750, 20, 'MWXE4', NULL, 'used', '2026-03-29 08:11:20', '2026-03-28 11:55:26'),
(751, 20, 'HO6H2', NULL, 'used', '2026-03-29 10:26:55', '2026-03-28 11:55:26'),
(752, 20, 'VUJEK', NULL, 'used', '2026-03-29 07:31:23', '2026-03-28 11:55:26'),
(753, 20, 'FIWGA', NULL, 'used', '2026-03-29 07:38:40', '2026-03-28 11:55:26'),
(754, 20, 'BYDZ9', NULL, 'used', '2026-03-30 04:28:46', '2026-03-28 11:55:26'),
(755, 20, 'PGV3Z', NULL, 'used', '2026-03-29 11:07:04', '2026-03-28 11:55:26'),
(756, 20, '647ZP', NULL, 'expired', NULL, '2026-03-28 11:55:26'),
(757, 20, 'BTLOF', NULL, 'used', '2026-03-29 10:38:25', '2026-03-28 11:55:26'),
(758, 20, 'QKIWH', NULL, 'used', '2026-03-29 13:48:05', '2026-03-28 11:55:26'),
(759, 20, '9ISKH', NULL, 'used', '2026-03-30 09:42:54', '2026-03-28 11:55:26'),
(760, 20, '3S67E', NULL, 'used', '2026-03-31 12:28:19', '2026-03-28 11:55:26'),
(761, 20, '0S8RC', NULL, 'used', '2026-03-29 10:28:50', '2026-03-28 11:55:26'),
(762, 20, 'VA42B', NULL, 'used', '2026-03-29 12:07:43', '2026-03-28 11:55:26'),
(763, 20, 'RZGON', NULL, 'used', '2026-03-29 03:55:48', '2026-03-28 11:55:26'),
(764, 20, 'W9D2E', NULL, 'used', '2026-03-29 12:19:48', '2026-03-28 11:55:26'),
(765, 20, 'ILISX', NULL, 'used', '2026-03-29 08:00:40', '2026-03-28 11:55:26'),
(983, 21, 'CFQHI', NULL, 'used', '2026-03-30 11:44:03', '2026-03-28 14:30:07'),
(984, 21, 'IJZF2', NULL, 'used', '2026-03-30 07:22:58', '2026-03-28 14:30:07'),
(985, 21, 'MFJ6H', NULL, 'used', '2026-03-30 06:27:25', '2026-03-28 14:30:07'),
(986, 21, 'T68YO', NULL, 'used', '2026-03-31 05:34:37', '2026-03-28 14:30:07'),
(987, 21, 'OVKUA', NULL, 'expired', NULL, '2026-03-28 14:30:07'),
(988, 21, '5IUZS', NULL, 'used', '2026-03-30 09:33:23', '2026-03-28 14:30:07'),
(989, 21, '4OILY', NULL, 'used', '2026-03-30 10:47:31', '2026-03-28 14:30:07'),
(981, 21, '12S84', NULL, 'unused', NULL, '2026-03-28 14:30:07'),
(982, 21, '6ADT2', NULL, 'used', '2026-03-30 02:19:47', '2026-03-28 14:30:07'),
(966, 21, 'TIWQM', NULL, 'used', '2026-03-30 09:54:06', '2026-03-28 14:30:07'),
(967, 21, 'BQPB8', NULL, 'expired', NULL, '2026-03-28 14:30:07'),
(968, 21, 'FIKEX', NULL, 'used', '2026-03-30 10:16:03', '2026-03-28 14:30:07'),
(969, 21, '0VGY8', NULL, 'used', '2026-03-30 10:48:35', '2026-03-28 14:30:07'),
(970, 21, 'I6XB8', NULL, 'used', '2026-03-30 08:00:11', '2026-03-28 14:30:07'),
(971, 21, 'QFJN3', NULL, 'used', '2026-03-30 08:48:02', '2026-03-28 14:30:07'),
(972, 21, 'AMMRT', NULL, 'used', '2026-03-30 07:43:49', '2026-03-28 14:30:07'),
(973, 21, 'WLYO5', NULL, 'used', '2026-03-31 13:05:04', '2026-03-28 14:30:07'),
(974, 21, 'E4TVW', NULL, 'used', '2026-03-30 11:39:14', '2026-03-28 14:30:07'),
(975, 21, 'ZN562', NULL, 'used', '2026-03-31 10:08:18', '2026-03-28 14:30:07'),
(976, 21, '0NNTT', NULL, 'used', '2026-03-30 11:05:08', '2026-03-28 14:30:07'),
(977, 21, 'VQUCN', NULL, 'used', '2026-03-30 12:15:02', '2026-03-28 14:30:07'),
(978, 21, 'YUPWH', NULL, 'used', '2026-03-31 11:21:15', '2026-03-28 14:30:07'),
(979, 21, '9S7VH', NULL, 'used', '2026-04-01 03:50:20', '2026-03-28 14:30:07'),
(980, 21, 'UA4RR', NULL, 'used', '2026-03-31 12:46:15', '2026-03-28 14:30:07'),
(892, 22, 'CAQTJ', NULL, 'used', '2026-03-31 10:15:27', '2026-03-28 12:14:07'),
(901, 22, '1UQ86', NULL, 'used', '2026-03-31 04:44:58', '2026-03-28 12:14:07'),
(894, 22, 'LIUSZ', NULL, 'used', '2026-03-29 12:35:43', '2026-03-28 12:14:07'),
(891, 22, '6T0BR', NULL, 'used', '2026-03-29 12:36:07', '2026-03-28 12:14:07'),
(896, 22, 'JO5YE', NULL, 'used', '2026-03-30 03:05:38', '2026-03-28 12:14:07'),
(898, 22, 'K3C8F', NULL, 'used', '2026-03-30 09:46:54', '2026-03-28 12:14:07'),
(893, 22, '2WTPA', NULL, 'used', '2026-03-29 05:39:48', '2026-03-28 12:14:07'),
(903, 22, '53G7B', NULL, 'used', '2026-03-30 07:46:32', '2026-03-28 12:14:07'),
(902, 22, 'X9L3W', NULL, 'used', '2026-03-29 13:35:50', '2026-03-28 12:14:07'),
(895, 22, 'P40GL', NULL, 'used', '2026-03-30 12:23:43', '2026-03-28 12:14:07'),
(897, 22, 'K2Y1S', NULL, 'used', '2026-03-29 11:07:36', '2026-03-28 12:14:07'),
(900, 22, 'TIUD0', NULL, 'used', '2026-03-29 05:51:21', '2026-03-28 12:14:07'),
(899, 22, '5SKQS', NULL, 'used', '2026-03-30 04:18:54', '2026-03-28 12:14:07'),
(904, 22, 'K3I3O', NULL, 'used', '2026-03-31 12:02:41', '2026-03-28 12:14:07'),
(905, 22, '08AJD', NULL, 'used', '2026-03-30 09:12:31', '2026-03-28 12:14:07'),
(906, 22, 'JGBAW', NULL, 'used', '2026-03-31 07:10:43', '2026-03-28 12:14:07'),
(907, 22, 'NY4ZP', NULL, 'used', '2026-03-30 13:07:05', '2026-03-28 12:14:07'),
(908, 22, '7NUIV', NULL, 'used', '2026-03-31 07:12:49', '2026-03-28 12:14:07'),
(909, 22, 'TUH5T', NULL, 'used', '2026-03-30 12:11:28', '2026-03-28 12:14:07'),
(910, 22, 'PI1LH', NULL, 'unused', NULL, '2026-03-28 12:14:07'),
(911, 22, 'BUFYZ', NULL, 'used', '2026-03-29 08:17:47', '2026-03-28 12:14:07'),
(912, 22, 'KUA4X', NULL, 'used', '2026-03-29 05:56:00', '2026-03-28 12:14:07'),
(913, 22, 'QKAUE', NULL, 'used', '2026-03-31 12:06:01', '2026-03-28 12:14:07'),
(914, 22, 'NAEPF', NULL, 'expired', NULL, '2026-03-28 12:14:07'),
(915, 22, 'GSL7W', NULL, 'used', '2026-03-31 09:28:51', '2026-03-28 12:14:07'),
(946, 23, 'FHSIW', NULL, 'used', '2026-03-29 12:14:05', '2026-03-28 12:18:41'),
(945, 23, '8QTVN', NULL, 'used', '2026-03-30 13:02:33', '2026-03-28 12:18:41'),
(957, 23, 'Z6SLZ', NULL, 'used', '2026-03-29 10:21:13', '2026-03-28 12:18:41'),
(951, 23, '0S6R0', NULL, 'used', '2026-03-29 04:44:52', '2026-03-28 12:18:41'),
(952, 23, '783AH', NULL, 'used', '2026-03-31 10:48:53', '2026-03-28 12:18:41'),
(953, 23, 'EXY2A', NULL, 'used', '2026-03-30 02:57:29', '2026-03-28 12:18:41'),
(954, 23, '6IEGM', NULL, 'used', '2026-03-30 14:12:35', '2026-03-28 12:18:41'),
(955, 23, 'G50J3', NULL, 'used', '2026-03-29 01:40:53', '2026-03-28 12:18:41'),
(956, 23, 'PXOJM', NULL, 'used', '2026-03-30 14:12:40', '2026-03-28 12:18:41'),
(949, 23, 'B0Y72', NULL, 'used', '2026-03-31 04:37:55', '2026-03-28 12:18:41'),
(947, 23, 'A1LV9', NULL, 'used', '2026-03-31 13:59:40', '2026-03-28 12:18:41'),
(942, 23, 'NJH7Z', NULL, 'used', '2026-03-29 01:22:35', '2026-03-28 12:18:41'),
(943, 23, 'Z4CYD', NULL, 'used', '2026-03-30 12:14:13', '2026-03-28 12:18:41'),
(944, 23, '333LT', NULL, 'used', '2026-03-30 04:37:08', '2026-03-28 12:18:41'),
(948, 23, '2ZFQQ', NULL, 'used', '2026-03-29 10:24:44', '2026-03-28 12:18:41'),
(950, 23, 'EDH8D', NULL, 'used', '2026-03-31 12:08:23', '2026-03-28 12:18:41'),
(941, 23, 'UMX6N', NULL, 'used', '2026-03-31 13:00:57', '2026-03-28 12:18:41'),
(958, 23, 'OB6UJ', NULL, 'used', '2026-03-29 01:40:30', '2026-03-28 12:18:41'),
(959, 23, '0RG4C', NULL, 'used', '2026-03-31 11:10:57', '2026-03-28 12:18:41'),
(960, 23, '2264V', NULL, 'expired', NULL, '2026-03-28 12:18:41'),
(961, 23, 'A7KG5', NULL, 'used', '2026-03-29 01:41:14', '2026-03-28 12:18:41'),
(962, 23, 'MLV35', NULL, 'used', '2026-03-29 12:31:41', '2026-03-28 12:18:41'),
(963, 23, '66ZN3', NULL, 'used', '2026-03-29 03:43:36', '2026-03-28 12:18:41'),
(964, 23, 'G0BUA', NULL, 'used', '2026-03-31 12:42:42', '2026-03-28 12:18:41'),
(965, 23, 'ZJ88U', NULL, 'used', '2026-03-31 11:30:34', '2026-03-28 12:18:41'),
(990, 21, '65WJ2', NULL, 'unused', NULL, '2026-03-28 14:30:07'),
(1115, 24, 'C31DX', NULL, 'used', '2026-04-28 13:25:54', '2026-04-27 10:57:02'),
(1114, 24, 'GSBLL', NULL, 'used', '2026-04-28 00:28:26', '2026-04-27 10:57:02'),
(1113, 24, '25IV0', NULL, 'used', '2026-04-30 07:15:53', '2026-04-27 10:57:02'),
(1112, 24, 'HKCIS', NULL, 'used', '2026-04-30 06:15:50', '2026-04-27 10:57:02'),
(1111, 24, 'KLQXV', NULL, 'used', '2026-04-30 05:14:16', '2026-04-27 10:57:02'),
(1110, 24, 'R8OF4', NULL, 'used', '2026-04-29 08:26:32', '2026-04-27 10:57:02'),
(1109, 24, 'YAPUO', NULL, 'used', '2026-04-29 12:54:18', '2026-04-27 10:57:02'),
(1108, 24, '1XJB9', NULL, 'used', '2026-04-29 09:53:40', '2026-04-27 10:57:02'),
(1107, 24, '8V1E4', NULL, 'used', '2026-04-29 09:15:55', '2026-04-27 10:57:02'),
(1106, 24, 'CDCNG', NULL, 'used', '2026-04-28 08:37:36', '2026-04-27 10:57:02'),
(1105, 24, 'OX3TI', NULL, 'used', '2026-04-29 12:18:48', '2026-04-27 10:57:02'),
(1104, 24, 'JK3Z3', NULL, 'unused', NULL, '2026-04-27 10:57:02'),
(1103, 24, 'JM2FH', NULL, 'expired', NULL, '2026-04-27 10:57:02'),
(1102, 24, 'U02TB', NULL, 'used', '2026-04-29 06:26:06', '2026-04-27 10:57:02'),
(1101, 24, 'RP48A', NULL, 'used', '2026-04-29 03:38:43', '2026-04-27 10:57:02'),
(1100, 24, 'KBLTR', NULL, 'expired', NULL, '2026-04-27 10:57:02'),
(1099, 24, 'RFIP3', NULL, 'used', '2026-04-28 00:44:54', '2026-04-27 10:57:02'),
(1098, 24, 'YR0GY', NULL, 'used', '2026-04-28 11:09:26', '2026-04-27 10:57:02'),
(1097, 24, '17SSK', NULL, 'used', '2026-04-30 03:09:10', '2026-04-27 10:57:02'),
(1096, 24, 'TZMZB', NULL, 'used', '2026-04-29 09:38:52', '2026-04-27 10:57:02'),
(1095, 24, 'AUIN8', NULL, 'used', '2026-04-28 13:25:28', '2026-04-27 10:57:02'),
(1094, 24, 'KHMUL', NULL, 'used', '2026-04-30 03:21:40', '2026-04-27 10:57:02'),
(1093, 24, 'S6EKD', NULL, 'expired', NULL, '2026-04-27 10:57:02'),
(1092, 24, 'LD8V8', NULL, 'used', '2026-04-28 14:40:38', '2026-04-27 10:57:02'),
(1091, 24, 'SV248', NULL, 'used', '2026-04-29 08:24:38', '2026-04-27 10:57:02'),
(1116, 25, 'CEL5D', NULL, 'used', '2026-04-28 10:51:18', '2026-04-27 12:29:36'),
(1117, 25, 'YMQJI', NULL, 'used', '2026-04-30 10:29:53', '2026-04-27 12:29:36'),
(1118, 25, 'SKKTP', NULL, 'used', '2026-04-29 09:33:46', '2026-04-27 12:29:36'),
(1119, 25, 'T2HM9', NULL, 'used', '2026-04-30 01:20:48', '2026-04-27 12:29:36'),
(1120, 25, 'CFRNF', NULL, 'used', '2026-04-28 10:31:39', '2026-04-27 12:29:36'),
(1121, 25, 'EM08W', NULL, 'used', '2026-04-30 03:23:55', '2026-04-27 12:29:36'),
(1122, 25, 'FOFHC', NULL, 'used', '2026-04-29 10:41:22', '2026-04-27 12:29:36'),
(1123, 25, 'H9B4N', NULL, 'used', '2026-04-28 04:03:41', '2026-04-27 12:29:36'),
(1124, 25, 'NQSS1', NULL, 'unused', NULL, '2026-04-27 12:29:36'),
(1125, 25, 'AEROX', NULL, 'used', '2026-04-29 13:00:59', '2026-04-27 12:29:36'),
(1126, 25, 'XBG25', NULL, 'expired', NULL, '2026-04-27 12:29:36'),
(1127, 25, '3Y8E0', NULL, 'used', '2026-04-30 13:38:33', '2026-04-27 12:29:36'),
(1128, 25, 'LWCWX', NULL, 'used', '2026-04-30 03:43:23', '2026-04-27 12:29:36'),
(1129, 25, '4XPZZ', NULL, 'used', '2026-04-30 10:39:17', '2026-04-27 12:29:36'),
(1130, 25, 'N40LB', NULL, 'used', '2026-04-30 03:31:11', '2026-04-27 12:29:36'),
(1131, 25, 'FRGSJ', NULL, 'used', '2026-04-30 12:31:46', '2026-04-27 12:29:36'),
(1132, 25, 'N32VZ', NULL, 'used', '2026-04-28 13:03:27', '2026-04-27 12:29:36'),
(1133, 25, 'NK3O0', NULL, 'used', '2026-04-30 01:09:14', '2026-04-27 12:29:36'),
(1134, 25, 'RYWOG', NULL, 'used', '2026-04-30 09:51:25', '2026-04-27 12:29:36'),
(1135, 25, '1V5UL', NULL, 'expired', NULL, '2026-04-27 12:29:36'),
(1136, 25, 'J8VXG', NULL, 'used', '2026-04-30 13:33:00', '2026-04-27 12:29:36'),
(1137, 25, 'XS1LR', NULL, 'used', '2026-04-28 11:52:53', '2026-04-27 12:29:36'),
(1138, 25, 'IVS84', NULL, 'used', '2026-04-29 12:03:45', '2026-04-27 12:29:36'),
(1139, 25, 'HBFIR', NULL, 'used', '2026-04-30 03:39:29', '2026-04-27 12:29:36'),
(1140, 25, 'U7Z04', NULL, 'used', '2026-04-30 11:23:25', '2026-04-27 12:29:36'),
(1141, 26, 'WTN05', NULL, 'used', '2026-04-30 13:09:35', '2026-04-27 12:33:46'),
(1142, 26, 'HHUE7', NULL, 'used', '2026-04-29 11:10:04', '2026-04-27 12:33:46'),
(1143, 26, 'EFX64', NULL, 'expired', NULL, '2026-04-27 12:33:46'),
(1144, 26, 'QQ0DG', NULL, 'used', '2026-04-28 13:40:55', '2026-04-27 12:33:46'),
(1145, 26, 'ABSJ3', NULL, 'used', '2026-04-28 11:37:36', '2026-04-27 12:33:46'),
(1146, 26, '3M3OR', NULL, 'unused', NULL, '2026-04-27 12:33:46'),
(1147, 26, 'TG1M9', NULL, 'unused', NULL, '2026-04-27 12:33:46'),
(1148, 26, 'INBZQ', NULL, 'used', '2026-04-28 05:05:04', '2026-04-27 12:33:46'),
(1149, 26, 'RH4ZN', NULL, 'used', '2026-04-30 11:29:25', '2026-04-27 12:33:46'),
(1150, 26, 'O6CZ3', NULL, 'used', '2026-04-29 09:49:34', '2026-04-27 12:33:46'),
(1151, 26, 'RQ2OV', NULL, 'used', '2026-04-30 11:29:30', '2026-04-27 12:33:46'),
(1152, 26, 'RBT6S', NULL, 'unused', NULL, '2026-04-27 12:33:46'),
(1153, 26, 'GHMZX', NULL, 'used', '2026-04-28 10:34:15', '2026-04-27 12:33:46'),
(1154, 26, 'R2V7G', NULL, 'used', '2026-04-28 10:34:00', '2026-04-27 12:33:46'),
(1155, 26, 'IN6CV', NULL, 'expired', NULL, '2026-04-27 12:33:46'),
(1156, 26, '7W4MT', NULL, 'used', '2026-04-30 14:17:18', '2026-04-27 12:33:46'),
(1157, 26, '6XCPQ', NULL, 'used', '2026-04-30 09:35:14', '2026-04-27 12:33:46'),
(1158, 26, 'HOJU3', NULL, 'used', '2026-04-29 03:36:05', '2026-04-27 12:33:46'),
(1159, 26, 'XIRLJ', NULL, 'used', '2026-04-28 05:59:06', '2026-04-27 12:33:46'),
(1160, 26, 'MWNJB', NULL, 'used', '2026-04-28 03:29:42', '2026-04-27 12:33:46'),
(1161, 26, 'NU2OL', NULL, 'used', '2026-04-30 03:25:58', '2026-04-27 12:33:46'),
(1162, 26, 'DR2QN', NULL, 'expired', NULL, '2026-04-27 12:33:46'),
(1163, 26, 'DE43X', NULL, 'used', '2026-04-30 13:25:39', '2026-04-27 12:33:46'),
(1164, 26, 'EB5QH', NULL, 'used', '2026-04-30 04:20:06', '2026-04-27 12:33:46'),
(1165, 26, 'S4IUD', NULL, 'used', '2026-04-29 08:59:26', '2026-04-27 12:33:46'),
(1166, 27, 'MBUUN', NULL, 'used', '2026-04-28 13:03:24', '2026-04-27 12:39:02'),
(1167, 27, '8R0H4', NULL, 'used', '2026-04-29 12:34:08', '2026-04-27 12:39:02'),
(1168, 27, 'Q7XUW', NULL, 'used', '2026-04-30 01:24:18', '2026-04-27 12:39:02'),
(1169, 27, 'UGAHI', NULL, 'used', '2026-04-29 08:21:44', '2026-04-27 12:39:02'),
(1170, 27, 'FCLYJ', NULL, 'used', '2026-04-30 12:48:22', '2026-04-27 12:39:02'),
(1171, 27, 'YNKFP', NULL, 'used', '2026-04-30 13:22:08', '2026-04-27 12:39:02'),
(1172, 27, '90QQW', NULL, 'used', '2026-04-30 12:40:24', '2026-04-27 12:39:02'),
(1173, 27, 'EV8HY', NULL, 'used', '2026-04-30 12:44:04', '2026-04-27 12:39:02'),
(1174, 27, 'QNBKK', NULL, 'used', '2026-04-28 13:06:59', '2026-04-27 12:39:02'),
(1175, 27, '6L2AW', NULL, 'used', '2026-04-30 04:35:37', '2026-04-27 12:39:02'),
(1176, 27, '4DXO6', NULL, 'used', '2026-04-29 01:43:20', '2026-04-27 12:39:02'),
(1177, 27, 'MDQGL', NULL, 'used', '2026-04-28 06:25:39', '2026-04-27 12:39:02'),
(1178, 27, 'H8MKG', NULL, 'used', '2026-04-29 11:02:34', '2026-04-27 12:39:02'),
(1179, 27, 'S7CY0', NULL, 'used', '2026-04-29 09:35:00', '2026-04-27 12:39:02'),
(1180, 27, 'Z4R14', NULL, 'used', '2026-04-28 04:13:18', '2026-04-27 12:39:02'),
(1181, 27, 'J10H9', NULL, 'used', '2026-04-30 11:03:06', '2026-04-27 12:39:02'),
(1182, 27, 'KDD7O', NULL, 'used', '2026-04-30 11:21:27', '2026-04-27 12:39:02'),
(1183, 27, 'NG58J', NULL, 'used', '2026-04-29 12:32:28', '2026-04-27 12:39:02'),
(1184, 27, 'VSS8N', NULL, 'used', '2026-04-29 09:25:15', '2026-04-27 12:39:02'),
(1185, 27, '2ZLV5', NULL, 'used', '2026-04-29 04:11:27', '2026-04-27 12:39:02'),
(1186, 27, 'DI45J', NULL, 'used', '2026-04-29 13:45:43', '2026-04-27 12:39:02'),
(1187, 27, '6LTNO', NULL, 'used', '2026-04-30 03:33:30', '2026-04-27 12:39:02'),
(1188, 27, 'WE863', NULL, 'used', '2026-04-29 12:05:22', '2026-04-27 12:39:02'),
(1189, 27, 'LXDYA', NULL, 'used', '2026-04-29 08:29:04', '2026-04-27 12:39:02'),
(1190, 27, 'E00Z3', NULL, 'used', '2026-04-30 00:34:21', '2026-04-27 12:39:02'),
(1639, 28, 'MIB5N', NULL, 'used', '2026-06-04 10:09:43', '2026-06-02 12:15:14'),
(1632, 28, 'PFCIS', NULL, 'used', '2026-06-03 13:04:09', '2026-06-02 12:15:14'),
(1633, 28, 'YY07K', NULL, 'used', '2026-06-04 12:22:19', '2026-06-02 12:15:14'),
(1634, 28, '2O50K', NULL, 'used', '2026-06-03 08:14:48', '2026-06-02 12:15:14'),
(1635, 28, 'GG2N3', NULL, 'used', '2026-06-02 22:49:21', '2026-06-02 12:15:14'),
(1636, 28, 'TE8JK', NULL, 'used', '2026-06-02 13:53:49', '2026-06-02 12:15:14'),
(1621, 28, '27AMW', NULL, 'used', '2026-06-03 09:02:59', '2026-06-02 12:15:14'),
(1622, 28, 'WUHRN', NULL, 'used', '2026-06-02 22:47:19', '2026-06-02 12:15:14'),
(1623, 28, 'FQN21', NULL, 'used', '2026-06-04 09:11:57', '2026-06-02 12:15:14'),
(1624, 28, '4RI0V', NULL, 'used', '2026-06-03 10:21:33', '2026-06-02 12:15:14'),
(1625, 28, 'ES1BA', NULL, 'unused', NULL, '2026-06-02 12:15:14'),
(1626, 28, '41UOB', NULL, 'used', '2026-06-04 11:02:59', '2026-06-02 12:15:14'),
(1627, 28, 'AQTBD', NULL, 'used', '2026-06-04 12:35:59', '2026-06-02 12:15:14'),
(1628, 28, '1BB01', NULL, 'used', '2026-06-03 06:28:36', '2026-06-02 12:15:14'),
(1629, 28, '20P1N', NULL, 'used', '2026-06-03 11:41:29', '2026-06-02 12:15:14'),
(1630, 28, 'W8QID', NULL, 'used', '2026-06-03 03:39:12', '2026-06-02 12:15:14'),
(1631, 28, '15MYB', NULL, 'used', '2026-06-02 22:48:08', '2026-06-02 12:15:14'),
(1616, 28, '2ZI9R', NULL, 'used', '2026-06-03 09:21:15', '2026-06-02 12:15:14'),
(1617, 28, 'VW2XD', NULL, 'used', '2026-06-03 10:31:15', '2026-06-02 12:15:14'),
(1618, 28, '2F25K', NULL, 'used', '2026-06-02 22:48:20', '2026-06-02 12:15:14'),
(1619, 28, '4WTPX', NULL, 'used', '2026-06-03 13:31:46', '2026-06-02 12:15:14'),
(1620, 28, 'E08H7', NULL, 'used', '2026-06-02 13:56:19', '2026-06-02 12:15:14'),
(1611, 29, '702ZC', NULL, 'unused', NULL, '2026-06-02 12:15:07'),
(1610, 29, 'F7YDD', NULL, 'used', '2026-06-03 09:47:16', '2026-06-02 12:15:07'),
(1597, 29, 'W87XX', NULL, 'used', '2026-06-04 09:21:03', '2026-06-02 12:15:07'),
(1609, 29, '30D7A', NULL, 'unused', NULL, '2026-06-02 12:15:07'),
(1608, 29, 'KRNJK', NULL, 'used', '2026-06-04 03:35:48', '2026-06-02 12:15:07'),
(1596, 29, 'HNTNW', NULL, 'used', '2026-06-03 10:06:20', '2026-06-02 12:15:07'),
(1595, 29, '0B1GQ', NULL, 'used', '2026-06-03 10:36:01', '2026-06-02 12:15:07'),
(1607, 29, 'O2G0M', NULL, 'used', '2026-06-04 07:08:06', '2026-06-02 12:15:07'),
(1606, 29, '6DF7X', NULL, 'used', '2026-06-03 03:53:43', '2026-06-02 12:15:07'),
(1605, 29, 'N0HCU', NULL, 'used', '2026-06-04 09:49:42', '2026-06-02 12:15:07'),
(1604, 29, 'Y830I', NULL, 'used', '2026-06-03 06:25:40', '2026-06-02 12:15:07'),
(1603, 29, 'EU8EP', NULL, 'used', '2026-06-03 04:35:35', '2026-06-02 12:15:07'),
(1602, 29, 'RADFA', NULL, 'used', '2026-06-03 10:07:00', '2026-06-02 12:15:07'),
(1601, 29, 'AZO0K', NULL, 'used', '2026-06-02 12:50:55', '2026-06-02 12:15:07'),
(1594, 29, 'ZO5T0', NULL, 'used', '2026-06-04 11:24:50', '2026-06-02 12:15:07'),
(1600, 29, 'Z5NKX', NULL, 'used', '2026-06-03 11:13:36', '2026-06-02 12:15:07'),
(1599, 29, 'G7ZMG', NULL, 'used', '2026-06-03 13:06:46', '2026-06-02 12:15:07'),
(1593, 29, 'JV6CM', NULL, 'used', '2026-06-03 09:16:22', '2026-06-02 12:15:07'),
(1592, 29, 'JK3RO', NULL, 'used', '2026-06-03 00:32:57', '2026-06-02 12:15:07'),
(1598, 29, 'LFHAN', NULL, 'used', '2026-06-02 22:48:36', '2026-06-02 12:15:07'),
(1591, 29, 'U448D', NULL, 'used', '2026-06-03 05:07:36', '2026-06-02 12:15:07'),
(1588, 30, 'F2R4Q', NULL, 'used', '2026-06-04 12:16:04', '2026-06-02 12:14:55'),
(1587, 30, 'IB7QM', NULL, 'used', '2026-06-03 04:58:07', '2026-06-02 12:14:55'),
(1586, 30, 'VST6S', NULL, 'used', '2026-06-03 07:58:53', '2026-06-02 12:14:55'),
(1585, 30, 'OVC5R', NULL, 'used', '2026-06-04 10:19:46', '2026-06-02 12:14:55'),
(1584, 30, 'DB6I7', NULL, 'unused', NULL, '2026-06-02 12:14:55'),
(1583, 30, 'KRRA7', NULL, 'used', '2026-06-02 13:11:03', '2026-06-02 12:14:55'),
(1570, 30, '892OL', NULL, 'used', '2026-06-03 01:10:20', '2026-06-02 12:14:55'),
(1582, 30, '2T44C', NULL, 'used', '2026-06-04 04:55:57', '2026-06-02 12:14:55'),
(1581, 30, 'N9UP5', NULL, 'used', '2026-06-03 02:39:40', '2026-06-02 12:14:55'),
(1580, 30, 'WZU0E', NULL, 'unused', NULL, '2026-06-02 12:14:55'),
(1579, 30, 'UOUK1', NULL, 'used', '2026-06-03 12:44:38', '2026-06-02 12:14:55'),
(1578, 30, 'JRQ4N', NULL, 'used', '2026-06-03 03:10:51', '2026-06-02 12:14:55'),
(1577, 30, 'I328S', NULL, 'used', '2026-06-04 12:23:38', '2026-06-02 12:14:55'),
(1576, 30, '8TNY9', NULL, 'used', '2026-06-03 13:32:11', '2026-06-02 12:14:55'),
(1575, 30, 'R3RL9', NULL, 'used', '2026-06-03 13:57:07', '2026-06-02 12:14:55'),
(1574, 30, '7VREX', NULL, 'unused', NULL, '2026-06-02 12:14:55'),
(1573, 30, 'ZEMG8', NULL, 'used', '2026-06-03 05:01:29', '2026-06-02 12:14:55'),
(1569, 30, '7HWFF', NULL, 'used', '2026-06-03 11:32:52', '2026-06-02 12:14:55'),
(1568, 30, 'JL6J1', NULL, 'used', '2026-06-03 13:56:39', '2026-06-02 12:14:55'),
(1561, 31, 'SUFK0', NULL, 'used', '2026-06-03 11:20:13', '2026-06-02 12:14:38'),
(1560, 31, 'UPREW', NULL, 'unused', NULL, '2026-06-02 12:14:38'),
(1559, 31, 'QT2P1', NULL, 'used', '2026-06-02 12:19:34', '2026-06-02 12:14:38'),
(1558, 31, 'JJGRR', NULL, 'used', '2026-06-03 12:26:16', '2026-06-02 12:14:38'),
(1557, 31, 'V2M9W', NULL, 'used', '2026-06-03 10:41:41', '2026-06-02 12:14:38'),
(1556, 31, 'TMRKY', NULL, 'used', '2026-06-03 10:17:24', '2026-06-02 12:14:38'),
(1555, 31, '620J0', NULL, 'unused', NULL, '2026-06-02 12:14:38'),
(1554, 31, 'U6WKR', NULL, 'used', '2026-06-03 03:13:12', '2026-06-02 12:14:38'),
(1553, 31, 'IG4IE', NULL, 'used', '2026-06-03 08:45:32', '2026-06-02 12:14:38'),
(1544, 31, 'HLM5P', NULL, 'used', '2026-06-03 13:05:30', '2026-06-02 12:14:38'),
(1552, 31, 'X79LT', NULL, 'used', '2026-06-04 12:05:45', '2026-06-02 12:14:38'),
(1543, 31, '2UTWY', NULL, 'used', '2026-06-04 11:29:14', '2026-06-02 12:14:38'),
(1551, 31, 'VFZ4G', NULL, 'used', '2026-06-03 10:21:24', '2026-06-02 12:14:38'),
(1550, 31, '07WOI', NULL, 'unused', NULL, '2026-06-02 12:14:38'),
(1549, 31, 'W7P6N', NULL, 'used', '2026-06-03 09:40:20', '2026-06-02 12:14:38'),
(1548, 31, 'BU3EO', NULL, 'used', '2026-06-03 03:02:32', '2026-06-02 12:14:38'),
(1547, 31, 'N4E0B', NULL, 'used', '2026-06-04 06:18:44', '2026-06-02 12:14:38'),
(1542, 31, '1JGFQ', NULL, 'used', '2026-06-03 14:21:56', '2026-06-02 12:14:38'),
(1546, 31, '0UYV9', NULL, 'used', '2026-06-03 10:29:10', '2026-06-02 12:14:38'),
(1541, 31, 'L78H7', NULL, 'used', '2026-06-04 01:27:55', '2026-06-02 12:14:38'),
(1545, 31, 'IJCXS', NULL, 'used', '2026-06-03 10:05:50', '2026-06-02 12:14:38'),
(1637, 28, '353B5', NULL, 'unused', NULL, '2026-06-02 12:15:14'),
(1638, 28, 'V7DRL', NULL, 'used', '2026-06-04 06:49:05', '2026-06-02 12:15:14'),
(1572, 30, 'IMYSL', NULL, 'used', '2026-06-03 04:57:10', '2026-06-02 12:14:55'),
(1571, 30, 'XLELA', NULL, 'used', '2026-06-04 07:07:53', '2026-06-02 12:14:55'),
(1567, 30, 'YBW92', NULL, 'used', '2026-06-04 13:16:18', '2026-06-02 12:14:55'),
(1566, 30, 'LTTFY', NULL, 'used', '2026-06-03 13:56:06', '2026-06-02 12:14:55'),
(1562, 31, '6L7WW', NULL, 'used', '2026-06-04 11:28:59', '2026-06-02 12:14:38'),
(1563, 31, '21CGG', NULL, 'used', '2026-06-04 11:14:33', '2026-06-02 12:14:38'),
(1564, 31, 'C8HHL', NULL, 'used', '2026-06-04 09:58:09', '2026-06-02 12:14:38'),
(1565, 31, 'N5R3Q', NULL, 'used', '2026-06-03 13:42:40', '2026-06-02 12:14:38'),
(1589, 30, 'FRP9T', NULL, 'used', '2026-06-03 09:08:07', '2026-06-02 12:14:55'),
(1590, 30, '3TJC9', NULL, 'unused', NULL, '2026-06-02 12:14:55'),
(1612, 29, 'PI1DP', NULL, 'used', '2026-06-04 06:58:09', '2026-06-02 12:15:07'),
(1613, 29, '1874T', NULL, 'used', '2026-06-04 12:41:43', '2026-06-02 12:15:07'),
(1614, 29, 'MLEGL', NULL, 'used', '2026-06-03 08:49:07', '2026-06-02 12:15:07'),
(1615, 29, 'GEZEN', NULL, 'used', '2026-06-03 23:52:07', '2026-06-02 12:15:07'),
(1640, 28, 'CPTUW', NULL, 'used', '2026-06-03 09:05:03', '2026-06-02 12:15:14'),
(1641, 32, 'LZYC1', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1642, 32, 'J1YRS', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1643, 32, 'ASQ6S', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1644, 32, 'DBDAM', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1645, 32, '1VVE7', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1646, 32, '672I3', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1647, 32, 'H40YR', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1648, 32, 'ZN99J', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1649, 32, 'FNGRW', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1650, 32, 'CLHZD', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1651, 32, 'D9DO9', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1652, 32, 'MXC39', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1653, 32, 'QI5QS', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1654, 32, 'WY4Q1', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1655, 32, 'WRC3Q', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1656, 32, '2TJIW', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1657, 32, 'VBNH4', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1658, 32, 'NV4IP', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1659, 32, '65Q26', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1660, 32, '3BTOO', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1661, 32, '04C91', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1662, 32, '3FLDU', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1663, 32, '9PXKG', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1664, 32, 'EDDWQ', NULL, 'unused', NULL, '2026-06-03 08:40:18'),
(1665, 32, '1IHEJ', NULL, 'unused', NULL, '2026-06-03 08:40:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_audit_action` (`action`),
  ADD KEY `idx_audit_entity` (`entity`,`entity_id`),
  ADD KEY `idx_audit_user` (`user_id`),
  ADD KEY `idx_audit_created` (`created_at`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `free_vouchers`
--
ALTER TABLE `free_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_free_code` (`code`),
  ADD KEY `idx_fv_status` (`status`),
  ADD KEY `idx_fv_code` (`code`);

--
-- Indexes for table `given_vouchers`
--
ALTER TABLE `given_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_gv_voucher` (`voucher_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `migration` (`migration`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `taken_vouchers`
--
ALTER TABLE `taken_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `campaign_id` (`campaign_id`);

--
-- Indexes for table `taken_voucher_items`
--
ALTER TABLE `taken_voucher_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_tvi_voucher` (`voucher_id`),
  ADD KEY `idx_tvi_campaign` (`campaign_id`),
  ADD KEY `idx_tvi_status` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_sessions`
--
ALTER TABLE `user_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_us_user` (`user_id`),
  ADD KEY `idx_us_last_seen` (`last_seen`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_campaign_code` (`campaign_id`,`code`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_campaign_status` (`campaign_id`,`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=312;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `free_vouchers`
--
ALTER TABLE `free_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `given_vouchers`
--
ALTER TABLE `given_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=882;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `taken_vouchers`
--
ALTER TABLE `taken_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `taken_voucher_items`
--
ALTER TABLE `taken_voucher_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1666;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
