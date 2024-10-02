<?php
session_start();
    include('config.php');

    $requirement_ID = $_GET['requirement_ID'];
    $User_ID = $_SESSION['User_ID'];


    $query1 = "SELECT *FROM requirements WHERE requirement_ID = '$requirement_ID'";
    $result1 = mysqli_query($connection, $query1);
    $requirementData = mysqli_fetch_assoc($result1);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

  .button-link {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        color: white;
        background-color: #007bff;
        text-align: center;
        text-decoration: none;
        border-radius: 4px;
        border: none;
        cursor: pointer;
    }

    .button-link:hover {
        background-color: #0056b3;
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
    require_once "config.php";

    if (isset($_GET['requirement_ID'])) {
        $requirement_ID = $_GET['requirement_ID'];

        $query = "SELECT * FROM requirements WHERE requirement_ID = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $requirement_ID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
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
            echo "<h3>Estimated Cost</h3>";
            echo "<label for='estimated_cost'>Estimated Cost</label>";
            echo "<input type='text' id='estimated_cost' name='estimated_cost' value='{$row['estimated_cost']}' readonly><br>";
            echo "</div>";

            echo "<div class=\"row-container\">";
            echo "<h3>Project Dates</h3>";
            echo "<p>Start Date: <input type='text' value='{$row['start_date']}' readonly></p>";
            echo "<p>End Date: <input type='text' value='{$row['end_date']}' readonly></p>";        
            echo "</div>";
            
            echo "<p>Type: <input type='text' value='{$row['type']}' readonly></p>";

            echo "<table style='border-collapse: collapse; width: 100%;'>";
            echo "<thead>";
                        echo "<tr>";
                            echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Part</th>";
                            echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Materials</th>";
                            echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Name</th>";
                            echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Quantity</th>";
                            echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Price</th>";
                            echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Total</th>";
                        echo "</tr>";
                    echo "</thead>";
                echo "<tbody>";
                
                $query_materials = "SELECT * FROM prematerials";
                $stmt_materials = mysqli_prepare($connection, $query_materials);
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
                $stmt_materials = mysqli_prepare($connection, $query_materials);
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

            echo "</form>";
            echo "</div>"; 

        } else {
            echo "<div class='main'>";
            echo "<p>No approved plan found with Approved Plan ID: $requirement_ID</p>";
            echo "</div>"; 
        }

        mysqli_stmt_close($stmt);
        mysqli_close($connection);
    } else {
        echo "<div class='main'>";
        echo "<p>Approved Plan ID is missing.</p>";
        echo "</div>"; 
    }
?>

<h2 style="text-align:center">Progress and Attendance</h2>
  <!--TABLE-->
  <div class="product-container">
      <h2 style="text-align:center">Progress</h2>
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
              $sql = "SELECT * FROM progress WHERE requirement_ID = $requirementID";
              

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
            $sql = "SELECT * FROM attendance WHERE requirement_ID = $requirementID";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<div class="table-container">';
                echo '    <table class="styled-table">';
                echo '        <tr>';
                echo '            <th class="table-header">Type</th>';
                echo '            <th class="table-header">Time in</th>';
                echo '            <th class="table-header">Time out</th>';
                echo '        </tr>';

                while ($row = $result->fetch_assoc()) {
                    echo '        <tr>';
                    echo '            <td class="table-cell"><h3>' . $row["Type_of_work"] . '</h3></td>';
                    echo '            <td class="table-cell"><h3>' . $row["Time_in"] . '</h3></td>';
                    echo '            <td class="table-cell"><h3>' . $row["Time_out"] . '</h3></td>';          
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
        
  </div>



  <a id="addLaborBtn" 
   href="userpaycarpenter.php?requirement_ID=<?php echo $requirementData['requirement_ID']; ?>&User_ID=<?php echo $_SESSION['User_ID']; ?>" 
   class="button-link">Pay Carpenter
</a>


</body>
</html>