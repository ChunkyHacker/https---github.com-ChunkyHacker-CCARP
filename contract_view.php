<?php
    session_start();

    include('config.php');

    if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']);
    echo "<script>alert('$message');</script>";
  }
  
    if (!isset($_SESSION['User_ID'])) {
        echo "<script>alert('You need to log in first.'); window.location.href='login.php';</script>";
        exit();
    }
    
    $user_ID = $_SESSION['User_ID']; // Corrected session variable
  
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
    if (isset($plan_ID) && $plan_ID > 0) {
        $contractQuery = "SELECT status, rejection_reason, labor_cost, duration FROM contracts WHERE plan_ID = ? AND Carpenter_ID = ?";
        $contractStmt = mysqli_prepare($conn, $contractQuery);
        mysqli_stmt_bind_param($contractStmt, "ii", $plan_ID, $Carpenter_ID);
        mysqli_stmt_execute($contractStmt);
        $contractResult = mysqli_stmt_get_result($contractStmt);
        
        if ($contractData = mysqli_fetch_assoc($contractResult)) {
            $contractStatus = $contractData['status'];
            $rejectionReason = isset($contractData['rejection_reason']) ? $contractData['rejection_reason'] : '';
            $laborCost = $contractData['labor_cost'];
            $duration = $contractData['duration'];
        }
        mysqli_stmt_close($contractStmt);
    }

    // Set the background color based on status
    $statusColor = '#FFA500'; // Default orange for pending
    if ($contractStatus == 'accepted') {
        $statusColor = '#4CAF50'; // Green for accepted
    } else if ($contractStatus == 'rejected') {
        $statusColor = '#F44336'; // Red for rejected
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Compute Budget</title>
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
    font-size: 20px;
    background-color: #f5f5f5;
  }

  .header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    padding: 10px;
    background: #FF8C00;
    color: #000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 100;
  }

  .header h1 {
    font-size: 20px; /* Set header font size to 20px */
    padding-left: 20px;
  }

  .header a {
    font-size: 20px; /* Set font size of links to 20px */
    font-weight: bold;
    text-decoration: none;
    color: #000;
  }

  .topnav {
    position: fixed;
    top: 120px;
    width: 100%;
    overflow: hidden;
    background-color: #505050;
    z-index: 100;
  }

  .topnav a {
    float: left;
    display: block;
    color: black;
    text-align: center;
    padding: 14px 16px;
    font-size: 20px; /* Set font size of topnav links to 20px */
  }

  .topnav a:hover,
  .topnav a.active:hover {
    background-color: #FF8C00;
    color: black;
  }

  button {
    background-color: #FF8C00;
    color: #FFFFFF;
    border: none;
    padding: 10px 20px;
    font-size: 20px; /* Set button font size to 20px */
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

  /* Contract Modal Overlay */
  #contractModal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center; /* Centers modal vertically */
      justify-content: center; /* Centers modal horizontally */
      padding: 20px; /* Prevents touching the edges */
  }

  /* Modal Content */
  .contract-modal-content {
      background: white;
      width: 80%;
      max-width: 900px;
      border-radius: 8px;
      box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
      max-height: 85vh; /* Prevents modal from being too tall */
      overflow-y: auto; /* Allows scrolling if needed */
      padding: 20px;
      position: relative;
      margin: auto; /* Ensures centering */
  }

  /* Close Button */
  .close-modal {
      float: right;
      font-size: 20px;
      font-weight: bold;
      cursor: pointer;
  }

  /* Project Image */
  .project-img {
      width: 700px;
      height: 400px;
      border: 1px solid #ccc;
      padding: 5px;
  }

  /* Submit Button */
  .submit-btn {
      padding: 12px 20px;
      font-size: 20px;
      font-weight: bold;
      color: white;
      background-color: #28a745;
      border: none;
      cursor: pointer;
      border-radius: 5px;
      box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
  }
    /* Signed Contract */

    .signedcontract-modal {
        display: none; /* Remove second display: flex */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center; /* Centers modal vertically */
        justify-content: center; /* Centers modal horizontally */
        padding: 20px; /* Prevents touching the edges */
    }


  .signedcontract-modal-content {
      background: white;
      width: 80%;
      max-width: 900px;
      border-radius: 8px;
      box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
      max-height: 85vh; /* Prevents modal from being too tall */
      overflow-y: auto; /* Allows scrolling if needed */
      padding: 20px;
      position: relative;
      margin: auto; /* Ensures centering */
  }

  .close-modal {
      float: right;
      font-size: 20px;
      font-weight: bold;
      cursor: pointer;
  }

  .close-modal:hover, .close-modal:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
  }

  .signedcontract-img {
      width: 80%;
      max-width: 500px;
      border-radius: 10px;
      box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
      margin-top: 15px;
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
    font-size: 28px;
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
    border-collapse: collapse;
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
        <a href="#" class="sidebar-link" onclick="openSignedContractModal(); return false;">
            <i class="fas fa-file-contract"></i> View Contract
        </a>
        <a href="#" class="sidebar-link" onclick="document.getElementById('addMaterialsModal').style.display='block'; return false;">
            <i class="fas fa-plus-circle"></i> Add Materials
        </a>
        <a href="#" class="sidebar-link" onclick="document.getElementById('addLaborModal').style.display='block'; return false;">
            <i class="fas fa-hammer"></i> Add Labor
        </a>
    </div>
    <a href="usercomment.php"><i class="fas fa-home"></i> Home</a>
    <a href="userprofile.php"><i class="fas fa-user"></i> Profile</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>

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
            echo "</div>"; // Close third row grid-container
            
            // Add this section below the photos display area
            echo "<div class='contract-status-display' style='margin: 20px 0; padding: 15px; border-radius: 5px; text-align: center;'>";

            // Get the contract status from the database
            $contractStatusQuery = "SELECT status, rejection_reason, labor_cost, duration FROM contracts WHERE plan_ID = ? AND Carpenter_ID = ?";
            $contractStatusStmt = mysqli_prepare($conn, $contractStatusQuery);
            mysqli_stmt_bind_param($contractStatusStmt, "ii", $plan_ID, $Carpenter_ID);
            mysqli_stmt_execute($contractStatusStmt);
            $contractStatusResult = mysqli_stmt_get_result($contractStatusStmt);

            // Debug information
            echo "<div style='display: none;'>";
            echo "Plan ID: " . $plan_ID . "<br>";
            echo "Carpenter ID: " . $Carpenter_ID . "<br>";
            echo "Query: " . $contractStatusQuery . "<br>";
            echo "Rows found: " . mysqli_num_rows($contractStatusResult) . "<br>";
            echo "</div>";

            if (mysqli_num_rows($contractStatusResult) > 0) {
                $contractData = mysqli_fetch_assoc($contractStatusResult);
                $contractStatus = $contractData['status'];
                $rejectionReason = $contractData['rejection_reason'];
                $laborCost = $contractData['labor_cost'];
                $duration = $contractData['duration'];
                
                // Set status color
                $statusColor = '#FFC107'; // Default yellow for pending
                if ($contractStatus == 'accepted') {
                    $statusColor = '#4CAF50'; // Green for accepted
                } elseif ($contractStatus == 'rejected') {
                    $statusColor = '#F44336'; // Red for rejected
                }
                
                // Display status badge
                echo "<div style='display: inline-block; padding: 10px 20px; background-color: " . $statusColor . "; color: white; font-weight: bold; font-size: 18px; border-radius: 5px; margin-bottom: 10px;'>";
                echo "Contract Status: " . ucfirst($contractStatus);
                echo "</div>";
                
                // Display labor cost and duration
                echo "<div style='margin-top: 15px;'>";
                echo "<p><strong>Labor Cost:</strong> $" . number_format($laborCost, 2) . "</p>";
                echo "<p><strong>Duration:</strong> " . $duration . " days</p>";
                echo "</div>";
                
                // Display rejection reason if rejected
                if ($contractStatus == 'rejected' && !empty($rejectionReason)) {
                    echo "<div style='margin-top: 15px; padding: 15px; background-color: #ffebee; border-left: 4px solid #F44336; text-align: left; border-radius: 4px;'>";
                    echo "<h3 style='margin-top: 0; color: #D32F2F;'>Rejection Reason:</h3>";
                    echo "<p style='font-size: 16px;'>" . htmlspecialchars($rejectionReason) . "</p>";
                    echo "</div>";
                }
                
                // Display acceptance message if accepted
                if ($contractStatus == 'accepted') {
                    echo "<div style='margin-top: 15px; padding: 15px; background-color: #e8f5e9; border-left: 4px solid #4CAF50; text-align: left; border-radius: 4px;'>";
                    echo "<h3 style='margin-top: 0; color: #2E7D32;'>Contract Accepted</h3>";
                    echo "<p style='font-size: 16px;'>The contract has been accepted and is now in effect. Work can proceed according to the agreed terms.</p>";
                    echo "</div>";
                }
                
                // Display pending message if pending
                if ($contractStatus == 'pending') {
                    echo "<div style='margin-top: 15px; padding: 15px; background-color: #fff8e1; border-left: 4px solid #FFC107; text-align: left; border-radius: 4px;'>";
                    echo "<h3 style='margin-top: 0; color: #F57F17;'>Pending Decision</h3>";
                    echo "<p style='font-size: 16px;'>This contract is awaiting your decision. Please review the terms and either accept or reject the contract.</p>";
                    echo "<button onclick='openSignedContractModal()' style='background-color: " . $statusColor . "; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 10px;'>";
                    echo "<i class='fas fa-file-contract'></i> Review Contract";
                    echo "</button>";
                    echo "</div>";
                }
            } else {
                // No contract record found
                echo "<div style='display: inline-block; padding: 10px 20px; background-color: #9E9E9E; color: white; font-weight: bold; font-size: 18px; border-radius: 5px; margin-bottom: 10px;'>";
                echo "Contract Status: Not Available";
                echo "</div>";
                
                echo "<div style='margin-top: 15px; padding: 15px; background-color: #f5f5f5; border-left: 4px solid #9E9E9E; text-align: left; border-radius: 4px;'>";
                echo "<p style='font-size: 16px;'>No contract has been created for this project yet. Please contact the administrator.</p>";
                echo "</div>";
            }

            mysqli_stmt_close($contractStatusStmt);
            echo "</div>"; // Close contract-status-display div

            // Go Back button
            echo "<div style='text-align: left; margin: 20px 0; padding-left: 20px;'>";
            echo "<button onclick=\"window.location.href='" . htmlspecialchars($_SERVER['HTTP_REFERER']) . "'\" 
                  style='background-color: #FF8C00; color: white; border: none; padding: 10px 20px; 
                  border-radius: 5px; cursor: pointer; font-size: 16px;'>Go Back</button>";
            echo "</div>";
                        
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

<!-- Contract Modal -->
<div id="signedContractModal" class="modal">
    <div class="modal-content" style="width: 800px; max-width: 90%;">
        <span class="close" onclick="closeSignedContractModal()">&times;</span>
        
        <div class="contract-container" style="padding: 40px;">
            <h2 class="contract-title" style="text-align: center; font-size: 28px; font-weight: normal; margin-bottom: 30px; text-transform: uppercase;">
                CONSTRUCTION AGREEMENT
            </h2>

            <p class="contract-text" style="text-align: justify; line-height: 1.6; margin-bottom: 20px;">
                This agreement is made between <strong><?php echo $clientName; ?></strong> (Client) 
                and <strong>CCARP Construction Company</strong> (Contractor), 
                regarding the construction project with the following details:
            </p>

            <div class="project-details" style="margin: 20px 0;">
                <p><strong>Lot Area:</strong> <?php echo $row['length_lot_area']; ?>m x <?php echo $row['width_lot_area']; ?>m (<?php echo $row['square_meter_lot']; ?> sqm)</p>
                <p><strong>Floor Area:</strong> <?php echo $row['length_floor_area']; ?>m x <?php echo $row['width_floor_area']; ?>m (<?php echo $row['square_meter_floor']; ?> sqm)</p>
                <p><strong>Project Type:</strong> <?php echo $row['type']; ?></p>
                <p><strong>Initial Budget:</strong> PHP <?php echo number_format($row['initial_budget'], 2); ?></p>
            </div>

            <div class="project-photo" style="text-align: center; margin: 30px 0;">
                <?php if (!empty($row['Photo'])): ?>
                    <img src="<?php echo $row['Photo']; ?>" alt="Project Photo" 
                         style="max-width: 400px; height: auto; border: 1px solid #ddd; padding: 5px;">
                <?php endif; ?>
            </div>

            <div class="dates-section" style="margin: 20px 0;">
                <p><strong>Start Date:</strong> <?php echo date("F j, Y", strtotime($row['start_date'])); ?></p>
                <p><strong>End Date:</strong> <?php echo date("F j, Y", strtotime($row['end_date'])); ?></p>
                <?php
                    // Calculate duration
                    $startDateTime = new DateTime($row['start_date']);
                    $endDateTime = new DateTime($row['end_date']);
                    $interval = $startDateTime->diff($endDateTime);
                    $duration = $interval->format('%a');
                ?>
                <p><strong>Duration:</strong> <?php echo $duration; ?> day(s)</p>
            </div>

            <div class="labor-cost" style="margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 5px;">
                <p><strong>Labor Cost:</strong> PHP <?php echo number_format($row['initial_budget'] * 0.3, 2); ?></p>
            </div>

            <p class="contract-text" style="text-align: justify; line-height: 1.6; margin: 20px 0;">
                Both parties agree to the conditions stated above. The contractor is responsible for completing the 
                project within the agreed timeframe and budget.
            </p>

            <div class="signature-section" style="margin-top: 50px; display: flex; justify-content: space-between; padding: 0 50px;">
                <div class="signature-line" style="width: 250px; text-align: center;">
                    <p>_______________________</p>
                    <p style="margin-top: 5px;"><?php echo $clientName; ?></p>
                    <p style="margin-top: 0; font-size: 14px; font-weight: bold; color: #000;">Client Signature over Printed Name</p>
                </div>
                <div class="signature-line" style="width: 250px; text-align: center;">
                    <p>_______________________</p>
                    <p style="margin-top: 5px;">CCARP Representative</p>
                    <p style="margin-top: 0; font-size: 14px; font-weight: bold; color: #000;">Contractor Signature over Printed Name</p>
                </div>
            </div>

            <!-- Status Badge - Using the variables we set above -->
            <div style="margin-top: 30px; text-align: left; padding-left: 50px;">
                <span id="contractStatus" style="padding: 5px 15px; border-radius: 5px; font-weight: bold; font-size: 16px; 
                      background-color: <?php echo $statusColor; ?>; color: white;">
                    Status: <?php echo ucfirst($contractStatus); ?>
                </span>
                
                <?php if(!empty($rejectionReason)): ?>
                <div style="margin-top: 10px; padding: 10px; background-color: #ffeeee; border-left: 3px solid #F44336; border-radius: 3px;">
                    <p><strong>Rejection Reason:</strong> <?php echo $rejectionReason; ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="contract-acceptance" style="margin: 20px; text-align: center;">
            <?php if($contractStatus == 'pending'): ?>
            <!-- Dropdown for contract status selection -->
            <div style="margin-bottom: 20px;">
                <label for="contractStatusSelect" style="display: block; margin-bottom: 10px; font-weight: bold; font-size: 16px;">
                    Contract Decision:
                </label>
                <select id="contractStatusSelect" onchange="handleStatusChange()" 
                        style="padding: 10px; width: 300px; font-size: 16px; border: 1px solid #ddd; border-radius: 4px;">
                    <option value="">-- Select Decision --</option>
                    <option value="accepted">Accept Contract</option>
                    <option value="rejected">Reject Contract</option>
                </select>
            </div>
            
            <!-- Acceptance confirmation (hidden by default) -->
            <div id="acceptanceConfirmation" style="display: none; margin-bottom: 20px; padding: 15px; background-color: #e8f5e9; border-radius: 5px; text-align: left;">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    <input type="checkbox" id="acceptTerms" 
                           style="width: 20px; height: 20px; margin-right: 10px; cursor: pointer;"> 
                    <label for="acceptTerms" style="cursor: pointer; font-size: 16px;">
                        I have read and agree to the terms of this contract
                    </label>
                </div>
                <button id="confirmAcceptBtn" onclick="acceptContract()" 
                        style="background-color: #4CAF50; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 10px;">
                    Confirm Acceptance
                </button>
            </div>
            
            <!-- Rejection form (hidden by default) -->
            <div id="rejectionForm" style="display: none; margin-bottom: 20px; text-align: left; padding: 15px; background-color: #ffebee; border-radius: 5px;">
                <h3 style="margin-top: 0; color: #333;">Rejection Reason</h3>
                <p>Please provide a reason for rejecting this contract:</p>
                <textarea id="rejectionReason" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; min-height: 100px;"></textarea>
                <button onclick="rejectContract()" 
                        style="background-color: #F44336; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 15px;">
                    Submit Rejection
                </button>
            </div>
            
            <button onclick="closeSignedContractModal()" 
                    style="background-color: #f1f1f1; color: #333; border: 1px solid #ddd; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">
                Cancel
            </button>
            <?php else: ?>
            <!-- If contract is already approved or rejected, just show close button -->
            <button onclick="closeSignedContractModal()" 
                    style="background-color: #f1f1f1; color: #333; border: 1px solid #ddd; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">
                Close
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add this button where you want to display it -->
<button onclick="openSignedContractModal()" class="view-contract-btn" 
        style="background-color: #4a6fdc; color: white; border: none; padding: 10px 20px; 
               border-radius: 5px; cursor: pointer; font-size: 16px; margin: 10px 0;">
    <i class="fas fa-file-contract"></i> View Contract
</button>

<script>
    function openSignedContractModal() {
        document.getElementById('signedContractModal').style.display = 'block';
    }
    
    function closeSignedContractModal() {
        document.getElementById('signedContractModal').style.display = 'none';
        resetContractForm();
    }
    
    function resetContractForm() {
        // Reset dropdown
        if (document.getElementById('contractStatusSelect')) {
            document.getElementById('contractStatusSelect').value = '';
        }
        
        // Hide acceptance and rejection sections
        if (document.getElementById('acceptanceConfirmation')) {
            document.getElementById('acceptanceConfirmation').style.display = 'none';
        }
        if (document.getElementById('rejectionForm')) {
            document.getElementById('rejectionForm').style.display = 'none';
        }
        
        // Reset checkbox
        if (document.getElementById('acceptTerms')) {
            document.getElementById('acceptTerms').checked = false;
        }
        
        // Reset rejection reason
        if (document.getElementById('rejectionReason')) {
            document.getElementById('rejectionReason').value = '';
        }
    }
    
    function handleStatusChange() {
        const selectedStatus = document.getElementById('contractStatusSelect').value;
        
        // Hide both sections first
        document.getElementById('acceptanceConfirmation').style.display = 'none';
        document.getElementById('rejectionForm').style.display = 'none';
        
        // Show the appropriate section based on selection
        if (selectedStatus === 'accepted') {
            document.getElementById('acceptanceConfirmation').style.display = 'block';
            // Auto-check the terms checkbox
            document.getElementById('acceptTerms').checked = true;
            // Enable the confirm button immediately
            document.getElementById('confirmAcceptBtn').disabled = false;
        } else if (selectedStatus === 'rejected') {
            document.getElementById('rejectionForm').style.display = 'block';
        }
    }
    
    function acceptContract() {
        if (document.getElementById('acceptTerms') && document.getElementById('acceptTerms').checked) {
            // Get the plan ID from the URL
            const planId = <?php echo $plan_ID; ?>;
            
            console.log('Sending acceptance request for plan ID:', planId);
            
            // Disable the button to prevent multiple submissions
            const acceptButton = document.getElementById('confirmAcceptBtn');
            acceptButton.disabled = true;
            acceptButton.textContent = 'Processing...';
            
            // Send AJAX request to update contract status
            fetch('update_contract_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'plan_id=' + planId + '&status=accepted'
            })
            .then(response => {
                console.log('Response received:', response);
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);
                if (data.success) {
                    // Update the status badge immediately
                    document.getElementById('contractStatus').textContent = 'Status: Accepted';
                    document.getElementById('contractStatus').style.backgroundColor = '#4CAF50';
                    
                    // Hide the decision dropdown and show only the close button
                    document.querySelector('.contract-acceptance').innerHTML = 
                        '<button onclick="closeSignedContractModal()" ' +
                        'style="background-color: #f1f1f1; color: #333; border: 1px solid #ddd; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">' +
                        'Close</button>';
                    
                    // Show success message
                    alert('Contract accepted successfully!');
                    
                    // Reload the page to reflect the changes
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                    // Re-enable the button
                    acceptButton.disabled = false;
                    acceptButton.textContent = 'Confirm Acceptance';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your request: ' + error.message);
                // Re-enable the button
                acceptButton.disabled = false;
                acceptButton.textContent = 'Confirm Acceptance';
            });
        } else {
            alert('Please check the agreement box before confirming.');
        }
    }
    
    function rejectContract() {
        const rejectionReason = document.getElementById('rejectionReason').value;
        if (rejectionReason.trim() !== '') {
            // Get the plan ID from the URL
            const planId = <?php echo $plan_ID; ?>;
            
            console.log('Sending rejection request for plan ID:', planId, 'with reason:', rejectionReason);
            
            // Disable the button to prevent multiple submissions
            const rejectButton = document.querySelector('#rejectionForm button');
            rejectButton.disabled = true;
            rejectButton.textContent = 'Processing...';
            
            // Send AJAX request to update contract status
            fetch('update_contract_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'plan_id=' + planId + '&status=rejected&reason=' + encodeURIComponent(rejectionReason)
            })
            .then(response => {
                console.log('Response received:', response);
                return response.json();
            })
            .then(data => {
                console.log('Data received:', data);
                if (data.success) {
                    // Update the status badge immediately
                    document.getElementById('contractStatus').textContent = 'Status: Rejected';
                    document.getElementById('contractStatus').style.backgroundColor = '#F44336';
                    
                    // Add the rejection reason to the display
                    const statusDiv = document.getElementById('contractStatus').parentNode;
                    const reasonDiv = document.createElement('div');
                    reasonDiv.style = 'margin-top: 10px; padding: 10px; background-color: #ffeeee; border-left: 3px solid #F44336; border-radius: 3px;';
                    reasonDiv.innerHTML = '<p><strong>Rejection Reason:</strong> ' + rejectionReason + '</p>';
                    statusDiv.appendChild(reasonDiv);
                    
                    // Hide the acceptance options
                    document.querySelector('.contract-acceptance').innerHTML = 
                        '<button onclick="closeSignedContractModal()" ' +
                        'style="background-color: #f1f1f1; color: #333; border: 1px solid #ddd; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">' +
                        'Close</button>';
                    
                    alert('Contract rejected successfully.');
                    
                    // Reload the page to reflect the changes
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                    // Re-enable the button
                    rejectButton.disabled = false;
                    rejectButton.textContent = 'Submit Rejection';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your request.');
                // Re-enable the button
                rejectButton.disabled = false;
                rejectButton.textContent = 'Submit Rejection';
            });
        } else {
            alert('Please provide a reason for rejecting the contract.');
        }
    }
