-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2023 at 04:20 AM
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
-- Database: `vehicle-parking-db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ID` int(10) NOT NULL,
  `AdminName` varchar(120) DEFAULT NULL,
  `UserName` varchar(120) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Security_Code` int(55) NOT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID`, `AdminName`, `UserName`, `MobileNumber`, `Security_Code`, `Email`, `Password`, `AdminRegdate`) VALUES
(1, 'Adam Razali', 'adam', 1151406321, 1100, 'muhammadnuradam1105@gmail.com', '3e7b522b9756d2578d3a86d8f366be6e', '2021-01-05 05:38:23');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_email` varchar(100) NOT NULL,
  `customer_id` int(50) NOT NULL,
  `customer_password` varchar(500) NOT NULL,
  `customer_username` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_email`, `customer_id`, `customer_password`, `customer_username`) VALUES
('muhammadnuradam1105@gmail.com', 1, '$2y$10$75kapEdsstvG8H2jkFFhB.QrJ9fLvFxMJiBKPyGA0VuLFmsREPI5.', 'adam'),
('mal@gmail.com', 2, '$2y$10$dq.Eex9vYeW6Dt3le2u21O/0KxOwgDDMTC5VfCLbU/jQ1jrYpD7CG', 'Mal'),
('arep@gmail.com', 3, '$2y$10$4ONVV3/3IJf3vEWMaVY.R.1e6mTK2KeQxAcXt9tIJENN9f70jLKh2', 'Arep'),
('paan@gmail.com', 4, '$2y$10$BagJxmSkV/hDNeF1Zmwlheh1hZJmDiwDkaoXtuy/JjyHQrw1tp6kG', 'Paan'),
('afiq03@gmail.com', 6, '$2y$10$sVW8BYI0MJI74Ak9Rwi5luAX4/3uqvO1Y3YDBtW4pHN9SA0Tvhfay', 'moko');

-- --------------------------------------------------------

--
-- Table structure for table `vcategory`
--

CREATE TABLE `vcategory` (
  `ID` int(10) NOT NULL,
  `VehicleCat` varchar(120) DEFAULT NULL,
  `shortDescription` varchar(50) NOT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `vcategory`
--

INSERT INTO `vcategory` (`ID`, `VehicleCat`, `shortDescription`, `CreationDate`) VALUES
(7, 'A', 'VIP', '2023-05-30 12:17:23'),
(8, 'B', 'REGULAR', '2023-05-30 12:21:46'),
(9, 'C', 'OKU', '2023-05-30 12:21:53');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_info`
--

CREATE TABLE `vehicle_info` (
  `ID` int(10) NOT NULL,
  `ParkingNumber` varchar(120) DEFAULT NULL,
  `VehicleCategory` varchar(120) NOT NULL,
  `VehicleCompanyname` varchar(120) DEFAULT NULL,
  `RegistrationNumber` varchar(120) DEFAULT NULL,
  `OwnerName` varchar(120) DEFAULT NULL,
  `OwnerContactNumber` bigint(10) DEFAULT NULL,
  `InTime` timestamp NULL DEFAULT current_timestamp(),
  `OutTime` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `ParkingCharge` varchar(120) NOT NULL,
  `Remark` mediumtext NOT NULL,
  `Status` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `vehicle_info`
--

INSERT INTO `vehicle_info` (`ID`, `ParkingNumber`, `VehicleCategory`, `VehicleCompanyname`, `RegistrationNumber`, `OwnerName`, `OwnerContactNumber`, `InTime`, `OutTime`, `ParkingCharge`, `Remark`, `Status`) VALUES
(1, 'A1', 'A', 'HYUNDAI', 'GGZ-1155', 'LIYANA', 1156457645, '2021-03-09 05:58:38', '2023-06-17 01:28:46', '6', 'NA', ''),
(4, 'C5', 'C', 'PROTON', 'PLO-8507', 'NAJWA', 1283452475, '2021-03-09 08:58:38', '2023-06-16 11:11:14', '35', 'Vehicle Out', 'Out'),
(61, 'B64', 'B', 'AUDI', 'FHF-2353', 'ATHIRAH', 67867867, '2023-05-30 01:40:00', '2023-06-20 15:59:34', '12', 'out', 'Out'),
(64, 'B56', 'B', 'TESLA', 'FKJ-1922', 'EERSYAD', 143544556, '2023-05-31 06:56:00', '2023-06-11 04:53:16', '4', 'NA', 'Out'),
(66, 'A3', 'A', 'TESLA', 'FFF-1222', 'ADAM', 143544556, '2023-05-31 07:50:00', '2023-06-19 15:50:35', '16', 'NA', 'Out'),
(69, 'B10', 'B', 'HONDA', 'JGW-7346', 'HAIKAL', 114534765, '2023-06-07 13:23:00', '2023-06-25 03:54:35', '4', 'NA', ''),
(72, 'B26', 'B', 'PERODUA', 'JGC-7665', 'IKHMAL', 1151406321, '2023-06-10 10:20:00', '2023-06-16 11:04:25', '4', 'none', 'Out'),
(79, 'A2', 'A', 'PROTON', 'AAA-1111', 'ADAM', 143544556, '2023-06-18 17:35:00', '2023-06-18 23:35:00', '23.2', 'NA', ''),
(82, 'B33', 'B', 'TESLA', 'FFF-1222', 'MOKO', 179807654, '2023-06-19 15:00:00', '2023-06-25 02:25:37', '4', '', 'Out'),
(84, 'A8', 'A', 'PERODUA', 'JGC-7665', 'Adam', 1151406321, '2023-06-21 04:52:00', '2023-06-21 05:00:05', '16', 'N/A', 'Out'),
(85, 'B3', 'B', 'TOYOTA', 'SHJ-9878', 'HAIRIE', 197861976, '2023-06-21 04:57:00', '2023-06-21 09:57:00', '4', 'N/A', ''),
(86, 'B7', 'B', 'HYUNDAI', 'AVD-8682', 'KHAIRUL', 198175267, '2023-06-19 04:58:00', '2023-06-19 07:58:00', '4', 'N/A', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `vcategory`
--
ALTER TABLE `vcategory`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `vehicle_info`
--
ALTER TABLE `vehicle_info`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vcategory`
--
ALTER TABLE `vcategory`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vehicle_info`
--
ALTER TABLE `vehicle_info`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
