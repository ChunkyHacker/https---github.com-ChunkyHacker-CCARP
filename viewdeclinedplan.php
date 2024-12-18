<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Declined Plan Details</title>
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
            font-size: 40px;
            border-left: 20px solid transparent;
            text-decoration: none;
        }

        .right {
            margin-right: 20px;
        }

        .header a {
            font-size: 25px;
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
            font-size: 24px;
            margin-bottom: 20px;
            color: #FF8C00;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
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
            font-size: 16px;
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
    include('config.php');

    if (isset($_GET['declined_plan_ID'])) {
        $declined_plan_ID = $_GET['declined_plan_ID'];

        $query = "SELECT * FROM declinedplan WHERE declined_plan_ID = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $declined_plan_ID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='main'>";
            echo "<h1>Client's Declined Plan Details</h1>";
        
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
            echo "<input type='text' id='square_meter_lot' name='square_meter_lot' value='{$row['square_meter_lot']}' readonly><br>";
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
            echo "<p>Part: (Foot) <input type='text' value='{$row['part']}' readonly></p>";
            echo "<p>Materials: <input type='text' value='{$row['materials']}' readonly></p>";
            echo "<p>Were the details of the project has suits for your scope of work for the carpenter? <input type='text' value='{$row['q1']}' readonly></p>" ;           
            echo "<p>Can the carpenter finish the project on time?  <input type='text' value='{$row['q2']}' readonly></p>" ;     
            echo "<p>Does the carpenter accept the project?  <input type='text' value='{$row['q3']}' readonly></p>" ;                       
            echo "<p>Reason of decline <input type='text' value='{$row['comment']}' readonly></p>";            
        
            // Display the resized photo with a link to open the modal
            $photoPath = $row['Photo'];
            if (!empty($photoPath) && file_exists($photoPath)) {
                echo "<p>Photo:</p>";
                echo "<a href='#' onclick='openModal(\"{$photoPath}\")'>";
                echo "<img src='{$photoPath}' alt='Plan Photo' style='width: 900px; height: 400px;'>";
                echo "</a>";
            }
            echo "<form action='deletedeclinedplan.php' method='post'>";
            echo "<input type='hidden' name='plan_id' value='$declined_plan_ID'>";
            echo "<input type='submit' value='Delete your plan'>";
            echo "</form>";

        
            echo "</div>";
        } else {
            echo "<div class='main'>";
            echo "<p>No client's plan found with Plan ID: $declined_plan_id</p>";
            echo "</div>";
        }        
        
        echo '<button onclick="window.location.href = \'profile.php\'">Go back</button>';

        mysqli_stmt_close($stmt);
        mysqli_close($connection);

    } else {
        echo "<div class='main'>";
        echo "<p>Plan ID is missing.</p>";
        echo '<button onclick="window.location.href = \'profile.php\'">Go back</button>';
        echo "</div>";
    }
    ?>
    </div>
</div>
</body>
</html>
