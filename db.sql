-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Εξυπηρετητής: db
-- Χρόνος δημιουργίας: 29 Μάη 2025 στις 20:43:44
-- Έκδοση διακομιστή: 8.0.42
-- Έκδοση PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `mydatabase`
--
CREATE DATABASE IF NOT EXISTS `mydatabase` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `mydatabase`;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `playlists`
--

CREATE TABLE `playlists` (
  `id` int NOT NULL,
  `title` varchar(128) NOT NULL,
  `is_public` tinyint(1) DEFAULT '1',
  `user_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Άδειασμα δεδομένων του πίνακα `playlists`
--

INSERT INTO `playlists` (`id`, `title`, `is_public`, `user_id`, `created_at`) VALUES
(2, 'website', 1, 2, '2025-05-29 19:09:08');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Άδειασμα δεδομένων του πίνακα `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'konan', 'ko.anagnostakos@gmail.com', '$2y$10$ruCgpsHiOus6FV00iK5ruO/BPS6MLe0XdcM4ojGKpYJtpPtggOlPO', '2025-05-29 16:04:38'),
(2, 'alex', 'alex10miska@gmail.com', '$2y$10$mI/UjjWnY7D/UDeUyKapQeN7UtAvn1gfhPrWZod.a1p4Nqddnjt8q', '2025-05-29 16:31:44'),
(3, 'alexmiska10', 'inf2021140@ionio.gr', '$2y$10$9UcIJ1XuWg7RzEBUt1Dup.z.pW5DSBRzIMNFc8lOAnC.z5eyZ7P1S', '2025-05-29 19:23:59');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `videos`
--

CREATE TABLE `videos` (
  `id` int NOT NULL,
  `playlist_id` int DEFAULT NULL,
  `youtube_id` varchar(32) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `position` int DEFAULT '0',
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Άδειασμα δεδομένων του πίνακα `videos`
--

INSERT INTO `videos` (`id`, `playlist_id`, `youtube_id`, `title`, `position`, `added_at`) VALUES
(1, 2, '71EZb94AS1k', 'What is WordPress? And How Does It Work? | Explained for Beginners', 0, '2025-05-29 19:09:19'),
(2, 2, '3_pjNppPLMA', 'How to build a website in seconds #ai #productivity #aitools #websitebuilder @Framer', 0, '2025-05-29 19:14:56');

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `playlists`
--
ALTER TABLE `playlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Ευρετήρια για πίνακα `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Ευρετήρια για πίνακα `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `playlist_id` (`playlist_id`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `playlists`
--
ALTER TABLE `playlists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT για πίνακα `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT για πίνακα `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Περιορισμοί για άχρηστους πίνακες
--

--
-- Περιορισμοί για πίνακα `playlists`
--
ALTER TABLE `playlists`
  ADD CONSTRAINT `playlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Περιορισμοί για πίνακα `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_ibfk_1` FOREIGN KEY (`playlist_id`) REFERENCES `playlists` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