</script>

<!-- Add Materials Modal -->
<div id="addMaterialsModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('addMaterialsModal').style.display='none'">&times;</span>
        <h2>Add Materials</h2>
        <!-- Add your materials form here -->
        <form id="materialsForm">
            <!-- Form fields for materials -->
            <div class="form-group">
                <label for="materialName">Material Name:</label>
                <input type="text" id="materialName" name="materialName" required>
            </div>
            <div class="form-group">
                <label for="materialQuantity">Quantity:</label>
                <input type="number" id="materialQuantity" name="materialQuantity" required>
            </div>
            <div class="form-group">
                <label for="materialPrice">Price:</label>
                <input type="number" id="materialPrice" name="materialPrice" required>
            </div>
            <div class="form-actions">
                <button type="submit">Add Material</button>
                <button type="button" class="cancel-btn" onclick="document.getElementById('addMaterialsModal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Labor Modal -->
<div id="addLaborModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('addLaborModal').style.display='none'">&times;</span>
        <h2>Add Labor</h2>
        <!-- Add your labor form here -->
        <form id="laborForm">
            <!-- Form fields for labor -->
            <div class="form-group">
                <label for="laborType">Labor Type:</label>
                <input type="text" id="laborType" name="laborType" required>
            </div>
            <div class="form-group">
                <label for="laborHours">Hours:</label>
                <input type="number" id="laborHours" name="laborHours" required>
            </div>
            <div class="form-group">
                <label for="laborRate">Hourly Rate:</label>
                <input type="number" id="laborRate" name="laborRate" required>
            </div>
            <div class="form-actions">
                <button type="submit">Add Labor</button>
                <button type="button" class="cancel-btn" onclick="document.getElementById('addLaborModal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Edit -->
