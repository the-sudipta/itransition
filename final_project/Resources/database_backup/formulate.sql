-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2025 at 11:36 PM
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
(61, 44, NULL, 23, '6'),
(62, 45, NULL, 23, 'Ducimus ut ea suscipit magni.'),
(63, 44, NULL, 24, '8'),
(64, 45, NULL, 24, 'Qui nostrum deserunt aut et id atque minima.'),
(111, 80, 93, 42, 'Yes'),
(112, 91, 118, 43, 'dasdas'),
(113, 92, NULL, 43, 'fasdfsdfas'),
(114, 93, 93, 43, 'Yes'),
(115, 93, 121, 43, 'Nostrum'),
(116, 93, 122, 43, 'asdfasd');

-- --------------------------------------------------------

--
-- Table structure for table `api_token`
--

CREATE TABLE `api_token` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(256) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `api_token`
--

INSERT INTO `api_token` (`id`, `user_id`, `token`, `created_at`) VALUES
(1, 23, 'c1aa206c2964d5ffbc51ce021ea29a44aa5b31d532a817331bb4f142b0cb53d4', '2025-07-16 00:45:36'),
(2, 17, '9bc8f05f91e6fa1b28aa52ddbc8ceab7e8310e7e9f69e80d3e9a695222afe67d', '2025-07-16 00:54:37');

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
(4, 2, 12, 'Perspiciatis perferendis ipsa reiciendis perferendis voluptatem sit.', '2025-05-31 03:01:22'),
(9, 5, 12, 'Dolorum magnam doloremque labore dignissimos modi rerum consequatur.', '2025-04-20 09:33:42'),
(11, 6, 11, 'Inventore molestiae voluptatem inventore et.', '2025-05-21 10:12:47'),
(17, 9, 12, 'Quisquam non rerum a repellat et.', '2025-05-03 22:58:35'),
(18, 9, 13, 'Et architecto qui sed quasi.', '2025-04-10 03:57:30'),
(19, 10, 13, 'Mollitia enim sed voluptatibus ea ipsa harum vero.', '2025-06-14 08:56:37'),
(20, 10, 13, 'Consequuntur rem architecto aspernatur aliquam ut similique et.', '2025-06-17 04:46:44'),
(31, 10, 17, 'A test Comment', '2025-07-01 08:14:07'),
(32, 18, 17, 'I Loved it !', '2025-07-02 04:42:41'),
(33, 18, 17, 'Test 2', '2025-07-03 13:00:02'),
(34, 21, 21, 'Hii there !', '2025-07-08 19:45:12');

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
('DoctrineMigrations\\Version20250628182305', '2025-06-28 20:24:42', 210),
('DoctrineMigrations\\Version20250713062850', '2025-07-13 08:29:05', 935),
('DoctrineMigrations\\Version20250713103518', '2025-07-13 12:35:31', 946),
('DoctrineMigrations\\Version20250713120707', '2025-07-13 14:07:12', 1076),
('DoctrineMigrations\\Version20250713122542', '2025-07-13 14:25:51', 427),
('DoctrineMigrations\\Version20250715162828', '2025-07-15 18:28:36', 979);

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
(10, 5, 13, '2025-05-20 19:27:18'),
(11, 6, 11, '2025-04-11 02:23:54'),
(12, 6, 13, '2025-03-28 07:58:17'),
(13, 6, 11, '2025-03-21 02:53:00'),
(14, 6, 13, '2025-05-14 13:41:45'),
(15, 7, 11, '2025-03-19 06:44:16'),
(16, 7, 12, '2025-05-23 15:33:32'),
(23, 9, 12, '2025-05-07 15:39:42'),
(24, 9, 12, '2025-05-03 14:01:14'),
(42, 18, 17, '2025-07-02 06:44:56'),
(43, 22, 21, '2025-07-08 19:48:53');

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
(4, 2, 13),
(5, 3, 11),
(14, 7, 13),
(19, 10, 11),
(20, 10, 11),
(32, 10, 17),
(35, 18, 17),
(37, 21, 21);

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
(106, 85, 'Qui', '2'),
(107, 86, 'Russia', '0'),
(108, 86, 'India', '1'),
(109, 86, 'Thailand', '2'),
(110, 86, 'Germany', '3'),
(111, 86, 'Switzerland', '4'),
(112, 88, 'ASFDSFSDF', '0'),
(113, 88, 'ASFDFADSFSD', '1'),
(114, 88, 'SDFSASDGA', '2'),
(115, 89, 'ASDFASF', '0'),
(116, 89, 'ASDFFASDFAA', '1'),
(117, 89, 'ASDFASDFASDA SD FASDFASF DSA F', '2'),
(118, 91, 'dasdas', '0'),
(119, 91, 'It is best', '1'),
(120, 91, 'No', '2'),
(121, 93, 'Nostrum', '0'),
(122, 93, 'asdfasd', '1'),
(123, 93, 'ASDFASDFASDA SD FASDFASF DSA F', '2'),
(124, 93, 'fasfa', '3');

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
(80, 18, 'radio', 'Did you like the project?', 'N/A', 0, '0'),
(81, 10, 'radio', 'Perferendis velit aut voluptatibus beatae nemo.', 'N/A', 0, '0'),
(82, 10, 'radio', 'Et voluptatum et commodi eos consequatur.', 'N/A', 0, '1'),
(83, 10, 'radio', 'Facere et reprehenderit tempora ea reprehenderit.', 'N/A', 0, '2'),
(84, 10, 'text', 'Inventore cupiditate natus aspernatur aliquam.', 'N/A', 0, '3'),
(85, 10, 'checkbox', 'Numquam ut nihil enim.', 'N/A', 0, '4'),
(86, 19, 'radio', 'Which country are you from?', 'N/A', 0, '0'),
(87, 19, 'text', 'What is your name?', 'N/A', 0, '1'),
(88, 20, 'radio', 'ASFASDFASD', 'N/A', 0, '0'),
(89, 20, 'checkbox', 'AFASDF ASDFAS ASD FASD', 'N/A', 0, '1'),
(91, 22, 'radio', 'My First Question', 'N/A', 0, '0'),
(92, 22, 'text', 'fasdfasd saf asdf asd', 'N/A', 0, '2'),
(93, 22, 'checkbox', 'This is my 2nd Question', 'N/A', 0, '1'),
(94, 21, 'text', ' ASFASDF SADFSAD F ASSADF ASD SDF?', 'N/A', 0, '0');

