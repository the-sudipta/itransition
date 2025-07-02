-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2025 at 06:00 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `formulate`
--

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `choosen_option_id` int(11) DEFAULT NULL,
  `form_submit_id` int(11) NOT NULL,
  `answer_text` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`id`, `question_id`, `choosen_option_id`, `form_submit_id`, `answer_text`) VALUES
(1, 1, NULL, 1, '0'),
(2, 2, NULL, 1, '5'),
(3, 4, NULL, 1, '4'),
(4, 5, NULL, 1, 'Itaque necessitatibus corporis non saepe dolor.'),
(5, 6, NULL, 2, 'Quia quia et doloremque maxime ut sit.'),
(6, 7, NULL, 2, '4'),
(7, 8, NULL, 2, 'Enim beatae aut quaerat qui eaque.'),
(8, 11, NULL, 3, '0'),
(9, 12, NULL, 3, '2'),
(10, 11, NULL, 4, '5'),
(11, 12, NULL, 4, '4'),
(12, 11, NULL, 5, '6'),
(13, 12, NULL, 5, '1'),
(14, 11, NULL, 6, '9'),
(15, 12, NULL, 6, '10'),
(24, 21, NULL, 9, 'Impedit odit laboriosam optio quia ea commodi qui.'),
(25, 22, NULL, 9, 'Quo et quidem culpa rerum at.'),
(26, 24, NULL, 9, 'Nihil sunt ut quibusdam atque at.'),
(27, 21, NULL, 10, 'Ut quo excepturi eveniet aut at.'),
(28, 22, NULL, 10, 'Quod voluptatem et sunt recusandae consequuntur laudantium.'),
(29, 24, NULL, 10, 'Voluptates consequatur sed eligendi sed.'),
(30, 27, NULL, 11, 'Quos quisquam vitae vitae tempore.'),
(31, 29, NULL, 11, 'Eaque reprehenderit dolorem voluptas eos harum culpa hic.'),
(32, 30, NULL, 11, '4'),
(33, 27, NULL, 12, 'Commodi dolores ipsa suscipit quaerat recusandae.'),
(34, 29, NULL, 12, 'Ut accusantium perspiciatis laborum aut ea omnis.'),
(35, 30, NULL, 12, '7'),
(36, 27, NULL, 13, 'Voluptatem non quisquam doloremque quaerat.'),
(37, 29, NULL, 13, 'Illum ipsum id tempore consectetur.'),
(38, 30, NULL, 13, '9'),
(39, 27, NULL, 14, 'Beatae voluptatem fugit dolores repudiandae.'),
(40, 29, NULL, 14, 'Numquam laudantium ut ea repellat modi corrupti.'),
(41, 30, NULL, 14, '7'),
(42, 32, NULL, 15, '7'),
(43, 34, NULL, 15, '0'),
(44, 35, NULL, 15, '5'),
(45, 32, NULL, 16, '2'),
(46, 34, NULL, 16, '0'),
(47, 35, NULL, 16, '5'),
(48, 32, NULL, 17, '4'),
(49, 34, NULL, 17, '2'),
(50, 35, NULL, 17, '1'),
(59, 44, NULL, 22, '9'),
(60, 45, NULL, 22, 'Qui dicta est accusantium totam odit minus.'),
(61, 44, NULL, 23, '6'),
(62, 45, NULL, 23, 'Ducimus ut ea suscipit magni.'),
(63, 44, NULL, 24, '8'),
(64, 45, NULL, 24, 'Qui nostrum deserunt aut et id atque minima.'),
(65, 44, NULL, 25, '8'),
(66, 45, NULL, 25, 'Eum itaque voluptatibus qui nemo qui et.'),
(71, 51, NULL, 30, '7'),
(72, 52, NULL, 30, '4'),
(73, 54, NULL, 30, 'Omnis minima at provident itaque necessitatibus sed.'),
(74, 51, NULL, 31, '0'),
(75, 52, NULL, 31, '6'),
(76, 54, NULL, 31, 'Iste tempore ex explicabo officiis similique.'),
(77, 51, NULL, 32, '2'),
(78, 52, NULL, 32, '1'),
(79, 54, NULL, 32, 'Et reiciendis numquam voluptates nam iste.'),
(80, 51, NULL, 33, '9'),
(81, 52, NULL, 33, '8'),
(82, 54, NULL, 33, 'Ullam iusto labore alias doloribus est inventore cum et.'),
(83, 57, NULL, 34, '0'),
(84, 58, NULL, 34, '0'),
(85, 59, NULL, 34, 'Explicabo tempore impedit error.'),
(86, 61, NULL, 35, 'Qui nostrum nesciunt ad aperiam.'),
(87, 62, NULL, 35, 'Voluptatibus quia delectus aut numquam.'),
(88, 63, NULL, 35, 'Laudantium itaque ducimus nulla quae unde.'),
(89, 64, NULL, 35, 'Delectus ipsam ipsa ut at in ratione.'),
(90, 65, NULL, 35, 'Dolores illo mollitia necessitatibus culpa sapiente dolorum.'),
(91, 66, NULL, 36, 'Et id nemo ad delectus fugiat necessitatibus accusamus.'),
(92, 67, NULL, 36, 'Assumenda sint voluptate sed minus voluptatem excepturi.'),
(93, 69, NULL, 36, 'Reiciendis enim non aliquid.'),
(94, 70, NULL, 36, '4'),
(95, 71, NULL, 37, 'Quia dolorem autem dolor non.'),
(96, 72, NULL, 37, 'Ut tempore consectetur eos.'),
(97, 74, NULL, 37, '0'),
(98, 75, NULL, 37, 'Totam id at labore natus similique.'),
(99, 71, NULL, 38, 'Qui deleniti cum autem enim cupiditate dolores.'),
(100, 72, NULL, 38, 'Et enim hic quod et asperiores non aperiam.'),
(101, 74, NULL, 38, '5'),
(102, 75, NULL, 38, 'Laudantium minima qui ea ullam.'),
(103, 71, NULL, 39, 'Beatae qui dolor molestias et et accusantium vel.'),
(104, 72, NULL, 39, 'Qui laborum ut voluptatibus aspernatur molestias adipisci.'),
(105, 74, NULL, 39, '4'),
(106, 75, NULL, 39, 'Error minima perferendis doloremque autem recusandae accusamus.'),
(107, 71, NULL, 40, 'Excepturi qui ex corrupti provident quidem recusandae non sint.'),
(108, 72, NULL, 40, 'Molestiae magnam placeat non nihil et et.'),
(109, 74, NULL, 40, '9'),
(110, 75, NULL, 40, 'Doloremque est reprehenderit ipsam id asperiores.'),
(111, 80, 93, 42, 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `template_id`, `user_id`, `content`, `created_at`) VALUES
(1, 1, 11, 'Ad dolorem reprehenderit voluptatem.', '2025-05-08 05:19:16'),
(2, 1, 13, 'At explicabo voluptas sit iusto optio alias.', '2025-06-06 18:36:02'),
(3, 2, 14, 'Dolorem sed omnis nihil numquam voluptatem et maiores.', '2025-05-19 06:33:49'),
(4, 2, 12, 'Perspiciatis perferendis ipsa reiciendis perferendis voluptatem sit.', '2025-05-31 03:01:22'),
(5, 3, 15, 'Similique eaque ea est iste dolorem cum.', '2025-05-28 21:41:22'),
(6, 3, 14, 'Sit odit alias aut sint ratione libero quia.', '2025-06-08 13:08:04'),
(9, 5, 12, 'Dolorum magnam doloremque labore dignissimos modi rerum consequatur.', '2025-04-20 09:33:42'),
(10, 5, 14, 'Vitae ut sit perferendis voluptates quod.', '2025-04-23 16:15:38'),
(11, 6, 11, 'Inventore molestiae voluptatem inventore et.', '2025-05-21 10:12:47'),
(12, 6, 15, 'Et eveniet adipisci facere et.', '2025-04-23 12:03:44'),
(13, 7, 14, 'Iusto dicta et minus voluptatem reprehenderit.', '2025-06-20 21:19:16'),
(14, 7, 14, 'Nostrum quaerat laborum et et aut.', '2025-02-05 10:26:11'),
(17, 9, 12, 'Quisquam non rerum a repellat et.', '2025-05-03 22:58:35'),
(18, 9, 13, 'Et architecto qui sed quasi.', '2025-04-10 03:57:30'),
(19, 10, 13, 'Mollitia enim sed voluptatibus ea ipsa harum vero.', '2025-06-14 08:56:37'),
(20, 10, 13, 'Consequuntur rem architecto aspernatur aliquam ut similique et.', '2025-06-17 04:46:44'),
(21, 11, 15, 'Nihil odit qui ea.', '2025-05-15 18:41:58'),
(22, 11, 11, 'Ut maxime tempora quod unde est.', '2025-02-06 16:38:26'),
(23, 12, 15, 'Quia repellat non et et repellat porro modi suscipit.', '2025-05-23 14:44:37'),
(24, 12, 14, 'Ex quasi sunt unde sit perferendis accusantium.', '2025-04-27 12:26:16'),
(25, 13, 12, 'Aperiam fuga quia excepturi sed animi.', '2025-05-15 02:03:53'),
(26, 13, 14, 'Sit sapiente autem vitae ut tempora.', '2025-02-02 03:35:57'),
(27, 14, 13, 'Sunt ea recusandae harum nostrum qui quo.', '2025-03-25 03:06:43'),
(28, 14, 15, 'Commodi et dolorum perferendis beatae maiores et.', '2025-03-18 10:11:45'),
(29, 15, 12, 'Natus assumenda eum dignissimos placeat eius.', '2025-05-14 15:36:31'),
(30, 15, 13, 'Quisquam laborum qui fuga id nesciunt.', '2025-06-15 11:39:02'),
(31, 10, 17, 'A test Comment', '2025-07-01 08:14:07'),
(32, 18, 17, 'I Loved it !', '2025-07-02 04:42:41');

