-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2025 at 06:32 AM
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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `firstname`, `lastname`, `username`, `password`) VALUES
(3, 'daidailabdinbir', 'daidai', 'daidai3', '123');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_ID` int(11) NOT NULL,
  `type_of_work` enum('Per Day','On The Job','','') NOT NULL,
  `Time_in` varchar(255) NOT NULL,
  `Time_out` varchar(255) NOT NULL,
  `contract_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `Photo` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carpenters`
--

INSERT INTO `carpenters` (`Carpenter_ID`, `First_Name`, `Last_Name`, `Phone_Number`, `Email`, `Address`, `Date_Of_Birth`, `Experiences`, `Project_Completed`, `username`, `password`, `specialization`, `Photo`) VALUES
(1, 'John Denvier', 'Alngog', 9153520025, 'denvieralngog10@gmail.com', 'st jude village daliao toril davao city', '2002-09-10', 'EEnttrryy', 'sasdahsdasad', 'denviercarp', '123', 'Roofer, Tile Setter', 0x6173736574732f63617270656e7465722f706963747572652e6a7067),
(2, 'car1', 'car1', 9153520025, 'car1@gmail.com', 'asdasd', '2025-02-03', '222', '22', 'car1', '123', 'Roofer, Tile Setter', 0x6173736574732f63617270656e7465722f53637265656e73686f745f35312e6a7067),
(3, 'car2', 'car2', 9153520025, 'denvieralngog10@gmail.com', 'dasd', '2025-02-20', 'ww', 'ww', 'car2', '123', '', 0x6173736574732f63617270656e7465722f7764737063732e6a7067),
(4, 'car3', 'car3', 9153520025, 'car3@gmail.com', 'asdasd', '2025-02-22', 'www', 'wwww', 'car3', '123', '', 0x6173736574732f63617270656e7465722f53637265656e73686f745f35382e6a7067),
(5, 'car4', 'car4', 9153520025, 'car4@gmail.com', 'wwww', '2025-02-22', 'wwww', 'www', 'car4', '123', '', 0x6173736574732f63617270656e7465722f3437363839323938365f323033353233323535333633313635385f363639363135313432313333333234323636335f6e202831292e706e67);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `carpenter_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `comment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `completed_task`
--

CREATE TABLE `completed_task` (
  `completed_task_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `task_id` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `contract_ID` int(11) NOT NULL,
  `timestamp` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `contract_ID` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `labor_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `plan_ID` int(11) NOT NULL,
  `approval_ID` int(11) NOT NULL,
  `Carpenter_ID` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `attendance_ID` int(11) NOT NULL,
  `Progress_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `completed_task_id` int(11) NOT NULL,
  `transaction_ID` int(11) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL,
  `type_of_work` enum('select type of work','Per day','On The Job') DEFAULT 'select type of work'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expenses_id` int(11) NOT NULL,
  `materials` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `carpenter_name` varchar(255) NOT NULL,
  `Netpay` int(11) NOT NULL,
  `Days_Of_Work` int(11) NOT NULL,
  `Rate_per_day` int(11) NOT NULL,
  `overall_cost` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `like_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `carpenter_id` int(11) NOT NULL,
  `like_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `Payment_ID` int(11) NOT NULL,
  `Contract_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `Carpenter_ID` int(11) NOT NULL,
  `Labor_Cost` decimal(10,2) NOT NULL,
  `Duration` int(11) NOT NULL,
  `type_of_work` varchar(255) NOT NULL,
  `Payment_Method` varchar(50) NOT NULL,
  `Payment_Date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `more_details` longtext NOT NULL,
  `Photo` varchar(255) NOT NULL,
  `carpenter_limit` int(11) NOT NULL DEFAULT 1,
  `status` enum('open','closed') DEFAULT 'open',
  `views` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plan_approval`
--

