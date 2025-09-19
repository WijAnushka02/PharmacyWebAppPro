-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2025 at 11:15 AM
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
-- Database: `pharmacy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Admin_ID` int(11) NOT NULL,
  `Admin_name` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Admin_ID`, `Admin_name`, `Email`) VALUES
(1, 'John Doe', 'john.doe@pharmacy.com'),
(2, 'Jane Smith', 'jane.smith@pharmacy.com'),
(3, 'Bob Johnson', 'b.johnson@pharmacy.com'),
(4, 'Emily White', 'e.white@pharmacy.com'),
(5, 'David Brown', 'd.brown@pharmacy.com'),
(6, 'Sarah Davis', 's.davis@pharmacy.com'),
(7, 'Michael Lee', 'm.lee@pharmacy.com'),
(8, 'Jessica Hall', 'jessica.h@pharmacy.com'),
(9, 'Daniel Evans', 'd.evans@pharmacy.com'),
(10, 'Olivia Miller', 'o.miller@pharmacy.com');

-- --------------------------------------------------------

--
-- Table structure for table `admin_staff_role`
--

CREATE TABLE `admin_staff_role` (
  `Admin_ID` int(11) NOT NULL,
  `Pharmacist_ID` int(11) NOT NULL,
  `Role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_staff_role`
--

INSERT INTO `admin_staff_role` (`Admin_ID`, `Pharmacist_ID`, `Role`) VALUES
(1, 101, 'Senior Pharmacist'),
(2, 102, 'Junior Pharmacist'),
(3, 103, 'Junior Pharmacist'),
(4, 104, 'Junior Pharmacist'),
(5, 105, 'Senior Pharmacist'),
(6, 106, 'Senior Pharmacist'),
(7, 107, 'Junior Pharmacist'),
(8, 108, 'Junior Pharmacist'),
(9, 109, 'Senior Pharmacist'),
(10, 110, 'Junior Pharmacist');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(6) UNSIGNED NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `first_name`, `last_name`, `phone`, `email`, `password`, `reg_date`) VALUES
(1, 'anushka', 'wijesinghe', '0781847525', 'anushkadilinuwan03@gmail.com', '$2y$10$2uLSz6D2yuZIXj9sT93U9OjF71PVymxmHS6f/.ejVczAIsOmYYK6S', '2025-09-15 09:55:52');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Customer_ID` int(11) NOT NULL,
  `Patient_name` varchar(255) DEFAULT NULL,
  `DoB` date DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Contact` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Customer_ID`, `Patient_name`, `DoB`, `Phone`, `Email`, `Address`, `Contact`) VALUES
(301, 'Alex Ray', '1990-05-15', '555-401-402', 'a.ray@email.com', '123 Main Street', 'Emergency Contact'),
(302, 'Ben Carter', '1985-11-20', '555-403-404', 'b.carter@email.com', '456 Oak Avenue', 'Emergency Contact'),
(303, 'Cathy Lee', '1995-03-25', '555-405-406', 'c.lee@email.com', '789 Pine Road', 'Emergency Contact'),
(304, 'Dan Miller', '1970-08-30', '555-407-408', 'd.miller@email.com', '101 Maple Drive', 'Emergency Contact'),
(305, 'Eva Green', '1992-04-10', '555-409-410', 'e.green@email.com', '202 Cedar Lane', 'Emergency Contact'),
(306, 'Frank Black', '1965-07-05', '555-411-412', 'f.black@email.com', '303 Birch Place', 'Emergency Contact'),
(307, 'Grace Kelly', '1988-09-12', '555-413-414', 'g.kelly@email.com', '404 Elm Street', 'Emergency Contact'),
(308, 'Harry Potter', '1980-01-22', '555-415-416', 'h.potter@email.com', '505 Wizard Way', 'Emergency Contact'),
(309, 'Hermione Granger', '1979-05-25', '555-417-418', 'h.granger@email.com', '606 Magic Mews', 'Emergency Contact'),
(310, 'Ron Weasley', '1981-03-01', '555-419-420', 'r.weasley@email.com', '707 Sorcery St', 'Emergency Contact');

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `Medicine_ID` int(11) NOT NULL,
  `Medicine_name` varchar(255) DEFAULT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`Medicine_ID`, `Medicine_name`, `Description`) VALUES
