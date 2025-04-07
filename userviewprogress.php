<?php
    include('config.php');
    session_start();

    if (!isset($_SESSION['User_ID'])) {
        echo "<script>alert('You need to log in first.'); window.location.href='login.php';</script>";
        exit();
    }

    $user_ID = $_SESSION['User_ID']; // Get logged-in user ID

    // Get user details
    $sql = "SELECT * FROM users WHERE User_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $userDetails = $result->fetch_assoc();

    // Get the contract status from the contracts table
    $contractStatus = 'pending'; // Default status
    $rejectionReason = '';

    // Check if we have a valid plan_ID
    if (isset($_GET['plan_ID'])) {
        $plan_ID = $_GET['plan_ID'];
    } else {
        echo "Plan ID is not set.";
        exit();
    }

    // Debugging: Display User_ID and Plan_ID
    echo "<script>console.log('Debug: User_ID = $user_ID, Plan_ID = $plan_ID');</script>";

    // Fetch the contract details for the logged-in user or carpenter
    $contractQuery = "SELECT * FROM contracts WHERE plan_ID = ? AND (User_ID = ? OR Carpenter_ID = ?)";
    $contractStmt = mysqli_prepare($conn, $contractQuery);
    mysqli_stmt_bind_param($contractStmt, "iii", $plan_ID, $user_ID, $user_ID);
    mysqli_stmt_execute($contractStmt);
    $contractResult = mysqli_stmt_get_result($contractStmt);
    $contract = mysqli_fetch_assoc($contractResult);

    // Check if the contract exists for the logged-in user
    if (!$contract) {
        echo "<script>console.log('Debug: No matching contract found for User_ID = $user_ID, Plan_ID = $plan_ID');</script>";
        echo "<p>No contract found for this user.</p>";
        exit();
    }

    // Get the contract ID for filtering sub-tables
    $contractID = $contract['contract_ID'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>View Progress</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<script src="process4\search.js"></script>
<script src="process4\edititem.js"></script>
<script src="process5\quantityxcost.js"></script>
<script src="process5\daysofworkxlaborcost.js"></script>
<script src="process5\additionalcostpluslaborcost.js"></script>
<style>
    * {
    box-sizing: border-box;
  }

  body {
    font-family: Arial, Helvetica, sans-serif;
    margin: 0;
    padding: 0;
    font-size: 22px;
    background-color: #f5f5f5;
  }


  button {
    background-color: #FF8C00;
    color: #FFFFFF;
    border: none;
    padding: 10px 20px;
    font-size: 22px; /* Set button font size to 22px */
    cursor: pointer;
    margin: 20px;
    border-radius: 4px;
  }

  button:hover {
    background-color: #FFA500;
  }

    .modal,
  .editModal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    overflow: auto; /* Para maka-scroll ang tibuok modal */
  }
  .modal-content,
  .edit-modal-content {
    background-color: #f2f2f2;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 20px;
    border-radius: 5px;
    width: 70%;
    max-height: 80vh; /* Dili molapas sa screen */
    overflow-y: auto; /* Maka-scroll kung taas ang sulod */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
  }
  
  .modal-content h2,
  .edit-modal-content h2 {
    margin-bottom: 20px;
    font-size: 20px; /* Set font size of headings in modals to 20px */
  }

  .modal-content form div {
    margin-bottom: 15px;
  }

  .modal-content form label,
  .edit-modal-content form label {
    font-size: 20px; /* Set font size of labels to 20px */
    font-weight: bold;
    margin-bottom: 5px;
  }

  .modal-content form input,
  .modal-content form textarea,
  .modal-content form select {
    width: 100%;
    padding: 8px;
    font-size: 20px; /* Set font size of inputs, textarea, and selects to 20px */
    color: #000;
    background-color: #fff;
    border-radius: 4px;
    border: 1px solid #ccc;
  }

  .modal-content form button,
  .edit-modal-content form button {
    font-size: 20px; /* Set font size of buttons inside modals to 20px */
  }
  /* Project Image */
  .project-img {
      width: 700px;
      height: 400px;
      border: 1px solid #ccc;
      padding: 5px;
  }



  .main {
    margin-left: 250px; /* Match sidebar width */
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    max-width: 1200px;
    margin: 0 auto;
  }

  .main h1 {
    font-size: 24px;
    margin-bottom: 25px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
    color: #333;
  }

  .row-container h3 {
    font-size: 20px; /* Set font size of row-container headings to 20px */
    margin-bottom: 10px;
    color: #555;
  }

  input[type="text"] {
    width: 100%;
    padding: 8px;
    font-size: 20px; /* Set font size of text inputs to 20px */
    color: #000;
    background-color: #fff;
    border-radius: 4px;
    border: 1px solid #ccc;
    margin-bottom: 10px;
  }

  img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
  }

  .sort {
    display: inline-block;
    margin-bottom: 10px;
  }

  .sort select {
    padding: 8px;
    font-size: 20px; /* Set font size of sort select to 20px */
    color: #000;
    background-color: #fff;
    border-radius: 4px;
  }

  .table-container {
    border-radius: 8px;
    overflow: hidden;
    margin-top: 20px;
    margin-bottom: 40px;
  }

  .styled-table {
    width: 100%;
    border-collapse: collapse; /* Ensures borders are combined */
    font-size: 20px; /* Increase font size for table */
  }

  .styled-table th, .styled-table td {
    border: 1px solid #ddd; /* Add border to table cells */
    padding: 12px; /* Add padding for better spacing */
    text-align: center; /* Center align text */
  }

  .styled-table th {
    background-color: #FF8C00; /* Header background color */
    color: white; /* Header text color */
    font-size: 20px; /* Increase font size for header */
  }

  .table-header {
    padding: 12px;
    text-align: center;
    background-color: #FF8C00;
    color: black;
    font-weight: bold;
    border-bottom: 1px solid #333;
  }

  .table-cell {
    padding: 2px;
    text-align: center;
    background-color: #f2f2f2;
    border-bottom: 1px solid #333;
  }

  .table-cell img {
    max-width: 100px;
    max-height: 100px;
    border-radius: 4px;
  }

  .footer {
    padding: 10px;
    text-align: center;
    background: #FF8C00;
    position: relative;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
  }

  @media screen and (max-width: 700px) {
    .row {
      flex-direction: column;
    }
    body {
      padding-top: 300px;
    }
  }

  @media (max-width: 768px) {
    .product-card {
      width: calc(50% - 20px);
    }
  }

  @media (max-width: 480px) {
    .product-card {
      width: 100%;
    }
  }

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
    padding: 20px 10px;
    margin-bottom: 20px;
  }

  .sidenav .profile-section img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-bottom: 10px;
    border: 3px solid white;
  }

  .sidenav h3 {
    font-size: 24px;
    margin-bottom: 5px;
    color: black;
  }

  .sidenav p {
    font-size: 18px;
    margin-bottom: 20px;
    color: black;
  }

  .sidenav a {
    padding: 12px 15px;
    text-decoration: none;
    font-size: 18px;
    color: black;
    display: block;
    transition: 0.3s;
  }

  .sidenav a:hover {
    background-color: rgba(0,0,0,0.1);
  }

  .sidenav a i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
  }

  /* Add these styles for the grid layout */
  .grid-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
  }

  .grid-item {
    background: white;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  }

  .grid-item h3 {
    font-size: 20px;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #FF8C00;
    color: #333;
    white-space: nowrap; /* Prevent text wrapping */
  }

  /* Client photo styling to match screenshot */
  .client-photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    display: block;
    margin: 0 auto 15px;
    border: 3px solid #FF8C00;
  }

  /* Sidebar styling to match screenshot */
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
    padding: 20px 10px;
    margin-bottom: 20px;
  }
  
  .sidenav .profile-section img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-bottom: 10px;
    border: 3px solid white;
  }
  
  .sidenav h3 {
    font-size: 24px;
    margin-bottom: 5px;
    color: black;
  }
  
  .sidenav p {
    font-size: 18px;
    margin-bottom: 20px;
    color: black;
  }
  
  .sidenav a {
    padding: 12px 15px;
    text-decoration: none;
    font-size: 18px;
    color: black;
    display: block;
    transition: 0.3s;
  }
  
  .sidenav a:hover {
    background-color: rgba(0,0,0,0.1);
  }
  
  .sidenav a i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
  }
  
  /* Form input styling */
  label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #555;
    font-size: 16px;
  }
  
  input[type="text"], textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    background-color: #f9f9f9;
  }

  /* Updated grid layout to match screenshot */
  .grid-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
  }
  
  .grid-item {
    background: white;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  }
  
  .grid-item h3 {
    font-size: 20px;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #FF8C00;
    color: #333;
  }
  
  /* Client photo styling to match screenshot */
  .client-photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    display: block;
    margin: 0 auto 15px;
    border: 3px solid #FF8C00;
  }
  
  /* Sidebar styling to match screenshot */
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
    padding: 20px 10px;
    margin-bottom: 20px;
  }
  
  .sidenav .profile-section img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-bottom: 10px;
    border: 3px solid white;
  }
  
  .sidenav h3 {
    font-size: 24px;
    margin-bottom: 5px;
    color: black;
  }
  
  .sidenav p {
    font-size: 18px;
    margin-bottom: 20px;
    color: black;
  }
  
  .sidenav a {
    padding: 12px 15px;
    text-decoration: none;
    font-size: 18px;
    color: black;
    display: block;
    transition: 0.3s;
  }
  
  .sidenav a:hover {
    background-color: rgba(0,0,0,0.1);
  }
  
  .sidenav a i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
  }
  
  /* Form input styling */
  label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #555;
    font-size: 16px;
  }
  
  input[type="text"], textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
    background-color: #f9f9f9;
  }

  /* Ensure consistent card heights */
  .grid-item {
    background: white;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    height: auto;
    min-height: 250px; /* Set minimum height for cards */
  }
  
  /* Fix for the main container */
  .main {
    padding: 20px;
    margin-left: 250px;
    background-color: white;
  }
  
  /* Fix for the grid container */
  .grid-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 20px;
  }

  /* Increase font size for other elements */
  h1, h2, h3 {
    font-size: 24px; /* Increase font size for headings */
  }
