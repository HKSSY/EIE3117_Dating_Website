-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 08, 2022 at 08:39 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dating_web_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `self_description` varchar(800) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `nickname`, `password`, `email`, `dob`, `gender`, `self_description`) VALUES
(1, 'test', '$2y$10$0VVcXYc9yzHlqH6pnPM7nuf/kureEz2vn23DfvkCvjmMef4FP1kJ2', 'test@test.com', '2000-01-01', 'Male', 'Hello world'),
(41, 'may', '$2y$10$inzt8ACqLVLramE9TAU4Pe2jTFfOI5BUWhH0QR4b7hGj96uZbciN.', 'may@test.com', '2005-07-20', 'Female', 'LOL'),
(42, 'test99', '$2y$10$zxMPBvU7Tbyu4cyyTaT1a.SuCHKcllcfLnovEgOMzwm7lOMtt8yTi', 'test99@test.com', '2006-02-07', 'Male', 'dfgdfgdfgfdg'),
(43, 'tom', '$2y$10$oJzRrRnILWyez53x7m10OOqVi0er.tQZCP/dPQTe4jeeY8ktKk5DK', 'tom@tom.com', '2005-08-16', 'Male', 'alert(&#34;XSS test&#34;);'),
(44, '<script>alert(\"XSS attack test\");</script>', '$2y$10$xmG86ehDqWysWPBiYue1wO2dN9yXbU2EWLaa5kikoRfWlaCk3rkLa', 'alex@test.com', '2006-02-07', 'Male', '<script>alert(\"XSS attack test\");</script>'),
(45, 'alex', '$2y$10$OrR/H5QiPro1b2JPjMK.a.i4PxH8mUZ2HhUUx80.8Psi1VXeBLVyq', 'alex@test.com', '2005-07-12', 'Male', 'I am free');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT -1,
  `sender_nickname` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `submit_date` datetime NOT NULL DEFAULT current_timestamp(),
  `sender_user_id` int(11) NOT NULL,
  `receiver_user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `page_id`, `parent_id`, `sender_nickname`, `content`, `submit_date`, `sender_user_id`, `receiver_user_id`) VALUES
(1, 1, -1, 'tom', 'Hi', '2022-03-02 02:43:45', 43, 1),
(2, 1, 1, 'test', 'hi', '2022-03-02 02:43:51', 1, 43),
(3, 1, 2, 'tom', 'halo', '2022-03-02 02:43:58', 43, 1),
(4, 43, 3, 'test', 'lol', '2022-03-02 02:44:44', 1, 43),
(5, 1, 4, 'tom', 'lol', '2022-03-02 02:44:48', 43, 1),
(6, 1, 5, 'tom', 'lol', '2022-03-02 02:45:05', 43, 1),
(7, 1, 6, 'test', 'hihihi', '2022-03-02 02:51:45', 1, 43),
(8, 1, -1, 'tom', 'LOL', '2022-03-02 03:00:03', 43, 1),
(9, 1, 8, 'tom', 'lol', '2022-03-02 03:00:12', 43, 1),
(10, 43, -1, 'test', 'oooo', '2022-03-02 03:01:17', 1, 43),
(11, 43, 10, 'test', 'loo', '2022-03-02 03:01:48', 1, 43),
(12, 1, 10, 'tom', 'looo', '2022-03-02 03:02:03', 43, 1),
(13, 43, 12, 'test', 'ghjghjfghj', '2022-03-02 03:02:40', 1, 43),
(14, 1, 13, 'tom', 'ghjghj', '2022-03-02 03:02:46', 43, 1),
(15, 43, 14, 'test', 'vbnvcbnvbcnvbnvb', '2022-03-02 03:03:16', 1, 43),
(16, 1, 10, 'tom', 'gfhfghfgh', '2022-03-02 03:03:24', 43, 1),
(17, 1, -1, '<script>alert(\"XSS attack test\");</script>', 'Hi', '2022-03-02 11:16:20', 44, 1),
(18, 1, 17, 'test', 'asasa', '2022-03-02 11:29:21', 1, 44),
(20, 41, -1, 'alex', 'Hello nice to meet you', '2022-03-03 22:01:23', 45, 41),
(22, 41, -1, 'tom', 'Hi!', '2022-03-05 01:56:11', 43, 41),
(23, 41, -1, '<script>alert(\"XSS attack test\");</script>', 'Great profile image', '2022-03-05 01:57:39', 44, 41),
(26, 41, 20, 'may', 'Hello\r\n', '2022-03-05 20:35:33', 41, 45);

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `title` varchar(35) NOT NULL,
  `description` varchar(45) NOT NULL,
  `filepath` text NOT NULL,
  `uploaded_date` datetime NOT NULL DEFAULT current_timestamp(),
  `profile_image` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `userid`, `title`, `description`, `filepath`, `uploaded_date`, `profile_image`) VALUES
(1, 1, '', '', 'upload_image/1646314978ValeforZero-1496646267555831809-img1.jpg', '2022-03-03 21:42:57', 1),
(16, 43, '', '', 'upload_image/E1vYZWNUYAEGDUP-orig.jpg', '2022-02-26 17:45:25', 1),
(17, 45, 'Cat', '', 'upload_image/1646315473IMG_6731.jpg', '2022-03-03 21:51:12', 1),
(18, 41, '', '', 'upload_image/164631593820201009_120533.jpg', '2022-03-03 21:58:58', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_user_id_fk` (`sender_user_id`),
  ADD KEY `receiver_user_id` (`receiver_user_id`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_fk` (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `receiver_user_id` FOREIGN KEY (`receiver_user_id`) REFERENCES `accounts` (`id`),
  ADD CONSTRAINT `sender_user_id_fk` FOREIGN KEY (`sender_user_id`) REFERENCES `accounts` (`id`);

--
-- Constraints for table `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`userid`) REFERENCES `accounts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