(501, 'Paracetamol', 'Pain reliever and fever reducer'),
(502, 'Amoxicillin', 'Antibiotic for bacterial infections'),
(503, 'Ibuprofen', 'NSAID for pain and inflammation'),
(504, 'Atorvastatin', 'Treats high cholesterol'),
(505, 'Lisinopril', 'ACE inhibitor for high blood pressure'),
(506, 'Metformin', 'Manages blood sugar levels for type 2 diabetes'),
(507, 'Salbutamol', 'Bronchodilator for asthma'),
(508, 'Omeprazole', 'Reduces stomach acid'),
(509, 'Ciprofloxacin', 'Broad-spectrum antibiotic'),
(510, 'Levothyroxine', 'Treats hypothyroidism');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `Order_ID` int(11) NOT NULL,
  `Total_price` decimal(10,2) DEFAULT NULL,
  `Customer_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`Order_ID`, `Total_price`, `Customer_ID`) VALUES
(701, 15.50, 301),
(702, 25.00, 302),
(703, 12.00, 303),
(704, 30.75, 304),
(705, 45.00, 305),
(706, 18.25, 306),
(707, 22.50, 307),
(708, 50.00, 308),
(709, 9.99, 309),
(710, 35.50, 310);

-- --------------------------------------------------------

--
-- Table structure for table `order_contains_medicine`
--

CREATE TABLE `order_contains_medicine` (
  `Order_ID` int(11) NOT NULL,
  `Medicine_ID` int(11) NOT NULL,
  `Quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_contains_medicine`
--

INSERT INTO `order_contains_medicine` (`Order_ID`, `Medicine_ID`, `Quantity`) VALUES
(701, 501, 10),
(702, 502, 5),
(703, 503, 8),
(704, 504, 2),
(705, 505, 1),
(706, 506, 6),
(707, 507, 3),
(708, 508, 4),
(709, 509, 15),
(710, 510, 7);

-- --------------------------------------------------------

--
-- Table structure for table `patient_users`
--

CREATE TABLE `patient_users` (
  `id` int(6) UNSIGNED NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_users`
--

INSERT INTO `patient_users` (`id`, `first_name`, `last_name`, `phone`, `address`, `email`, `password`, `reg_date`) VALUES
(1, 'Anushka', 'Wijesinghe', '0781847525', 'cn n cnmbvbxc mjhdhfhjdsh\r\ncbjhbvc\r\nbcbzhbcbv', 'anushkadilinuwan03@gmail.com', '$2y$10$mal38sLXIpdW1n6R.3hh7.9RTgik44aUI2kNzoSdTEqx7NpCB33jG', '2025-09-15 10:08:03');

-- --------------------------------------------------------

--
-- Table structure for table `perception`
--

