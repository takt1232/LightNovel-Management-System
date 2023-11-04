-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2023 at 09:18 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `novelmanagementdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `book_title` varchar(255) NOT NULL,
  `book_type` varchar(255) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `synopsis` text NOT NULL,
  `status` enum('Submitted','Approved','Published','') NOT NULL,
  `approval_token` enum('Approved by writer','Not Approved by writer','','') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `book_cover` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `book_title`, `book_type`, `author_id`, `synopsis`, `status`, `approval_token`, `file_path`, `book_cover`) VALUES
(4, 'fourth edit atempt', '', 6, 'fourth attempt trying to edit changing with file', 'Published', 'Approved by writer', '../uploads/6545737a5f13c.pdf', NULL),
(5, 'asd', '', 6, 'asd', 'Submitted', 'Not Approved by writer', '../uploads/6545302c4e96e.pdf', NULL),
(6, 'Testing with no cover', '', 6, 'test', 'Submitted', 'Not Approved by writer', '../uploads/65458c78d9e01.pdf', NULL),
(7, 'testing with cover', '', 6, 'asd', 'Published', 'Approved by writer', '../uploads/65458cc43bf77.pdf', '../images/65458cc43c1db.JPG'),
(8, 'Another Book', '', 6, 'Alamat ng WTF', 'Published', 'Approved by writer', '../uploads/654598d797aa9.pdf', '../images/654598d798192.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `rating` int(5) DEFAULT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `book_id`, `rating`, `comment`) VALUES
(1, 6, 4, 5, 'Good book'),
(2, 6, 4, 2, 'NNN'),
(3, 6, 4, 5, 'asdds'),
(4, 6, 4, 1, '111'),
(5, 6, 4, 5, '111'),
(6, 6, 8, 3, 'asdsd');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('Writer','Admin','Viewer','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `role`) VALUES
(4, 'admin', '123', 'superidol@gmail.com', 'Admin'),
(6, 'test', '123', 'lkadj@gmail.com', 'Writer'),
(7, 'Another Writer', '123', 'superidol@gmail.com', 'Writer');

-- --------------------------------------------------------

--
-- Table structure for table `verification`
--

CREATE TABLE `verification` (
  `verification_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `verified_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `FK_books_users_user_id` (`author_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `FK_reviews_users_user_id` (`user_id`),
  ADD KEY `FK_reviews_books_book_id` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `verification`
--
ALTER TABLE `verification`
  ADD PRIMARY KEY (`verification_id`),
  ADD KEY `FK_verification_users_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `verification`
--
ALTER TABLE `verification`
  MODIFY `verification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `FK_books_users_user_id` FOREIGN KEY (`author_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `FK_reviews_books_book_id` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_reviews_users_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `verification`
--
ALTER TABLE `verification`
  ADD CONSTRAINT `FK_verification_users_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
