<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress</title>
</head>
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
        font-size: 20px;
        border-left: 20px solid transparent; 
        padding-left: 20px; /* Adjust padding */
        text-decoration: none;
    }

    .right {
        margin-right: 20px;
    }

    .header a {
        font-size: 20px;
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
        font-size: 20px;
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
        font-size: 20px;
        cursor: pointer;
        margin: 20px;
        border-radius: 4px;
    }

    #addLaborBtn {
        background-color: #FF8C00;
        color: #FFFFFF;
        border: none;
        padding: 10px 20px;
        font-size: 20px;
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

    /* Modal Styles */
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
        font-size: 20px;
    }

    .modal-content form div {
        margin-bottom: 15px;
    }

    .modal-content form label {
        display: block;
        font-size: 20px;
        font-weight: bold;
        text-align: left;
        margin-bottom: 5px;
    }

    .modal-content form input,
    .modal-content form textarea,
    .modal-content form select {
        width: 100%;
        padding: 8px;
        font-size: 20px;
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
        font-size: 20px;
        cursor: pointer;
        border-radius: 4px;
    }

    .modal-content form button:hover {
        background-color: #FFA500;
    }

    /* Edit Modal Styles */
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
        font-size: 20px;
        margin-bottom: 20px;
        color: #333;
    }

    .row-container h3 {
        font-size: 20px;
        margin-bottom: 10px;
        color: #555;
    }

    label {
        display: block;
        font-size: 20px;
        margin-bottom: 5px;
        color: #777;
    }

    input[type='text'] {
        width: 100%;
        padding: 8px;
        font-size: 20px;
        color: #000;
        background-color: #fff;
        border-radius: 4px;
        border: 1px solid #ccc;
        margin-bottom: 10px;
    }

    p {
        margin-bottom: 10px;
        font-size: 20px;
        color: #333;
    }

    img {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
    }

    /* Sort */
    .sort {
        display: inline-block;
        margin-bottom: 10px;
        margin-left: 40px;
    }

    .sort select {
        padding: 8px;
        font-size: 20px;
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
        border-bottom: 1px solid #ddd;
    }

    .styled-table th,
    .styled-table td {
        padding: 12px;
        text-align: center;
        font-size: 20px;
        border: 1px solid #ddd;
    }

    .styled-table td {
        color: #333;
    }

    .styled-table tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .styled-table tr:nth-child(odd) {
        background-color: #fff;
    }

    .action-buttons {
        display: flex;
        justify-content: space-around;
    }

    .action-buttons button {
        background-color: #FF8C00;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        font-size: 20px;
        border-radius: 4px;
    }

    .action-buttons button:hover {
        background-color: #FFA500;
    }
</style>

<?php
// Check if the success parameter is passed in the URL
if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_GET['message'])) {
    // Sanitize the message to prevent XSS
    $message = htmlspecialchars($_GET['message']);
    // Output the alert with the message
    echo "<script>alert('$message');</script>";
}

//Attendance
if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_GET['action'])) {
    $action = htmlspecialchars($_GET['action']);
    
    // Determine the message based on the action
    if ($action == 'time_in') {
        echo "<script>alert('Time In recorded successfully!');</script>";
    } elseif ($action == 'time_out') {
        echo "<script>alert('Time Out recorded successfully!');</script>";
    }
}

if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_GET['action']) && $_GET['action'] == 'time_out') {
    echo "<script>alert('Time Out recorded successfully!');</script>";
}
?>

<head>
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
</head>
<body>

