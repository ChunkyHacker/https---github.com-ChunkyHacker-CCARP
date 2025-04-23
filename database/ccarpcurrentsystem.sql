-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2025 at 09:28 AM
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
(3, 'francel jean', 'bicbic', 'daidai3', '123');

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

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_ID`, `type_of_work`, `Time_in`, `Time_out`, `contract_ID`) VALUES
(1, 'Per Day', '4/21/2025, 2:21:58 PM', '4/21/2025, 2:22:56 PM', 1),
(2, 'Per Day', '4/22/2025, 1:35:19 PM', '4/22/2025, 1:35:28 PM', 2),
(3, 'Per Day', '4/22/2025, 2:18:33 PM', '4/22/2025, 2:18:40 PM', 3),
(4, 'Per Day', '4/22/2025, 2:46:51 PM', '4/22/2025, 2:46:56 PM', 4);

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
(1, 'John Denvier', 'Alngog Carpenter', 9153520025, 'denvieralngog10@gmail.com', 'st jude village daliao toril davao city', '2002-09-10', 'EEnttrryy', 'sasdahsdasad', 'denviercarp', '123', 'Roofer, Tile Setter', 0x6173736574732f63617270656e7465722f706963747572652e6a7067),
(2, 'Carpenter', 'One', 9153520025, 'car1@gmail.com', 'asdasd', '2025-02-03', '222', '22', 'car1', '123', 'Roofer, Tile Setter', 0x6173736574732f63617270656e7465722f53637265656e73686f745f35312e6a7067),
(3, 'Carpenter', 'Two', 9153520025, 'denvieralngog10@gmail.com', 'dasd', '2025-02-20', 'ww', 'ww', 'car2', '123', 'wwww', 0x6173736574732f63617270656e7465722f7764737063732e6a7067),
(4, 'car3', 'car3', 9153520025, 'car3@gmail.com', 'asdasd', '2025-02-22', 'www', 'wwww', 'car3', '123', '', 0x6173736574732f63617270656e7465722f53637265656e73686f745f35382e6a7067),
(5, 'car4', 'car4', 9153520025, 'car4@gmail.com', 'wwww', '2025-02-22', 'wwww', 'www', 'car4', '123', '', 0x6173736574732f63617270656e7465722f3437363839323938365f323033353233323535333633313635385f363639363135313432313333333234323636335f6e202831292e706e67),
(6, 'Angelito', 'Engcoy', 9153520025, 'angelito@gmail.com', 'Blk 1 Prudential Village Daliao Toril', '2002-10-09', '8', '8', 'angelito', '123', '', 0x6173736574732f63617270656e7465722f3430353830393635325f3132323130303031333733303133303833385f3437383638353031393236393037333839395f6e2e6a7067),
(7, 'Cesar ', 'Peralta', 9153520025, 'cesar@gmail.com', 'bago oshiro', '2002-10-09', '9', '9', 'cesar', '123', '', 0x6173736574732f63617270656e7465722f626c616e6b2d70726f66696c652d706963747572652d3937333436305f313238302e706e67),
(8, 'Francisco', 'Bicbic', 9153520025, 'francisco@gmail.com', 'Blk 1 Upper Piedad Toril Davao City', '2002-09-10', '8', '8', 'francisco', '123', '', 0x6173736574732f63617270656e7465722f626c616e6b2d70726f66696c652d706963747572652d3937333436305f313238302e706e67);

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

--
-- Dumping data for table `completed_task`
--

INSERT INTO `completed_task` (`completed_task_id`, `name`, `task_id`, `status`, `contract_ID`, `timestamp`) VALUES
(1, 'Install door', 1, 'Completed', 1, '4/21/2025, 2:22:16 PM'),
(2, 'maghalo ug semento', 3, 'Completed', 2, '4/22/2025, 1:36:09 PM'),
(3, 'nag halo ug semento', 5, 'Completed', 3, '4/22/2025, 2:19:20 PM'),
(4, 'maghalo ug semento', 7, 'Completed', 4, '4/22/2025, 2:47:39 PM');

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

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`contract_ID`, `created_at`, `labor_cost`, `plan_ID`, `approval_ID`, `Carpenter_ID`, `duration`, `status`, `rejection_reason`, `attendance_ID`, `Progress_id`, `task_id`, `completed_task_id`, `transaction_ID`, `User_ID`, `type_of_work`) VALUES
(2, '2025-04-22 05:31:51', 9600.00, 2, 2, 6, 16, 'completed', '', 0, 0, 0, 0, NULL, 2, 'Per day'),
(3, '2025-04-22 06:18:23', 11000.00, 3, 4, 7, 22, 'completed', '', 0, 0, 0, 0, NULL, 3, 'Per day'),
(4, '2025-04-22 06:46:40', 10800.00, 4, 5, 8, 18, 'completed', '', 0, 0, 0, 0, NULL, 4, 'Per day');

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

--
-- Dumping data for table `job_ratings`
--

INSERT INTO `job_ratings` (`job_rating_ID`, `Carpenter_ID`, `plan_ID`, `q1_rating`, `q2_rating`, `q3_rating`, `q4_rating`, `q5_rating`, `q6_rating`, `q7_rating`, `q8_answer`, `q8_explanation`, `q9_answer`, `q10_answer`, `rating_date`) VALUES
(2, 6, 2, 5, 3, 4, 4, 5, 5, 4, 'no', '', 'Kanang Dali ra makakita ug trabaho using this website', 'Unta ma market ni kay para mutaas ang engagement', '2025-04-22 05:31:36'),
(3, 7, 3, 4, 4, 4, 4, 5, 3, 4, 'no', '', 'Kanang dali ra makakita ug trabaho gamit ani na platform ', 'pataason pa ang engagement', '2025-04-22 06:18:00'),
(4, 8, 4, 4, 5, 4, 3, 4, 4, 4, 'no', '', 'Gwapo ni nga website kay maka kita jud ka ug trabaho', 'Unta mailhan pani sa mga sumusunod ', '2025-04-22 06:46:23');

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

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`like_id`, `plan_id`, `carpenter_id`, `like_date`) VALUES
(1, 1, 1, '2025-04-21 07:11:37'),
(2, 1, 6, '2025-04-22 05:29:19'),
(3, 2, 6, '2025-04-22 05:29:20'),
(4, 1, 7, '2025-04-22 06:13:48'),
(5, 2, 7, '2025-04-22 06:13:51'),
(6, 3, 7, '2025-04-22 06:13:53'),
(7, 2, 8, '2025-04-22 06:43:24'),
(8, 3, 8, '2025-04-22 06:43:29'),
(9, 4, 8, '2025-04-22 06:43:31'),
(10, 2, 2, '2025-04-22 06:58:09'),
(11, 3, 2, '2025-04-22 06:58:13'),
(12, 4, 2, '2025-04-22 06:58:14'),
(14, 3, 3, '2025-04-22 06:58:49'),
(15, 4, 3, '2025-04-22 06:58:51');

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

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`Payment_ID`, `Contract_ID`, `User_ID`, `Carpenter_ID`, `Labor_Cost`, `Duration`, `type_of_work`, `Payment_Method`, `Payment_Date`) VALUES
(1, 1, 1, 1, 8000.00, 16, 'Per day', 'creditcard', '2025-04-20 16:00:00');

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

--
-- Dumping data for table `plan`
--

INSERT INTO `plan` (`plan_ID`, `User_ID`, `length_lot_area`, `width_lot_area`, `square_meter_lot`, `length_floor_area`, `width_floor_area`, `square_meter_floor`, `initial_budget`, `estimated_cost`, `start_date`, `end_date`, `type`, `more_details`, `Photo`, `carpenter_limit`, `status`, `views`) VALUES
(2, 2, '12', '2', '24', '12', '1', '12', '80000000', '', '2025-04-22', '2025-05-08', '', 'I want this house to be build', 'assets/plan/how-to-design-a-house.jpg', 0, 'open', 3),
(3, 3, '40', '32', '1280', '12', '2', '24', '800000', '', '2025-04-22', '2025-05-14', '', 'I want this house to be build', 'assets/plan/how-to-design-a-house.jpg', 0, 'open', 3),
(4, 4, '14', '2', '28', '10', '2', '20', '800000', '', '2025-04-22', '2025-05-10', '', 'I want this house to be build', 'assets/plan/how-to-design-a-house.jpg', 0, 'open', 3);

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

--
-- Dumping data for table `plan_approval`
--

INSERT INTO `plan_approval` (`approval_ID`, `plan_ID`, `carpenter_ID`, `total_score`, `scope_scores`, `site_scores`, `workforce_scores`, `client_responses`, `client_comments`, `evaluator_name`, `evaluation_date`, `final_decision`, `status`) VALUES
(1, 1, 1, 70, '[5,5,5,5,5]', '[5,5,5,5,5]', '[5,5,5,5]', '[\"yes\",\"yes\",\"yes\",\"yes\",\"yes\"]', '[\"Yes \",\"Yes\",\"Yes\",\"Yes\",\"Yes\"]', 'John Denvier Alngog Carpenter', '2025-04-21', 'accept', 'approve'),
(2, 2, 6, 70, '[5,5,5,5,5]', '[5,5,5,5,5]', '[5,5,5,5]', '[\"yes\",\"yes\",\"yes\",\"yes\",\"yes\"]', '[\"Yes\",\"Yes\",\"Yes\",\"Yes\",\"Yes\"]', 'Angelito Engcoy', '2025-04-22', 'accept', 'approve'),
(4, 3, 7, 70, '[5,5,5,5,5]', '[5,5,5,5,5]', '[5,5,5,5]', '[\"yes\",\"yes\",\"yes\",\"yes\",\"yes\"]', '[\"Yes\",\"Yes\",\"Yes\",\"Yes\",\"Yes\"]', 'Cesar  Peralta', '2025-04-22', 'accept', 'approve'),
(5, 4, 8, 70, '[5,5,5,5,5]', '[5,5,5,5,5]', '[5,5,5,5]', '[\"yes\",\"yes\",\"yes\",\"yes\",\"yes\"]', '[\"yes\",\"yes\",\"yes\",\"yes\",\"yes\"]', 'Francisco Bicbic', '2025-04-22', 'accept', 'approve');

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

--
-- Dumping data for table `prematerials`
--

INSERT INTO `prematerials` (`prematerials_ID`, `part`, `materials`, `name`, `quantity`, `price`, `total`, `estimated_cost`) VALUES
(1, 'Bedroom', 'Holcim-Excel - 40 kg / bag', 'Holcim-Excel - 40 kg / bag', 3, 225, 675, 0.00),
(2, 'Bedroom', 'Mindanao -40 Kg / bag', 'Holcim-Excel - 40 kg / bag', 1, 225, 225, 0.00),
(3, 'Bedroom', 'Holcim-Excel - 40 kg / bag', 'Holcim-Excel - 40 kg / bag', 1, 225, 225, 0.00),
(4, 'Bedroom', 'Holcim-Excel - 40 kg / bag', 'Holcim-Excel - 40 kg / bag', 1, 225, 225, 0.00);

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

--
-- Dumping data for table `project_turnover`
--

INSERT INTO `project_turnover` (`turnover_id`, `contract_ID`, `plan_ID`, `task_id`, `completed_task_id`, `actual_completion_date`, `client_signature`, `turnover_notes`, `supporting_documents`, `confirmation_status`, `client_feedback`, `created_at`, `approved_by`, `approved_at`, `turnedover_by`) VALUES
(2, 2, 2, 4, 2, '2025-04-22', 'Angelito Engcoy', 'please check', '', 'confirmed', 'okay nani', '2025-05-02 05:48:00', 2, '2025-04-22 05:48:40', 6),
(3, 3, 3, 6, 3, '2025-04-22', 'Cesar Peralta', 'Please check', '', 'confirmed', 'Okay nani', '2025-05-03 06:23:34', 3, '2025-04-22 06:24:30', 7),
(4, 4, 4, 8, 4, '2025-04-22', 'Francisco Bicbic', 'please check', '', 'confirmed', 'Okay nani', '2025-05-03 06:48:48', 4, '2025-04-22 07:04:16', 8);

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

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`rating_ID`, `contract_ID`, `plan_ID`, `user_ID`, `Carpenter_ID`, `site_prep_score`, `materials_score`, `foundation_score`, `mep_score`, `exterior_score`, `interior_score`, `windows_score`, `insulation_score`, `comments`, `rating_date`, `landscaping_score`, `final_score`) VALUES
(2, 2, 2, 2, 6, 4, 5, 4, 4, 5, 5, 5, 5, 'none so far', '2025-04-22 07:46:23', 4, 5),
(3, 3, 3, 3, 7, 4, 2, 2, 4, 4, 4, 4, 4, 'Goods', '2025-04-22 08:22:18', 4, 4),
(4, 4, 4, 4, 8, 4, 5, 5, 4, 4, 4, 4, 5, 'GOODS', '2025-04-22 08:56:16', 5, 5);

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

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`Progress_id`, `Name`, `Status`, `contract_ID`) VALUES
(1, 'Install Door', 'Done', 1),
(2, 'maghalo ug semento', 'Done', 2),
(3, 'naghalo ug semento', 'Not yet started', 3),
(4, 'maghalo ug semento', 'Done', 4);

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

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`task_id`, `task`, `status`, `contract_ID`, `timestamp`) VALUES
(2, 'Put nails in roof', 'Not yet started', 1, '4/21/2025, 2:22:35 PM'),
(4, 'mag butang ug hollow blocks', 'Not yet started', 2, '4/22/2025, 1:36:05 PM'),
(6, 'gilansang ang atop', 'Not yet started', 3, '4/22/2025, 2:19:10 PM'),
(8, 'itaod ang plywood', 'Not yet started', 4, '4/22/2025, 2:47:30 PM');

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
(1, 'John Denvier', 'Alngog', 9153520025, 'denvieralngog10@gmail.com', 'sdsdasadaasadsd', '2024-11-14', 'denvier', '123', 0x6173736574732f757365722f34396234656636352d356636382d343633362d616166662d3136623239306534316232352e77656270),
(2, 'Cecilia', 'Maglangit', 9153520025, 'cecilia@gmail.com', 'Blk 1 St Jude Village Daliao Toril Davao CIty', '2002-09-10', 'cecilia', '123', 0x6173736574732f757365722f626c616e6b2d70726f66696c652d706963747572652d3937333436305f313238302e706e67),
(3, 'Mark John', 'Canada', 9153520025, 'markjohn@gmail.com', 'Villarosa Dacoville', '2002-09-10', 'mark', '123', 0x6173736574732f757365722f3439323331363630355f3132323134393133313534323534363531335f363038333538333530303132323431393931385f6e2e6a7067),
(4, 'Marice', 'Oberez', 9153520025, 'marice@gmail.com', 'Purok 9 Upper Piedad Bato', '2002-10-09', 'marice', '123', 0x6173736574732f757365722f3434383936353832355f313438393435333035313635373038355f313130323438393935383731373538363031395f6e2e6a7067);

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
  ADD PRIMARY KEY (`prematerials_ID`);

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
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `carpenters`
--
ALTER TABLE `carpenters`
  MODIFY `Carpenter_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `completed_task`
--
ALTER TABLE `completed_task`
  MODIFY `completed_task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `contract_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expenses_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_ratings`
--
ALTER TABLE `job_ratings`
  MODIFY `job_rating_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `Payment_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `plan`
--
ALTER TABLE `plan`
  MODIFY `plan_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `plan_approval`
--
ALTER TABLE `plan_approval`
  MODIFY `approval_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `prematerials`
--
ALTER TABLE `prematerials`
  MODIFY `prematerials_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `prematerialsinventory`
--
ALTER TABLE `prematerialsinventory`
  MODIFY `prematerialsinventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `project_turnover`
--
ALTER TABLE `project_turnover`
  MODIFY `turnover_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `Progress_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