CREATE TABLE `perception` (
  `Perception_ID` int(11) NOT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `Image_url` varchar(255) DEFAULT NULL,
  `Issue_date` date DEFAULT NULL,
  `Exp_date` date DEFAULT NULL,
  `Customer_ID` int(11) DEFAULT NULL,
  `Medicine_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perception`
--

INSERT INTO `perception` (`Perception_ID`, `Status`, `Image_url`, `Issue_date`, `Exp_date`, `Customer_ID`, `Medicine_ID`) VALUES
(601, 'Verified', 'http://example.com/rx_1.jpg', '2025-08-01', '2026-08-01', 301, 501),
(602, 'Pending', 'http://example.com/rx_2.jpg', '2025-08-05', '2026-08-05', 302, 502),
(603, 'Verified', 'http://example.com/rx_3.jpg', '2025-08-10', '2026-08-10', 303, 503),
(604, 'Verified', 'http://example.com/rx_4.jpg', '2025-08-15', '2026-08-15', 304, 504),
(605, 'Expired', 'http://example.com/rx_5.jpg', '2024-05-20', '2025-05-20', 305, 505),
(606, 'Verified', 'http://example.com/rx_6.jpg', '2025-08-20', '2026-08-20', 306, 506),
(607, 'Verified', 'http://example.com/rx_7.jpg', '2025-08-25', '2026-08-25', 307, 507),
(608, 'Pending', 'http://example.com/rx_8.jpg', '2025-08-28', '2026-08-28', 308, 508),
(609, 'Verified', 'http://example.com/rx_9.jpg', '2025-09-01', '2026-09-01', 309, 509),
(610, 'Verified', 'http://example.com/rx_10.jpg', '2025-09-05', '2026-09-05', 310, 510);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `Pharmacist_ID` int(11) NOT NULL,
  `Pharmacist_name` varchar(255) DEFAULT NULL,
  `License_No` varchar(255) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Contact` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`Pharmacist_ID`, `Pharmacist_name`, `License_No`, `Phone`, `Contact`, `Email`) VALUES
(101, 'Alice Williams', 'PL12345', '555-101-202', 'Emergency Contact', 'alice.w@pharmacy.com'),
(102, 'Chris Evans', 'PL12346', '555-103-204', 'Emergency Contact', 'c.evans@pharmacy.com'),
(103, 'Lisa Green', 'PL12347', '555-105-206', 'Emergency Contact', 'l.green@pharmacy.com'),
(104, 'Peter Parker', 'PL12348', '555-107-208', 'Emergency Contact', 'p.parker@pharmacy.com'),
(105, 'Mary Jane', 'PL12349', '555-109-210', 'Emergency Contact', 'm.jane@pharmacy.com'),
(106, 'Robert Bruce', 'PL12350', '555-111-212', 'Emergency Contact', 'r.bruce@pharmacy.com'),
(107, 'Wanda Maximoff', 'PL12351', '555-113-214', 'Emergency Contact', 'w.maximoff@pharmacy.com'),
(108, 'Natasha Romanoff', 'PL12352', '555-115-216', 'Emergency Contact', 'n.romanoff@pharmacy.com'),
(109, 'Tony Stark', 'PL12353', '555-117-218', 'Emergency Contact', 't.stark@pharmacy.com'),
(110, 'Steve Rogers', 'PL12354', '555-119-220', 'Emergency Contact', 's.rogers@pharmacy.com');

-- --------------------------------------------------------

--
-- Table structure for table `staff_supplies_medicine`
--

CREATE TABLE `staff_supplies_medicine` (
  `Pharmacist_ID` int(11) NOT NULL,
  `Supplier_ID` int(11) NOT NULL,
  `Medicine_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_supplies_medicine`
--

INSERT INTO `staff_supplies_medicine` (`Pharmacist_ID`, `Supplier_ID`, `Medicine_ID`) VALUES
(101, 201, 501),
(102, 202, 502),
(103, 203, 503),
(104, 204, 504),
(105, 205, 505),
(106, 206, 506),
(107, 207, 507),
(108, 208, 508),
(109, 209, 509),
(110, 210, 510);

-- --------------------------------------------------------

--
-- Table structure for table `staff_users`
--

CREATE TABLE `staff_users` (
  `id` int(6) UNSIGNED NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `staff_id` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_users`
--

INSERT INTO `staff_users` (`id`, `first_name`, `last_name`, `staff_id`, `phone`, `email`, `password`, `address`, `reg_date`) VALUES
(1, 'Anushka', 'Wijesinghe', '1111', '0781847525', 'anushkadilinuwan03@gmail.com', '$2y$10$gAv1KZ5F1B0tHImTNPzIlOfiZn.ezD5a1Afu/ZywRuXCOv6UAsXT.', 'cn n cnmbvbxc mjhdhfhjdsh\r\ncbjhbvc\r\nbcbzhbcbv', '2025-09-15 10:06:21');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `Medicine_ID` int(11) NOT NULL,
  `Quantity_in_stock` int(11) DEFAULT NULL,
  `Exp_Date` date NOT NULL,
  `Unit_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`Medicine_ID`, `Quantity_in_stock`, `Exp_Date`, `Unit_price`) VALUES
(501, 150, '2026-12-31', 1.50),
(502, 75, '2026-11-30', 2.50),
(503, 200, '2027-01-15', 1.25),
(504, 90, '2026-10-20', 3.75),
(505, 60, '2026-09-10', 4.50),
(506, 120, '2027-02-01', 2.25),
(507, 85, '2026-08-05', 3.00),
(508, 100, '2026-12-01', 5.00),
(509, 110, '2027-03-01', 0.99),
(510, 130, '2026-10-15', 3.50);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `Supplier_ID` int(11) NOT NULL,
  `Supplier_name` varchar(255) DEFAULT NULL,
  `Telephone` varchar(20) DEFAULT NULL,
  `Contact` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`Supplier_ID`, `Supplier_name`, `Telephone`, `Contact`, `Email`, `Address`) VALUES
(201, 'PharmaCorp Inc.', '555-301-302', 'John Doe', 'contact@pharmacor.com', '123 Pharma Ave'),
(202, 'MediSupply Co.', '555-303-304', 'Jane Smith', 'info@medisupply.com', '456 Medicine St'),
(203, 'Global Meds Ltd.', '555-305-306', 'Bob Johnson', 'info@globalmeds.com', '789 Health Blvd'),
(204, 'HealthBridge', '555-307-308', 'Emily White', 'support@healthbridge.com', '101 Wellness Rd'),
(205, 'BioGenesis', '555-309-310', 'David Brown', 'contact@biogenesis.com', '202 Life Lane'),
(206, 'TrueCare Pharma', '555-311-312', 'Sarah Davis', 'info@truecare.com', '303 Cure Circle'),
(207, 'Apex Supplies', '555-313-314', 'Michael Lee', 'apex@apex.com', '404 Rx Drive'),
(208, 'Nexus Healthcare', '555-315-316', 'Jessica Hall', 'nexus@nexus.com', '505 Wellness Way'),
(209, 'Synergy Meds', '555-317-318', 'Daniel Evans', 'synergy@synergy.com', '606 Synergy Sq'),
(210, 'Vitality Vends', '555-319-320', 'Olivia Miller', 'vitality@vitality.com', '707 Vitality Path');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Admin_ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `admin_staff_role`
--
ALTER TABLE `admin_staff_role`
  ADD PRIMARY KEY (`Admin_ID`,`Pharmacist_ID`),
  ADD KEY `Pharmacist_ID` (`Pharmacist_ID`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Customer_ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `medicine`
--
ALTER TABLE `medicine`
  ADD PRIMARY KEY (`Medicine_ID`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`Order_ID`),
  ADD KEY `Customer_ID` (`Customer_ID`);

--
-- Indexes for table `order_contains_medicine`
--
ALTER TABLE `order_contains_medicine`
  ADD PRIMARY KEY (`Order_ID`,`Medicine_ID`),
  ADD KEY `Medicine_ID` (`Medicine_ID`);

--
-- Indexes for table `patient_users`
--
ALTER TABLE `patient_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `perception`
--
ALTER TABLE `perception`
  ADD PRIMARY KEY (`Perception_ID`),
  ADD KEY `Customer_ID` (`Customer_ID`),
  ADD KEY `Medicine_ID` (`Medicine_ID`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`Pharmacist_ID`),
  ADD UNIQUE KEY `License_No` (`License_No`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `staff_supplies_medicine`
--
ALTER TABLE `staff_supplies_medicine`
  ADD PRIMARY KEY (`Pharmacist_ID`,`Supplier_ID`,`Medicine_ID`),
  ADD KEY `Supplier_ID` (`Supplier_ID`),
  ADD KEY `Medicine_ID` (`Medicine_ID`);

--
-- Indexes for table `staff_users`
--
ALTER TABLE `staff_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`Medicine_ID`,`Exp_Date`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`Supplier_ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `patient_users`
--
ALTER TABLE `patient_users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff_users`
--
ALTER TABLE `staff_users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_staff_role`
--
ALTER TABLE `admin_staff_role`
  ADD CONSTRAINT `admin_staff_role_ibfk_1` FOREIGN KEY (`Admin_ID`) REFERENCES `admin` (`Admin_ID`),
  ADD CONSTRAINT `admin_staff_role_ibfk_2` FOREIGN KEY (`Pharmacist_ID`) REFERENCES `staff` (`Pharmacist_ID`);

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`);

--
-- Constraints for table `order_contains_medicine`
--
ALTER TABLE `order_contains_medicine`
  ADD CONSTRAINT `order_contains_medicine_ibfk_1` FOREIGN KEY (`Order_ID`) REFERENCES `order` (`Order_ID`),
  ADD CONSTRAINT `order_contains_medicine_ibfk_2` FOREIGN KEY (`Medicine_ID`) REFERENCES `medicine` (`Medicine_ID`);

--
-- Constraints for table `perception`
--
ALTER TABLE `perception`
  ADD CONSTRAINT `perception_ibfk_1` FOREIGN KEY (`Customer_ID`) REFERENCES `customer` (`Customer_ID`),
  ADD CONSTRAINT `perception_ibfk_2` FOREIGN KEY (`Medicine_ID`) REFERENCES `medicine` (`Medicine_ID`);

--
-- Constraints for table `staff_supplies_medicine`
--
ALTER TABLE `staff_supplies_medicine`
  ADD CONSTRAINT `staff_supplies_medicine_ibfk_1` FOREIGN KEY (`Pharmacist_ID`) REFERENCES `staff` (`Pharmacist_ID`),
  ADD CONSTRAINT `staff_supplies_medicine_ibfk_2` FOREIGN KEY (`Supplier_ID`) REFERENCES `supplier` (`Supplier_ID`),
  ADD CONSTRAINT `staff_supplies_medicine_ibfk_3` FOREIGN KEY (`Medicine_ID`) REFERENCES `medicine` (`Medicine_ID`);

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`Medicine_ID`) REFERENCES `medicine` (`Medicine_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
