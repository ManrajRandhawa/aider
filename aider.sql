-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2020 at 11:45 AM
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
(19, 'Arsenal', '1,5', 'INACTIVE'),
(20, 'Bro', '2,4', 'INACTIVE');

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
(1, 7, NULL, 'MSU, Seksyen 13, Shah Alam, Selangor, Malaysia', '147, Jalan Bangau, Taman Berkeley, Klang, Selangor, Malaysia', '2020-10-08 15:51:35', '24.50', '0.00', '0.00', 'FINDING-RIDER', '18,19'),
(2, 7, NULL, '140, Jalan Tiara 3, Bandar Baru Klang, 41150 Klang, Selangor, Malaysia', '147, Jalan Bangau, Taman Berkeley, Klang, Selangor, Malaysia', '2020-10-08 15:57:46', '8.45', '0.00', '0.00', 'FINDING-RIDER', '18'),
(3, 7, NULL, '140, Jalan Tiara 3, Bandar Baru Klang, 41150 Klang, Selangor, Malaysia', '147, Jalan Bangau, Taman Berkeley, Klang, Selangor, Malaysia', '2020-10-08 15:58:02', '8.45', '0.00', '0.00', 'FINDING-RIDER', '18'),
(4, 7, NULL, '43, Jalan Dato Yusuf Shahbudin 20, Taman Sentosa, 41200 Klang, Selangor, Malaysia', '147, Jalan Bangau, Taman Berkeley, Klang, Selangor, Malaysia', '2020-10-10 13:49:25', '17.45', '0.00', '0.00', 'FINDING-RIDER', '18'),
(5, 7, 18, '43, Jalan Dato Yusuf Shahbudin 20, Taman Sentosa, 41200 Klang, Selangor, Malaysia', '147, Jalan Bangau, Taman Berkeley, Klang, Selangor, Malaysia', '2020-10-10 13:50:16', '17.45', '5.24', '3.49', 'COMPLETED', ''),
(6, 7, NULL, '43, Jalan Dato Yusuf Shahbudin 20, Taman Sentosa, 41200 Klang, Selangor, Malaysia', '147, Jalan Bangau, Taman Berkeley, Klang, Selangor, Malaysia', '2020-10-10 13:50:28', '17.45', '0.00', '0.00', 'FINDING-RIDER', '18'),
(7, 7, NULL, '43, Jalan Dato Yusuf Shahbudin 20, Taman Sentosa, 41200 Klang, Selangor, Malaysia', '147, Jalan Bangau, Taman Berkeley, Klang, Selangor, Malaysia', '2020-10-10 13:51:03', '17.45', '0.00', '0.00', 'FINDING-RIDER', ''),
(8, 7, NULL, '43, Jalan Dato Yusuf Shahbudin 20, Taman Sentosa, 41200 Klang, Selangor, Malaysia', '147, Jalan Bangau, Taman Berkeley, Klang, Selangor, Malaysia', '2020-10-10 13:52:24', '17.45', '0.00', '0.00', 'FINDING-RIDER', ''),
(9, 7, NULL, '43, Jalan Dato Yusuf Shahbudin 20, Taman Sentosa, 41200 Klang, Selangor, Malaysia', '147, Jalan Bangau, Taman Berkeley, Klang, Selangor, Malaysia', '2020-10-10 13:55:53', '17.45', '0.00', '0.00', 'FINDING-RIDER', '');

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
(1, 1, 'DRIVER', 7, NULL, '2020-10-08 15:51:35', 'FINDING-RIDER', '18,19'),
(2, 2, 'DRIVER', 7, NULL, '2020-10-08 15:57:46', 'FINDING-RIDER', '18'),
(3, 3, 'DRIVER', 7, NULL, '2020-10-08 15:58:02', 'FINDING-RIDER', '18'),
(4, 4, 'DRIVER', 7, NULL, '2020-10-10 13:49:25', 'FINDING-RIDER', '18'),
(5, 5, 'DRIVER', 7, 18, '2020-10-10 13:50:16', 'COMPLETED', ''),
(6, 6, 'DRIVER', 7, NULL, '2020-10-10 13:50:28', 'FINDING-RIDER', '18'),
(7, 7, 'DRIVER', 7, NULL, '2020-10-10 13:51:03', 'FINDING-RIDER', ''),
(8, 8, 'DRIVER', 7, NULL, '2020-10-10 13:52:24', 'FINDING-RIDER', ''),
(9, 9, 'DRIVER', 7, NULL, '2020-10-10 13:55:53', 'FINDING-RIDER', '');

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
(7, 'Manraj Singh', 'myemail.manraj@gmail.com', '$2y$10$fUfhpXy73PsN/5kD7I2yYeOhTrHQJ8Z2SFhP/UAdjazFxjM92UHNq', '0173385550', '1704.80', '147, Jalan Bangau, Taman Berkeley, Klang, Selangor, Malaysia', 'NO'),
(12, 'Test Account', 'kewab32872@qqmimpi.com', '$2y$10$IJpHXLh3G/HyEDe78tgBC.RQyntwFjAqZdnBRG2L6kN.XPnqCaeku', '0123456789', '0.00', '', 'NO'),
(13, 'Test Man', 'test-zh1maa6k3@srv1.mail-tester.com', '$2y$10$hDVeBVwUbH.pptoTJDlOGOVTJii9D.AKz/7ZuU9fgtuC8DYw7iKbi', '0123456789', '0.00', '', 'YES'),
(14, 'Karamveer Singh', 'myemail.karamveer@gmail.com', '$2y$10$kg9cBhjxp9AQAwc4GyjfnOGkCAdmow9waC/lQSP4BMAJCuxVn/jCq', '0173055030', '0.00', '', 'YES');

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
  `Wallet_Balance` decimal(16,2) NOT NULL,
  `Withdraw_Amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `Total_Earnings` decimal(16,2) NOT NULL DEFAULT 0.00,
  `Bank` varchar(32) DEFAULT NULL,
  `Account_Number` int(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `aider_user_rider`
--

INSERT INTO `aider_user_rider` (`ID`, `Name`, `Email_Address`, `Password_Hash`, `Phone_Number`, `Vehicle_Model`, `Vehicle_Plate_Number`, `Loc_LNG`, `Loc_LAT`, `Team_ID`, `Rider_Type`, `Approval_Status`, `Status`, `Transaction_Type`, `Transaction_ID`, `First_Login`, `Wallet_Balance`, `Withdraw_Amount`, `Total_Earnings`, `Bank`, `Account_Number`) VALUES
(1, 'Manraj Singh', 'myemail.manraj@gmail.com', '$2y$10$AsABq.fr50cvWHpsY3XWGuClALDXSGe6osUz2A3RlH3Y3U6EcOpFS', '0173385550', 'BMW 5-Series', 'BMW1234', '', '', 19, 'RIDER', 'APPROVED', 'INACTIVE', '', 0, 'NO', '90.00', '0.00', '82.81', 'RHB', 1234567),
(2, 'Test', 'test@test.com', '$2y$10$AsABq.fr50cvWHpsY3XWGuClALDXSGe6osUz2A3RlH3Y3U6EcOpFS', '0173385550', 'BMW 5 Series (Test)', 'TEST1234', '', '', 20, 'RIDER', 'APPROVED', 'ACTIVE', '', 0, 'NO', '28.64', '0.00', '0.00', NULL, NULL),
(3, 'Test #2', 'mifot18998@mimpi99.com', '$2y$10$NnJm1bPw6l9DxCqTqRQkkONCpBDjUAxZKeV7oTYCE2uO1ECyWxDWS', '0123456789', 'BMW 7-Series', 'LOL3213', '', '', 0, 'DRIVER', 'APPROVED', 'ACTIVE', '', 0, 'YES', '0.00', '0.00', '0.00', NULL, NULL),
(4, 'George Chiew', 'kewage5302@elesb.net', '$2y$10$39Mde2wFuuL0EwL6TQke1.F7wivrZryh5AP1SjRsosV3iR7h.aO1i', '0123456789', 'Honda 80 CC', 'ABC123', '101.4671419', '3.0601361', 20, 'RIDER', 'APPROVED', 'ACTIVE', '', 0, 'NO', '0.00', '0.00', '0.00', NULL, NULL),
(5, 'Rajeshpal Singh', 'official.rajeshpal@gmail.com', '$2y$10$HZjZlQAhg10vtKND3ZGps.5eYWldwbyU8wKNtRsrM300QT6Dp.Tom', '0166800550', 'Proton X70', 'BAM1234', '', '', 19, 'DRIVER', 'APPROVED', 'ACTIVE', '', 0, 'YES', '0.00', '0.00', '0.00', NULL, NULL);

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `aider_user_admin`
--
ALTER TABLE `aider_user_admin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `aider_user_customer`
--
ALTER TABLE `aider_user_customer`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `aider_user_merchant`
--
ALTER TABLE `aider_user_merchant`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aider_user_rider`
--
ALTER TABLE `aider_user_rider`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
