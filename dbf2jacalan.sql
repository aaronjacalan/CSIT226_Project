-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 08:44 AM
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
-- Database: `dbf2jacalan`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblcustomer`
--

CREATE TABLE `tblcustomer` (
  `customerid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcustomer`
--

INSERT INTO `tblcustomer` (`customerid`, `userid`) VALUES
(1, 3),
(3, 5),
(4, 7),
(5, 9),
(6, 10),
(7, 12);

-- --------------------------------------------------------

--
-- Table structure for table `tblpayment`
--

CREATE TABLE `tblpayment` (
  `paymentid` int(11) NOT NULL,
  `staffid` int(11) NOT NULL,
  `customerid` int(11) NOT NULL,
  `paymentMethod` varchar(50) NOT NULL,
  `totalAmount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblpayment`
--

INSERT INTO `tblpayment` (`paymentid`, `staffid`, `customerid`, `paymentMethod`, `totalAmount`) VALUES
(1, 1, 1, 'GCASH', 199.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblroom`
--

CREATE TABLE `tblroom` (
  `roomid` int(11) NOT NULL,
  `customerid` int(11) DEFAULT NULL,
  `isAvailable` tinyint(1) NOT NULL,
  `dateFrom` date NOT NULL,
  `dateTo` date NOT NULL,
  `roomType` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblroom`
--

INSERT INTO `tblroom` (`roomid`, `customerid`, `isAvailable`, `dateFrom`, `dateTo`, `roomType`, `price`) VALUES
(1, 1, 0, '2025-04-09', '2025-04-10', 'Type1', 1500.00),
(2, 3, 0, '2025-04-11', '2025-04-12', 'Type1', 1500.00),
(3, 5, 0, '2025-03-11', '2025-03-12', 'Type2', 2000.00),
(4, 4, 0, '2025-02-13', '2025-02-14', 'Type1', 1500.00),
(5, 6, 0, '2025-02-19', '2025-02-20', 'Type2', 2000.00);

-- --------------------------------------------------------

--
-- Table structure for table `tblstaff`
--

CREATE TABLE `tblstaff` (
  `staffid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstaff`
--

INSERT INTO `tblstaff` (`staffid`, `userid`) VALUES
(1, 6),
(2, 8),
(3, 11),
(4, 13);

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `userid` int(11) NOT NULL,
  `usertype` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`userid`, `usertype`, `fname`, `lname`, `birthdate`, `username`, `password`) VALUES
(3, 1, 'Gabriel', 'Espelita', '2034-04-06', 'gabe_e34', 'pass'),
(5, 1, 'Aaron', 'Jacalan', '2031-03-12', 'aaronjac', '12345678#'),
(6, 2, 'Raimar', 'Epan', '2021-12-25', 'raie_25', 'shauns'),
(7, 1, 'Mickey', 'Mouse', '2025-04-01', 'mickey_0401', 'passsss'),
(8, 2, 'Donald', 'Duck', '2024-11-18', 'dd_2024', 'donald123'),
(9, 1, 'Goofy', 'Goof', '2023-09-12', 'goofy_g9', 'goofy@321'),
(10, 1, 'Daisy', 'Duck', '2024-02-27', 'daisy_d27', 'daisy!2024'),
(11, 2, 'Pluto', 'Dog', '2022-08-14', 'pluto_d814', 'pluto@2022'),
(12, 1, 'Jane', 'Doe', '1122-12-21', 'jane_doe', 'janedoe#'),
(13, 2, 'joe', 'mama', '1212-12-12', 'admin', 'admin121#');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblcustomer`
--
ALTER TABLE `tblcustomer`
  ADD PRIMARY KEY (`customerid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `tblpayment`
--
ALTER TABLE `tblpayment`
  ADD PRIMARY KEY (`paymentid`),
  ADD KEY `staffid` (`staffid`),
  ADD KEY `customerid` (`customerid`);

--
-- Indexes for table `tblroom`
--
ALTER TABLE `tblroom`
  ADD PRIMARY KEY (`roomid`),
  ADD KEY `customerid` (`customerid`);

--
-- Indexes for table `tblstaff`
--
ALTER TABLE `tblstaff`
  ADD PRIMARY KEY (`staffid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblcustomer`
--
ALTER TABLE `tblcustomer`
  MODIFY `customerid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblpayment`
--
ALTER TABLE `tblpayment`
  MODIFY `paymentid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblroom`
--
ALTER TABLE `tblroom`
  MODIFY `roomid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblstaff`
--
ALTER TABLE `tblstaff`
  MODIFY `staffid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblcustomer`
--
ALTER TABLE `tblcustomer`
  ADD CONSTRAINT `tblcustomer_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `tbluser` (`userid`);

--
-- Constraints for table `tblpayment`
--
ALTER TABLE `tblpayment`
  ADD CONSTRAINT `tblpayment_ibfk_1` FOREIGN KEY (`staffid`) REFERENCES `tblstaff` (`staffid`),
  ADD CONSTRAINT `tblpayment_ibfk_2` FOREIGN KEY (`customerid`) REFERENCES `tblcustomer` (`customerid`);

--
-- Constraints for table `tblroom`
--
ALTER TABLE `tblroom`
  ADD CONSTRAINT `tblroom_ibfk_1` FOREIGN KEY (`customerid`) REFERENCES `tblcustomer` (`customerid`);

--
-- Constraints for table `tblstaff`
--
ALTER TABLE `tblstaff`
  ADD CONSTRAINT `tblstaff_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `tbluser` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