-- --------------------------------------------------------

--
-- Table structure for table `salesforce_account`
--

CREATE TABLE `salesforce_account` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `refresh_token` varchar(255) NOT NULL,
  `instance_url` varchar(255) NOT NULL,
  `issued_at` datetime NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salesforce_account`
--

INSERT INTO `salesforce_account` (`id`, `user_id`, `access_token`, `refresh_token`, `instance_url`, `issued_at`, `expires_at`) VALUES
(10, 17, '00Dd200000HWEMD!AQEAQNT8.m1VRmHbwpY3Z.whZ.5VNv7K8YprI07cz2H79qN66y_QjaIwduXIC2jo_f23I1Xx7MMitY8w5RNi4LF19b2XZ9G6', '5Aep861Jfo34KnkW6KTwsrywtA_dwxrJmLY5IOLIWyz50W68oQrdUhks8_5N9x_WucHkdyp0w85zDFJovTjD0cD', 'https://intransition-dev-ed.develop.my.salesforce.com', '2025-07-16 04:32:59', '2025-07-16 04:32:59'),
(11, 23, '00Dd200000HWEMD!AQEAQD0cMoH8w_oDDAcOfWKRssNIymoLPccIjwZL1cl5sh7HKVaRm3LWrTMBx85OmCxY5FHZg9LOOkeTAOvw8_8j8xs0o9PW', '5Aep861Jfo34KnkW6KTwsrywtA_dwxrJmLY5IOLIWyz50W68oSX_QKIOOUSjU7xJRXhs9Xy3qMVMJ8T9ekxxq08', 'https://intransition-dev-ed.develop.my.salesforce.com', '2025-07-16 01:53:13', '2025-07-16 01:53:13');

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
(18, 17, 'poll', 'My First Form', 'Excited to submit. Updating also done !', 'uploads/forms/unsplash-Jesse-Bowser_686440aa7396d.jpg', 0, '4', '2025-07-02 02:10:18', '2025-07-02 04:40:11'),
(19, 17, 'survey', 'test sad', 'Final Test', 'uploads/forms/unsplash-Rachel-Davis_68662afa975d9.jpg', 0, '1', '2025-07-03 13:02:19', '2025-07-03 13:02:19'),
(20, 17, 'survey', 'TST', 'FASFASDFFSF', 'uploads/forms/unsplash-Peter-Conlan_6868f7ea84006.jpg', 0, '1', '2025-07-05 16:01:15', '2025-07-05 16:01:15'),
(21, 17, 'survey', 'ASDFA S SDAF AS', 'FADSF ASF FASDF ADSF DSF ASF ASDASDF DSFAS', 'uploads/forms/photo-1552819686-670bdfa2b85b_686f46717a8e2.jpg', 1, '2', '2025-07-05 23:59:54', '2025-07-10 10:49:53'),
(22, 21, 'survey', 'Exam', 'Please help me out', 'uploads/forms/unsplash-Nathan-Anderson-1_686d218c73b09.jpg', 1, '1', '2025-07-08 19:47:56', '2025-07-08 19:47:56');

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
(60, 18, 'firstform'),
(61, 18, 'beautiful'),
(62, 10, 'architecto'),
(63, 10, 'repudiandae'),
(64, 10, 'reiciendis'),
(65, 19, 'survey'),
(66, 19, 'india'),
(67, 19, 'russia'),
(68, 20, 'ADSA'),
(69, 20, 'ASDFAS'),
(73, 22, 'architecto'),
(74, 22, 'beautiful'),
(75, 21, 'FASDF '),
(76, 21, 'AS DFDS F'),
(77, 21, 'ASF ASD');

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
(11, 'baxoso6606@baxima.com', '$2y$13$k3RGKUyJ2YsktvvNmTVcKOXOrEJ1AVdUuqVNBh0qIlDxaW6SB8r/C', '2024-08-28 02:20:45', '[\"ROLE_USER\"]'),
(12, 'vwolff@langosh.com', '$2y$13$k3RGKUyJ2YsktvvNmTVcKOXOrEJ1AVdUuqVNBh0qIlDxaW6SB8r/C', '2025-04-01 06:07:32', '[\"ROLE_USER\"]'),
(13, 'torrance.reicherte@hettinger.com', '$2y$13$k3RGKUyJ2YsktvvNmTVcKOXOrEJ1AVdUuqVNBh0qIlDxaW6SB8r/C', '2024-07-21 11:43:42', '[\"ROLE_USER\"]'),
(17, 'test1@gmail.com', '$2y$13$phbCyDYM3oButpIEkG6o6OR57XzX9vG3MGi0Hcr0q7qc05w6YB1Ee', '2025-06-28 21:24:35', '[\"ROLE_USER\"]'),
(18, 'admin@gmail.com', '$2y$13$qwWFdBObgFoDyPwxaM3W8OaUg74tM5O8ESbTHPWbgP.hh2cEyutJO', '2025-06-28 21:24:35', '[\"ROLE_ADMIN\"]'),
(19, 'test3@gmail.com', '$2y$13$yhnmMnIkOXVnKfuGdDW5hO9MTB4.Z1X8D2maI9Oy1Ok6hwUmB7g.m', '2025-07-03 13:24:08', '[\"ROLE_USER\"]'),
(21, 'test5@gmail.com', '$2y$13$jpwSj/RW5c2m.UBHdQbwTO/.iLYQNL8UxrXEOhwp17hHISz.oIeZ.', '2025-07-08 19:44:41', '[\"ROLE_USER\"]'),
(22, 'test6@gmail.com', '$2y$13$JYByBYNCNsVvdvOapC/QueNodvkJlgCYUw5BaApYMn.SShUS8emtC', '2025-07-12 21:01:28', '[\"ROLE_USER\"]'),
(23, 'test2@gmail.com', '$2y$13$aj/VTqdYaakuW.PE14wtlOpEyDRxEbSriFPxnCinJJwYdFwfbOcGG', '2025-07-16 00:45:17', '[\"ROLE_USER\"]');

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
-- Indexes for table `api_token`
--
ALTER TABLE `api_token`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_7BA2F5EBA76ED395` (`user_id`);

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
-- Indexes for table `salesforce_account`
--
ALTER TABLE `salesforce_account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_DB842773A76ED395` (`user_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `api_token`
--
ALTER TABLE `api_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `form_submit`
--
ALTER TABLE `form_submit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `like`
--
ALTER TABLE `like`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `option`
--
ALTER TABLE `option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `salesforce_account`
--
ALTER TABLE `salesforce_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `template`
--
ALTER TABLE `template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `template_tag`
--
ALTER TABLE `template_tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
-- Constraints for table `api_token`
--
ALTER TABLE `api_token`
  ADD CONSTRAINT `FK_7BA2F5EBA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

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
-- Constraints for table `salesforce_account`
--
ALTER TABLE `salesforce_account`
  ADD CONSTRAINT `FK_DB842773A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

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
