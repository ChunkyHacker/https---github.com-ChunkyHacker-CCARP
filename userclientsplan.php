
<?php
    session_start(); 
    include('config.php');

    if (!isset($_SESSION['User_ID'])) {
    header('Location: login.html');
    exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients Plan</title>
    <style>
                * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Verdana, sans-serif;
            margin: 0;
            background-color: #FF8C00;
            font-size: 20px; /* Adjusted font size */
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

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
            align-items: center;
            text-decoration: none;
            z-index: 100;
        }

        .header h1 {
            font-size: 20px; /* Adjusted font size */
            border-left: 20px solid transparent;
            text-decoration: none;
        }

        .right {
            margin-right: 20px;
        }

        .header a {
            font-size: 20px; /* Adjusted font size */
            font-weight: bold;
            text-decoration: none;
            color: #000000;
            margin-right: 15px;
        }

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

            body {
                font-family: Arial, Helvetica, sans-serif;
                margin: 0;
                padding-top: 300px;
                font-size: 20px; /* Adjusted font size */
            }
        }

        /* CSS styles for the modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(255, 140, 0, 0.4);
        }

        .modal-content {
            background-color: #FF8C00;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-height: 80%;
            overflow-y: auto;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .modal-content {
            background-color: #fefefe;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        h2 {
            font-size: 20px; /* Adjusted font size */
            margin-bottom: 20px;
            color: #FF8C00;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 20px; /* Adjusted font size */
            margin-bottom: 5px;
            color: #000000;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 20px; /* Adjusted font size for form elements */
        }

        .post-btn, .cancel-btn {
            margin-bottom: 10px;
        }

        .cancel-btn {
            background-color: red;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            width: 100%;
        }

        .cancel-btn:hover {
            background-color: #000000;
        }

        button {
            background-color: #FF8C00;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 20px; /* Adjusted font size */
        }

        button:hover {
            background-color: #000000;
        }

        @media screen and (max-width: 600px) {
            .modal-content {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="usercomment.php">
            <h1>
                <img src="assets/img/logos/logo.png" alt=""  style="width: 75px; margin-right: 10px;">
            </h1>
        </a>
        <div class="right">
            <a class="active" href="usercomment.php">Home</a>
            <a href="about/index.html">About</a>
            <a href="#contact">Get Ideas</a>
            <a href="plan.php">Project</a>
            <a href="logout.php" style="text-decoration: none; color: black; margin-right: 20px;">Log Out</a>
        </div>
    </div>
    <div class="container">
        <div class="modal-content">
        <?php
        include('config.php');

        if (isset($_GET['plan_id'])) {
            $plan_id = $_GET['plan_id'];

            $query = "SELECT * FROM plan WHERE plan_ID = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $plan_id);
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

                echo "<div class=\"row-container\" style='display: grid; grid-template-columns: repeat(3, 1fr); grid-gap: 10px; text-align: left;'>";
                echo "<h3>Lot area</h3>";
                
                // Length on the left
                echo "<div style='grid-column: 1; display: flex; flex-direction: column;'>";
                echo "<label for='length_lot_area'>Length for lot area</label>";
                echo "<input type='text' id='length_lot_area' name='length_lot_area' value='{$row['length_lot_area']}' readonly>";
                echo "</div>";
                
                // Width in the center
                echo "<div style='grid-column: 2; display: flex; flex-direction: column;'>";
                echo "<label for='width_lot_area'>Width for lot area</label>";
                echo "<input type='text' id='width_lot_area' name='width_lot_area' value='{$row['width_lot_area']}' readonly>";
                echo "</div>";
                
                // Square meter on the right
                echo "<div style='grid-column: 3; display: flex; flex-direction: column;'>";
                echo "<label for='square_meter_lot'>Square Meter(Sq):</label>";
                echo "<input type='text' id='square_meter_lot' name='square_meter_lot' value='{$row['square_meter_lot']}' readonly>";
                echo "</div>";
                echo "</div>";
                
                echo "<div class=\"row-container\" style='display: grid; grid-template-columns: repeat(3, 1fr); grid-gap: 10px; text-align: left;'>";
                echo "<h3>Floor Area</h3>";
                
                // Length on the left
                echo "<div style='grid-column: 1; display: flex; flex-direction: column;'>";
                echo "<label for='length_floor_area'>Length for floor area</label>";
                echo "<input type='text' id='length_floor_area' name='length_floor_area' value='{$row['length_floor_area']}' readonly>";
                echo "</div>";
                
                // Width in the center
                echo "<div style='grid-column: 2; display: flex; flex-direction: column;'>";
                echo "<label for='width_floor_area'>Width for floor area</label>";
                echo "<input type='text' id='width_floor_area' name='width_floor_area' value='{$row['width_floor_area']}' readonly>";
                echo "</div>";
                
                // Square meter on the right
                echo "<div style='grid-column: 3; display: flex; flex-direction: column;'>";
                echo "<label for='square_meter_floor'>Square Meter(Sq):</label>";
                echo "<input type='text' id='square_meter_floor' name='square_meter_floor' value='{$row['square_meter_floor']}' readonly>";
                echo "</div>";               
                echo "</div>";
                
                echo "<div class=\"row-container\" style='display: grid; grid-template-columns: repeat(2, 1fr); grid-gap: 10px; text-align: left;'>";
                echo "<div style='display: flex; flex-direction: column;'>";
                echo "<h3>Initial Budget</h3>";
                // Initial Budget on the left
                echo "<label for='initial_budget'>Initial Budget</label>";
                echo "<input type='text' id='initial_budget' name='initial_budget' value='{$row['initial_budget']}' readonly>";
                echo "</div>";
                // Estimated Cost on the right
                echo "<div style='display: flex; flex-direction: column;'>";
                echo "</div>";
                echo "</div>";
                
                echo "<div class=\"row-container\">";
                echo "<h3>Project Dates</h3>";
                echo "<div style='display: grid; grid-template-columns: repeat(2, 1fr); grid-gap: 10px;'>";
                echo "<div style='display: flex; flex-direction: column;'>";
                echo "<label>Start Date:</label>";
                echo "<input type='text' value='{$row['start_date']}' readonly>";
                echo "</div>";
                
                echo "<div style='display: flex; flex-direction: column;'>";
                echo "<label>End Date:</label>";
                echo "<input type='text' value='{$row['end_date']}' readonly>";
                echo "</div>";
                echo "</div>";
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

                echo "</div>";
                
                

                // Display the resized photo with a link to open the modal
                $photoPath = $row['Photo'];
                if (!empty($photoPath) && file_exists($photoPath)) {
                    echo "<p>Photo:</p>";
                    echo "<div style='text-align: center;'>";  // Center the content
                    echo "<a href='#' onclick='openModal(\"{$photoPath}\")'>";
                    echo "<img src='{$photoPath}' alt='Plan Photo' style='width: 700px; height: 400px;'>";
                    echo "</a>";
                    echo "</div>";
                }

                echo "<form action='deleteplan.php' method='post' onsubmit='return confirmDelete()'>";
                echo "<input type='hidden' name='plan_id' value='$plan_id'>";
                echo "<input type='submit' value='Delete your plan'>";
                echo "</form>";
            
                echo '<button onclick="window.location.href = \'userprofile.php\'">Go back</button>';

                echo "</div>";
            } else {
                echo "<div class='main'>";
                echo "<p>No client's plan found with Plan ID: $plan_id</p>";
                echo "</div>";
            }
            echo "</div>";
        
            

            mysqli_stmt_close($stmt);
            mysqli_close($conn);

        } else {

        }
        ?>

        </div>
    </div>
</body>
<script>
function confirmDelete() {
    return confirm('Are you sure you want to delete this plan?');
}
</script>
</html>
