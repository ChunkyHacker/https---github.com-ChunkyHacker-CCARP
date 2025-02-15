<?php
    include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Plan Details</title>
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
            font-size: 20px; /* Set body font size to 20px */
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
            font-size: 20px; /* Adjust header font size */
            border-left: 20px solid transparent;
            text-decoration: none;
        }

        .right {
            margin-right: 20px;
        }

        .header a {
            font-size: 20px; /* Set font size to 20px */
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
                font-size: 20px; /* Set font size to 20px */
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

        /* Contract Modal Overlay */
        #contractModal {
            display: none; /* Para di mo-open automatic */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center; 
            justify-content: center;
            padding: 20px;
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
            font-size: 20px; /* Adjust heading size */
            margin-bottom: 20px;
            color: #FF8C00;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 20px; /* Adjust label font size */
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
            font-size: 20px; /* Adjust input font size */
        }

        .post-btn, .cancel-btn {
            margin-bottom: 10px;
        }

        .table-container {
            margin-bottom: 20px; /* Adds spacing between tables */
            padding: 10px;
            border: 1px solid #ccc; /* Optional: Adds a light border to separate */
            border-radius: 5px; /* Optional: Rounded corners */
            background-color: #f9f9f9; /* Optional: Light background for separation */        }

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
            font-size: 20px; /* Set cancel button font size */
        }

        .cancel-btn:hover {
            background-color: #000000;
        }

        button {
            background-color: #FF8C00;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 20px; /* Set button font size */
        }

        button:hover {
            background-color: #000000;
        }

        .button-container {
            margin-bottom: 15px; /* Adds space between buttons */
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
    require_once "config.php";

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
            echo "<h3>Estimated Cost</h3>";
            echo "<label for='estimated_cost'>Estimated Cost</label>";
            echo "<input type='text' id='estimated_cost' name='estimated_cost' value='{$row['estimated_cost']}' readonly><br>";
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
                while ($required_material_row = mysqli_fetch_assoc($result_required_materials)) {
                    if ($required_material_row) {
                        echo '<tr>
                                <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['material']) . '</td>
                                <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['type']) . '</td>';
                        
                        // Image column
                        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">';
                        
                        // Check if the image path exists and is valid
                        $image_path = htmlspecialchars($required_material_row['image']);
                        if (!empty($image_path) && file_exists($image_path)) {
                            echo '<img src="' . $image_path . '" alt="Material Image" style="max-width: 100px; max-height: 100px;">';
                        } else {
                            // Display a default image if the path is invalid or empty
                            echo '<img src="assets/default-product.png" alt="Default Image" style="max-width: 100px; max-height: 100px;">';
                        }
                        
                        echo '</td>';
                    
                        // Remaining columns
                        echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['quantity']) . '</td>
                              <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['price']) . '</td>
                              <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['total']) . '</td>
                              <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['materials_overall_cost']) . '</td>
                            </tr>';
                    }
                }
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
            echo "<div id='contractModal' style='display: none;'>
                <div class='contract-modal-content'>
                    <span onclick='closeContractModal()' class='close-modal'>&times;</span>
                    <h2>Construction Agreement</h2>

                    <div class='contract-container'>
                    <p class='contract-text'>
                        This agreement is made between <span class='highlight'>$client_name</span> (Client) and <span class='highlight'>$contractor_name</span> (Contractor), regarding the construction project with the following details:
                    </p>

                    <p><strong>Lot Area:</strong> {$contract['length_lot_area']}m x {$contract['width_lot_area']}m ({$contract['square_meter_lot']} sqm)</p>
                    <p><strong>Floor Area:</strong> {$contract['length_floor_area']}m x {$contract['width_floor_area']}m ({$contract['square_meter_floor']} sqm)</p>
                    <p><strong>Project Type:</strong> {$contract['type']}</p>
                    <p><strong>Initial Budget:</strong> PHP " . number_format($contract['initial_budget'], 2) . "</p>

                    <div class='project-photo'>
                        <h3>Project Photo</h3>";
                            if (!empty($contract['photo_path']) && file_exists($contract['photo_path'])) {
                                echo "<a href='#' onclick='openPhotoModal(\"{$contract['photo_path']}\")'>
                                        <img src='{$contract['photo_path']}' alt='Project Photo' class='project-img'>
                                    </a>";
                            } else {
                                echo "<p>No project photo available.</p>";
                            }
                echo "</div>

                            <p class='contract-text'>
                                The project is approved by <span class='highlight'>$contractor_name</span> and will proceed according to the agreed terms.
                            </p>

                            <p><strong>Start Date:</strong> " . date("F j, Y", strtotime($contract['start_date'])) . "</p>
                            <p><strong>End Date:</strong> " . date("F j, Y", strtotime($contract['end_date'])) . "</p>

                            <p class='contract-text'>
                                Both parties agree to the conditions stated above. The contractor is responsible for completing the project within the agreed timeframe and budget.
                            </p>

                            <form method='POST' action='save_agreement.php'>
                                <input type='hidden' name='requirement_ID' value='{$contract['requirement_ID']}'>
                            </form>
                        </div>
                    </div>
                </div>";
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

            echo "</form>";
            echo "</div>"; 
            echo '<div class="button-container">
                    <button onclick="history.back()">Go back</button>
                </div>';
            
            echo '<div class="button-container">
                    <button onclick="window.location.href=\'usercomputebudget.php?requirement_ID=' . htmlspecialchars($row['requirement_ID']) . '\'">Compute Budget</button>
                </div>';
            
            echo '<div class="button-container">
                    <button onclick="window.location.href=\'userviewprogress.php?requirement_ID=' . htmlspecialchars($row['requirement_ID']) . '\'">Check Progress</button>
                </div>';     
            echo '<div class="button-container">
                    <button onclick="window.location.href=\'viewexpenses.php?requirement_ID=' . htmlspecialchars($row['requirement_ID']) . '\'">Track expenses on your project</button>
                </div>';
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
</div>
</body>
</html>
