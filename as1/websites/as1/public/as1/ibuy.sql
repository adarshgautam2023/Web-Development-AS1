-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2023 at 08:43 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ibuy`
--

-- --------------------------------------------------------

--
-- Table structure for table `auctions`
--

CREATE TABLE `auctions` (
  `auction_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `endDate` datetime DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `register_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `auctions`
--

INSERT INTO `auctions` (`auction_id`, `title`, `description`, `endDate`, `category_id`, `register_id`) VALUES
(1, 'jordan', 'brand new', '2022-12-27 09:00:00', 3, 1),
(2, 'pressure cooker', 'lilltleold but works fine', '2022-12-27 09:00:00', 1, 1),
(3, 'carpet', 'red colored well handicrafted', '2022-12-27 09:00:00', 1, 1),
(5, 'motor g75', 'imported from foreign ', '2022-12-28 09:00:00', 5, 1),
(6, 'nike vapor boot', 'newly released ', '2022-12-29 09:00:00', 4, 1),
(7, 'dell xps', 'used for 2 months. No any scratches and everything works fine', '2022-12-27 09:00:00', 1, 1),
(8, 'iphone 13', 'brand new', '2022-12-27 09:00:00', 2, 1),
(9, 'Vintage t-shirt', 'vintage probably hard to find these days', '2022-12-27 09:00:00', 3, 1),
(10, 'flowers', 'fresh', '2022-12-27 09:00:00', 1, 1),
(11, 'cycle ', 'new model', '2022-12-27 09:00:00', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `bid_id` int(11) NOT NULL,
  `bid` varchar(255) DEFAULT NULL,
  `auction_id` int(11) DEFAULT NULL,
  `register_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(1, 'Home & Appliances'),
(2, 'Electronics'),
(3, 'Fashion'),
(4, 'Sport'),
(5, 'Motors');

-- --------------------------------------------------------

--
-- Table structure for table `registers`
--

CREATE TABLE `registers` (
  `register_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registers`
--

INSERT INTO `registers` (`register_id`, `name`, `email`, `password`, `user_type`) VALUES
(1, 'someone', 'someone123@gmail.com', 'ab3ccc8dba2a0cf7a1ce4d830bb8a6238ff03833', NULL),
(2, 'admin', 'admin123@gmail.com', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin'),
(3, 'Adarsh Gautam', 'Adarsh@gmail.com', 'adarsh123', NULL),
(4, 'Hero', 'Hero@123', '$2y$10$AGHhEY0n90sFlDGcBxOWfejiT.3X.KBo6Cr0PsxRcD/bravBwP6v2', 'admin'),
(5, 'Adarsh', 'Adarsh123@gmail.com', '6b7d883748a18ee41183c99375fe614ce432104c', NULL),
(6, 'Adarsh', 'Adarsh1@gmail.com', '$2y$10$uPG4sgbtMZglJ.KPncWqduw6V5PfefHwQvoF7x5Zdj1b3BhmQeuB.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `writeReview` varchar(255) DEFAULT NULL,
  `auction_id` int(11) DEFAULT NULL,
  `register_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auctions`
--
ALTER TABLE `auctions`
  ADD PRIMARY KEY (`auction_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `register_id` (`register_id`);

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`bid_id`),
  ADD KEY `auction_id` (`auction_id`),
  ADD KEY `register_id` (`register_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `registers`
--
ALTER TABLE `registers`
  ADD PRIMARY KEY (`register_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `auction_id` (`auction_id`),
  ADD KEY `register_id` (`register_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auctions`
--
ALTER TABLE `auctions`
  MODIFY `auction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `bid_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `registers`
--
ALTER TABLE `registers`
  MODIFY `register_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auctions`
--
ALTER TABLE `auctions`
  ADD CONSTRAINT `auctions_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `auctions_ibfk_2` FOREIGN KEY (`register_id`) REFERENCES `registers` (`register_id`);

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_ibfk_1` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`auction_id`),
  ADD CONSTRAINT `bids_ibfk_2` FOREIGN KEY (`register_id`) REFERENCES `registers` (`register_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`auction_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`register_id`) REFERENCES `registers` (`register_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