<?php
    include('config.php');

    if (isset($_GET['requirement_ID'])) {
        $requirement_ID = $_GET['requirement_ID'];

        $query = "SELECT * FROM projectrequirements WHERE requirement_ID = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $requirement_ID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
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
            
            echo "<table style='border-collapse: collapse; width: 100%;'>";
              echo "<thead>";
                          echo "<tr>";
                              echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Part</th>";
                              echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Materials</th>";
                              echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Name</th>";
                              echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Quantity</th>";
                              echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Price</th>";
                              echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Total</th>";
                              echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Estimated Cost</th>";

                          echo "</tr>";
                      echo "</thead>";
                  echo "<tbody>";
                  
                  $query_materials = "SELECT * FROM prematerials";
                  $stmt_materials = mysqli_prepare($conn, $query_materials);
                  mysqli_stmt_execute($stmt_materials);
                  $result_materials = mysqli_stmt_get_result($stmt_materials);
                  
                  while ($material_row = mysqli_fetch_assoc($result_materials)) {
                      echo "<tr>";
                          echo "<td style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>" . htmlspecialchars($material_row['part']) . "</td>";
                          echo "<td style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>" . htmlspecialchars($material_row['materials']) . "</td>";
                          echo "<td style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>" . htmlspecialchars($material_row['name']) . "</td>";
                          echo "<td style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>" . htmlspecialchars($material_row['quantity']) . "</td>";
                          echo "<td style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>" . htmlspecialchars($material_row['price']) . "</td>";
                          echo "<td style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>" . htmlspecialchars($material_row['total']) . "</td>";
                          echo "<td style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>" . htmlspecialchars($material_row['estimated_cost']) . "</td>";
                      echo "</tr>";
                  }
                  
                  echo "</tbody>";
              echo "</table>";

              echo '<table style="border-collapse: collapse; width: 100%;">';
                echo '<thead>';
                    echo '<tr>';
                        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Materials</th>';
                        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Type</th>';
                        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Image</th>';
                        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Quantity</th>';
                        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Price</th>';
                        echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Total</th>';
                    echo '</tr>';
                echo '</thead>';
              echo '<tbody>';
                  
                  //requiredmaterials
                  $query_materials = "SELECT * FROM requiredmaterials";
                  $stmt_materials = mysqli_prepare($conn, $query_materials);
                  mysqli_stmt_execute($stmt_materials);
                  $result_materials = mysqli_stmt_get_result($stmt_materials);
                  
                  while ($material_row = mysqli_fetch_assoc($result_materials)) {
                      echo '<tr>';
                          echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['material']) . '</td>';
                          echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['type']) . '</td>';
                          echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">';
                              echo '<img src="' . htmlspecialchars($material_row['image']) . '" alt="Material Image" style="max-width: 100px; max-height: 100px;">';
                          echo '</td>';
                          echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['quantity']) . '</td>';
                          echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['price']) . '</td>';
                          echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['total']) . '</td>';
                      echo '</tr>';
                  }
                  
              echo '</tbody>';
            echo '</table>';

        // Display the resized photo with a link to open the modal
        $photoPath = $row['Photo'];
        if (!empty($photoPath) && file_exists($photoPath)) {
            echo "<p>Photo:</p>";
            echo "<a href='#' onclick='openModal(\"{$photoPath}\")'>";
            echo "<img src='{$photoPath}' alt='Plan Photo' style='width: 900px; height: 400px;'>";
            echo "</a>";
        }
               
        echo "<p>Labor Cost: <input type='text' value='{$row['labor_cost']}' readonly></p>";

        // Check if the file exists and display it
        $contractPath = $row['contract'];
        if (file_exists($contractPath)) {
            // Display the contract file as a link
            echo "<p>Contract File: <a href='" . htmlspecialchars($contractPath) . "' target='_blank'>View Contract</a></p>";
        
            // Optionally, embed the file if it's a PDF
            if (pathinfo($contractPath, PATHINFO_EXTENSION) === 'pdf') {
                echo "<embed src='" . htmlspecialchars($contractPath) . "' type='application/pdf' width='600' height='400'>";
            }
        } else {
            echo "<p>Contract file not found.</p>";
        }
        
            echo "</form>";
            echo "</div>"; 

        } else {
            echo "<div class='main'>";
            echo "<p>No approved plan found with Approved Plan ID: $requirement_ID</p>";
            echo "</div>"; 
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        echo "<div class='main'>";
        echo "<p>Approved Plan ID is missing.</p>";
        echo "</div>"; 
    }

