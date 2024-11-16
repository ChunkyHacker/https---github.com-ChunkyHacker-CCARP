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
    // listrequirements.php
    include('config.php');

    if (isset($_GET['approved_plan_ID'])) {
        $approved_plan_ID = $_GET['approved_plan_ID'];

        // Fetch and display client's plan based on the approved_plan_ID
        $query = "SELECT * FROM approvedplan WHERE approved_plan_ID = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $approved_plan_ID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='main'>";
            echo "<h1>List Requirements</h1>";
            // Fetch user details from the 'users' table
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
            $ec=$row['estimated_cost'];

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
            echo "<h3>Estimated Cost</h3>";
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
                                            
            echo "<div class=\"row-container\">";
            echo "<h3>Further Details</h3>";
            
            echo "<div style='display: grid; grid-template-columns: repeat(3, 1fr); grid-gap: 10px;'>";
            
            echo "<table style='border-collapse: collapse; width: 100%;'>";
            echo "<thead>";
                        echo "<tr>";
                            echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Part</th>";
                            echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Materials</th>";
                            echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Name</th>";
                            echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Quantity</th>";
                            echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Price</th>";
                            echo "<th style='border: 1px solid #dddddd; text-align: left; padding: 8px;'>Total</th>";
                            echo '<th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Estimated Cost</th>';

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
                        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['estimated_cost']) . '</td>';

                    echo "</tr>";
                }
                
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
                
                
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

            echo "<div style='display: flex; flex-direction: column;'>";
            echo "<label for='type'>Approved By: </label>";
            echo "<input type='text' id='type' name='approved_by:' value='{$row['approved_by']}' readonly>";
            echo "</div>";

            echo '<form action="addrequirements.php" method="post" enctype="multipart/form-data">';
            echo "<h2>Add New Requirements</h2>";
            echo "<input type='hidden' name='approved_plan_id' value='$approved_plan_ID'>";
                        

            $query = "SELECT * FROM approvedplan WHERE approved_plan_ID = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $approved_plan_ID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($row = mysqli_fetch_assoc($result)) {
                // Check if 'estimated_cost' is present in the fetched row
                if (isset($row['estimated_cost'])) {
                    echo "<label id='estimated_cost_label' for='estimated_cost'>Estimated Cost</label>";
                    echo "<input type='hidden' id='estimated_cost' name='estimated_cost' value='" . $ec . "'>";
                } else {
                    echo "Estimated Cost not found in the fetched row.";
                }
            } else {
                echo "No data found for the specified approved_plan_ID.";
            }
                        
            echo "<label for='labor_cost'>Labor Cost:</label>";
            echo "<input type='text' name='labor_cost' id='labor_cost' required>";                   
            
            echo "<input type='submit' value='Submit Requirements'>";
            echo "<input type='button' value='Go back' onclick='window.location.href = \"profile.php\";'>";
            
            echo "</form>";
                        
            echo "</div>"; // Close main div
            } else {
            echo "<div class='main'>";
            echo "<h1>No approved plan found</h1>";
            echo "<p>Sorry, there is no approved plan with Approved Plan ID: $approved_plan_ID</p>";
            echo "</div>"; // Close main div
        }

        // Close the statement
        mysqli_stmt_close($stmt);
        // Close the database conn$conn
        mysqli_close($conn);
    } else {
        // Handle the case when approved_plan_ID is not provided in the URL
        echo "<div class='main'>";
        echo "<h1>Approved Plan ID is missing</h1>";
        echo "<p>Please provide the Approved Plan ID in the URL</p>";
        echo "</div>"; // Close main div
    }
    ?>
</div>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        updateTotalCost();
    });

    function updateTotalCost() {
        var estimatedCost = parseFloat(document.getElementById("estimated_cost").value) || 0;
        var laborCost = parseFloat(document.getElementById("labor_cost").value) || 0;
        var remainingCost = parseFloat(document.getElementById("remaining_cost").value) || 0;

        var totalCost = estimatedCost + remainingCost + laborCost;

        document.getElementById("total_cost").value = totalCost.toFixed(2);
    }

    document.getElementById("labor_cost").addEventListener("input", updateTotalCost);
    document.getElementById("remaining_cost").addEventListener("input", updateTotalCost);
    document.getElementById("estimated_cost").addEventListener("input", updateTotalCost);
</script>
</html>