<div id="editModal" class="editModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1001;">
  <div class="edit-modal-content">
    <span class="close" onclick="closeEditModal()">&times;</span>
    <h2>Edit Item</h2>
    <form id="editForm" action="edit_item.php" method="post">
      <!-- Edit form fields go here -->
      <input type="hidden" name="item_id" id="editItemId" value="">
      <label for="editItemName">Item Name:</label>
      <input type="text" name="editItemName" id="editItemName" required>
      <label for="editPrice">Price:</label>
      <input type="text" name="editPrice" id="editPrice" required>
      <label for="editType">Type:</label>
      <input type="text" name="editType" id="editType" required>
      <label for="editQuantity">Quantity:</label>
      <input type="text" name="editQuantity" id="editQuantity" required>
      <button type="button" onclick="saveChanges()">Save Changes</button>
    </form>
  </div>
</div>

<script>
  // Updated script to handle all modals
  var materialsModal = document.getElementById('addMaterialsModal');
  var laborModal = document.getElementById('addLaborModal');
  var contractModal = document.getElementById('addContractModal'); // New modal

  var materialsBtn = document.getElementById('addMaterialsBtn');
  var laborBtn = document.getElementById('addLaborBtn');
  var contractBtn = document.getElementById('addContractBtn'); // New button for contract

  var closeBtns = document.getElementsByClassName('close');

  // Open the Materials modal
  materialsBtn.onclick = function() {
      materialsModal.style.display = 'block';
  }

  // Open the Labor modal
  laborBtn.onclick = function() {
      laborModal.style.display = 'block';
  }

  // Open the Contract modal
  contractBtn.onclick = function() {
      contractModal.style.display = 'block';
  }

  // Close all modals when any close button is clicked
  for (var i = 0; i < closeBtns.length; i++) {
      closeBtns[i].onclick = function() {
          materialsModal.style.display = 'none';
          laborModal.style.display = 'none';
          contractModal.style.display = 'none'; // Close contract modal
      }
  }

  // Close the modal if the user clicks outside of it
  window.onclick = function(event) {
      if (event.target == materialsModal) {
          materialsModal.style.display = 'none';
      } else if (event.target == laborModal) {
          laborModal.style.display = 'none';
      } else if (event.target == contractModal) {
          contractModal.style.display = 'none';
      }
  }