?>
  <div class="product-container">
        <h2 style="text-align:center">Signed Contract</h2>
        <?php
        include('config.php');

        // Assuming you have a valid $requirementID from the query string or another source
        if (isset($_GET['requirement_ID'])) {
            $requirementID = $_GET['requirement_ID'];

            // Query to fetch the signed contract for the given requirement_ID
            $sql = "SELECT * FROM signedcontract WHERE requirement_ID = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $requirementID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // Check if the contract exists for this requirement ID
            if ($row = mysqli_fetch_assoc($result)) {
                $contractPath = $row['signedcontract'];  // Path to the uploaded file

                // Check if the file exists
                if (file_exists($contractPath)) {
                    // Display the contract file as a link
                    echo "<p>Contract File: <a href='" . htmlspecialchars($contractPath) . "' target='_blank'>View Contract</a></p>";

                    // Optionally, embed the file if it's a PDF
                    if (pathinfo($contractPath, PATHINFO_EXTENSION) === 'pdf') {
                        echo "<embed src='" . htmlspecialchars($contractPath) . "' type='application/pdf' width='600' height='400'>";
                    }
                } else {
                    echo "<p>Contract file not found.</p>";
                }
            } else {
                echo "<p>No signed contract found for this requirement.</p>";
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            echo "<p>No requirement ID provided.</p>";
        }

        // Close the database connection
        mysqli_close($conn);
        ?>
  </div>
<h2 style="text-align:center">Progress and Attendance</h2>

<button id="addMaterialsBtn">Add Progress</button>

<div id="addMaterialsModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add progress</h2>
        <form id="addMaterialsForm" method="post" action="addprogress.php" enctype="multipart/form-data">
            <div>
                <label for="Name">Name</label>
                <input type="text" id="material_name" name="Name" required>
            </div>
            <div>
                <label for="Status">Status</label>
                <select id="status" name="Status" required>
                    <option value="Not yet started">Not yet started</option>
                    <option value="Working">Working</option>
                    <option value="Done">Done</option>
                </select>
            </div>
            <div>
                <label for="Materials">Materials Used</label>
                <input type="text" id="Materials" name="Materials" required>
            </div>
            <div>
                <label for="Material_cost">Material Cost:</label>
                <input type="number" id="Material_cost" name="Material_cost" required>
            </div>
            <!-- Hidden input field for requirement_ID -->
            <input type="hidden" name="requirement_ID" value="<?php echo isset($_GET['requirement_ID']) ? $_GET['requirement_ID'] : ''; ?>">
            <button type="submit">Add Progress</button>
        </form>
    </div>
</div>


  <button id="addLaborBtn">Add Attendance</button>

  <div id="addLaborModal" class="modal" style="display: none;">
      <div class="modal-content">
          <span class="close">&times;</span>
          <h2>Attendance</h2>
          <form id="addLaborForm" method="post" action="addattendance.php" enctype="multipart/form-data">

              <div>
                  <label for="type_of_work">Type of work:</label>
                  <select id="type_of_work" name="Type_of_work">
                      <option value="Inadlaw">Inadlaw</option>
                      <option value="Pakyawan">Pakyawan</option>
                  </select>
              </div>
              <div>
                  <label for="Time_in">Time-in:</label>
                  <input type="Time_in" id="Time_in" name="Time_in" required readonly>
                  <button onclick="addTimeStamp()">Add Timestamp</button>
              </div>
              <div>
                  <label for="overall_cost">Time-out</label>
                  <input type="number" id="overall_cost" name="overall_cost" step="0.01" min="0" readonly>
              </div>

              <!-- Hidden input field for requirement_ID -->
              <input type="hidden" name="requirement_ID" value="<?php echo isset($_GET['requirement_ID']) ? $_GET['requirement_ID'] : ''; ?>">

              <button type="submit">Add Attendance</button>
          </form>
      </div>
  </div>
  <!--TABLE-->
  <div class="product-container">
      <h2 style="text-align:center">Progress</h2>
      <?php
          include('config.php');


          // Check if requirement_ID is provided in the URL parameter
          if(isset($_GET['requirement_ID'])) {
              $requirementID = $_GET['requirement_ID'];

              // Prepare the SQL query with a WHERE clause to filter by requirement_ID
              $sql = "SELECT * FROM report WHERE requirement_ID = $requirementID";

              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                  echo '<div class="table-container">';
                  echo '    <table class="styled-table">';
                  echo '        <tr>';
                  echo '            <th class="table-header">Name</th>';
                  echo '            <th class="table-header">Status</th>';
                  echo '            <th class="table-header">Materials</th>';
                  echo '            <th class="table-header">Cost</th>';
                  echo '        </tr>';

                  while ($row = $result->fetch_assoc()) {
                      echo '        <tr>';
                      echo '            <td class="table-cell"><h3>' . $row["Name"] . '</h3></td>';
                      echo '            <td class="table-cell"><h3>' . $row["Status"] . '</h3></td>';
                      echo '            <td class="table-cell"><h3>' . $row["Materials"] . '</h3></td>';
                      echo '            <td class="table-cell"><h3>' . $row["Material_cost"] . '</h3></td>';
                      echo '        </tr>';
                  }

                  echo '</table>';
                  echo '</div>';

              } else {
                  echo '<p>No Progress yet</p>';
              }
          } else {
              echo '<p>Error: requirement_ID is missing.</p>';
          }
      ?>
  </div>

  <div class="product-container">
    <h2 style="text-align:center">Attendance</h2>
    <?php
        include('config.php');

        // Check if requirement_ID is provided in the URL parameter
        if(isset($_GET['requirement_ID'])) {
            $requirementID = $_GET['requirement_ID'];

            // Prepare the SQL query with a WHERE clause to filter by requirement_ID
            $sql = "SELECT * FROM attendance WHERE requirement_ID = $requirementID";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<div class="table-container">';
                echo '    <table class="styled-table">';
                echo '        <tr>';
                echo '            <th class="table-header">Type</th>';
                echo '            <th class="table-header">Time in</th>';
                echo '            <th class="table-header">Time out</th>';
                echo '            <th class="table-header">Action</th>';
                echo '        </tr>';

                while ($row = $result->fetch_assoc()) {
                    echo '        <tr>';
                    echo '            <td class="table-cell"><h3>' . $row["Type_of_work"] . '</h3></td>';
                    echo '            <td class="table-cell"><h3>' . $row["Time_in"] . '</h3></td>';
                    echo '            <td class="table-cell"><h3>' . $row["Time_out"] . '</h3></td>';
                    echo '            <td class="table-cell">';
                    echo '              <button type="button" onclick="openModal()">Add Time out</button>';
                    echo '            </td>';           
                    echo '        </tr>';
                }

                echo '    </table>';
                echo '</div>';

            } else {
                echo '<p>No attendance yet.</p>';
            }
        } else {
            echo '<p>Error: requirement_ID is missing.</p>';
        }

        $conn->close();
    ?>
                <!-- Hidden modal form -->
        <div id="timeOutModal" class="modal">
          <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Add Time out</h2>
            <form id="timeOutForm" action="update_time_out.php" method="post">
              <div>
                <label for="timeOutField">Time out:</label>
                <input type="text" id="timeOutField" name="Time_out" readonly>
              </div>
                <input type="hidden" name="requirement_ID" value="<?php echo isset($_GET['requirement_ID']) ? $_GET['requirement_ID'] : ''; ?>">
              <button type="button" onclick="addTimestamp()">Add Timestamp</button>
              <button type="submit">Save</button>
            </form>
          </div>
        </div>
  </div>
</body>
<script>
  // Function to open the modal
function openModal() {
  document.getElementById('timeOutModal').style.display = 'block';
}

// Function to close the modal
function closeModal() {
  document.getElementById('timeOutModal').style.display = 'none';
}

// Function to add timestamp to the time out field
function addTimestamp() {
  var currentDate = new Date();
  var formattedDateTime = currentDate.toLocaleString(); // Format timestamp as desired
  document.getElementById('timeOutField').value = formattedDateTime;
}
</script>
<script>
function addTimeStamp() {
    // Get the current date and time
    var currentDate = new Date();
    
    // Format the date and time as desired
    var formattedDateTime = currentDate.toLocaleString(); // You can adjust the format based on your preference
    
    // Get the input element
    var additionalCostInput = document.getElementById("Time_in");
    
    // Set the input value to the formatted timestamp
    additionalCostInput.value = formattedDateTime;
}
</script>
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