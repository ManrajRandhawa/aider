-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2020 at 12:28 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aider`
--

-- --------------------------------------------------------

--
-- Table structure for table `aider_bill_parcel`
--

CREATE TABLE `aider_bill_parcel` (
  `ID` int(11) NOT NULL,
  `Bill_Reference` varchar(32) NOT NULL,
  `Bill_Amount` decimal(16,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `aider_driver_team`
--

CREATE TABLE `aider_driver_team` (
  `ID` int(11) NOT NULL,
  `Team_Name` varchar(64) NOT NULL,
  `Team_Members` varchar(16) NOT NULL,
  `Team_Status` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `aider_driver_team`
--

INSERT INTO `aider_driver_team` (`ID`, `Team_Name`, `Team_Members`, `Team_Status`) VALUES
(15, 'ABC', '1,2', 'ACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `aider_merchant_product`
--

CREATE TABLE `aider_merchant_product` (
  `ID` int(11) NOT NULL,
  `Merchant_ID` int(11) NOT NULL,
  `Name` int(11) NOT NULL,
  `Price` decimal(16,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `aider_settings`
--

CREATE TABLE `aider_settings` (
  `ID` int(11) NOT NULL,
  `Base_Fare` decimal(16,2) NOT NULL,
  `Price_Per_KM` decimal(16,2) NOT NULL,
  `Maximum_Radius_KM` int(16) NOT NULL,
  `Aider_Driver_Primary_Cut` int(16) NOT NULL,
  `Aider_Driver_Secondary_Cut` int(16) NOT NULL,
  `Aider_Parcel_Cut` int(16) NOT NULL,
  `Aider_Food_Cut` int(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `aider_settings`
--

INSERT INTO `aider_settings` (`ID`, `Base_Fare`, `Price_Per_KM`, `Maximum_Radius_KM`, `Aider_Driver_Primary_Cut`, `Aider_Driver_Secondary_Cut`, `Aider_Parcel_Cut`, `Aider_Food_Cut`) VALUES
(1, '5.00', '1.50', 500, 30, 20, 20, 30);

-- --------------------------------------------------------

--
-- Table structure for table `aider_transaction_driver`
--

CREATE TABLE `aider_transaction_driver` (
  `ID` int(11) NOT NULL,
  `Customer_ID` int(11) NOT NULL,
  `Team_ID` int(11) DEFAULT NULL,
  `Pickup_Location` varchar(255) NOT NULL,
  `Dropoff_Location` varchar(255) NOT NULL,
  `Transaction_Datetime` datetime NOT NULL,
  `Price` decimal(16,2) NOT NULL,
  `Primary_Rider_Cut` decimal(16,2) NOT NULL,
  `Secondary_Rider_Cut` decimal(16,2) NOT NULL,
  `Status` varchar(32) NOT NULL,
  `Team_Denied_List` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `aider_transaction_driver`
--

INSERT INTO `aider_transaction_driver` (`ID`, `Customer_ID`, `Team_ID`, `Pickup_Location`, `Dropoff_Location`, `Transaction_Datetime`, `Price`, `Primary_Rider_Cut`, `Secondary_Rider_Cut`, `Status`, `Team_Denied_List`) VALUES
(1, 7, 15, '155, Jalan Bangau, Taman Berkeley, 41150 Klang, Selangor, Malaysia', 'Klang Parade, Jalan Meru, Kawasan 17, Klang, Selangor, Malaysia', '2020-10-02 18:27:21', '8.00', '2.40', '1.60', 'COMPLETED', ''),
(2, 7, 15, '155, Jalan Bangau, Taman Berkeley, 41150 Klang, Selangor, Malaysia', 'Klang Parade, Jalan Meru, Kawasan 17, Klang, Selangor, Malaysia', '2020-10-02 18:32:26', '8.00', '2.40', '1.60', 'COMPLETED', ''),
(3, 7, 15, '155, Jalan Bangau, Taman Berkeley, 41150 Klang, Selangor, Malaysia', 'Klang Parade, Jalan Meru, Kawasan 17, Klang, Selangor, Malaysia', '2020-10-02 18:34:04', '8.00', '2.40', '1.60', 'COMPLETED', ''),
(4, 7, 15, '155, Jalan Bangau, Taman Berkeley, 41150 Klang, Selangor, Malaysia', 'Klang Parade, Jalan Meru, Kawasan 17, Klang, Selangor, Malaysia', '2020-10-02 18:35:07', '8.00', '2.40', '1.60', 'COMPLETED', ''),
(5, 7, 15, '161, Jalan Bangau, Taman Berkeley, 41150 Klang, Selangor, Malaysia', 'MSU, Seksyen 13, Shah Alam, Selangor, Malaysia', '2020-10-06 15:31:02', '26.15', '7.85', '5.23', 'HEADING_TO_DESTINATION', '');

-- --------------------------------------------------------

--
-- Table structure for table `aider_transaction_food`
--

CREATE TABLE `aider_transaction_food` (
  `ID` int(11) NOT NULL,
  `Customer_ID` int(11) NOT NULL,
  `Merchant_ID` int(11) NOT NULL,
  `Rider_ID` int(11) NOT NULL,
  `Pickup_Location` varchar(255) NOT NULL,
  `Dropoff_Location` varchar(255) NOT NULL,
  `Order_Info` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`Order_Info`)),
  `Pickup_Datetime` datetime NOT NULL,
  `Price` decimal(16,2) NOT NULL,
  `Transaction_Datetime` datetime NOT NULL,
  `Status` varchar(32) NOT NULL,
  `Rider_Denied_List` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `aider_transaction_parcel`
--

CREATE TABLE `aider_transaction_parcel` (
  `ID` int(11) NOT NULL,
  `Customer_ID` int(11) NOT NULL,
  `Rider_ID` int(11) DEFAULT NULL,
  `Pickup_Location` varchar(255) NOT NULL,
  `Dropoff_Location` varchar(255) NOT NULL,
  `Pickup_Details_Name` varchar(32) DEFAULT NULL,
  `Pickup_Details_PhoneNum` varchar(32) DEFAULT NULL,
  `Dropoff_Details_Name` varchar(32) DEFAULT NULL,
  `Dropoff_Details_PhoneNum` varchar(32) DEFAULT NULL,
  `Pickup_Datetime` datetime NOT NULL,
  `Price` decimal(16,2) NOT NULL,
  `Rider_Cut` decimal(16,2) NOT NULL,
  `Transaction_Datetime` datetime NOT NULL,
  `Status` varchar(32) NOT NULL,
  `Rider_Denied_List` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `aider_transaction_parcel`
--

INSERT INTO `aider_transaction_parcel` (`ID`, `Customer_ID`, `Rider_ID`, `Pickup_Location`, `Dropoff_Location`, `Pickup_Details_Name`, `Pickup_Details_PhoneNum`, `Dropoff_Details_Name`, `Dropoff_Details_PhoneNum`, `Pickup_Datetime`, `Price`, `Rider_Cut`, `Transaction_Datetime`, `Status`, `Rider_Denied_List`) VALUES
(1, 7, 1, 'MSU, Seksyen 13, Shah Alam, Selangor, Malaysia', '147, Jalan Bangau, Taman Berkeley, Klang, Selangor, Malaysia', '', '', '', '', '2020-10-02 18:36:07', '24.50', '4.90', '2020-10-02 18:36:07', 'COMPLETED', ''),
(2, 7, 1, 'MSU, Seksyen 13, Shah Alam, Selangor, Malaysia', '147, Jalan Bangau, Taman Berkeley, Klang, Selangor, Malaysia', '', '', '', '', '2020-10-02 18:37:30', '24.50', '4.90', '2020-10-02 18:37:30', 'COMPLETED', '');

-- --------------------------------------------------------

--
-- Table structure for table `aider_transaction_sorting`
--

CREATE TABLE `aider_transaction_sorting` (
  `ID` int(11) NOT NULL,
  `Transaction_ID` int(32) NOT NULL,
  `Transaction_Type` varchar(16) NOT NULL,
  `Customer_ID` int(16) NOT NULL,
  `RT_ID` int(16) DEFAULT NULL,
  `Transaction_Datetime` datetime NOT NULL,
  `Transaction_Status` varchar(32) NOT NULL,
  `Rider_Denied_List` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `aider_transaction_sorting`
--

INSERT INTO `aider_transaction_sorting` (`ID`, `Transaction_ID`, `Transaction_Type`, `Customer_ID`, `RT_ID`, `Transaction_Datetime`, `Transaction_Status`, `Rider_Denied_List`) VALUES
(1, 1, 'DRIVER', 7, 15, '2020-10-02 18:27:21', 'COMPLETED', ''),
(2, 2, 'DRIVER', 7, 15, '2020-10-02 18:32:26', 'COMPLETED', ''),
(3, 3, 'DRIVER', 7, 15, '2020-10-02 18:34:04', 'COMPLETED', ''),
(4, 4, 'DRIVER', 7, 15, '2020-10-02 18:35:07', 'COMPLETED', ''),
(5, 1, 'PARCEL', 7, 1, '2020-10-02 18:36:07', 'COMPLETED', ''),
(6, 2, 'PARCEL', 7, 1, '2020-10-02 18:37:30', 'COMPLETED', ''),
(7, 5, 'DRIVER', 7, 15, '2020-10-06 15:31:02', 'HEADING_TO_DESTINATION', '');

-- --------------------------------------------------------

--
-- Table structure for table `aider_user_admin`
--

CREATE TABLE `aider_user_admin` (
  `ID` int(11) NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Email_Address` varchar(255) NOT NULL,
  `Password_Hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `aider_user_admin`
--

INSERT INTO `aider_user_admin` (`ID`, `Name`, `Email_Address`, `Password_Hash`) VALUES
(2, 'Manraj Singh', 'myemail.manraj@gmail.com', '$2y$10$lA7oOOKITby6SEA.UFrZmu/wYlVRGUkVYweJH6ZfFVDyecQqQIkdC');

-- --------------------------------------------------------

--
-- Table structure for table `aider_user_customer`
--

CREATE TABLE `aider_user_customer` (
  `ID` int(11) NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Email_Address` varchar(255) NOT NULL,
  `Password_Hash` varchar(255) NOT NULL,
  `Phone_Number` varchar(32) NOT NULL,
  `Credit` decimal(16,2) NOT NULL,
  `Home_Address` varchar(255) NOT NULL,
  `First_Login` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `aider_user_customer`
