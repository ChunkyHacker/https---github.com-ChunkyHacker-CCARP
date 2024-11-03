-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2024 at 06:06 PM
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
-- Database: `ccarpcurrentsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `approvedplan`
--

CREATE TABLE `approvedplan` (
  `approved_plan_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `length_lot_area` varchar(255) NOT NULL,
  `width_lot_area` varchar(255) NOT NULL,
  `square_meter_lot` varchar(255) NOT NULL,
  `length_floor_area` varchar(255) NOT NULL,
  `width_floor_area` varchar(255) NOT NULL,
  `square_meter_floor` varchar(255) NOT NULL,
  `initial_budget` varchar(255) NOT NULL,
  `estimated_cost` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `type` varchar(255) NOT NULL,
  `Photo` varchar(255) NOT NULL,
  `q1` enum('Yes','No') NOT NULL,
  `q2` enum('Yes','No') NOT NULL,
  `q3` enum('Yes','No') NOT NULL,
  `q4` enum('Yes','No') NOT NULL,
  `q5` enum('Yes','No') NOT NULL,
  `comment` varchar(255) NOT NULL,
  `status` enum('approve','decline','','') NOT NULL,
  `approved_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approvedplan`
--

INSERT INTO `approvedplan` (`approved_plan_ID`, `User_ID`, `length_lot_area`, `width_lot_area`, `square_meter_lot`, `length_floor_area`, `width_floor_area`, `square_meter_floor`, `initial_budget`, `estimated_cost`, `start_date`, `end_date`, `type`, `Photo`, `q1`, `q2`, `q3`, `q4`, `q5`, `comment`, `status`, `approved_by`) VALUES
(1, 1, '12', '12', '144', '12', '12', '144', '121121', '', '2024-11-17', '2024-11-29', '', 'assets/plan/A_person_working_on_self_development_3993484cd4.webp', 'Yes', 'Yes', 'Yes', 'Yes', 'Yes', 'approved', 'approve', 'test1 test1');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_ID` int(11) NOT NULL,
  `Type_of_work` enum('Inadlaw','Pakyawan','','') NOT NULL,
  `Time_in` varchar(255) NOT NULL,
  `Time_out` varchar(255) NOT NULL,
  `requirement_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_ID`, `Type_of_work`, `Time_in`, `Time_out`, `requirement_ID`) VALUES
(1, 'Inadlaw', '11/3/2024, 11:28:17 AM', '11/3/2024, 11:28:26 AM', 1);

-- --------------------------------------------------------

--
-- Table structure for table `carpenters`
--

CREATE TABLE `carpenters` (
  `Carpenter_ID` int(11) NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `Phone_Number` bigint(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Date_Of_Birth` date NOT NULL,
  `Experiences` varchar(255) NOT NULL,
  `Project_Completed` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `Photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carpenters`
--