-- --------------------------------------------------------

--
-- Table structure for table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20250621233153', '2025-06-22 01:32:52', 12638),
('DoctrineMigrations\\Version20250621235915', '2025-06-22 01:59:25', 128),
('DoctrineMigrations\\Version20250628182305', '2025-06-28 20:24:42', 210);

-- --------------------------------------------------------

--
-- Table structure for table `form_submit`
--

CREATE TABLE `form_submit` (
  `id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `form_submit`
--

INSERT INTO `form_submit` (`id`, `template_id`, `user_id`, `created_at`) VALUES
(1, 1, 12, '2025-05-29 02:19:40'),
(2, 2, 12, '2025-05-24 07:14:54'),
(3, 3, 15, '2025-06-12 09:59:09'),
(4, 3, 12, '2025-05-26 10:06:16'),
(5, 3, 13, '2025-05-23 01:50:56'),
(6, 3, 14, '2025-06-20 22:12:23'),
(9, 5, 15, '2025-06-04 19:49:48'),
(10, 5, 13, '2025-05-20 19:27:18'),
(11, 6, 11, '2025-04-11 02:23:54'),
(12, 6, 13, '2025-03-28 07:58:17'),
(13, 6, 11, '2025-03-21 02:53:00'),
(14, 6, 13, '2025-05-14 13:41:45'),
(15, 7, 11, '2025-03-19 06:44:16'),
(16, 7, 12, '2025-05-23 15:33:32'),
(17, 7, 15, '2025-04-22 10:49:09'),
(22, 9, 15, '2025-04-22 15:55:43'),
(23, 9, 12, '2025-05-07 15:39:42'),
(24, 9, 12, '2025-05-03 14:01:14'),
(25, 9, 15, '2025-03-27 16:25:22'),
(26, 10, 11, '2025-06-17 11:50:11'),
(27, 10, 15, '2025-06-13 15:14:31'),
(28, 10, 15, '2025-06-21 07:12:37'),
(29, 10, 11, '2025-06-19 08:06:44'),
(30, 11, 11, '2025-03-07 23:16:23'),
(31, 11, 14, '2025-02-10 17:00:36'),
(32, 11, 11, '2025-05-23 03:48:56'),
(33, 11, 12, '2025-04-19 02:33:23'),
(34, 12, 15, '2025-05-07 19:07:00'),
(35, 13, 14, '2025-04-19 05:27:05'),
(36, 14, 13, '2025-01-13 12:08:12'),
(37, 15, 13, '2025-04-13 09:23:49'),
(38, 15, 11, '2025-06-19 12:08:09'),
(39, 15, 14, '2025-04-29 21:19:55'),
(40, 15, 14, '2025-03-16 15:24:52'),
(42, 18, 17, '2025-07-02 06:44:56');

-- --------------------------------------------------------

--
-- Table structure for table `like`
--

CREATE TABLE `like` (
  `id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `like`
--

INSERT INTO `like` (`id`, `template_id`, `user_id`) VALUES
(1, 1, 13),
(2, 1, 15),
(3, 2, 15),
(4, 2, 13),
(5, 3, 11),
(6, 3, 15),
(9, 5, 14),
(10, 5, 15),
(11, 6, 15),
(12, 6, 14),
(13, 7, 14),
(14, 7, 13),
(17, 9, 14),
(18, 9, 15),
(19, 10, 11),
(20, 10, 11),
(21, 11, 13),
(22, 11, 12),
(23, 12, 11),
(24, 12, 15),
(25, 13, 13),
(26, 13, 12),
(27, 14, 13),
(28, 14, 12),
(29, 15, 11),
(30, 15, 14),
(32, 10, 17),
(34, 18, 17);

-- --------------------------------------------------------

--
-- Table structure for table `option`
--

CREATE TABLE `option` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `text` longtext NOT NULL,
  `position` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `option`
--

INSERT INTO `option` (`id`, `question_id`, `text`, `position`) VALUES
(1, 3, 'Ut', '1'),
(2, 3, 'Ipsam', '2'),
(3, 3, 'Rerum', '3'),
(4, 9, 'Quisquam', '1'),
(5, 9, 'Mollitia', '2'),
(6, 9, 'Aliquam', '3'),
(7, 10, 'Voluptas', '1'),
(8, 10, 'Ipsam', '2'),
(9, 10, 'Repudiandae', '3'),
(10, 13, 'Voluptatibus', '1'),
(11, 13, 'Molestiae', '2'),
(12, 13, 'Voluptates', '3'),
(13, 14, 'Enim', '1'),
(14, 14, 'Quo', '2'),
(15, 14, 'Et', '3'),
(16, 15, 'Et', '1'),
(17, 15, 'Expedita', '2'),
(18, 15, 'Dolorem', '3'),
(22, 23, 'Ea', '1'),
(23, 23, 'Eius', '2'),
(24, 23, 'Nihil', '3'),
(25, 25, 'Eius', '1'),
(26, 25, 'Quo', '2'),
(27, 25, 'Vel', '3'),
(28, 26, 'Deserunt', '1'),
(29, 26, 'Voluptas', '2'),
(30, 26, 'Reprehenderit', '3'),
(31, 28, 'Aut', '1'),
(32, 28, 'Provident', '2'),
(33, 28, 'Ut', '3'),
(34, 31, 'Et', '1'),
(35, 31, 'Magnam', '2'),
(36, 31, 'Quae', '3'),
(37, 33, 'Dolores', '1'),
(38, 33, 'Fugit', '2'),
(39, 33, 'Eum', '3'),
(49, 41, 'Aliquid', '1'),
(50, 41, 'Repellendus', '2'),
(51, 41, 'Nisi', '3'),
(52, 42, 'Enim', '1'),
(53, 42, 'Velit', '2'),
(54, 42, 'Quod', '3'),
(55, 43, 'Dignissimos', '1'),
(56, 43, 'Harum', '2'),
(57, 43, 'Qui', '3'),
(70, 53, 'Unde', '1'),
(71, 53, 'Aut', '2'),
(72, 53, 'Aut', '3'),
(73, 55, 'Excepturi', '1'),
(74, 55, 'Aliquam', '2'),
(75, 55, 'Itaque', '3'),
(76, 56, 'Dolores', '1'),
(77, 56, 'Molestiae', '2'),
(78, 56, 'Quis', '3'),
(79, 60, 'Velit', '1'),
(80, 60, 'Voluptate', '2'),
(81, 60, 'Ut', '3'),
(82, 68, 'Laudantium', '1'),
(83, 68, 'Tempore', '2'),
(84, 68, 'Vitae', '3'),
(85, 73, 'Asperiores', '1'),
(86, 73, 'Deserunt', '2'),
(87, 73, 'Temporibus', '3'),
(93, 80, 'Yes', '0'),
(94, 80, 'No', '1'),
(95, 81, 'Non', '0'),
(96, 81, 'Et', '1'),
(97, 81, 'Alias', '2'),
(98, 82, 'Nostrum', '0'),
(99, 82, 'Debitis', '1'),
(100, 82, 'Nostrum', '2'),
(101, 83, 'Ea', '0'),
(102, 83, 'Earum', '1'),
(103, 83, 'Adipisci', '2'),
(104, 85, 'In', '0'),
(105, 85, 'Iure', '1'),
(106, 85, 'Qui', '2');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `show_in_results` tinyint(1) NOT NULL,
  `position` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id`, `template_id`, `type`, `title`, `description`, `show_in_results`, `position`) VALUES
(1, 1, 'integer', 'Similique labore et quam vitae et.', 'Repudiandae quo omnis omnis alias reiciendis ut odit.', 0, '1'),
(2, 1, 'integer', 'Quibusdam repellendus sunt tempora quis ea.', 'Voluptatem quo est eos iusto quibusdam accusantium.', 0, '2'),
(3, 1, 'checkbox', 'Est et ea cumque atque labore.', 'Ducimus velit ullam minima possimus nobis aperiam eum.', 0, '3'),
(4, 1, 'integer', 'Qui maxime natus quo at dolor.', 'Aut delectus quia fugiat.', 1, '4'),
(5, 1, 'string', 'Non porro exercitationem.', 'Ratione reiciendis ut corrupti distinctio quisquam vero.', 0, '5'),
(6, 2, 'string', 'Ut beatae odit quas in perspiciatis.', 'Et mollitia in sint quibusdam exercitationem.', 0, '1'),
(7, 2, 'integer', 'Quisquam harum hic voluptatum.', 'Consectetur dolorem vel ducimus aut.', 1, '2'),
(8, 2, 'string', 'Architecto cum et quae.', 'Quibusdam vero laborum maxime voluptatem voluptatibus hic aspernatur.', 0, '3'),
(9, 2, 'checkbox', 'Quod sunt temporibus.', 'Excepturi fugiat tenetur eveniet illo voluptatem voluptatem quos.', 1, '4'),
(10, 2, 'checkbox', 'Ut facilis quae natus voluptas.', 'Et nisi eligendi quam sunt id alias ducimus.', 0, '5'),
(11, 3, 'integer', 'Quo consequatur adipisci velit.', 'Voluptates quo animi aut laboriosam voluptatum earum.', 0, '1'),
(12, 3, 'integer', 'Dolor dolores eos praesentium inventore.', 'Saepe ut minima rerum autem.', 0, '2'),
(13, 3, 'checkbox', 'Voluptatem voluptates impedit fugit libero occaecati.', 'Iste asperiores et occaecati praesentium est ut modi.', 0, '3'),
(14, 3, 'checkbox', 'Minima quam assumenda quia modi nesciunt.', 'Quisquam qui autem a magni eligendi modi et.', 1, '4'),
(15, 3, 'radio', 'Unde dolores dignissimos numquam.', 'Consequatur hic quaerat enim quas.', 1, '5'),
(21, 5, 'string', 'Et hic corporis quaerat fugit eos.', 'Saepe quod ut ut et quibusdam cum.', 0, '1'),
(22, 5, 'string', 'Quia sunt beatae.', 'Nihil dolorem distinctio soluta recusandae suscipit.', 1, '2'),
(23, 5, 'radio', 'Totam debitis et saepe.', 'Quia quibusdam et cupiditate dolor officia et alias et.', 0, '3'),
(24, 5, 'string', 'Consectetur sunt in vero.', 'Veritatis necessitatibus nihil recusandae aperiam assumenda adipisci.', 0, '4'),
(25, 5, 'radio', 'Molestiae officiis itaque unde.', 'Est quia quis quidem ratione aut.', 0, '5'),
(26, 6, 'radio', 'Accusamus vel impedit sequi.', 'Voluptatum quaerat voluptatem repellendus temporibus.', 0, '1'),
(27, 6, 'string', 'Enim ex perferendis.', 'Nemo ad voluptatum omnis facilis in et.', 0, '2'),
(28, 6, 'radio', 'Corrupti amet nihil expedita voluptatem.', 'Atque facilis tempore velit est.', 0, '3'),
(29, 6, 'text', 'Placeat aut eaque quis.', 'Tempore molestias aut ut incidunt ullam sapiente ut.', 0, '4'),
(30, 6, 'integer', 'Distinctio quaerat non hic.', 'Enim deserunt vel odit ipsa.', 1, '5'),
(31, 7, 'checkbox', 'Voluptatem in sit.', 'Ullam consequatur nihil autem et quod.', 0, '1'),
(32, 7, 'integer', 'Tempora vero qui doloribus nostrum.', 'Et expedita deleniti quae eius blanditiis aperiam.', 0, '2'),
(33, 7, 'checkbox', 'Enim iusto commodi cum sunt.', 'Temporibus libero rerum odio aut.', 1, '3'),
(34, 7, 'integer', 'Velit suscipit et.', 'Magni voluptas et impedit iste dolores hic.', 1, '4'),
(35, 7, 'integer', 'Et nam corporis natus excepturi sunt.', 'Earum nulla commodi beatae rerum officiis vero vitae.', 1, '5'),
(41, 9, 'checkbox', 'Aut quos facere commodi.', 'Voluptatem saepe facilis laudantium amet dolores commodi.', 0, '1'),
(42, 9, 'radio', 'Fuga corporis vitae numquam.', 'Laborum iure beatae eaque non qui maiores dicta.', 0, '2'),
(43, 9, 'radio', 'Itaque qui natus fuga corporis similique.', 'Aut sunt quia omnis reiciendis.', 0, '3'),
(44, 9, 'integer', 'Quibusdam ut voluptatibus.', 'Cum deserunt similique dolor voluptatem.', 0, '4'),
(45, 9, 'string', 'Accusamus expedita sint ullam.', 'Perspiciatis aliquam a quis voluptas voluptate qui.', 0, '5'),
(51, 11, 'integer', 'Ipsam error illum beatae qui.', 'Praesentium ipsa unde esse velit corporis.', 0, '1'),
(52, 11, 'integer', 'Voluptas natus culpa aspernatur.', 'Est qui omnis sunt aut error aliquid exercitationem.', 0, '2'),
(53, 11, 'checkbox', 'Repellat explicabo non.', 'Officia iure iste debitis quia explicabo quasi possimus neque.', 0, '3'),
(54, 11, 'text', 'Dolorem laboriosam neque repellendus non.', 'Quasi ipsa aspernatur necessitatibus ullam.', 0, '4'),
(55, 11, 'checkbox', 'In facere occaecati saepe.', 'Autem omnis id corrupti velit hic voluptas expedita.', 1, '5'),
(56, 12, 'checkbox', 'Tempore et aliquid.', 'Soluta ea soluta officiis.', 0, '1'),
(57, 12, 'integer', 'Voluptatem est iusto.', 'Assumenda quaerat in ut illum qui occaecati omnis.', 0, '2'),
(58, 12, 'integer', 'A et praesentium nulla.', 'Ut perferendis architecto qui et.', 0, '3'),
(59, 12, 'text', 'Ea officiis quod quo.', 'Atque natus iste repudiandae rerum laboriosam.', 0, '4'),
(60, 12, 'radio', 'Id necessitatibus dolorum nisi.', 'Veritatis qui enim voluptatem dolor architecto.', 0, '5'),
(61, 13, 'text', 'At beatae ut.', 'Sunt eius ab eum in corporis nesciunt.', 0, '1'),
(62, 13, 'text', 'Quod ab quidem sit porro.', 'Mollitia quisquam sunt blanditiis sit dolorem.', 0, '2'),
(63, 13, 'string', 'Sed ducimus non sed eaque.', 'Optio corporis quisquam eius nulla.', 0, '3'),
(64, 13, 'string', 'Atque repellendus cupiditate necessitatibus ratione.', 'Aperiam corrupti quia consequatur nesciunt sit.', 0, '4'),
(65, 13, 'text', 'Adipisci voluptatem voluptas qui.', 'Optio adipisci est iusto laboriosam ut et tempore.', 1, '5'),
(66, 14, 'string', 'Sint cumque voluptate non.', 'Aut sapiente molestiae ut nisi tempora voluptas quas.', 1, '1'),
(67, 14, 'text', 'Nisi et dignissimos autem quo non.', 'Accusamus quo accusamus sint sunt adipisci.', 0, '2'),
(68, 14, 'checkbox', 'Ipsam sunt sit qui qui ipsa.', 'Rerum dolorum quos accusantium vero.', 1, '3'),
(69, 14, 'text', 'Architecto rem iure dolores ducimus.', 'Vel omnis dolorum culpa sed iste sequi et distinctio.', 0, '4'),
(70, 14, 'integer', 'Ut ex asperiores quia.', 'Voluptas vel esse eum maxime dolor.', 1, '5'),
(71, 15, 'text', 'Esse in laudantium doloremque.', 'Sint eos repudiandae velit sunt architecto.', 0, '1'),
(72, 15, 'string', 'Quia dolores modi vel.', 'Omnis et illo neque est quia expedita.', 1, '2'),
(73, 15, 'radio', 'Corrupti incidunt quia qui est.', 'Voluptas molestiae aut quia earum.', 1, '3'),
(74, 15, 'integer', 'Et a provident quasi.', 'Eligendi quidem nisi omnis iste autem aut.', 0, '4'),
(75, 15, 'text', 'Optio est adipisci.', 'Esse facilis ipsam totam.', 1, '5'),
(80, 18, 'radio', 'Did you like the project?', 'N/A', 0, '0'),
(81, 10, 'radio', 'Perferendis velit aut voluptatibus beatae nemo.', 'N/A', 0, '0'),
(82, 10, 'radio', 'Et voluptatum et commodi eos consequatur.', 'N/A', 0, '1'),
(83, 10, 'radio', 'Facere et reprehenderit tempora ea reprehenderit.', 'N/A', 0, '2'),
(84, 10, 'text', 'Inventore cupiditate natus aspernatur aliquam.', 'N/A', 0, '3'),
(85, 10, 'checkbox', 'Numquam ut nihil enim.', 'N/A', 0, '4');

-- --------------------------------------------------------

--
-- Table structure for table `template`
--

CREATE TABLE `template` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_public` tinyint(1) NOT NULL,
  `version` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `last_updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `template`
--

INSERT INTO `template` (`id`, `user_id`, `topic`, `title`, `description`, `image`, `is_public`, `version`, `created_at`, `last_updated_at`) VALUES
(1, 11, 'Poll', 'Repellat et ut quisquam.', 'Quidem qui sunt et voluptatem tempora nam voluptas. Vel eum excepturi quod molestiae assumenda. Dolor fugit minus et dolores est voluptatem. Molestias aut illo praesentium numquam blanditiis.', 'https://via.placeholder.com/600x400.png/0044ff?text=architecto', 1, '1', '2025-03-13 19:42:50', '2025-01-06 02:28:45'),
(2, 17, 'Feedback', 'Quia quis explicabo possimus.', 'Exercitationem quia qui consequuntur. Quasi et blanditiis est rerum quisquam enim. Illo tenetur eum et est fugiat quis. Hic recusandae quos et ipsa voluptas sit a.', 'https://via.placeholder.com/600x400.png/00ddbb?text=nihil', 0, '1', '2025-03-16 00:38:15', '2025-01-28 06:31:33'),
(3, 11, 'Poll', 'Voluptatem optio eos.', 'Ea temporibus velit debitis assumenda porro doloribus. Sint et odio at id. Consectetur cum ullam consequuntur ea dolorum. Quisquam cum nihil repellat eius.', 'https://via.placeholder.com/600x400.png/00cccc?text=eos', 1, '1', '2025-05-18 18:18:39', '2025-01-23 08:59:06'),
(5, 17, 'Survey', 'Incidunt dolorem ut eius.', 'Eos occaecati voluptatem provident aperiam. Officiis nihil iste architecto enim. Animi id omnis dicta atque.', 'https://via.placeholder.com/600x400.png/00dd44?text=eos', 0, '1', '2025-04-12 12:08:06', '2025-02-08 06:33:16'),
(6, 12, 'Poll', 'Assumenda labore numquam aut.', 'Quam nam et eveniet qui. Est quisquam rerum asperiores et sequi. Inventore atque aut velit quo quos ut consequuntur. Eum a libero est consequuntur.', 'https://via.placeholder.com/600x400.png/00cc11?text=omnis', 1, '1', '2025-03-20 08:16:44', '2025-03-30 14:12:59'),
(7, 13, 'Poll', 'Earum placeat inventore fugit.', 'Animi quam error qui aut doloribus. Libero et illum aut quasi exercitationem.', 'https://via.placeholder.com/600x400.png/0033bb?text=ipsa', 1, '1', '2025-01-23 05:17:06', '2025-05-11 12:06:04'),
(9, 13, 'Survey', 'Ad incidunt.', 'Accusamus quae ut molestiae exercitationem voluptatem praesentium. Natus et et aliquid vitae adipisci eius ipsam. Eum amet autem libero optio delectus quia. Quisquam nobis totam rerum qui eum eos laborum.', 'https://via.placeholder.com/600x400.png/0011bb?text=dicta', 1, '1', '2024-12-27 15:56:31', '2025-02-25 17:12:54'),
(10, 17, 'survey', 'Doloremque aspernatur.', 'Magni ut dolores excepturi. Corporis totam aut beatae sit hic quo tenetur. Omnis nemo distinctio sequi praesentium magni quis non. Aut amet ipsa numquam.', 'uploads/forms/unsplash-Denys-Nevozhai_6864830c27368.jpg', 1, '2', '2025-06-11 23:03:19', '2025-07-02 06:53:32'),
(11, 14, 'Survey', 'Aperiam laudantium culpa id.', 'Laborum est ab enim cumque ullam. Ipsam sapiente distinctio reiciendis. Voluptate molestiae nobis consectetur culpa.', 'https://via.placeholder.com/600x400.png/0088bb?text=nemo', 1, '1', '2025-01-19 09:00:41', '2024-12-30 16:45:54'),
(12, 14, 'Survey', 'Cum amet delectus dolorem.', 'Voluptatem recusandae soluta non iusto eius ab quia vel. Voluptatem saepe veniam dolorem voluptas ut atque. Occaecati sit eveniet qui fugit. Quasi dolores doloribus saepe voluptatem et tempore omnis.', 'https://via.placeholder.com/600x400.png/00aa11?text=nihil', 1, '1', '2025-04-02 14:06:35', '2025-05-30 05:16:34'),
(13, 17, 'Survey', 'Ipsum temporibus magni est quam.', 'Sit nisi ex est omnis quaerat natus. Expedita omnis dicta dolores eaque delectus dignissimos placeat rerum. Rem quia accusantium voluptatem eum ut modi. Laudantium in doloribus est sit repellat eos aliquid.', 'https://via.placeholder.com/600x400.png/0099cc?text=iste', 0, '1', '2025-01-29 23:57:43', '2025-04-23 06:46:34'),
(14, 17, 'Survey', 'Quae neque eum excepturi.', 'Et distinctio culpa est ab aut. Dolorem quae fugiat veniam eos voluptates aliquam corrupti. Beatae nisi doloribus ea quia odit.', 'https://via.placeholder.com/600x400.png/00cc55?text=nemo', 0, '1', '2025-01-09 22:22:37', '2025-03-18 13:28:11'),
(15, 15, 'Survey', 'Consequatur eum et qui.', 'Nisi cum doloribus vel inventore. Temporibus quisquam ipsa nobis voluptate quasi. Nihil omnis cupiditate beatae occaecati vel. Fugit rerum dolores aliquid et quis sed.', 'https://via.placeholder.com/600x400.png/009922?text=aut', 1, '1', '2025-02-18 06:42:11', '2025-02-23 05:59:44'),
(18, 17, 'poll', 'My First Form', 'Excited to submit. Updating also done !', 'uploads/forms/unsplash-Jesse-Bowser_686440aa7396d.jpg', 1, '4', '2025-07-02 02:10:18', '2025-07-02 04:40:11');

-- --------------------------------------------------------

--
-- Table structure for table `template_tag`
--

CREATE TABLE `template_tag` (
  `id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `template_tag`
--

INSERT INTO `template_tag` (`id`, `template_id`, `tag`) VALUES
(1, 1, 'tenetur'),
(2, 1, 'in'),
(3, 1, 'dolorem'),
(4, 2, 'aliquam'),
(5, 2, 'neque'),
(6, 2, 'quibusdam'),
(7, 3, 'placeat'),
(8, 3, 'eos'),
(9, 3, 'minima'),
(13, 5, 'laboriosam'),
(14, 5, 'vitae'),
(15, 5, 'perspiciatis'),
(16, 6, 'voluptatibus'),
(17, 6, 'fugiat'),
(18, 6, 'architecto'),
(19, 7, 'quas'),
(20, 7, 'rerum'),
(21, 7, 'non'),
(25, 9, 'dolores'),
(26, 9, 'a'),
(27, 9, 'perferendis'),
(31, 11, 'quasi'),
(32, 11, 'quo'),
(33, 11, 'minima'),
(34, 12, 'distinctio'),
(35, 12, 'necessitatibus'),
(36, 12, 'veritatis'),
(37, 13, 'sed'),
(38, 13, 'quos'),
(39, 13, 'ex'),
(40, 14, 'vitae'),
(41, 14, 'dignissimos'),
(42, 14, 'unde'),
(43, 15, 'asperiores'),
(44, 15, 'ut'),
(45, 15, 'reiciendis'),
(60, 18, 'firstform'),
(61, 18, 'beautiful'),
(62, 10, 'architecto'),
(63, 10, 'repudiandae'),
(64, 10, 'reiciendis');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `created_at`, `roles`) VALUES
(11, 'hblanda@gerlach.biz', '$2y$13$k3RGKUyJ2YsktvvNmTVcKOXOrEJ1AVdUuqVNBh0qIlDxaW6SB8r/C', '2024-08-28 02:20:45', '[\"ROLE_USER\"]'),
(12, 'vwolff@langosh.com', '$2y$13$k3RGKUyJ2YsktvvNmTVcKOXOrEJ1AVdUuqVNBh0qIlDxaW6SB8r/C', '2025-04-01 06:07:32', '[\"ROLE_USER\"]'),
(13, 'torrance.reichert@hettinger.com', '$2y$13$k3RGKUyJ2YsktvvNmTVcKOXOrEJ1AVdUuqVNBh0qIlDxaW6SB8r/C', '2024-07-21 11:43:42', '[\"ROLE_USER\"]'),
(14, 'hassie.bernhard@yahoo.com', '$2y$13$k3RGKUyJ2YsktvvNmTVcKOXOrEJ1AVdUuqVNBh0qIlDxaW6SB8r/C', '2025-04-27 21:47:26', '[\"ROLE_USER\"]'),
(15, 'brenden.kulas@gmail.com', '$2y$13$k3RGKUyJ2YsktvvNmTVcKOXOrEJ1AVdUuqVNBh0qIlDxaW6SB8r/C', '2025-05-05 06:35:50', '[\"ROLE_USER\"]'),
(17, 'test1@gmail.com', '$2y$13$k3RGKUyJ2YsktvvNmTVcKOXOrEJ1AVdUuqVNBh0qIlDxaW6SB8r/C', '2025-06-28 21:24:35', '[\"ROLE_USER\"]');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DADD4A251E27F6BF` (`question_id`),
  ADD KEY `IDX_DADD4A25D3823036` (`choosen_option_id`),
  ADD KEY `IDX_DADD4A25FBABB4DB` (`form_submit_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526C5DA0FB8` (`template_id`),
  ADD KEY `IDX_9474526CA76ED395` (`user_id`);

--
-- Indexes for table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `form_submit`
--
ALTER TABLE `form_submit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3DDF67ED5DA0FB8` (`template_id`),
  ADD KEY `IDX_3DDF67EDA76ED395` (`user_id`);

--
-- Indexes for table `like`
--
ALTER TABLE `like`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_AC6340B35DA0FB8` (`template_id`),
  ADD KEY `IDX_AC6340B3A76ED395` (`user_id`);