</script>

<script>
    // quantityxcost.js
    // Get references to the input fields
    var quantityInput = document.getElementById('quantity');
    var costInput = document.getElementById('cost');
    var totalCostInput = document.getElementById('total_cost');

    // Add event listeners to quantity and cost input fields
    quantityInput.addEventListener('input', calculateTotalCost);
    costInput.addEventListener('input', calculateTotalCost);

    // Function to calculate total cost
    function calculateTotalCost() {
        // Parse quantity and cost inputs as numbers
        var quantity = parseFloat(quantityInput.value);
        var cost = parseFloat(costInput.value);

        // Calculate total cost
        var totalCost = quantity * cost;

        // Update total cost input field with the calculated value
        totalCostInput.value = totalCost.toFixed(2); // Displaying up to 2 decimal places
    }
</script>
<script>
    // daysofworkxlaborcost.js
    // Get references to the input fields
    var daysOfWorkInput = document.getElementById('days_of_work');
    var rateInput = document.getElementById('rate');
    var totalLaborCostInput = document.getElementById('total_of_laborcost');

    // Add event listeners to days of work and rate input fields
    daysOfWorkInput.addEventListener('input', calculateTotalLaborCost);
    rateInput.addEventListener('input', calculateTotalLaborCost);

    // Function to calculate total labor cost
    function calculateTotalLaborCost() {
        // Parse days of work and rate inputs as numbers
        var daysOfWork = parseFloat(daysOfWorkInput.value);
        var rate = parseFloat(rateInput.value);

        // Calculate total labor cost
        var totalLaborCost = isNaN(daysOfWork) ? rate : daysOfWork * rate;

        // Update total labor cost input field with the calculated value
        totalLaborCostInput.value = isNaN(totalLaborCost) ? '' : totalLaborCost.toFixed(2); // Displaying up to 2 decimal places
    }
</script>
<script>
    // Get references to the input fields
    const totalLaborCostInput = document.getElementById("total_of_laborcost");
    const additionalCostInput = document.getElementById("additional_cost");
    const overallCostInput = document.getElementById("overall_cost");

    // Function to calculate the overall cost
    function calculateOverallCost() {
        // Parse values as floats; default to 0 if parsing fails
        const totalLaborCost = parseFloat(totalLaborCostInput.value) || 0;
        const additionalCost = parseFloat(additionalCostInput.value) || 0;

        // Calculate the overall cost
        const overallCost = totalLaborCost + additionalCost;

        // Update the overall cost input field
        overallCostInput.value = overallCost.toFixed(2); // Display up to 2 decimal places
    }

    // Initial calculation on page load
    calculateOverallCost();

    // Add event listener to recalculate when the additional cost changes
    additionalCostInput.addEventListener("input", calculateOverallCost);
</script>
</body>
</html>
  