INSERT INTO `carpenters` (`Carpenter_ID`, `First_Name`, `Last_Name`, `Phone_Number`, `Email`, `Address`, `Date_Of_Birth`, `Experiences`, `Project_Completed`, `username`, `password`, `specialization`, `Photo`) VALUES
(1, 'test1', 'test1', 123123, '12312@gmail.com', 'asdasda', '2024-11-05', 'asda', 'asdas', 'test1', 'test', 'Roofer, Tile Setter', 'assets/carpenter/20220910103120.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `constructionmaterials`
--

CREATE TABLE `constructionmaterials` (
  `material_id` int(11) NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `type` enum('Materials','Equipment','Tool','') NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `cost` varchar(255) NOT NULL,
  `total_cost` varchar(255) NOT NULL,
  `requirement_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `constructionmaterials`
--

INSERT INTO `constructionmaterials` (`material_id`, `material_name`, `type`, `quantity`, `cost`, `total_cost`, `requirement_ID`) VALUES
(1, 'asda', 'Materials', '2', '100', '200.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `declinedplan`
--

CREATE TABLE `declinedplan` (
  `declined_plan_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `length_lot_area` varchar(255) NOT NULL,
  `width_lot_area` varchar(255) NOT NULL,
  `square_meter_lot` varchar(255) NOT NULL,
  `length_floor_area` varchar(255) NOT NULL,
  `width_floor_area` varchar(255) NOT NULL,
  `square_meter_floor` varchar(255) NOT NULL,
  `initial_budget` varchar(255) NOT NULL,
  `estimated_cost` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `type` varchar(255) NOT NULL,
  `part` varchar(255) NOT NULL,
  `materials` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `total` varchar(255) NOT NULL,
  `Photo` varchar(255) NOT NULL,
  `q1` enum('Yes','No') NOT NULL,
  `q2` enum('Yes','No') NOT NULL,
  `q3` enum('Yes','No') NOT NULL,
  `q4` enum('Yes','No') NOT NULL,
  `q5` enum('Yes','No') NOT NULL,
  `comment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `housetype`
--

CREATE TABLE `housetype` (
  `housetype_id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_ID` int(11) NOT NULL,
  `itemname` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `itemimage` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_ID`, `itemname`, `quantity`, `price`, `type`, `itemimage`) VALUES
(1, 'Hammer', 1, 200, 'Equipment', '6610f3234f58a_Claw-hammer.jpg'),
(2, 'Cement', 1, 200, 'Materials', '6610f43375812_unnamed.jpg'),
(3, 'Plywood', 1, 300, 'Materials', '6610f4d52d4e9_7102Q1SpRBL._AC_SL1500_.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `labor`
--

CREATE TABLE `labor` (
  `labor_ID` int(11) NOT NULL,
  `carpenter_name` varchar(255) NOT NULL,
  `type_of_work` enum('Inadlaw','Pakyawan','','') NOT NULL,
  `days_of_work` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `total_of_laborcost` varchar(255) NOT NULL,
  `additional_cost` varchar(255) NOT NULL,
  `overall_cost` varchar(255) NOT NULL,
  `requirement_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `labor`
--

INSERT INTO `labor` (`labor_ID`, `carpenter_name`, `type_of_work`, `days_of_work`, `rate`, `total_of_laborcost`, `additional_cost`, `overall_cost`, `requirement_ID`) VALUES
(1, 'test1 test1', 'Inadlaw', '12', '100', '1200.00', '600', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `Payroll_ID` int(11) NOT NULL,
  `carpenter_name` varchar(255) NOT NULL,
  `Netpay` int(11) NOT NULL,
  `Days_Of_Work` int(11) NOT NULL,
  `Rate_per_day` int(11) NOT NULL,
  `payment_method` enum('cash_on_hand','creditcard','Gcash','') NOT NULL,
  `sender` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`Payroll_ID`, `carpenter_name`, `Netpay`, `Days_Of_Work`, `Rate_per_day`, `payment_method`, `sender`) VALUES
(1, 'test1 test1', 1200, 12, 100, '', 'test test'),
(2, 'test1 test1', 1200, 12, 100, '', 'test test');

-- --------------------------------------------------------

--
-- Table structure for table `plan`
--

CREATE TABLE `plan` (
  `plan_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `length_lot_area` varchar(255) NOT NULL,
  `width_lot_area` varchar(255) NOT NULL,
  `square_meter_lot` varchar(255) NOT NULL,
  `length_floor_area` varchar(255) NOT NULL,
  `width_floor_area` varchar(255) NOT NULL,
  `square_meter_floor` varchar(255) NOT NULL,
  `initial_budget` varchar(255) NOT NULL,
  `estimated_cost` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `type` varchar(255) NOT NULL,
  `Photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prematerials`
--

CREATE TABLE `prematerials` (
  `prematerials_ID` int(11) NOT NULL,
  `part` varchar(255) NOT NULL,
  `materials` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prematerials`
--

INSERT INTO `prematerials` (`prematerials_ID`, `part`, `materials`, `name`, `quantity`, `price`, `total`) VALUES
(1, 'Bedroom', 'Bed', 'Bed', 1, 800, 800),
(2, 'Dining Room', 'Curtains', 'Curtains', 1, 400, 400),
(3, '', 'Dining Table', 'Bedding', 1, 400, 400),
(4, 'Bedroom', 'Matress', 'Bed', 1, 800, 800);

-- --------------------------------------------------------

--
-- Table structure for table `projectrequirements`
--

CREATE TABLE `projectrequirements` (
  `requirement_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `length_lot_area` varchar(255) NOT NULL,
  `width_lot_area` varchar(255) NOT NULL,
  `square_meter_lot` varchar(255) NOT NULL,
  `length_floor_area` varchar(255) NOT NULL,
  `width_floor_area` varchar(255) NOT NULL,
  `square_meter_floor` varchar(255) NOT NULL,
  `initial_budget` varchar(255) NOT NULL,
  `estimated_cost` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `type` varchar(255) NOT NULL,
  `Photo` varchar(255) NOT NULL,
  `labor_cost` varchar(255) NOT NULL,
  `approved_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projectrequirements`
--

INSERT INTO `projectrequirements` (`requirement_ID`, `User_ID`, `length_lot_area`, `width_lot_area`, `square_meter_lot`, `length_floor_area`, `width_floor_area`, `square_meter_floor`, `initial_budget`, `estimated_cost`, `start_date`, `end_date`, `type`, `Photo`, `labor_cost`, `approved_by`) VALUES
(1, 1, '12', '12', '144', '12', '12', '144', '121121', '', '2024-11-17', '2024-11-29', '', 'assets/plan/A_person_working_on_self_development_3993484cd4.webp', '4000', 'test1 test1');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `Progress_id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Status` enum('Not yet started','Working','Done','') NOT NULL,
  `Materials` varchar(255) NOT NULL,
  `Material_cost` varchar(255) NOT NULL,
  `Total_cost` varchar(255) NOT NULL,
  `requirement_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`Progress_id`, `Name`, `Status`, `Materials`, `Material_cost`, `Total_cost`, `requirement_ID`) VALUES
(1, 'asda', 'Working', 'Roof', '12', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `requiredmaterials`
--

CREATE TABLE `requiredmaterials` (
  `requiredmaterials_ID` int(11) NOT NULL,
  `material` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `total` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requiredmaterials`
--

INSERT INTO `requiredmaterials` (`requiredmaterials_ID`, `material`, `type`, `image`, `quantity`, `price`, `total`) VALUES
(1, 'Hammer', 'Equipment', '6610f3234f58a_Claw-hammer.jpg', '', '200', '200');

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `Shop_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Owner` varchar(255) NOT NULL,
  `Phonenumber` int(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Shop_Description` varchar(255) NOT NULL,
  `Logo` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`Shop_ID`, `Name`, `Address`, `Owner`, `Phonenumber`, `Email`, `Shop_Description`, `Logo`, `username`, `password`) VALUES
(2, 'aming tindahan', 'blk 1 st jude village toril davao city', 'denvier', 2147483647, 'denvieralngog10@gmail.com', 'asdasdasdasas', 'assets/shop/65e2bb48764af_COMPENDIUM-removebg-preview.png', 'amingtindahan', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `specialization`
--

CREATE TABLE `specialization` (
  `Specialization_ID` int(11) NOT NULL,
  `Specialization_Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `specialization`
--

INSERT INTO `specialization` (`Specialization_ID`, `Specialization_Name`) VALUES
(5, 'Roofer'),
(6, 'Tile Setter'),
(7, 'Trim Carpenter'),
(8, 'Shipbuilding'),
(9, 'Framer'),
(10, 'Finish Carpentry'),
(11, 'tigbuhat ug balay');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_ID` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `payment_method` enum('Cash','Online Payment','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_ID`, `total_price`, `payment_method`) VALUES
(1, 2600, 'Cash');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_ID` int(11) NOT NULL,
  `First_Name` varchar(255) NOT NULL,
  `Last_Name` varchar(255) NOT NULL,
  `Phone_Number` bigint(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `Date_Of_Birth` date NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_ID`, `First_Name`, `Last_Name`, `Phone_Number`, `Email`, `Address`, `Date_Of_Birth`, `Username`, `Password`, `Photo`) VALUES
(1, 'test', 'test', 123123, 'denvieralngog10@gmail.com', 'asdadad', '2024-11-21', 'test', 'test', 'assets/user/7th (2).png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approvedplan`
--
ALTER TABLE `approvedplan`
  ADD PRIMARY KEY (`approved_plan_ID`),
  ADD KEY `user_ID` (`User_ID`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_ID`),
  ADD KEY `requirement_ID` (`requirement_ID`);

--
-- Indexes for table `carpenters`
--
ALTER TABLE `carpenters`
  ADD PRIMARY KEY (`Carpenter_ID`);

--
-- Indexes for table `constructionmaterials`
--
ALTER TABLE `constructionmaterials`
  ADD PRIMARY KEY (`material_id`),
  ADD KEY `requirement_ID` (`requirement_ID`);

--
-- Indexes for table `declinedplan`
--
ALTER TABLE `declinedplan`
  ADD PRIMARY KEY (`declined_plan_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `housetype`
--
ALTER TABLE `housetype`
  ADD PRIMARY KEY (`housetype_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_ID`);

--
-- Indexes for table `labor`
--
ALTER TABLE `labor`
  ADD PRIMARY KEY (`labor_ID`),
  ADD KEY `requirement_ID` (`requirement_ID`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`Payroll_ID`);

--
-- Indexes for table `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`plan_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `prematerials`
--
ALTER TABLE `prematerials`
  ADD PRIMARY KEY (`prematerials_ID`);

--
-- Indexes for table `projectrequirements`
--
ALTER TABLE `projectrequirements`
  ADD PRIMARY KEY (`requirement_ID`),
  ADD KEY `user_ID` (`User_ID`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`Progress_id`),
  ADD KEY `requirement_ID` (`requirement_ID`);

--
-- Indexes for table `requiredmaterials`
--
ALTER TABLE `requiredmaterials`
  ADD PRIMARY KEY (`requiredmaterials_ID`);

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`Shop_ID`);

--
-- Indexes for table `specialization`
--
ALTER TABLE `specialization`
  ADD PRIMARY KEY (`Specialization_ID`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approvedplan`
--
ALTER TABLE `approvedplan`
  MODIFY `approved_plan_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `carpenters`
--
ALTER TABLE `carpenters`
  MODIFY `Carpenter_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `constructionmaterials`
--
ALTER TABLE `constructionmaterials`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `declinedplan`
--
ALTER TABLE `declinedplan`
  MODIFY `declined_plan_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `housetype`
--
ALTER TABLE `housetype`
  MODIFY `housetype_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `labor`
--
ALTER TABLE `labor`
  MODIFY `labor_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `Payroll_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plan`
--
ALTER TABLE `plan`
  MODIFY `plan_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prematerials`
--
ALTER TABLE `prematerials`
  MODIFY `prematerials_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `projectrequirements`
--
ALTER TABLE `projectrequirements`
  MODIFY `requirement_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `Progress_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `requiredmaterials`
--
ALTER TABLE `requiredmaterials`
  MODIFY `requiredmaterials_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `Shop_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `specialization`
--
ALTER TABLE `specialization`
  MODIFY `Specialization_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `approvedplan`
--
ALTER TABLE `approvedplan`
  ADD CONSTRAINT `approvedplan_ibfk_2` FOREIGN KEY (`user_ID`) REFERENCES `users` (`User_ID`);

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`requirement_ID`) REFERENCES `projectrequirements` (`requirement_ID`);

--
-- Constraints for table `constructionmaterials`
--
ALTER TABLE `constructionmaterials`
  ADD CONSTRAINT `constructionmaterials_ibfk_1` FOREIGN KEY (`requirement_ID`) REFERENCES `projectrequirements` (`requirement_ID`);

--
-- Constraints for table `declinedplan`
--
ALTER TABLE `declinedplan`
  ADD CONSTRAINT `declinedplan_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`);

--
-- Constraints for table `labor`
--
ALTER TABLE `labor`
  ADD CONSTRAINT `labor_ibfk_2` FOREIGN KEY (`requirement_ID`) REFERENCES `projectrequirements` (`requirement_ID`);

--
-- Constraints for table `plan`
--
ALTER TABLE `plan`
  ADD CONSTRAINT `plan_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `users` (`User_ID`),
  ADD CONSTRAINT `plan_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`);

--
-- Constraints for table `projectrequirements`
--
ALTER TABLE `projectrequirements`
  ADD CONSTRAINT `projectrequirements_ibfk_2` FOREIGN KEY (`user_ID`) REFERENCES `users` (`User_ID`),
  ADD CONSTRAINT `projectrequirements_ibfk_3` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`);

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`requirement_ID`) REFERENCES `projectrequirements` (`requirement_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
