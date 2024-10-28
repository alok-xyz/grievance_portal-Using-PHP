-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2024 at 04:41 PM
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
-- Database: `grievance_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `grievances`
--

CREATE TABLE `grievances` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `complaint` text NOT NULL,
  `status` enum('Pending','Resolved','UnderProcess','Warned') DEFAULT 'Pending',
  `remark` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grievances`
--

INSERT INTO `grievances` (`id`, `user_id`, `complaint`, `status`, `remark`, `created_at`) VALUES
(13, 5, 'hh', 'Resolved', 'Meaningless msg, We are taking legal action on YOU!', '2024-10-28 08:12:45'),
(14, 6, 'Hello Sir, I am lost My House & Job in the cyclone. I will be very thankful to you if you kindly arrange me a house & a simple job for me to encounter ny daily expenses.\r\nThank You!\r\nAlok Guha Roy\r\nSan Fransisco,USA', 'Resolved', 'Employment Prayer', '2024-10-28 09:17:45'),
(15, 7, 'Looking for A Husband ', 'Resolved', 'Warned & Consider', '2024-10-28 10:51:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`) VALUES
(5, 'Monojit Upadhayay', '$2y$10$A8DnS70Q3dSrYuT2ljSCCO9bhbEXnWHruQ9eE9Aox3Mhr.o1uagjq', 'monojitupadhyay9775@gmail.com', '8250554784'),
(6, 'Alok Guha Roy ', '$2y$10$6dqM3n.55TpndszhK6MaXulNkzUMuxAG8Mq7czkWA6zSRX6EBQIei', 'guhaalok19@gmail.com', '9064952071'),
(7, 'Puja Biswas', '$2y$10$o4hVqXQzW4uFBrClEuxAGe36PYBbuj1Mw/4Vb1R3ajtfvABk8hs7m', 'pb@gmail.com', '7580963254');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grievances`
--
ALTER TABLE `grievances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `grievances`
--
ALTER TABLE `grievances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `grievances`
--
ALTER TABLE `grievances`
  ADD CONSTRAINT `grievances_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