CREATE TABLE `plan_approval` (
  `approval_ID` int(11) NOT NULL,
  `plan_ID` int(11) NOT NULL,
  `carpenter_ID` int(11) NOT NULL,
  `total_score` int(11) DEFAULT 0,
  `scope_scores` text DEFAULT NULL,
  `site_scores` text DEFAULT NULL,
  `workforce_scores` text DEFAULT NULL,
  `client_responses` text DEFAULT NULL,
  `client_comments` text DEFAULT NULL,
  `evaluator_name` varchar(255) DEFAULT NULL,
  `evaluation_date` date DEFAULT NULL,
  `final_decision` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending'
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
  `total` int(11) NOT NULL,
  `estimated_cost` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prematerialsinventory`
--

CREATE TABLE `prematerialsinventory` (
  `prematerialsinventory_id` int(11) NOT NULL,
  `structure` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prematerialsinventory`
--

INSERT INTO `prematerialsinventory` (`prematerialsinventory_id`, `structure`, `name`, `price`) VALUES
(3, 'Holcim-Excel - 40 kg / bag', 'Holcim-Excel - 40 kg / bag', 225),
(4, 'Mindanao -40 Kg / bag', 'Mindanao -40 Kg / bag', 225),
(5, 'Hollow Blocks (non-load bearing)/piece', 'Hollow Blocks Width 101 mm (4\'\')', 9),
(6, 'Hollow Blocks (non-load bearing)/piece', 'Hollow Blocks - Width 152 mm (6\")', 9),
(7, 'GI Sheet, Roofing - Corrugated', 'Ace - 0.2mm Z215 6 ft.', 120),
(8, 'GI Sheet, Roofing - Corrugated', 'Apo - 0.2mm Z215 6 ft.', 120),
(9, 'Deformed Regular Steel Bars (Grade 230 or Grade 33)', 'Builders - 10mm x 6m', 145),
(10, 'Deformed Regular Steel Bars (Grade 230 or Grade 33)', 'Capasco - 10mm x 6m', 145),
(11, 'Common Wire Nail', 'Fidelity - 50mm (2\")', 60),
(12, 'Common Wire Nail', 'Highsteel - 50mm (2\")', 60),
(13, 'Steel Wire', 'Cadiz - 1.65mm', 68),
(14, 'Steel Wire', 'CBK - 1.65mm', 68);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_ID` int(11) NOT NULL,
  `contract_ID` int(11) NOT NULL,
  `plan_ID` int(11) NOT NULL,
  `user_ID` int(11) NOT NULL,
  `criteria1` int(11) NOT NULL,
  `criteria2` int(11) NOT NULL,
  `criteria3` int(11) NOT NULL,
  `criteria4` int(11) NOT NULL,
  `criteria5` int(11) NOT NULL,
  `criteria6` int(11) NOT NULL,
  `criteria7` int(11) NOT NULL,
  `criteria8` int(11) NOT NULL,
  `comments` text DEFAULT NULL,
  `rating_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `contract_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `task_id` int(11) NOT NULL,
  `task` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT 'Not yet started',
  `contract_ID` int(11) NOT NULL,
  `timestamp` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_ID` int(11) NOT NULL,
  `receipt_photo` mediumblob NOT NULL,
  `details` longtext NOT NULL,
  `contract_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `Photo` mediumblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_ID`, `First_Name`, `Last_Name`, `Phone_Number`, `Email`, `Address`, `Date_Of_Birth`, `Username`, `Password`, `Photo`) VALUES
