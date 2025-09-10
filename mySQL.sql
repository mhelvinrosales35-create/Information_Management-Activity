-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2025 at 06:37 AM
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
-- Database: `logistics`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbldeliverystaff`
--

CREATE TABLE `tbldeliverystaff` (
  `staff_id` int(11) NOT NULL,
  `user_id` int(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `assigned_area` varchar(100) NOT NULL,
  `status` enum('Available','On Delivery','Inactive') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbldeliverystaff`
--

INSERT INTO `tbldeliverystaff` (`staff_id`, `user_id`, `name`, `contact`, `assigned_area`, `status`, `created_at`) VALUES
(1, 2, 'John Doe', '09123456789', 'Zone A', 'Available', '2025-09-09 07:10:56');

-- --------------------------------------------------------

--
-- Table structure for table `tblpayments`
--

CREATE TABLE `tblpayments` (
  `payment_id` int(11) NOT NULL,
  `shipment_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` enum('Cash','Card','Online') NOT NULL,
  `status` enum('Pending','Paid','Failed') NOT NULL,
  `paid_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblpayments`
--

INSERT INTO `tblpayments` (`payment_id`, `shipment_id`, `amount`, `method`, `status`, `paid_at`) VALUES
(1, 1, 30.00, 'Card', 'Paid', '2025-09-09 06:14:25');

-- --------------------------------------------------------

--
-- Table structure for table `tblshipmentassignment`
--

CREATE TABLE `tblshipmentassignment` (
  `assignment_id` int(11) NOT NULL,
  `shipment_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Assigned','Picked Up','Delivered','Cancel') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblshipments`
--

CREATE TABLE `tblshipments` (
  `shipment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `origin` int(11) NOT NULL,
  `destination` varchar(100) NOT NULL,
  `receiver_name` varchar(100) NOT NULL,
  `receiver_contact` varchar(50) NOT NULL,
  `weight` decimal(10,2) NOT NULL,
  `status` enum('Pending','In Transit','Delivered','Cancel') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblshipments`
--

INSERT INTO `tblshipments` (`shipment_id`, `user_id`, `origin`, `destination`, `receiver_name`, `receiver_contact`, `weight`, `status`, `created_at`) VALUES
(1, 1, 1, 'Building M, Urban Deca Homes Ortigas, Pasig City', 'janz', '09666413862', 3.00, 'Pending', '2025-09-09 06:11:07');

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','customer') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`user_id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Melvin', '$2y$10$D72O8Q35lvRebe0NS0jUG.gQiB9wfCoy5dVFIVYN3GCQ04iIsUvku', 'customer', '2025-09-09 06:01:32'),
(2, 'Merejen', '$2y$10$qCFbxswKiCRCAlRETvpasO/FwupQFYfZTRP5jnz6yrTIzAoCERbX.', 'staff', '2025-09-09 06:42:09'),
(3, 'Admin', '$2y$10$UW/gR4Aj1H3r3VXtDmi8WumgNcWzS7k2X4f1ulQ9LKwFgtcKS6BFG', 'admin', '2025-09-09 08:23:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbldeliverystaff`
--
ALTER TABLE `tbldeliverystaff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `tblpayments`
--
ALTER TABLE `tblpayments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `tblshipmentassignment`
--
ALTER TABLE `tblshipmentassignment`
  ADD PRIMARY KEY (`assignment_id`);

--
-- Indexes for table `tblshipments`
--
ALTER TABLE `tblshipments`
  ADD PRIMARY KEY (`shipment_id`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbldeliverystaff`
--
ALTER TABLE `tbldeliverystaff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblpayments`
--
ALTER TABLE `tblpayments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblshipmentassignment`
--
ALTER TABLE `tblshipmentassignment`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblshipments`
--
ALTER TABLE `tblshipments`
  MODIFY `shipment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