--
-- Indexes for table `option`
--
ALTER TABLE `option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5A8600B01E27F6BF` (`question_id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B6F7494E5DA0FB8` (`template_id`);

--
-- Indexes for table `template`
--
ALTER TABLE `template`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_97601F83A76ED395` (`user_id`);

--
-- Indexes for table `template_tag`
--
ALTER TABLE `template_tag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_ADE23EA15DA0FB8` (`template_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `form_submit`
--
ALTER TABLE `form_submit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `like`
--
ALTER TABLE `like`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `option`
--
ALTER TABLE `option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `template`
--
ALTER TABLE `template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `template_tag`
--
ALTER TABLE `template_tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `FK_DADD4A251E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`),
  ADD CONSTRAINT `FK_DADD4A25D3823036` FOREIGN KEY (`choosen_option_id`) REFERENCES `option` (`id`),
  ADD CONSTRAINT `FK_DADD4A25FBABB4DB` FOREIGN KEY (`form_submit_id`) REFERENCES `form_submit` (`id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C5DA0FB8` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`),
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `form_submit`
--
ALTER TABLE `form_submit`
  ADD CONSTRAINT `FK_3DDF67ED5DA0FB8` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`),
  ADD CONSTRAINT `FK_3DDF67EDA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `FK_AC6340B35DA0FB8` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`),
  ADD CONSTRAINT `FK_AC6340B3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `option`
--
ALTER TABLE `option`
  ADD CONSTRAINT `FK_5A8600B01E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`);

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `FK_B6F7494E5DA0FB8` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`);

--
-- Constraints for table `template`
--
ALTER TABLE `template`
  ADD CONSTRAINT `FK_97601F83A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `template_tag`
--
ALTER TABLE `template_tag`
  ADD CONSTRAINT `FK_ADE23EA15DA0FB8` FOREIGN KEY (`template_id`) REFERENCES `template` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