(1, 'denvier', 'john', 11213, 'denvieralngog10@gmail.com', 'sdsdasadaasadsd', '2024-11-14', 'denvier', '123', 0x6173736574732f757365722f34396234656636352d356636382d343633362d616166662d3136623239306534316232352e77656270);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_ID`),
  ADD KEY `attendance` (`contract_ID`);

--
-- Indexes for table `carpenters`
--
ALTER TABLE `carpenters`
  ADD PRIMARY KEY (`Carpenter_ID`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `completed_task`
--
ALTER TABLE `completed_task`
  ADD PRIMARY KEY (`completed_task_id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `contract_ID` (`contract_ID`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`contract_ID`),
  ADD KEY `fk_contracts_approval` (`approval_ID`),
  ADD KEY `fk_contracts_carpenter` (`Carpenter_ID`),
  ADD KEY `fk_contracts_plan` (`plan_ID`),
  ADD KEY `attendance` (`attendance_ID`),
  ADD KEY `report` (`Progress_id`),
  ADD KEY `task` (`task_id`),
  ADD KEY `completed_task_id` (`completed_task_id`),
  ADD KEY `transaction_ID` (`transaction_ID`),
  ADD KEY `fk_contracts_user` (`User_ID`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expenses_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`like_id`),
  ADD UNIQUE KEY `unique_like` (`plan_id`,`carpenter_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`Payment_ID`),
  ADD KEY `Contract_ID` (`Contract_ID`),
  ADD KEY `User_ID` (`User_ID`),
  ADD KEY `fk_payment_carpenter` (`Carpenter_ID`);

--
-- Indexes for table `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`plan_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `plan_approval`
--
ALTER TABLE `plan_approval`
  ADD PRIMARY KEY (`approval_ID`),
  ADD KEY `plan_ID` (`plan_ID`),
  ADD KEY `carpenter_ID` (`carpenter_ID`);

--
-- Indexes for table `prematerials`
--
ALTER TABLE `prematerials`
  ADD PRIMARY KEY (`prematerials_ID`);

--
-- Indexes for table `prematerialsinventory`
--
ALTER TABLE `prematerialsinventory`
  ADD PRIMARY KEY (`prematerialsinventory_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_ID`),
  ADD KEY `contract_ID` (`contract_ID`),
  ADD KEY `plan_ID` (`plan_ID`),
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`Progress_id`),
  ADD KEY `report` (`contract_ID`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `task` (`contract_ID`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_ID`),
  ADD KEY `idx_contract_id` (`contract_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carpenters`
--
ALTER TABLE `carpenters`
  MODIFY `Carpenter_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `completed_task`
--
ALTER TABLE `completed_task`
  MODIFY `completed_task_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `contract_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expenses_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `Payment_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plan`
--
ALTER TABLE `plan`
  MODIFY `plan_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plan_approval`
--
ALTER TABLE `plan_approval`
  MODIFY `approval_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prematerials`
--
ALTER TABLE `prematerials`
  MODIFY `prematerials_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `prematerialsinventory`
--
ALTER TABLE `prematerialsinventory`
  MODIFY `prematerialsinventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `Progress_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`contract_ID`) REFERENCES `contracts` (`contract_ID`);

--
-- Constraints for table `completed_task`
--
ALTER TABLE `completed_task`
  ADD CONSTRAINT `completed_task_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `task` (`task_id`),
  ADD CONSTRAINT `completed_task_ibfk_2` FOREIGN KEY (`contract_ID`) REFERENCES `contracts` (`contract_ID`);

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `fk_contracts_approval` FOREIGN KEY (`approval_ID`) REFERENCES `plan_approval` (`approval_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_contracts_carpenter` FOREIGN KEY (`Carpenter_ID`) REFERENCES `carpenters` (`Carpenter_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_contracts_plan` FOREIGN KEY (`plan_ID`) REFERENCES `plan` (`plan_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_contracts_user` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_transaction` FOREIGN KEY (`transaction_ID`) REFERENCES `transaction` (`transaction_ID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_carpenter` FOREIGN KEY (`Carpenter_ID`) REFERENCES `users` (`User_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`Contract_ID`) REFERENCES `contracts` (`contract_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`) ON DELETE CASCADE;

--
-- Constraints for table `plan`
--
ALTER TABLE `plan`
  ADD CONSTRAINT `plan_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`),
  ADD CONSTRAINT `plan_ibfk_2` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`);

--
-- Constraints for table `plan_approval`
--
ALTER TABLE `plan_approval`
  ADD CONSTRAINT `plan_approval_ibfk_1` FOREIGN KEY (`plan_ID`) REFERENCES `plan` (`plan_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `plan_approval_ibfk_2` FOREIGN KEY (`carpenter_ID`) REFERENCES `carpenters` (`Carpenter_ID`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`contract_ID`) REFERENCES `contracts` (`contract_ID`),
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`plan_ID`) REFERENCES `plan` (`plan_ID`),
  ADD CONSTRAINT `ratings_ibfk_3` FOREIGN KEY (`user_ID`) REFERENCES `users` (`User_ID`);

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`contract_ID`) REFERENCES `contracts` (`contract_ID`);

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`contract_ID`) REFERENCES `contracts` (`contract_ID`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `fk_contract` FOREIGN KEY (`contract_ID`) REFERENCES `contracts` (`contract_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
