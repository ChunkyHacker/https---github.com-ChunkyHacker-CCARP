-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2025 at 04:05 PM
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
(8, 'admin', 'alngog', 'admin', '123'),
(9, 'john', 'john', 'john', '123');

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
(1, 'John Denviers', 'Alngog Carpenter', 9153520025, 'denvieralngog10@gmail.com', 'st jude village daliao toril davao city', '2002-09-10', 'EEnttrryy', 'sasdahsdasad', 'denviercarp', '123', 'Roofer, Tile Setter', 0x6173736574732f63617270656e7465722f706963747572652e6a7067),
(2, 'Carpenter', 'One', 9153520025, 'car1@gmail.com', 'asdasd', '2025-02-03', '222', '22', 'car1', '123', 'Roofer, Tile Setter', 0x6173736574732f63617270656e7465722f53637265656e73686f745f35312e6a7067),
(3, 'Carpenter', 'Two', 9153520025, 'denvieralngog10@gmail.com', 'dasd', '2025-02-20', 'ww', 'ww', 'car2', '123', 'wwww', 0x6173736574732f63617270656e7465722f7764737063732e6a7067),
(6, 'Angelito', 'Engcoy', 9153520025, 'angelito@gmail.com', 'Blk 1 Prudential Village Daliao Toril', '2002-10-09', '8', '8', 'angelito', '123', '', 0x6173736574732f63617270656e7465722f3430353830393635325f3132323130303031333733303133303833385f3437383638353031393236393037333839395f6e2e6a7067),
(7, 'Cesar ', 'Peralta', 9153520025, 'cesar@gmail.com', 'bago oshiro', '2002-10-09', '9', '9', 'cesar', '123', '', 0x6173736574732f63617270656e7465722f626c616e6b2d70726f66696c652d706963747572652d3937333436305f313238302e706e67),
(8, 'Francisco', 'Bicbic', 9153520025, 'francisco@gmail.com', 'Blk 1 Upper Piedad Toril Davao City', '2002-09-10', '8', '8', 'francisco', '123', '', 0x6173736574732f63617270656e7465722f626c616e6b2d70726f66696c652d706963747572652d3937333436305f313238302e706e67),
(9, 'carpenter', 'carpenter', 9153520025, 'denvieralngog10@gmail.com', 'blk 1 st jude village daliao', '2025-04-24', '222', '222', 'carpenter123', '123', '', 0x6173736574732f63617270656e7465722f626c616e6b2d70726f66696c652d706963747572652d3937333436305f313238302e706e67);

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
  `status` enum('pending','accepted','rejected','completed') DEFAULT 'pending',
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
-- Table structure for table `job_ratings`
--

