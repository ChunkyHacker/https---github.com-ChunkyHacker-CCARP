<?php
session_start();
include('config.php');

if (!isset($_SESSION['Carpenter_ID'])) {
    header('Location: login.html');
    exit();
}

$Carpenter_ID = $_SESSION['Carpenter_ID'];

// Get carpenter details
$sql = "SELECT * FROM carpenters WHERE Carpenter_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $Carpenter_ID);
$stmt->execute();
$result = $stmt->get_result();
$carpenterDetails = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Approved Plan Details</title>
    <style>
        /* Sidebar styles */
        .sidenav {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #FF8C00;
            overflow-x: hidden;
            padding-top: 20px;
        }

        .sidenav .profile-section {
            text-align: center;
            padding: 10px;
            margin-bottom: 10px;
        }

        .sidenav .profile-section img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 5px;
        }

        .sidenav a {
            padding: 8px 15px;
            text-decoration: none;
            font-size: 24px;
            color: #000000;
            display: block;
            transition: 0.3s;
            margin-bottom: 2px;
        }

        .sidenav a:hover {
            background-color: #000000;
            color: #FF8C00;
        }

        /* Adjust main content area */
        .container {
            margin-left: 250px;
            padding: 20px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .grid-item {
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: none;  /* Remove shadow */
        }

        h1 {
            font-size: 48px;
            margin-bottom: 30px;
            font-weight: bold;
            padding-left: 20px;
        }

        h3 {
            font-size: 32px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 24px;
            font-weight: normal;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 22px;
        }

        textarea {
            width: 100%;
            min-height: 100px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 22px;
            resize: none;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border: none;  /* Remove border */
            box-shadow: none;  /* Remove shadow */
        }

        /* Client photo style */
        .client-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            display: block;
        }

        /* Project Budget and Dates sections */
        .grid-item:nth-child(4),
        .grid-item:nth-child(5) {
            margin-top: 20px;
        }

        body {
            font-family: Verdana, sans-serif;
            margin: 0;
            background-color: white;
            font-size: 24px;
        }

        .sidenav h3 {
            font-size: 28px;
            margin-bottom: 2px;
            line-height: 1.2;
        }

        .sidenav p {
            font-size: 20px;
            margin-bottom: 10px;
        }

        button {
            font-size: 22px !important;
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidenav">
        <div class="profile-section">
            <?php
            // Display profile picture
            if (isset($carpenterDetails['Photo']) && !empty($carpenterDetails['Photo'])) {
                echo '<img src="' . $carpenterDetails['Photo'] . '" alt="Profile Picture">';
            } else {
                echo '<img src="assets/img/default-avatar.png" alt="Default Profile Picture">';
            }
            
            // Display name and ID
            echo "<h3>" . $carpenterDetails['First_Name'] . ' ' . $carpenterDetails['Last_Name'] . "</h3>";
            echo "<p>Carpenter ID: " . $Carpenter_ID . "</p>";
            ?>
        </div>
        <a href="comment.php><i class="fas fa-home"></i> Home</a>
        <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
        <a href="getideas.php"><i class="fas fa-lightbulb"></i> Get Ideas</a>
        <a href="project.php"><i class="fas fa-project-diagram"></i> Project</a>
        <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Rest of your existing content here -->
    <div class="container">
        <div class="modal-content">
            <?php
                if (!isset($_GET['plan_ID']) || empty($_GET['plan_ID'])) {
                    die("<div class='main'><p>Plan ID is missing.</p></div>");
                }

                $plan_ID = intval($_GET['plan_ID']);

                // Fetch Plan Details
                $query = "SELECT * FROM plan WHERE plan_ID = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $plan_ID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='main'>";
                    echo "<h1>Client's Plan Details</h1>";

                    // Fetch Client Name
                    $userId = $row['User_ID'];
                    $userQuery = "SELECT First_Name, Last_Name, Photo FROM users WHERE User_ID = ?";
                    $userStmt = mysqli_prepare($conn, $userQuery);
                    mysqli_stmt_bind_param($userStmt, "i", $userId);
                    mysqli_stmt_execute($userStmt);
                    $userResult = mysqli_stmt_get_result($userStmt);
                    $userDetails = mysqli_fetch_assoc($userResult);
                    $clientName = ($userDetails) ? "{$userDetails['First_Name']} {$userDetails['Last_Name']}" : "Unknown Client";

                    // Grid container for plan details
                    echo "<div class='grid-container'>";
                    
                    // Client Info
                    echo "<div class='grid-item'>";
                    echo "<h3>Client Information</h3>";
                    if (!empty($userDetails['Photo'])) {
                        echo "<img src='" . $userDetails['Photo'] . "' style='width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin: 10px auto; display: block;'>";
                    } else {
                        echo "<img src='assets/img/default-avatar.png' style='width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin: 10px auto; display: block;'>";
                    }
                    echo "<label>Client Name:</label>";
                    echo "<input type='text' value='{$clientName}' readonly>";
                    echo "</div>";

                    // Lot Area
                    echo "<div class='grid-item'>";
                    echo "<h3>Lot Area</h3>";
                    echo "<label>Length:</label>";
                    echo "<input type='text' value='{$row['length_lot_area']}' readonly>";
                    echo "<label>Width:</label>";
                    echo "<input type='text' value='{$row['width_lot_area']}' readonly>";
                    echo "<label>Square Meter:</label>";
                    echo "<input type='text' value='{$row['square_meter_lot']}' readonly>";
                    echo "</div>";

                    // Floor Area
                    echo "<div class='grid-item'>";
                    echo "<h3>Floor Area</h3>";
                    echo "<label>Length:</label>";
                    echo "<input type='text' value='{$row['length_floor_area']}' readonly>";
                    echo "<label>Width:</label>";
                    echo "<input type='text' value='{$row['width_floor_area']}' readonly>";
                    echo "<label>Square Meter:</label>";
                    echo "<input type='text' value='{$row['square_meter_floor']}' readonly>";
                    echo "</div>";

                    // Project Budget
                    echo "<div class='grid-item'>";
                    echo "<h3>Project Budget</h3>";
                    echo "<label>Initial Budget:</label>";
                    echo "<input type='text' value='{$row['initial_budget']}' readonly>";
                    echo "</div>";

                    // Project Dates
                    echo "<div class='grid-item'>";
                    echo "<h3>Project Dates</h3>";
                    echo "<label>Start Date:</label>";
                    echo "<input type='text' value='{$row['start_date']}' readonly>";
                    echo "<label>End Date:</label>";
                    echo "<input type='text' value='{$row['end_date']}' readonly>";
                    echo "</div>";

                    // More Details
                    echo "<div class='grid-item'>";
                    echo "<h3>More Details</h3>";
                    echo "<textarea readonly>{$row['more_details']}</textarea>";
                    echo "</div>";

                    // Plan Photos (spanning full width)
                    echo "<div class='grid-item' style='grid-column: span 3;'>";
                    echo "<h3>Plan Photos</h3>";
                    
                    // Fetch plan photos
                    $photoQuery = "SELECT Photo FROM plan WHERE plan_ID = ?";
                    $photoStmt = mysqli_prepare($conn, $photoQuery);
                    mysqli_stmt_bind_param($photoStmt, "i", $plan_ID);
                    mysqli_stmt_execute($photoStmt);
                    $photoResult = mysqli_stmt_get_result($photoStmt);
                    
                    echo "<div style='display: flex; gap: 20px; flex-wrap: wrap; justify-content: center;'>";
                    while ($photo = mysqli_fetch_assoc($photoResult)) {
                        echo "<img src='{$photo['Photo']}' style='width: 300px; height: 300px; object-fit: cover; border-radius: 8px; margin-bottom: 20px;'>";
                    }
                    echo "</div>";

                    // Add this section below the photos display area
                    echo "<div class='grid-container'>";
                    echo "<div class='grid-item' style='grid-column: span 3;'>";
                    echo "<h3>Contract Status</h3>";

                    // Get the contract status from the database
                    $contractStatusQuery = "SELECT status, rejection_reason FROM contracts WHERE plan_ID = ? AND Carpenter_ID = ?";
                    $contractStatusStmt = mysqli_prepare($conn, $contractStatusQuery);
                    mysqli_stmt_bind_param($contractStatusStmt, "ii", $plan_ID, $Carpenter_ID);
                    mysqli_stmt_execute($contractStatusStmt);
                    $contractStatusResult = mysqli_stmt_get_result($contractStatusStmt);

                    if (mysqli_num_rows($contractStatusResult) > 0) {
                        $contractData = mysqli_fetch_assoc($contractStatusResult);
                        $status = $contractData['status'];
                        $rejectionReason = $contractData['rejection_reason'];
                        
                        // Set status color
                        $statusColor = '#FFC107'; // Default yellow for pending
                        if ($status == 'accepted') {
                            $statusColor = '#4CAF50'; // Green for accepted
                        } elseif ($status == 'rejected') {
                            $statusColor = '#F44336'; // Red for rejected
                        }
                        
                        // Display status badge
                        echo "<div style='text-align: center; margin-bottom: 20px;'>";
                        echo "<span style='display: inline-block; padding: 10px 20px; background-color: " . $statusColor . "; color: white; font-weight: bold; font-size: 18px; border-radius: 5px;'>";
                        echo "Contract Status: " . ucfirst($status);
                        echo "</span>";
                        echo "</div>";
                        
                        // Display rejection reason if rejected
                        if ($status == 'rejected' && !empty($rejectionReason)) {
                            echo "<div style='margin: 15px auto; padding: 15px; background-color: #ffebee; border-left: 4px solid #F44336; text-align: left; border-radius: 4px; max-width: 80%;'>";
                            echo "<h3 style='margin-top: 0; color: #D32F2F; font-size: 20px;'>Rejection Reason:</h3>";
                            echo "<p style='font-size: 16px;'>" . htmlspecialchars($rejectionReason) . "</p>";
                            echo "</div>";
                        }
                        
                        // Display acceptance message if accepted
                        if ($status == 'accepted') {
                            echo "<div style='margin: 15px auto; padding: 15px; background-color: #e8f5e9; border-left: 4px solid #4CAF50; text-align: left; border-radius: 4px; max-width: 80%;'>";
                            echo "<h3 style='margin-top: 0; color: #2E7D32; font-size: 20px;'>Contract Accepted</h3>";
                            echo "<p style='font-size: 16px;'>The contract has been accepted by the client. Work can proceed according to the agreed terms.</p>";
                            echo "</div>";
                        }
                        
                        // Display pending message if pending
                        if ($status == 'pending') {
                            echo "<div style='margin: 15px auto; padding: 15px; background-color: #fff8e1; border-left: 4px solid #FFC107; text-align: left; border-radius: 4px; max-width: 80%;'>";
                            echo "<h3 style='margin-top: 0; color: #F57F17; font-size: 20px;'>Pending Decision</h3>";
                            echo "<p style='font-size: 16px;'>This contract is awaiting the client's decision. The client will review the terms and either accept or reject the contract.</p>";
                            echo "</div>";
                        }
                    } else {
                        // No contract record found for this carpenter
                        echo "<div style='text-align: center; margin-bottom: 20px;'>";
                        echo "<span style='display: inline-block; padding: 10px 20px; background-color: #9E9E9E; color: white; font-weight: bold; font-size: 18px; border-radius: 5px;'>";
                        echo "Contract Status: Not Available";
                        echo "</span>";
                        echo "</div>";
                        
                        echo "<div style='margin: 15px auto; padding: 15px; background-color: #f5f5f5; border-left: 4px solid #9E9E9E; text-align: left; border-radius: 4px; max-width: 80%;'>";
                        echo "<p style='font-size: 16px;'>No contract has been created for this project yet.</p>";
                        echo "</div>";
                    }

                    mysqli_stmt_close($contractStatusStmt);
                    echo "</div>";
                    echo "</div>"; // Close grid-container

                    // Buttons container with both Go back and Generate Contract
                    echo "<div style='display: flex; justify-content: flex-start; gap: 20px; margin-top: 30px; margin-left: 20px;'>";
                    
                    // Go back button
                    echo "<button onclick=\"window.location.href='profile.php'\" 
                        style='width: 150px; height: 50px; background-color: #FF8C00; color: white; 
                        border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>
                        Go back</button>";

                    // Generate Contract button
                    echo "<button onclick=\"window.location.href='generate_contract.php?plan_ID=" . $plan_ID . "'\" 
                        style='width: 150px; height: 50px; background-color: #4CAF50; color: white; 
                        border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>
                        Generate Contract</button>";

                    // Check if THIS carpenter has already rated this plan
                    $ratingCheck = "SELECT * FROM job_ratings WHERE Carpenter_ID = ? AND plan_ID = ?";
                    $ratingStmt = mysqli_prepare($conn, $ratingCheck);
                    mysqli_stmt_bind_param($ratingStmt, "ii", $Carpenter_ID, $plan_ID);
                    mysqli_stmt_execute($ratingStmt);
                    $ratingResult = mysqli_stmt_get_result($ratingStmt);

                    // Rate Job Opportunity or View Ratings button
                    if (mysqli_num_rows($ratingResult) > 0) {
                        // This carpenter has already rated, show their rating
                        echo "<button onclick=\"window.location.href='view_job_ratings.php?plan_ID=" . $plan_ID . "&Carpenter_ID=" . $Carpenter_ID . "'\" 
                            style='width: 150px; height: 50px; background-color: #2196F3; color: white; 
                            border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>
                            View My Rating</button>";
                    } else {
                        // This carpenter hasn't rated yet
                        echo "<button onclick=\"window.location.href='rate_job.php?plan_ID=" . $plan_ID . "'\" 
                            style='width: 150px; height: 50px; background-color: #2196F3; color: white; 
                            border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>
                            Rate Job</button>";
                    }
                    mysqli_stmt_close($ratingStmt);
                    echo "</div>";

                    echo "</div>"; // Close main div
                } else {
                    echo "<div class='main'>";
                    echo "<p>No plan found with Plan ID: $plan_ID</p>";
                    echo "</div>";
                }

                mysqli_stmt_close($stmt);
                mysqli_close($conn);
            ?>
        </div>
    </div>
</body>
</html>