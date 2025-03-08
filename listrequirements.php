<?php
    session_start(); 
    include('config.php');

    if (!isset($_SESSION['Carpenter_ID'])) {
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
    <title>List Requirements</title>
    <script src="process 3\materials.js"></script>
    <script src="process 3\remainingcost.js"></script>
    <script src="process 3\modal.js"></script> 
    <script src="process 3\searchitems.js"></script>
    <script src="process 3\filtertype.js"></script>
    <script src="process 3\totalcost.js"></script>
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

        #estimated_cost_label {
            display: none;
        }
        #estimated_cost {
            display: none;
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
<div class="container">
    <div class="modal-content">
    <?php
// listrequirements.php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['plan_ID'])) { // Using POST method
    $plan_ID = $_POST['plan_ID'];

    // Fetch and display client's plan based on the plan_ID from plan_approval
    $query = "SELECT * FROM plan_approval WHERE plan_ID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $plan_ID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='main'>";
        echo "<h1>List Requirements</h1>";

        $plan_ID = $row['plan_ID']; // Get the plan_ID from plan_approval

        // Now fetch the User_ID from the plan table
        $planQuery = "SELECT User_ID FROM plan WHERE plan_ID = ?";
        $planStmt = mysqli_prepare($conn, $planQuery);
        mysqli_stmt_bind_param($planStmt, "i", $plan_ID);
        mysqli_stmt_execute($planStmt);
        $planResult = mysqli_stmt_get_result($planStmt);
        
        if ($planRow = mysqli_fetch_assoc($planResult)) {
            $userId = $planRow['User_ID']; // Get User_ID from plan table
        } else {
            $userId = 0; // Default value if no user found
        }
        
        // Now fetch the user details
        if ($userId > 0) {
            $userQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
            $userStmt = mysqli_prepare($conn, $userQuery);
            mysqli_stmt_bind_param($userStmt, "i", $userId);
            mysqli_stmt_execute($userStmt);
            $userResult = mysqli_stmt_get_result($userStmt);
        
            echo "<label for='name'>Client Name: </label>";
            if ($userRow = mysqli_fetch_assoc($userResult)) {
                echo "<input type='text' id='name' name='User_ID' value='{$userRow['First_Name']} {$userRow['Last_Name']}' readonly><br>";
            } else {
                echo "<input type='text' id='name' value='No user found' readonly><br>";
            }
        } else {
            echo "<input type='text' id='name' value='No user assigned' readonly><br>";
        }

        echo "<div class=\"row-container\" style='display: grid; grid-template-columns: repeat(3, 1fr); grid-gap: 10px; text-align: left;'>";
        echo "<h3>Lot area</h3>";

        $plan_ID = $row['plan_ID']; // Kuhaon ang plan_ID gikan sa plan_approval

        // Fetch data from plan table
        $planQuery = "SELECT * FROM plan WHERE plan_ID = ?";
        $planStmt = mysqli_prepare($conn, $planQuery);
        mysqli_stmt_bind_param($planStmt, "i", $plan_ID);
        mysqli_stmt_execute($planStmt);
        $planResult = mysqli_stmt_get_result($planStmt);
        
        if ($planRow = mysqli_fetch_assoc($planResult)) {
            $length_lot_area = $planRow['length_lot_area'];
            $width_lot_area = $planRow['width_lot_area'];
            $square_meter_lot = $planRow['square_meter_lot'];
        } else {
            $length_lot_area = $width_lot_area = $square_meter_lot = "N/A"; // Default kung walay data
        }
        
        echo "<div class='row-container' style='display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; text-align: left; align-items: center;'>"; 

        echo "<h3 style='grid-column: span 3;'>Lot area</h3>"; // Ensure title spans 3 columns
        
        // Length on the left
        echo "<div>";
        echo "<label for='length_lot_area'>Length for lot area</label>";
        echo "<input type='text' id='length_lot_area' name='length_lot_area' value='{$length_lot_area}' readonly>";
        echo "</div>";
        
        // Width in the center
        echo "<div>";
        echo "<label for='width_lot_area'>Width for lot area</label>";
        echo "<input type='text' id='width_lot_area' name='width_lot_area' value='{$width_lot_area}' readonly>";
        echo "</div>";
        
        // Square meter on the right
        echo "<div>";
        echo "<label for='square_meter_lot'>Square Meter (Sq):</label>";
        echo "<input type='text' id='square_meter_lot' name='square_meter_lot' value='{$square_meter_lot}' readonly>";
        echo "</div>";
        
        echo "</div>"; // Close row-container
                
        echo "<div class=\"row-container\">";
        echo "<h3>Further Details</h3>";

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

        // Form to add requirements
        echo '<form action="addrequirements.php" method="post" enctype="multipart/form-data">';
        echo "<input type='hidden' name='plan_ID' value='$plan_ID'>";


        echo "<input type='submit' value='Generate Contract'>";
        echo "<input type='button' value='Go back' onclick='window.location.href = \"profile.php\";'>";
        echo "</form>";

        echo "</div>"; // Close main div
    } else {
        echo "<div class='main'>";
        echo "<h1>No plan approval found</h1>";
        echo "<p>Sorry, there is no approved plan with Plan ID: $plan_ID</p>";
        echo "</div>"; // Close main div
    }

    // Close the statement
    mysqli_stmt_close($stmt);
    // Close the database connection
    mysqli_close($conn);
} else {
    // Handle the case when plan_ID is not provided in the POST request
    echo "<div class='main'>";
    echo "<h1>Plan ID is missing</h1>";
    echo "<p>Please submit the form properly.</p>";
    echo "</div>"; // Close main div
}
?>
</div>
</body>
</html>
