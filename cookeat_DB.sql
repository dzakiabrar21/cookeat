-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2025 at 03:35 PM
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
-- Database: `cookeat`
--

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `post_id`, `user_id`, `msg`, `created_at`) VALUES
(1, 2, 5, 'wah keren ya sabang\r\n', '2025-04-26 04:13:40'),
(2, 4, 5, 'alfjejafe', '2025-04-26 06:41:51'),
(3, 4, 5, 'wes keren kali wak', '2025-04-26 06:56:45'),
(4, 4, 5, 'wkwkkw. mainlah kesini\r\n', '2025-04-26 06:57:33'),
(5, 4, 4, 'info nama tempat bang', '2025-04-26 07:37:39'),
(6, 4, 4, 'kemarin aku baru dari sabang juga mas', '2025-04-26 07:38:18'),
(7, 6, 5, 'kocak', '2025-04-26 13:19:32'),
(8, 6, 5, 'oaisfhoaiwe', '2025-04-26 13:22:34');

-- --------------------------------------------------------

--
-- Table structure for table `comment_like`
--

CREATE TABLE `comment_like` (
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media_type`
--

CREATE TABLE `media_type` (
  `type_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `media_type`
--

INSERT INTO `media_type` (`type_id`, `type`) VALUES
(1, 'photo'),
(2, 'video');

-- --------------------------------------------------------

--
-- Table structure for table `media_upload`
--

CREATE TABLE `media_upload` (
  `photo_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `media_upload`
--

INSERT INTO `media_upload` (`photo_id`, `post_id`, `type_id`) VALUES
(2, 2, 1),
(4, 6, 1),
(2, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE `photo` (
  `photo_id` int(11) NOT NULL,
  `photo_name` varchar(255) NOT NULL,
  `photo_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photo`
--

INSERT INTO `photo` (`photo_id`, `photo_name`, `photo_url`) VALUES
(1, '2024_05_25_23_05_IMG_4105.HEIC', 'assets/uploads/2024_05_25_23_05_IMG_4105.HEIC'),
(2, '2024_05_27_08_45_IMG_4232.JPG', 'assets/uploads/2024_05_27_08_45_IMG_4232.JPG'),
(3, 'Screenshot 2025-04-23 135007.png', 'assets/uploads/Screenshot 2025-04-23 135007.png'),
(4, '2024_05_27_08_41_IMG_4292.JPG', 'assets/uploads/2024_05_27_08_41_IMG_4292.JPG');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `total_like` int(11) DEFAULT 0,
  `total_comment` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`post_id`, `user_id`, `type_id`, `content`, `total_like`, `total_comment`, `created_at`) VALUES
(2, 4, 1, 'this is my adventure', 2, 1, '2025-04-25 20:43:42'),
(4, 5, 2, 'this is our last night', 1, 5, '2025-04-26 04:14:26'),
(6, 5, 1, 'sabang part 2', 1, 2, '2025-04-26 13:19:19');

-- --------------------------------------------------------

--
-- Table structure for table `post_like`
--

CREATE TABLE `post_like` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_like`
--

INSERT INTO `post_like` (`post_id`, `user_id`) VALUES
(2, 4),
(2, 5),
(4, 5),
(6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `reply`
--

CREATE TABLE `reply` (
  `comment_id_1` int(11) NOT NULL,
  `comment_id_2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reply`
--

INSERT INTO `reply` (`comment_id_1`, `comment_id_2`) VALUES
(3, 4),
(3, 6),
(7, 8);

-- --------------------------------------------------------

--
-- Table structure for table `saved_post`
--

CREATE TABLE `saved_post` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saved_post`
--

INSERT INTO `saved_post` (`post_id`, `user_id`, `created_at`) VALUES
(2, 4, '2025-04-25 20:48:32');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `photo_id` int(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `photo_id`, `username`, `email`, `password_hash`, `full_name`, `bio`, `created_at`) VALUES
(1, NULL, 'dzaki abrar', 'muhammaddzakiabrar21@gmail.com', '$2y$10$h4h3g18bSXBKH12vH06xhuKm3fJwZyQT2uTrWLm11OKrO87N0Jk.2', 'muhammad', 'ganteng', '2025-04-25 20:37:19'),
(4, NULL, 'jek brar', 'jek@gmail.com', '$2y$10$vOqD.HQYcyaG.g2W2csyYOfPCMToA7AiQbchpcG4INJ2SldLNW4pG', 'jek brar', 'handsome', '2025-04-25 20:40:05'),
(5, NULL, 'syukri', 'abi@gmail.co', '$2y$10$LvBLxyyJ.FxI.RBKMUZrxekyh5HA4Yeg4V2BFLfH3LAShB6qFLD6y', 'abisyukri', 'Hamba Allah', '2025-04-26 04:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE `video` (
  `video_id` int(11) NOT NULL,
  `video_name` varchar(255) NOT NULL,
  `video_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `video`
--

INSERT INTO `video` (`video_id`, `video_name`, `video_url`) VALUES
(1, '2024_05_25_20_15_IMG_4084.MOV', 'assets/uploads/2024_05_25_20_15_IMG_4084.MOV'),
(2, '2024_05_25_20_15_IMG_4084.MOV', 'assets/uploads/2024_05_25_20_15_IMG_4084.MOV');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `comment_like`
--
ALTER TABLE `comment_like`
  ADD PRIMARY KEY (`comment_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `media_type`
--
ALTER TABLE `media_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `media_upload`
--
ALTER TABLE `media_upload`
  ADD PRIMARY KEY (`photo_id`,`post_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`photo_id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `post_like`
--
ALTER TABLE `post_like`
  ADD PRIMARY KEY (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reply`
--
ALTER TABLE `reply`
  ADD PRIMARY KEY (`comment_id_1`,`comment_id_2`),
  ADD KEY `comment_id_2` (`comment_id_2`);

--
-- Indexes for table `saved_post`
--
ALTER TABLE `saved_post`
  ADD PRIMARY KEY (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `photo_id` (`photo_id`);

--
-- Indexes for table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`video_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `media_type`
--
ALTER TABLE `media_type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `photo`
--
ALTER TABLE `photo`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `video`
--
ALTER TABLE `video`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `comment_like`
--
ALTER TABLE `comment_like`
  ADD CONSTRAINT `comment_like_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`comment_id`),
  ADD CONSTRAINT `comment_like_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `media_upload`
--
ALTER TABLE `media_upload`
  ADD CONSTRAINT `media_upload_ibfk_1` FOREIGN KEY (`photo_id`) REFERENCES `photo` (`photo_id`),
  ADD CONSTRAINT `media_upload_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`),
  ADD CONSTRAINT `media_upload_ibfk_3` FOREIGN KEY (`type_id`) REFERENCES `media_type` (`type_id`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `media_type` (`type_id`);

--
-- Constraints for table `post_like`
--
ALTER TABLE `post_like`
  ADD CONSTRAINT `post_like_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`),
  ADD CONSTRAINT `post_like_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `reply`
--
ALTER TABLE `reply`
  ADD CONSTRAINT `reply_ibfk_1` FOREIGN KEY (`comment_id_1`) REFERENCES `comment` (`comment_id`),
  ADD CONSTRAINT `reply_ibfk_2` FOREIGN KEY (`comment_id_2`) REFERENCES `comment` (`comment_id`);

--
-- Constraints for table `saved_post`
--
ALTER TABLE `saved_post`
  ADD CONSTRAINT `saved_post_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`post_id`),
  ADD CONSTRAINT `saved_post_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`photo_id`) REFERENCES `photo` (`photo_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