CREATE TABLE `job_ratings` (
  `job_rating_ID` int(11) NOT NULL,
  `Carpenter_ID` int(11) NOT NULL,
  `plan_ID` int(11) NOT NULL,
  `q1_rating` int(11) NOT NULL,
  `q2_rating` int(11) NOT NULL,
  `q3_rating` int(11) NOT NULL,
  `q4_rating` int(11) NOT NULL,
  `q5_rating` int(11) NOT NULL,
  `q6_rating` int(11) NOT NULL,
  `q7_rating` int(11) NOT NULL,
  `q8_answer` varchar(5) NOT NULL,
  `q8_explanation` text DEFAULT NULL,
  `q9_answer` text NOT NULL,
  `q10_answer` text NOT NULL,
  `rating_date` timestamp NOT NULL DEFAULT current_timestamp()
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
  `estimated_cost` decimal(8,2) DEFAULT NULL,
  `User_ID` int(11) DEFAULT NULL
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
(19, 'GI Sheet, Roofing - Corrugated', 'Ace - 0.2mm Z215 6 ft.', 120),
(20, 'GI Sheet, Roofing - Corrugated', 'Apo - 0.2mm Z215 6 ft.', 120),
(21, 'Deformed Regular Steel Bars (Grade 230 or Grade 33)', 'Builders - 10mm x 6m', 145),
(22, 'Deformed Regular Steel Bars (Grade 230 or Grade 33)', 'Capasco - 10mm x 6m', 145),
(23, 'Common Wire Nail', 'Fidelity - 50mm (2\")', 60),
(24, 'Common Wire Nail', 'Highsteel - 50mm (2\")', 60),
(25, 'Steel Wire', 'Cadiz - 1.65mm', 68),
(26, 'Steel Wire', 'CBK - 1.65mm', 68);

-- --------------------------------------------------------

--
-- Table structure for table `project_turnover`
--

CREATE TABLE `project_turnover` (
  `turnover_id` int(11) NOT NULL,
  `contract_ID` int(11) DEFAULT NULL,
  `plan_ID` int(11) DEFAULT NULL,
  `task_id` int(11) DEFAULT NULL,
  `completed_task_id` int(11) DEFAULT NULL,
  `actual_completion_date` date DEFAULT NULL,
  `client_signature` varchar(255) DEFAULT NULL,
  `turnover_notes` text DEFAULT NULL,
  `supporting_documents` varchar(255) DEFAULT NULL,
  `confirmation_status` enum('pending','confirmed') DEFAULT 'pending',
  `client_feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `turnedover_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_ID` int(11) NOT NULL,
  `contract_ID` int(11) NOT NULL,
  `plan_ID` int(11) NOT NULL,
  `user_ID` int(11) NOT NULL,
  `Carpenter_ID` int(11) NOT NULL,
  `site_prep_score` int(11) NOT NULL,
  `materials_score` int(11) NOT NULL,
  `foundation_score` int(11) NOT NULL,
  `mep_score` int(11) NOT NULL,
  `exterior_score` int(11) NOT NULL,
  `interior_score` int(11) NOT NULL,
  `windows_score` int(11) NOT NULL,
  `insulation_score` int(11) NOT NULL,
  `comments` text DEFAULT NULL,
  `rating_date` datetime NOT NULL,
  `landscaping_score` int(11) NOT NULL,
  `final_score` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `Progress_id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Status` enum('Not yet started','Working','Done','') NOT NULL,
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
(2, 'Cecilia', 'Maglangit', 9153520025, 'cecilia@gmail.com', 'Blk 1 St Jude Village Daliao Toril Davao CIty', '2002-09-10', 'cecilia', '123', 0x6173736574732f757365722f626c616e6b2d70726f66696c652d706963747572652d3937333436305f313238302e706e67),
(3, 'Mark John', 'Canada', 9153520025, 'markjohn@gmail.com', 'Villarosa Dacoville', '2002-09-10', 'mark', '123', 0x6173736574732f757365722f3439323331363630355f3132323134393133313534323534363531335f363038333538333530303132323431393931385f6e2e6a7067),
(4, 'Marice', 'Oberez', 9153520025, 'marice@gmail.com', 'Purok 9 Upper Piedad Bato', '2002-10-09', 'marice', '123', 0x6173736574732f757365722f3434383936353832355f313438393435333035313635373038355f313130323438393935383731373538363031395f6e2e6a7067),
(6, 'alngog', 'alngog', 9153520025, 'alngog10@gmail.com', 'asdasdasd', '2025-04-24', '123', '123', 0x6173736574732f757365722f626c616e6b2d70726f66696c652d706963747572652d3937333436305f313238302e706e67),
(7, '111', '1111', 9153520025, 'alngog10@gmail.com', 'denvieralgngo10@gmail.com', '2025-04-24', '12322', '123', 0x6173736574732f757365722f626c616e6b2d70726f66696c652d706963747572652d3937333436305f313238302e706e67);

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
-- Indexes for table `job_ratings`
--
ALTER TABLE `job_ratings`
  ADD PRIMARY KEY (`job_rating_ID`),
  ADD KEY `Carpenter_ID` (`Carpenter_ID`),
  ADD KEY `plan_ID` (`plan_ID`);

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
  ADD PRIMARY KEY (`prematerials_ID`),
  ADD KEY `User_ID` (`User_ID`);

--
-- Indexes for table `prematerialsinventory`
--
ALTER TABLE `prematerialsinventory`
  ADD PRIMARY KEY (`prematerialsinventory_id`);

--
-- Indexes for table `project_turnover`
--
ALTER TABLE `project_turnover`
  ADD PRIMARY KEY (`turnover_id`),
  ADD KEY `contract_ID` (`contract_ID`),
  ADD KEY `plan_ID` (`plan_ID`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `completed_task_id` (`completed_task_id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `turnedover_by` (`turnedover_by`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_ID`),
  ADD KEY `contract_ID` (`contract_ID`),
  ADD KEY `plan_ID` (`plan_ID`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `fk_ratings_carpenter` (`Carpenter_ID`);

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
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carpenters`
--
ALTER TABLE `carpenters`
  MODIFY `Carpenter_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- AUTO_INCREMENT for table `job_ratings`
--
ALTER TABLE `job_ratings`
  MODIFY `job_rating_ID` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `prematerialsinventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `project_turnover`
--
ALTER TABLE `project_turnover`
  MODIFY `turnover_id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- Constraints for table `job_ratings`
--
ALTER TABLE `job_ratings`
  ADD CONSTRAINT `job_ratings_ibfk_1` FOREIGN KEY (`Carpenter_ID`) REFERENCES `carpenters` (`Carpenter_ID`),
  ADD CONSTRAINT `job_ratings_ibfk_2` FOREIGN KEY (`plan_ID`) REFERENCES `plan` (`plan_ID`);

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_carpenter` FOREIGN KEY (`Carpenter_ID`) REFERENCES `carpenters` (`Carpenter_ID`) ON DELETE CASCADE,
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
-- Constraints for table `prematerials`
--
ALTER TABLE `prematerials`
  ADD CONSTRAINT `prematerials_ibfk_1` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`);

--
-- Constraints for table `project_turnover`
--
ALTER TABLE `project_turnover`
  ADD CONSTRAINT `project_turnover_ibfk_1` FOREIGN KEY (`contract_ID`) REFERENCES `contracts` (`contract_ID`),
  ADD CONSTRAINT `project_turnover_ibfk_2` FOREIGN KEY (`plan_ID`) REFERENCES `plan` (`plan_ID`),
  ADD CONSTRAINT `project_turnover_ibfk_3` FOREIGN KEY (`task_id`) REFERENCES `task` (`task_id`),
  ADD CONSTRAINT `project_turnover_ibfk_4` FOREIGN KEY (`completed_task_id`) REFERENCES `completed_task` (`completed_task_id`),
  ADD CONSTRAINT `project_turnover_ibfk_5` FOREIGN KEY (`approved_by`) REFERENCES `users` (`User_ID`),
  ADD CONSTRAINT `project_turnover_ibfk_6` FOREIGN KEY (`turnedover_by`) REFERENCES `carpenters` (`Carpenter_ID`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `fk_ratings_carpenter` FOREIGN KEY (`Carpenter_ID`) REFERENCES `carpenters` (`Carpenter_ID`),
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
