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
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Compute Budget</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    padding-top: 180px;
    font-size: 20px; /* Set base font size to 20px */
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
      font-size: 16px;
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
    margin: auto;
    width: 70%;
    padding: 20px;
    text-align: left;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  }

  .main h1 {
    font-size: 20px; /* Set font size of main heading to 20px */
    margin-bottom: 20px;
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

</style>
</head>
<body>

  <div class="header">
      <a href="comment.php">
          <h1>
              <img src="assets/img/logos/logo.png" alt=""  style="width: 75px; margin-right: 10px;">
          </h1>
      </a>
      <div class="right">
          <a href="logout.php" style="text-decoration: none; color: black; margin-right: 20px;">Log Out</a>
      </div>
  </div>

  <div class="topnav">
      <a class="active" href="comment.php">Home</a>
      <a href="#about">About</a>
      <a href="#contact">Contact</a>
      <a href="#">Project</a>
  </div>

  <?php

    if (isset($_GET['requirement_ID'])) {
        $requirement_ID = $_GET['requirement_ID'];

        $query = "SELECT * FROM projectrequirements WHERE requirement_ID = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $requirement_ID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
        echo "<div class='main'>";
        echo "<h1>Client's Plan Details</h1>";

        $userId = $row['User_ID'];
        $userQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
        $userStmt = mysqli_prepare($conn, $userQuery);
        mysqli_stmt_bind_param($userStmt, "i", $userId);
        mysqli_stmt_execute($userStmt);
        $userResult = mysqli_stmt_get_result($userStmt);

        echo "<label for='name'>Client Name: </label>";
        if ($userRow = mysqli_fetch_assoc($userResult)) {
            echo "<input type='text' id='name' name='User_ID' value='{$userRow['First_Name']} {$userRow['Last_Name']}' readonly><br>";
        }

        echo "<div class=\"row-container\">";
        echo "<h3>Lot area</h3>";
        echo "<label for='length_lot_area'>Length for lot area</label>";
        echo "<input type='text' id='length_lot_area' name='length_lot_area' value='{$row['length_lot_area']}' readonly><br>";
        echo "<label for='width_lot_area'>Width for lot area</label>";
        echo "<input type='text' id='width_lot_area' name='width_lot_area' value='{$row['width_lot_area']}' readonly><br>";
        echo "<label for='square_meter_lot'>Square Meter(Sq):</label>";
        echo "</div>";
        
        echo "<div class=\"row-container\">";
        echo "<h3>Floor Area</h3>";
        echo "<label for='length_floor_area'>Length for floor area</label>";
        echo "<input type='text' id='length_floor_area' name='length_floor_area' value='{$row['length_floor_area']}' readonly><br>";
        echo "<label for='width_floor_area'>Width for floor area</label>";
        echo "<input type='text' id='width_floor_area' name='width_floor_area' value='{$row['width_floor_area']}' readonly><br>";
        echo "<label for='square_meter_lot'>Square Meter(Sq):</label>";
        echo "<input type='text' id='square_meter_floor' name='square_meter_floor' value='{$row['square_meter_floor']}' readonly><br>";
        echo "</div>";

        echo "<div class=\"row-container\">";
        echo "<h3>Initial Budget</h3>";
        echo "<label for='initial_budget'>Initial Budget</label>";
        echo "<input type='text' id='initial_budget' name='initial_budget' value='{$row['initial_budget']}' readonly><br>";
        echo "</div>";
        
        echo "<div class=\"row-container\">";
        echo "<h3>Project Dates</h3>";
        echo "<p>Start Date: <input type='text' value='{$row['start_date']}' readonly></p>";
        echo "<p>End Date: <input type='text' value='{$row['end_date']}' readonly></p>";        
        echo "</div>";

        echo '<div class="">';
        echo '<p></p>';
    
        // Query for prematerials table
        $query_materials = "SELECT * FROM prematerials";
        $stmt_materials = mysqli_prepare($conn, $query_materials);
        mysqli_stmt_execute($stmt_materials);
        $result_materials = mysqli_stmt_get_result($stmt_materials);
    
        $totalSum_materials = 0; // Initialize total sum variable for prematerials
        while ($material_row = mysqli_fetch_assoc($result_materials)) {
            // Add each total to the total sum for prematerials
            $totalSum_materials += $material_row['total'];
        }
    
        // Query for requiredmaterials table
        $query_required_materials = "SELECT * FROM requiredmaterials";
        $stmt_required_materials = mysqli_prepare($conn, $query_required_materials);
        mysqli_stmt_execute($stmt_required_materials);
        $result_required_materials = mysqli_stmt_get_result($stmt_required_materials);
    
        $totalSum_required = 0; // Initialize total sum variable for requiredmaterials
        while ($required_material_row = mysqli_fetch_assoc($result_required_materials)) {
            // Add each total to the total sum for requiredmaterials
            $totalSum_required += $required_material_row['total'];
        }
    
        // Calculate combined total sum
        $totalSum_combined = $totalSum_materials + $totalSum_required;
    
        // Start the HTML output
        echo '<div class="container">';  // Wrap everything in a container for structure
    
        // Table for prematerials
        echo '<div class="table-container">';  // Create a div to separate the table visually
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Part</th>';
        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Materials</th>';
        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Name</th>';
        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Quantity</th>';
        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Price</th>';
        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Total</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
    
        // Loop through and display data for prematerials
        mysqli_data_seek($result_materials, 0);
        while ($material_row = mysqli_fetch_assoc($result_materials)) {
            echo '<tr>';
            echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['part']) . '</td>';
            echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['materials']) . '</td>';
            echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['name']) . '</td>';
            echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['quantity']) . '</td>';
            echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['price']) . '</td>';
            echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['total']) . '</td>';
            echo '</tr>';
        }
    
        echo '</tbody>';
        echo '</table>';
        echo '</div>'; // End of prematerials table container
    
        // Table for requiredmaterials
        echo '<div class="table-container">';  // Create another div to separate this table
        echo '<table style="border-collapse: collapse; width: 100%;">';
        echo '<thead>';
        echo '<tr>';
        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Material</th>';
        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Type</th>';
        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Image</th>';
        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Quantity</th>';
        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Price</th>';
        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Total</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
    
        // Loop through and display data for required materials
        mysqli_data_seek($result_required_materials, 0);
        while ($required_material_row = mysqli_fetch_assoc($result_required_materials)) {
            echo '<tr>';
            echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['material']) . '</td>';
            echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['type']) . '</td>';
            echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><img src="' . htmlspecialchars($required_material_row['image']) . '" alt="' . htmlspecialchars($required_material_row['material']) . '" style="width: 100px; height: auto;"></td>';
            echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['quantity']) . '</td>';
            echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['price']) . '</td>';
            echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['total']) . '</td>';
            echo '</tr>';
        }
    
        echo '</tbody>';
        echo '</table>';
        echo '</div>'; // End of required materials table container
    
        echo '</div>'; // End of container div

        
        // Display the resized photo with a link to open the modal
        $photoPath = $row['Photo'];
        if (!empty($photoPath) && file_exists($photoPath)) {
          echo "<p>Photo:</p>";
          echo "<a href='#' onclick='openModal(\"{$photoPath}\")'>";
          echo "<img src='{$photoPath}' alt='Plan Photo' style='width: 900px; height: 400px;'>";
          echo "</a>";
        }
        
        // Get contract details from `contracts` table
        $requirement_ID = $_GET['requirement_ID'] ?? null;
        $contract = null;

        if ($requirement_ID) {
            $query = "SELECT * FROM contracts WHERE requirement_ID = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $requirement_ID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($contract = mysqli_fetch_assoc($result)) {
                $client_name = $contract['client_name'];
                $contractor_name = $contract['contractor_name'];
            } else {
                die("Contract not found.");
            }
        } else {
            die("Requirement ID missing.");
        }

        // Display Contract Button
        echo "<h3>Contract:</h3>";
        echo "<div style='text-align: center; margin-top: 20px;'>
                <button onclick='openContractModal()' 
                  style='padding: 12px 20px; font-size: 16px; 
                          font-weight: bold; color: white; background-color: #007BFF; 
                          border: none; cursor: pointer; border-radius: 5px; 
                          box-shadow: 2px 2px 5px rgba(0,0,0,0.2);'>
                    View Contract
                </button>
              </div>";

        // Contract Modal
        echo "<div id='contractModal'>
        <div class='contract-modal-content'>
            <span onclick='closeContractModal()' class='close-modal'>&times;</span>
            <h2>Construction Agreement</h2>

            <div class='contract-container'>
                <p class='contract-text'>
                    This agreement is made between <span class='highlight'>{$client_name}</span> (Client) and <span class='highlight'>{$contractor_name}</span> (Contractor), regarding the construction project with the following details:
                </p>

                <p><strong>Lot Area:</strong> {$contract['length_lot_area']}m x {$contract['width_lot_area']}m ({$contract['square_meter_lot']} sqm)</p>
                <p><strong>Floor Area:</strong> {$contract['length_floor_area']}m x {$contract['width_floor_area']}m ({$contract['square_meter_floor']} sqm)</p>
                <p><strong>Project Type:</strong> {$contract['type']}</p>
                <p><strong>Initial Budget:</strong> PHP " . number_format($contract['initial_budget'], 2) . "</p>";

            // Fetch the stored file path from the database
            $photoPath = $contract['Photo']; // This now contains the file path instead of BLOB

            if (!empty($photoPath) && file_exists($photoPath)) {
                echo "<p>Photo:</p>";
                echo "<img src='{$photoPath}' alt='Plan Photo' style='width: 900px; height: 400px;'>";
            } else {
                echo "<p>No project photo available.</p>";
            }

            echo "<p class='contract-text'>
                    The project is approved by <span class='highlight'>{$contractor_name}</span> and will proceed according to the agreed terms.
                </p>

                <p><strong>Start Date:</strong> " . date("F j, Y", strtotime($contract['start_date'])) . "</p>
                <p><strong>End Date:</strong> " . date("F j, Y", strtotime($contract['end_date'])) . "</p>";

            // Display Labor Cost
            // Assuming the labor cost is stored in the database and is fetched correctly.
            $labor_cost = $contract['labor_cost']; // Make sure to adjust this as per your database column name
            echo "<p><strong>Labor Cost:</strong> PHP " . number_format($labor_cost, 2) . "</p>";

            echo "<p class='contract-text'>
                    Both parties agree to the conditions stated above. The contractor is responsible for completing the project within the agreed timeframe and budget.
                </p>

                <form method='POST' action='save_signed_contract.php'>
                    <input type='hidden' name='requirement_ID' value='{$contract['requirement_ID']}'> 

                    <!-- Checkbox for Agreement -->
                    <div style='margin-top: 15px;'>
                        <input type='checkbox' id='agreeCheckbox' onchange='toggleSubmitButton()'>
                        <label for='agreeCheckbox'>I agree to the terms and conditions.</label>
                    </div>

                    <!-- Submit Button (Disabled by Default) -->
                    <button type='submit' id='submitBtn' class='submit-btn' disabled>
                        Sign Contract
                    </button>
                </form>

            </div>
        </div>
        </div>";

    echo "<script>
        function toggleSubmitButton() {
            let checkbox = document.getElementById('agreeCheckbox');
            let submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = !checkbox.checked;
        }
    </script>";

        // JavaScript Functions for Modal
        echo "<script>
        window.onload = function() {
            document.getElementById('contractModal').style.display = 'none';
        };

        function openContractModal() {
            document.getElementById('contractModal').style.display = 'flex';
        }

        function closeContractModal() {
            document.getElementById('contractModal').style.display = 'none';
        }

        function openPhotoModal(photoUrl) {
            window.open(photoUrl, '_blank');
        }
    </script>";                      
                  
        echo "</div>"; 
        
      } else {
        echo "<div class='main'>";
        echo "<p>No approved plan found with Approved Plan ID: $requirement_ID</p>";
        echo "</div>"; 
      }
      
      mysqli_close($conn);
    } else {
      echo "<div class='main'>";
      echo "<p>Approved Plan ID is missing.</p>";
      echo "</div>"; 
    }
  ?>


  <h2 style="text-align:center">Compute Budget</h2>

  <button id="addMaterialsBtn">Add Materials</button>

  <div id="addMaterialsModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Compute Budget for Materials</h2>
            <form id="addMaterialsForm" method="post" action="addmaterials.php" enctype="multipart/form-data">
                <div>
                    <label for="material_name">Material</label>
                    <input type="text" id="material_name" name="material_name" required>
                </div>
                <div>
                    <label for="type">Type</label>
                    <select id="material_type" name="type" required>
                        <option value="Materials">Materials</option>
                        <option value="Equipment">Equipment</option>
                        <option value="Tool">Tool</option>
                    </select>
                </div>
                <div>
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" required>
                </div>
                <div>
                    <label for="cost">Cost:</label>
                    <input type="number" id="cost" name="cost" step="0.01" min="0" required>
                </div>
                <div>
                    <label for="Total_cost">Total cost:</label>
                    <input type="text" id="total_cost" name="total_cost" readonly>
                </div>
                <!-- Hidden input field for requirement_ID -->
                <input type="hidden" name="requirement_ID" value="<?php echo isset($_GET['requirement_ID']) ? $_GET['requirement_ID'] : ''; ?>">
                <button type="submit">Add Materials</button>
            </form>
        </div>
  </div>

  <button id="addLaborBtn" data-requirement-id="<?php echo $requirementData['requirement_ID']; ?>">Add Labor</button>

  <div id="addLaborModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Compute Budget for Labor</h2>
            <form id="addLaborForm" method="post" action="addlabor.php" enctype="multipart/form-data">
                <div>
                    <label for="carpenter_name">Carpenter Name:</label>
                    <input type="text" name="carpenter_name" value="<?php echo isset($row['approved_by']) ? $row['approved_by'] : ''; ?>">
                </div>
                <div>
                    <label for="type_of_work">Type of work:</label>
                    <select id="type_of_work" name="type_of_work">
                        <option value="Inadlaw">Inadlaw</option>
                        <option value="Pakyawan">Pakyawan</option>
                    </select>
                </div>
                <div>
                    <label for="days_of_work">Days of work (if inadlaw): </label>
                    <input type="number" id="days_of_work" name="days_of_work">
                </div>
                <div>
                    <label for="rate">Rate:</label>
                    <input type="number" id="rate" name="rate" required>
                </div>
                <div>
                  <label for="total_of_laborcost">Total of Labor Cost</label>
                  <input type="number" id="total_of_laborcost" name="total_of_laborcost" required readonly value="0">
              </div>
              <div>
                  <label for="additional_cost">Additional Cost: (Optional)</label>
                  <input type="number" id="additional_cost" name="additional_cost" step="0.01" min="0" required>
              </div>
              <div>
                  <label for="overall_cost">Overall Cost: (Rate, Days, and Labor)</label>
                  <input type="number" id="overall_cost" name="overall_cost" step="0.01" min="0" required readonly>
              </div>
              <div>
                <label for="task">Task</label>
                <input type="text" name="task" id="" required>
              </div>
                <!-- Hidden input field for requirement_ID -->
                <input type="hidden" name="requirement_ID" value="<?php echo isset($_GET['requirement_ID']) ? $_GET['requirement_ID'] : ''; ?>">

                <button type="submit">Add Labor</button>
            </form>
        </div>
  </div>



  <div class="sort">
    <label for="filterType">Filter by Type:</label>
    <select id="filterType" onchange="handleSortChange(this)">
      <option value="select">All</option>
      <option value="equipment">Equipment</option>
      <option value="tools">Tools</option>
      <option value="materials">Materials</option>
    </select>
  </div>



    <div class="product-container">
        <h2 style="text-align:center">Material</h2>
        <?php
        include('config.php');


        // Check if requirement_ID is provided in the URL parameter
        if(isset($_GET['requirement_ID'])) {
            $requirementID = $_GET['requirement_ID'];

            // Prepare the SQL query with a WHERE clause to filter by requirement_ID
            $sql = "SELECT * FROM constructionmaterials WHERE requirement_ID = $requirementID";

            $result = $conn->query($sql);


            if ($result->num_rows > 0) {
                echo '<div class="table-container">';
                echo '    <table class="styled-table">';
                echo '        <tr>';
                echo '            <th class="table-header">Material</th>';
                echo '            <th class="table-header">Type</th>';
                echo '            <th class="table-header">Quantity</th>';
                echo '            <th class="table-header">Cost</th>';
                echo '            <th class="table-header">Total Cost</th>';
                echo '            <th class="table-header">Action</th>';
                echo '        </tr>';

                while ($row = $result->fetch_assoc()) {
                    echo '        <tr>';
                    echo '            <td class="table-cell"><h3>' . $row["material_name"] . '</h3></td>';
                    echo '            <td class="table-cell"><h3>' . $row["type"] . '</h3></td>';
                    echo '            <td class="table-cell"><h3>' . $row["quantity"] . '</h3></td>';
                    echo '            <td class="table-cell"><h3>' . $row["cost"] . '</h3></td>';
                    echo '            <td class="table-cell"><h3>' . $row["total_cost"] . '</h3></td>';
                    echo '            <td class="table-cell">';
                    echo '                <button onclick="openEditModal()">Edit</button>'; // Edit button
                    echo '                <button onclick="deleteItem()">Delete</button>'; // Delete button
                    echo '            </td>';            
                    echo '        </tr>';
                }

                echo '</table>';
                echo '</div>';

            } else {
                echo '<p>No materials found.</p>';
            }

            $conn->close();

        } else {
            echo "Error: requirement_ID is missing.";
        }
        ?>
    </div>


    <div class="product-container">
      <h2 style="text-align:center">Labor</h2>
      <?php
        include('config.php');


        // Query to select all columns from the prematerials table
        $sql = "SELECT * FROM labor";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<div class="table-container">';
            echo '    <table class="styled-table">';
            echo '        <tr>';
            echo '            <th class="table-header">Carpenter Name</th>';
            echo '            <th class="table-header">Type of Work</th>';
            echo '            <th class="table-header">Days of Work</th>';
            echo '            <th class="table-header">Rate</th>';
            echo '            <th class="table-header">Total</th>';
            echo '            <th class="table-header">Additional Cost</th>';
            echo '            <th class="table-header">Task</th>';
            echo '            <th class="table-header">Overall Cost</th>';
            echo '        </tr>';

            while ($row = $result->fetch_assoc()) {
                echo '        <tr>';
                echo '            <td class="table-cell"><h3>' . $row["carpenter_name"] . '</h3></td>';
                echo '            <td class="table-cell"><h3>' . $row["type_of_work"] . '</h3></td>';
                echo '            <td class="table-cell"><h3>' . $row["days_of_work"] . '</h3></td>';
                echo '            <td class="table-cell"><h3>' . $row["rate"] . '</h3></td>';
                echo '            <td class="table-cell"><h3>' . $row["total_of_laborcost"] . '</h3></td>';
                echo '            <td class="table-cell"><h3>' . $row["additional_cost"] . '</h3></td>';
                echo '            <td class="table-cell"><h3>' . $row["task"] . '</h3></td>';
                echo '            <td class="table-cell"><h3>' . $row["overall_cost"] . '</h3></td>';
                echo '        </tr>';
            }

            echo '    </table>';
            echo '</div>';
        } else {
            echo '<p>No materials found.</p>';
        }
      ?>
    </div>

    <div class="product-container">
        <h2 style="text-align:center">Signed Contract</h2>

        <?php
        include('config.php');

        if (isset($_GET['requirement_ID'])) {
            $requirementID = $_GET['requirement_ID'];

            // Query to fetch signed contract details with client's full name and created_at
            $sql = "SELECT sc.requirement_ID, u.First_Name, u.Last_Name, sc.contractor_name, 
                            sc.length_lot_area, sc.width_lot_area, sc.square_meter_lot, 
                            sc.length_floor_area, sc.width_floor_area, sc.square_meter_floor, 
                            sc.type, sc.initial_budget, sc.Photo, 
                            sc.start_date, sc.end_date, sc.created_at 
                    FROM signed_contracts sc
                    JOIN users u ON sc.client_ID = u.user_ID
                    WHERE sc.requirement_ID = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $requirementID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($signed_contract = mysqli_fetch_assoc($result)) {
                $clientName = htmlspecialchars($signed_contract['First_Name'] . " " . $signed_contract['Last_Name']);
                $formattedCreatedAt = date("F j, Y - g:i A", strtotime($signed_contract['created_at'])); // Format Date & Time

                // Display Contract Button
                echo "<h3>Contract:</h3>";
                echo "<div style='text-align: center; margin-top: 20px;'>
                        <button onclick='openSignedContractModal()' 
                          style='padding: 12px 20px; font-size: 16px; 
                                  font-weight: bold; color: white; background-color: #007BFF; 
                                  border: none; cursor: pointer; border-radius: 5px; 
                                  box-shadow: 2px 2px 5px rgba(0,0,0,0.2);'>
                            View Signed Contract
                        </button>
                      </div>";

                // Signed Contract Modal
                echo "<div id='signedContractModal' class='signedcontract-modal'>
                        <div class='signedcontract-modal-content'>
                            <span onclick='closeSignedContractModal()' class='close-modal'>&times;</span>
                            <h2>Signed Construction Agreement</h2>

                            <div class='signedcontract-container'>
                                <p><strong>Requirement ID:</strong> {$signed_contract['requirement_ID']}</p>

                                <p class='signedcontract-text'>
                                    This agreement is made between <span class='highlight'>" . $clientName . "</span> (Client) and <span class='highlight'>" . htmlspecialchars($signed_contract['contractor_name']) . "</span> (Contractor), regarding the construction project with the following details:
                                </p>

                                <p><strong>Lot Area:</strong> {$signed_contract['length_lot_area']}m x {$signed_contract['width_lot_area']}m ({$signed_contract['square_meter_lot']} sqm)</p>
                                <p><strong>Floor Area:</strong> {$signed_contract['length_floor_area']}m x {$signed_contract['width_floor_area']}m ({$signed_contract['square_meter_floor']} sqm)</p>
                                <p><strong>Project Type:</strong> {$signed_contract['type']}</p>
                                <p><strong>Initial Budget:</strong> PHP " . number_format($signed_contract['initial_budget'], 2) . "</p>

                                <div class='signedcontract-photo'>
                                    <h3>Project Photo</h3>";
                                    if (!empty($signed_contract['Photo']) && file_exists($signed_contract['Photo'])) {
                                        echo "<a href='#' onclick='openPhotoModal(\"" . htmlspecialchars($signed_contract['Photo']) . "\")'>
                                                <img src='" . htmlspecialchars($signed_contract['Photo']) . "' alt='Project Photo' class='signedcontract-img'>
                                              </a>";
                                    } else {
                                        echo "<p>No project photo available.</p>";
                                    }
                                echo "</div>

                                <p class='signedcontract-text'>
                                    The project is approved by <span class='highlight'>" . htmlspecialchars($signed_contract['contractor_name']) . "</span> and will proceed according to the agreed terms.
                                </p>

                                <p><strong>Start Date:</strong> " . date("F j, Y", strtotime($signed_contract['start_date'])) . "</p>
                                <p><strong>End Date:</strong> " . date("F j, Y", strtotime($signed_contract['end_date'])) . "</p>

                                <p class='signedcontract-text'>
                                    Both parties have agreed to the terms and conditions. The contractor is responsible for completing the project within the agreed timeframe and budget.
                                </p>

                                <p><strong>Signed By:</strong> <span class='highlight'>" . $clientName . "</span></p>
                                <p><strong>Signed on:</strong> <span class='highlight'>" . $formattedCreatedAt . "</span></p>
                            </div>
                        </div>
                      </div>";

                mysqli_stmt_close($stmt);
            } else {
                echo "<p style='color: red;'>No signed contract found for this requirement.</p>";
            }
        } else {
            echo "<p style='color: red;'>No requirement ID provided.</p>";
        }

        mysqli_close($conn);
        ?>
    </div>


  <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('signedContractModal').style.display = 'none';
    });

    function openSignedContractModal() {
        document.getElementById('signedContractModal').style.display = 'flex';
    }

    function closeSignedContractModal() {
        document.getElementById('signedContractModal').style.display = 'none';
    }

    function openPhotoModal(photoUrl) {
        window.open(photoUrl, '_blank');
    }
</script>



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
</body>

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
<script>
        // Wait until the DOM is fully loaded
        document.addEventListener("DOMContentLoaded", function() {
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
        });
</script>
</html>
  