--

INSERT INTO `aider_user_customer` (`ID`, `Name`, `Email_Address`, `Password_Hash`, `Phone_Number`, `Credit`, `Home_Address`, `First_Login`) VALUES
(7, 'Manraj Singh', 'myemail.manraj@gmail.com', '$2y$10$fUfhpXy73PsN/5kD7I2yYeOhTrHQJ8Z2SFhP/UAdjazFxjM92UHNq', '0173385550', '1739.15', '147, Jalan Bangau, Taman Berkeley, Klang, Selangor, Malaysia', 'NO'),
(12, 'Test Account', 'kewab32872@qqmimpi.com', '$2y$10$IJpHXLh3G/HyEDe78tgBC.RQyntwFjAqZdnBRG2L6kN.XPnqCaeku', '0123456789', '0.00', '', 'NO');

-- --------------------------------------------------------

--
-- Table structure for table `aider_user_merchant`
--

CREATE TABLE `aider_user_merchant` (
  `ID` int(11) NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Email_Address` varchar(255) NOT NULL,
  `Password_Hash` varchar(255) NOT NULL,
  `Location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `aider_user_rider`
--

CREATE TABLE `aider_user_rider` (
  `ID` int(11) NOT NULL,
  `Name` varchar(128) NOT NULL,
  `Email_Address` varchar(255) NOT NULL,
  `Password_Hash` varchar(255) NOT NULL,
  `Phone_Number` varchar(32) NOT NULL,
  `Vehicle_Model` varchar(128) NOT NULL,
  `Vehicle_Plate_Number` varchar(32) NOT NULL,
  `Loc_LNG` varchar(128) NOT NULL,
  `Loc_LAT` varchar(128) NOT NULL,
  `Team_ID` int(16) NOT NULL,
  `Rider_Type` varchar(16) NOT NULL,
  `Approval_Status` varchar(32) NOT NULL,
  `Status` varchar(32) NOT NULL,
  `Transaction_Type` varchar(16) NOT NULL,
  `Transaction_ID` int(16) NOT NULL,
  `First_Login` varchar(16) NOT NULL,
  `Wallet_Balance` decimal(16,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `aider_user_rider`
--

INSERT INTO `aider_user_rider` (`ID`, `Name`, `Email_Address`, `Password_Hash`, `Phone_Number`, `Vehicle_Model`, `Vehicle_Plate_Number`, `Loc_LNG`, `Loc_LAT`, `Team_ID`, `Rider_Type`, `Approval_Status`, `Status`, `Transaction_Type`, `Transaction_ID`, `First_Login`, `Wallet_Balance`) VALUES
(1, 'Manraj Singh', 'myemail.manraj@gmail.com', '$2y$10$AsABq.fr50cvWHpsY3XWGuClALDXSGe6osUz2A3RlH3Y3U6EcOpFS', '0173385550', 'BMW 5-Series', 'BMW1234', '101.467566', '3.060067', 15, 'RIDER', 'APPROVED', 'HEADING_TO_DESTINATION', 'DRIVER', 5, 'NO', '27.25'),
(2, 'Test', 'test@test.com', '$2y$10$AsABq.fr50cvWHpsY3XWGuClALDXSGe6osUz2A3RlH3Y3U6EcOpFS', '0173385550', 'BMW 5 Series (Test)', 'TEST1234', '', '', 15, 'RIDER', 'APPROVED', 'HEADING_TO_DESTINATION', 'DRIVER', 5, 'NO', '11.63'),
(3, 'Test #2', 'mifot18998@mimpi99.com', '$2y$10$NnJm1bPw6l9DxCqTqRQkkONCpBDjUAxZKeV7oTYCE2uO1ECyWxDWS', '0123456789', 'BMW 7-Series', 'LOL3213', '', '', 0, 'DRIVER', 'APPROVED', 'ACTIVE', '', 0, 'YES', '0.00'),
(4, 'George Chiew', 'kewage5302@elesb.net', '$2y$10$39Mde2wFuuL0EwL6TQke1.F7wivrZryh5AP1SjRsosV3iR7h.aO1i', '0123456789', 'Honda 80 CC', 'ABC123', '101.4671419', '3.0601361', 0, 'RIDER', 'APPROVED', 'ACTIVE', '', 0, 'NO', '0.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aider_driver_team`
--
ALTER TABLE `aider_driver_team`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `aider_merchant_product`
--
ALTER TABLE `aider_merchant_product`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `aider_settings`
--
ALTER TABLE `aider_settings`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `aider_transaction_driver`
--
ALTER TABLE `aider_transaction_driver`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `aider_transaction_food`
--
ALTER TABLE `aider_transaction_food`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `aider_transaction_parcel`
--
ALTER TABLE `aider_transaction_parcel`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `aider_transaction_sorting`
--
ALTER TABLE `aider_transaction_sorting`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `aider_user_admin`
--
ALTER TABLE `aider_user_admin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `aider_user_customer`
--
ALTER TABLE `aider_user_customer`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `aider_user_merchant`
--
ALTER TABLE `aider_user_merchant`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `aider_user_rider`
--
ALTER TABLE `aider_user_rider`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aider_driver_team`
--
ALTER TABLE `aider_driver_team`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `aider_merchant_product`
--
ALTER TABLE `aider_merchant_product`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aider_settings`
--
ALTER TABLE `aider_settings`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `aider_transaction_driver`
--
ALTER TABLE `aider_transaction_driver`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `aider_transaction_food`
--
ALTER TABLE `aider_transaction_food`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aider_transaction_parcel`
--
ALTER TABLE `aider_transaction_parcel`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `aider_transaction_sorting`
--
ALTER TABLE `aider_transaction_sorting`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `aider_user_admin`
--
ALTER TABLE `aider_user_admin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `aider_user_customer`
--
ALTER TABLE `aider_user_customer`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `aider_user_merchant`
--
ALTER TABLE `aider_user_merchant`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aider_user_rider`
--
ALTER TABLE `aider_user_rider`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