</style>
</head>
<body>

<!-- Add Sidebar Navigation -->
<div class="sidenav">
    <div class="profile-section">
        <?php
        // Display profile picture
        if (isset($userDetails['Photo']) && !empty($userDetails['Photo'])) {
            echo '<img src="' . $userDetails['Photo'] . '" alt="Profile Picture">';
        } else {
            echo '<img src="assets/img/default-avatar.png" alt="Default Profile Picture">';
        }
        
        // Display name and ID
        echo "<h3>" . $userDetails['First_Name'] . ' ' . $userDetails['Last_Name'] . "</h3>";
        echo "<p>User ID: " . $user_ID . "</p>";
        ?>
    </div>

    <div class="sidebar-section">
      <?php
      // Check if contract_ID is available
      if (isset($contract['contract_ID'])) {
          echo "<a href='userpaycarpenter.php?contract_ID={$contract['contract_ID']}'><i class='fas fa-money-bill-wave'></i> Pay Carpenter</a>";
      }
      ?>

      <a href="usercomment.php"><i class="fas fa-home"></i> Home</a>
      <a href="userprofile.php"><i class="fas fa-user"></i> Profile</a>
      <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>
</div>


<!-- Replace the main content area with this grid layout -->
<div class="container">
    <?php
    // Check if plan_ID is set
    if (isset($_GET['plan_ID'])) {
        $plan_ID = $_GET['plan_ID'];
        
        // Get plan details directly from the plan table
        $query = "SELECT p.*, u.First_Name, u.Last_Name, u.Photo as UserPhoto 
                 FROM plan p 
                 JOIN users u ON p.User_ID = u.User_ID 
                 WHERE p.plan_ID = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $plan_ID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        if ($row) {
            // Client name from the joined users table
            $clientName = "{$row['First_Name']} {$row['Last_Name']}";
            
            echo "<div class='main'>";
            echo "<h1>Client's Plan Details</h1>";
            
            // First row grid container
            echo "<div class='grid-container'>";
            
            // Client Info
            echo "<div class='grid-item'>";
            echo "<h3>Client Information</h3>";
            echo "<img src='" . (isset($row['UserPhoto']) ? $row['UserPhoto'] : 'assets/img/default-avatar.png') . "' class='client-photo'>";
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
            
            echo "</div>"; // Close first row grid-container
            
            // Second row grid container
            echo "<div class='grid-container'>";
            
            // Project Budget - Make sure this is included
            echo "<div class='grid-item'>";
            echo "<h3>Project Budget</h3>";
            echo "<label>Initial Budget:</label>";
            echo "<input type='text' value='PHP " . number_format($row['initial_budget'], 2) . "' readonly>";
            echo "</div>";
            
            // Project Dates
            echo "<div class='grid-item'>";
              echo "<h3>Project Dates</h3>";
              echo "<label>Start Date:</label>";
              echo "<input type='text' value='" . date("F j, Y", strtotime($row['start_date'])) . "' readonly>";
              echo "<label>End Date:</label>";
              echo "<input type='text' value='" . date("F j, Y", strtotime($row['end_date'])) . "' readonly>";
              echo "</div>";
              
              // More Details
              echo "<div class='grid-item'>";
              echo "<h3>More Details</h3>";
              if (isset($row['more_details']) && !empty($row['more_details'])) {
                  echo "<textarea readonly style='height: 100px;'>{$row['more_details']}</textarea>";
              } else {
                  echo "<textarea readonly style='height: 100px;'>No additional details provided.</textarea>";
              }
              echo "</div>";
              
              echo "</div>"; // Close second row grid-container
              
              // Plan Photos (spanning full width)
              echo "<div class='grid-container'>";
              echo "<div class='grid-item plan-photo-container' style='grid-column: span 3;'>";
              echo "<h3>Plan Photos</h3>";
              
              // Check if there's a photo in the plan table
              if (!empty($row['Photo'])) {
                  echo "<div style='text-align: center;'>";
                  echo "<img src='" . $row['Photo'] . "' class='plan-photo' alt='Plan Photo' style='max-width: 300px; height: auto;'>";
                  echo "</div>";
              } else {
                  echo "<p style='text-align: center; color: #777;'>No plan photos available.</p>";
              }
              echo "</div>";

                echo "<div class='grid-container'>"; // Open grid container

                // PROGRESS REPORT
                echo "<div class='grid-item'>";
                  echo "<h2>Progress</h2>";
                  $sqlReports = "SELECT * FROM report WHERE contract_ID = ?";
                  $stmtReports = mysqli_prepare($conn, $sqlReports);
                  mysqli_stmt_bind_param($stmtReports, "i", $contractID);
                  mysqli_stmt_execute($stmtReports);
                  $resultReports = mysqli_stmt_get_result($stmtReports);
                  
                  if (mysqli_num_rows($resultReports) > 0) {
                      echo "<table class='styled-table'>";
                      echo "<tr><th>Name</th><th>Status</th></tr>";
                      while ($row = mysqli_fetch_assoc($resultReports)) {
                          echo "<tr><td>{$row['Name']}</td><td>{$row['Status']}</td></tr>";
                      }
                      echo "</table>";
                  } else {
                      echo "<p>No Progress yet</p>";
                  }
                  echo "</div>"; // Close Progress grid-item
                  
                  // TASKS (Pending Tasks)
                  echo "<div class='grid-item'>";
                  echo "<h2>Tasks</h2>";
                  $sqlTasks = "SELECT * FROM task WHERE contract_ID = ? AND task_id NOT IN (SELECT task_id FROM completed_task)";
                  $stmtTasks = mysqli_prepare($conn, $sqlTasks);
                  mysqli_stmt_bind_param($stmtTasks, "i", $contractID);
                  mysqli_stmt_execute($stmtTasks);
                  $resultTasks = mysqli_stmt_get_result($stmtTasks);
                  
                  if (mysqli_num_rows($resultTasks) > 0) {
                      echo "<table class='styled-table'>";
                      echo "<tr><th>Task</th><th>Details</th></tr>";
                      while ($row = mysqli_fetch_assoc($resultTasks)) {
                          echo "<tr><td>" . htmlspecialchars($row['task']) . "</td><td>" . htmlspecialchars($row['details']) . "</td></tr>";
                      }
                      echo "</table>";
                  } else {
                      echo "<p>No pending tasks</p>";
                  }
                  echo "</div>"; // Close Tasks grid-item
                  
                  // Open grid container for Completed Tasks and Attendance
                  echo "<div class='grid-container'>";

                  // COMPLETED TASKS
                  echo "<div class='grid-item'>";
                  echo "<h2>Completed Tasks</h2>";
                  $sqlCompleted = "SELECT * FROM completed_task WHERE contract_ID = ?";
                  $stmtCompleted = $conn->prepare($sqlCompleted);
                  $stmtCompleted->bind_param("i", $contractID);
                  $stmtCompleted->execute();
                  $resultCompleted = $stmtCompleted->get_result();
                  
                  if ($resultCompleted->num_rows > 0) {
                      echo "<table class='styled-table'>";
                      echo "<tr><th>Task</th><th>Details</th><th>Status</th></tr>";
                      while ($row = $resultCompleted->fetch_assoc()) {
                          echo "<tr><td>" . htmlspecialchars($row['name']) . "</td><td>" . htmlspecialchars($row['details']) . "</td><td>" . ucfirst(htmlspecialchars($row['status'])) . "</td></tr>";
                      }
                      echo "</table>";
                  } else {
                      echo "<p>No completed tasks</p>";
                  }
                  echo "</div>"; // Close Completed Tasks grid-item
                  
                  // ATTENDANCE
                  echo "<div class='grid-item'>"; // Change to span one column
                  echo "<h2>Attendance</h2>";
                  $sqlAttendance = "SELECT * FROM attendance WHERE contract_ID = ?";
                  $stmtAttendance = mysqli_prepare($conn, $sqlAttendance);
                  mysqli_stmt_bind_param($stmtAttendance, "i", $contractID);
                  mysqli_stmt_execute($stmtAttendance);
                  $resultAttendance = mysqli_stmt_get_result($stmtAttendance);
                  
                  if (mysqli_num_rows($resultAttendance) > 0) {
                      echo "<table class='styled-table'>";
                      echo "<tr><th>Type</th><th>Time In</th><th>Time Out</th></tr>";
                      while ($row = mysqli_fetch_assoc($resultAttendance)) {
                          echo "<tr><td>{$row['Type_of_work']}</td><td>{$row['Time_in']}</td><td>{$row['Time_out']}</td></tr>";
                      }
                      echo "</table>";
                  } else {
                      echo "<p>No attendance records yet.</p>";
                  }
                  echo "</div>"; // Close Attendance grid-item

                  // Close the grid container
                  echo "</div>"; // Close grid-container

                  // Go Back button - moved to the left
                  echo "<div style='text-align: left; margin: 20px 0; padding-left: 20px; grid-column: span 3;'>"; // Span three columns for the button
                    echo "<button onclick=\"window.location.href='userprofile.php'\" 
                            style='background-color: #FF8C00; color: white; border: none; padding: 10px 20px; 
                            border-radius: 5px; cursor: pointer; font-size: 16px;'>Go Back</button>";
                    echo "<button onclick=\"window.location.href='rateplan.php?plan_ID=" . $plan_ID . "'\" 
                            style='background-color: #FF8C00; color: white; border: none; padding: 10px 20px; 
                            border-radius: 5px; cursor: pointer; font-size: 16px; margin-left: 10px;'>Rate Plan</button>";
                  echo "</div>";

                  // Rate the plan

            echo "</div>"; // Close grid-container
                          // Go Back button - moved to the left
            echo "</div>"; // Close main div
            
        } else {
            echo "<div class='main'>";
            echo "<p>No plan found with Plan ID: $plan_ID</p>";
            echo "</div>";
        }
    } else {
        echo "<div class='main'>";
        echo "<p style='font-size: 18px; color: red;'>Error: No Plan ID provided.</p>";
        echo "<p style='font-size: 16px; margin-top: 20px;'>Please go back and select a valid plan.</p>";
        echo "<button onclick='window.history.back()' style='margin-top: 20px; padding: 10px 20px; background-color: #FF8C00; color: white; border: none; border-radius: 5px; cursor: pointer;'>Go Back</button>";
        echo "</div>";
    }
    ?>
</div>
</body>
</html>
  