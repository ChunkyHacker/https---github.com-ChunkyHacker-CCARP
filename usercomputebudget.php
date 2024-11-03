<?php
    include('config.php');
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

  /* Style the body */
  body {
    font-family: Arial, Helvetica, sans-serif;
    margin: 0;
    padding-top: 180px;
  }

  /* Header*/
  .header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    padding: 10px;
    text-align: left;
    background: #FF8C00;
    color: #000000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-decoration: none;
    z-index: 100;
  }

  /* Increase the font size of the heading */
  .header h1 {
    font-size: 40px;
    border-left: 20px solid transparent; 
    padding-left: 20px; /* Adjust padding */
    text-decoration: none;
  }

  .right {
    margin-right: 20px;
  }

  .header a{
    font-size: 25px;
    font-weight: bold;
    text-decoration: none;
    color: #000000;
  }

  .topnav {
    position: fixed;
    top: 120px; /* Adjust the top position as per your needs */
    width: 100%;
    overflow: hidden;
    background-color: #505050;
    z-index: 100;
  }

  /* Style the links inside the navigation bar */
  .topnav a {
    position: sticky;
    float: left;
    display: block;
    color: black;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    font-size: 30px;
  }

  .topnav a,
  .topnav a.active {
    color: black;
  }

  .topnav a:hover,
  .topnav a.active:hover {
    background-color: #FF8C00;
    color: black;
  }


  /* When the screen is less than 600px wide, stack the links and the search field vertically instead of horizontally */
  @media screen and (max-width: 600px) {
    .topnav a, .topnav input[type=text] {
      float: none;
      display: block;
      text-align: left;
      width: 100%;
      margin: 0;
      padding: 14px;
    }
    .topnav input[type=text] {
      border: 1px solid #ccc;
    }
  }

  #addMaterialsBtn {
    background-color: #FF8C00;
    color: #FFFFFF;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    margin: 20px;
    border-radius: 4px;
  }

  #addLaborBtn {
    background-color: #FF8C00;
    color: #FFFFFF;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    margin: 20px;
    border-radius: 4px;
  }

  #addLaborBtn:hover {
    background-color: #FFA500;
  }

  #addMaterialBtn:hover {
    background-color: #FFA500;
  }

  Materials Styles */Materials {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
  }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000; /* Ensure modal appears on top of other elements */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #f2f2f2;
        position: fixed;
        top: 50%; /* Center modal vertically */
        left: 50%; /* Center modal horizontally */
        transform: translate(-50%, -50%); /* Move modal back to center */
        padding: 20px;
        border: 1px solid #888;
        width: 70%;
        border-radius: 5px;
        z-index: 1001; /* Ensure modal content appears on top of the modal background */
    }

    .close {
        color: #aaa;
        position: absolute;
        top: 0;
        right: 0;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        z-index: 1002; /* Ensure close button appears on top of modal content */
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    /* Rest of your styles remain unchanged */
    .modal-content h2 {
        margin-bottom: 20px;
    }

    .modal-content form div {
        margin-bottom: 15px;
    }

    .modal-content form label {
        display: block;
        font-size: 16px;
        font-weight: bold;
        text-align: left;
        margin-bottom: 5px;
    }

    .modal-content form input,
    .modal-content form textarea,
    .modal-content form select {
        width: 100%;
        padding: 8px;
        font-size: 16px;
        color: #000;
        background-color: #fff;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .modal-content form select {
        width: 100%;
    }

    .modal-content form button {
        background-color: #FF8C00;
        color: #FFFFFF;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 4px;
    }

    .modal-content form button:hover {
        background-color: #FFA500;
    }

/* Modal Styles */
  .editModal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: none;
    z-index: 1001; /* Ensure a higher z-index than the table */
  }

  .edit-modal-content {
    background-color: #f2f2f2;
    padding: 30px;
    border: 1px solid #888;
    max-width: 70%; /* Use max-width instead of width */
    width: auto; /* Adjusted width property */
    border-radius: 5px;
    position: absolute; /* Use absolute positioning */
    top: 50%; /* Set top to 50% */
    left: 50%; /* Set left to 50% */
    transform: translate(-50%, -50%); /* Center the modal */
  }

  /* Main column */
  .main {   
    margin: auto;
    width: 70%; /* Adjusted width for better visibility */
    padding: 20px;
    text-align: left;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  }

  .main h1 {
    font-size: 32px;
    margin-bottom: 20px;
    color: #333;
    }

    .row-container h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #555;
        }

        label {
            display: block;
            font-size: 18px;
            margin-bottom: 5px;
            color: #777;
        }

        input[type='text'] {
            width: 100%;
            padding: 8px;
            font-size: 16px;
            color: #000;
            background-color: #fff;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        p {
            margin-bottom: 10px;
            font-size: 18px;
            color: #333;
        }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }


  /*Sort*/
  .sort {
    display: inline-block;
    margin-bottom: 10px;
    margin-left: 40px;
  }

  .sort select {
    padding: 8px;
    font-size: 16px;
    color: #000000;
    background-color: #ffffff;
    border-radius: 4px;
  }

  .sort select:focus {
    outline: none;
    box-shadow: 0 0 8px 0 rgba(0, 0, 0, 0.2);
  }

  /* Table styling */
  .table-container {
    border-radius: 8px;
    overflow: hidden;
    margin-top: 20px; /* Adjusted margin-top for spacing */
    margin-bottom: 40px; /* Added margin-bottom for space below the table */
    padding:50px;
  }

  .styled-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #333;
  }

  .table-header {
    padding: 12px;
    text-align: center;
    background-color: #FF8C00; /* Orange color */
    color: black; /* Text color */
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

  .styled-table tr {
    border-bottom: 1px solid #333; /* Added border for table rows */
  }

  .styled-table th, .styled-table td {
    border-right: 1px solid #333; /* Added border for table columns */
  }

  /* Footer */
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

  /* Responsive layout - when the screen is less than 700px wide, make the two columns stack on top of each other instead of next to each other */
  @media screen and (max-width: 700px) {
    .row {   
      flex-direction: column;
    }
    body {
    font-family: Arial, Helvetica, sans-serif;
    margin: 0;
    padding-top: 300px;
  }
  }

  /* Responsive layout - when the screen is less than 400px wide, make the navigation links stack on top of each other instead of next to each other */
  @media screen and (max-width: 400px) {
    .navbar a {
      float: none;
      width: 100%;
    }
  }

  @media (max-width: 768px) {
    .product-card {
      width: calc(50% - 20px);
    }
  }

  @media (max-width: 480px) {
    .product-card {
      width: calc(100% - 20px);
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
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $requirement_ID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        echo "<div class='main'>";
        echo "<h1>Client's Plan Details</h1>";

        $userId = $row['User_ID'];
        $userQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
        $userStmt = mysqli_prepare($connection, $userQuery);
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
        
        
        // Display the resized photo with a link to open the modal
        $photoPath = $row['Photo'];
        if (!empty($photoPath) && file_exists($photoPath)) {
          echo "<p>Photo:</p>";
          echo "<a href='#' onclick='openModal(\"{$photoPath}\")'>";
          echo "<img src='{$photoPath}' alt='Plan Photo' style='width: 900px; height: 400px;'>";
          echo "</a>";
        }
        
        echo "<p>Labor Cost: <input type='text' value='{$row['labor_cost']}' readonly></p>";
        
        echo "</form>";
        echo "</div>"; 
        
      } else {
        echo "<div class='main'>";
        echo "<p>No approved plan found with Approved Plan ID: $requirement_ID</p>";
        echo "</div>"; 
      }
      
      mysqli_close($connection);
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
                    <label for="total_of_laborcost">Total of Labor cost</label>
                    <input type="number" id="total_of_laborcost" name="total_of_laborcost" required readonly>
                </div>
                <div>
                    <label for="additional_cost">Additional Cost: (Optional)</label>
                    <input type="number" id="additional_cost" name="additional_cost" step="0.01" min="0" required>
                </div>
                <div>
                    <label for="overall_cost">Overall Cost: (Rate, Days and Labor)</label>
                    <input type="number" id="overall_cost" name="overall_cost" step="0.01" min="0" required readonly>
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
        $host = "localhost";
        $username = "root";
        $password = "";
        $dbname = "ccarpcurrentsystem";

        $conn = new mysqli($host, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

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
        $host = "localhost";
        $username = "root";
        $password = "";
        $dbname = "ccarpcurrentsystem";

        $conn = new mysqli($host, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

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
                echo '            <td class="table-cell"><h3>' . $row["overall_cost"] . '</h3></td>';
                echo '        </tr>';
            }

            echo '    </table>';
            echo '</div>';
        } else {
            echo '<p>No materials found.</p>';
        }
      ?>



    <div class="product-container">
        <?php
        // Database connection settings
        $host = "localhost";
        $username = "root";
        $password = "";
        $dbname = "ccarpcurrentsystem";

        // Create a new connection
        $conn = new mysqli($host, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query to retrieve total cost
        $transactionQuery = "SELECT SUM(total_price) AS total_budget_cost FROM transaction";
        $totalResult = $conn->query($transactionQuery);
        $totalprice = 0;
        if ($totalResult->num_rows > 0) {
            $totalPriceRow = $totalResult->fetch_assoc();
            $totalPriceRow = $totalPriceRow["total_budget_cost"];
        }

        // Query to retrieve total material cost
        $materialsQuery = "SELECT SUM(total_cost) AS total_material_cost FROM constructionmaterials";
        $materialsResult = $conn->query($materialsQuery);
        $totalMaterialCost = 0;
        if ($materialsResult->num_rows > 0) {
            $totalMaterialCostRow = $materialsResult->fetch_assoc();
            $totalMaterialCost = $totalMaterialCostRow["total_material_cost"];
        }

        // Query to retrieve total labor cost
        $laborQuery = "SELECT SUM(total_of_laborcost) AS total_labor_cost FROM labor";
        $laborResult = $conn->query($laborQuery);
        $totalLaborCost = 0;
        if ($laborResult->num_rows > 0) {
            $totalLaborCostRow = $laborResult->fetch_assoc();
            $totalLaborCost = $totalLaborCostRow["total_labor_cost"];
        }

        // Calculate overall total cost
        $overallTotalCost = + $totalMaterialCost + $totalLaborCost + $totalprice;

        // Close the database connection
        $conn->close();
        
        // Display total cost information
        echo '<div class="table-container">';
        echo '    <table class="styled-table">';
        echo '        <tr>';
        echo '            <td class="table-header">Total Bought Materials</td>';
        echo '            <td class="table-header">Total Material Cost</td>';
        echo '            <td class="table-header">Total Labor Cost</td>';
        echo '            <td class="table-header">Over all total cost</td>';
        echo '        </tr>';
        echo '        <tr>';
        echo '            <td class="table-cell"><h3>' . $totalPriceRow . '</h3></td>';
        echo '            <td class="table-cell"><h3>' . $totalMaterialCost . '</h3></td>';
        echo '            <td class="table-cell"><h3>' . $totalLaborCost . '</h3></td>';
        echo '            <td class="table-cell"><h3>' . $overallTotalCost . '</h3></td>';
        echo '        </tr>';
        echo '    </table>';
        echo '</div>';
        ?>
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

  <div class="footer">
      <h2>E-Panday all rights reserved @2023</h2>
      <a href="#">Link 1</a> |
      <a href="#">Link 1</a> |
      <a href="#">Link 1</a> |
      <a href="#">Link 1</a>
  </div>
</body>

<script>
    //labormaterialmodal.js
    var materialsModal = document.getElementById('addMaterialsModal');
    var laborModal = document.getElementById('addLaborModal');
    var materialsBtn = document.getElementById('addMaterialsBtn');
    var laborBtn = document.getElementById('addLaborBtn');
    var closeBtns = document.getElementsByClassName('close');

    materialsBtn.onclick = function() {
        materialsModal.style.display = 'block';
    }

    laborBtn.onclick = function() {
        laborModal.style.display = 'block';
    }

    for (var i = 0; i < closeBtns.length; i++) {
        closeBtns[i].onclick = function() {
            materialsModal.style.display = 'none';
            laborModal.style.display = 'none';
        }
    }

    window.onclick = function(event) {
        if (event.target == materialsModal || event.target == laborModal) {
            materialsModal.style.display = 'none';
            laborModal.style.display = 'none';
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

        // Calculate the overall cost once on page load
        function calculateOverallCost() {
            const totalLaborCost = parseFloat(totalLaborCostInput.value) || 0;
            const additionalCost = parseFloat(additionalCostInput.value) || 0;

            // Calculate the overall cost
            const overallCost = totalLaborCost + additionalCost;

            // Update the overall cost input field
            overallCostInput.value = overallCost.toFixed(2); // Displaying up to 2 decimal places
        }

        // Initial calculation on page load
        calculateOverallCost();
</script>
</html>
  