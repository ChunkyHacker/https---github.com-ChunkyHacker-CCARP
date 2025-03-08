<?php
session_start();
include('config.php');

if (!isset($_SESSION['Carpenter_ID'])) {
    header('Location: login.html');
    exit();
}

$Carpenter_ID = $_SESSION['Carpenter_ID'];

// Get carpenter details
$sql = "SELECT * FROM carpenters WHERE Carpenter_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $Carpenter_ID);
$stmt->execute();
$result = $stmt->get_result();
$carpenterDetails = $result->fetch_assoc();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Evaluate Plan</title>
    <style>
        * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        }

        body {
        font-family: Verdana, sans-serif;
        margin: 0;
        background-color:rgb(255, 255, 255);
        font-size: 20px; /* Set base font size to 20px */
        }

        /* Sidebar styles */
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
            padding: 10px;
            margin-bottom: 10px;
        }

        .sidenav .profile-section img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 5px;
        }

        .sidenav a {
            padding: 8px 15px;
            text-decoration: none;
            font-size: 24px;
            color: #000000;
            display: block;
            transition: 0.3s;
            margin-bottom: 2px;
        }

        .sidenav a:hover {
            background-color: #000000;
            color: #FF8C00;
        }

        /* Adjust main content area */
        .container {
            margin-left: 250px;
            padding: 20px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .grid-item {
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: none;  /* Remove shadow */
        }

        h1 {
            font-size: 48px;
            margin-bottom: 30px;
            font-weight: bold;
            padding-left: 20px;
        }

        h3 {
            font-size: 32px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 24px;
            font-weight: normal;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 22px;
        }

        textarea {
            width: 100%;
            min-height: 100px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 22px;
            resize: none;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border: none;  /* Remove border */
            box-shadow: none;  /* Remove shadow */
        }

        /* Client photo style */
        .client-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            display: block;
        }

        /* Project Budget and Dates sections */
        .grid-item:nth-child(4),
        .grid-item:nth-child(5) {
            margin-top: 20px;
        }

        body {
            font-family: Verdana, sans-serif;
            margin: 0;
            background-color: white;
            font-size: 24px;
        }

        .post-btn,
        .cancel-btn {
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
        font-size: 20px; /* Set cancel button font size to 20px */
        }

        .cancel-btn:hover {
        background-color: #000000;
        }

        button {
        background-color: #FF8C00;
        color: #fff;
        border: none;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 20px; /* Set button font size to 20px */
        }

        button:hover {
        background-color: #000000;
        }

        /* New styling for the radio button groups */
        .radio-group {
            display: flex;
            gap: 20px; /* Space between Yes and No */
            align-items: center;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 5px; /* Space between radio button and text */
            cursor: pointer;
        }
  </style>

</head>
<body>
    <div class="sidenav">
        <div class="profile-section">
            <?php
            // Display profile picture
            if (isset($carpenterDetails['Photo']) && !empty($carpenterDetails['Photo'])) {
                echo '<img src="' . $carpenterDetails['Photo'] . '" alt="Profile Picture">';
            } else {
                echo '<img src="assets/img/default-avatar.png" alt="Default Profile Picture">';
            }
            
            // Display name and ID
            echo "<h3>" . $carpenterDetails['First_Name'] . ' ' . $carpenterDetails['Last_Name'] . "</h3>";
            echo "<p>Carpenter ID: " . $Carpenter_ID . "</p>";
            ?>
        </div>
        <a href="home.php"><i class="fas fa-home"></i> Home</a>
        <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
        <a href="getideas.php"><i class="fas fa-lightbulb"></i> Get Ideas</a>
        <a href="project.php"><i class="fas fa-project-diagram"></i> Project</a>
        <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="container">
        <div class="modal-content">
            <?php
                if (!isset($_GET['plan_ID']) || empty($_GET['plan_ID'])) {
                    die("<div class='main'><p>Plan ID is missing.</p></div>");
                }

                $plan_ID = intval($_GET['plan_ID']);

                // Fetch Plan Details
                $query = "SELECT * FROM plan WHERE plan_ID = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $plan_ID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='main'>";
                    echo "<h1>Client's Plan Details</h1>";

                    // Fetch Client Name
                    $userId = $row['User_ID'];
                    $userQuery = "SELECT First_Name, Last_Name, Photo FROM users WHERE User_ID = ?";
                    $userStmt = mysqli_prepare($conn, $userQuery);
                    mysqli_stmt_bind_param($userStmt, "i", $userId);
                    mysqli_stmt_execute($userStmt);
                    $userResult = mysqli_stmt_get_result($userStmt);
                    $userDetails = mysqli_fetch_assoc($userResult);
                    $clientName = ($userDetails) ? "{$userDetails['First_Name']} {$userDetails['Last_Name']}" : "Unknown Client";

                    // Grid container for plan details
                    echo "<div class='grid-container'>";
                    
                    // Client Info
                    echo "<div class='grid-item'>";
                    echo "<h3>Client Information</h3>";
                    if (!empty($userDetails['Photo'])) {
                        echo "<img src='" . $userDetails['Photo'] . "' style='width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin: 10px auto; display: block;'>";
                    } else {
                        echo "<img src='assets/img/default-avatar.png' style='width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin: 10px auto; display: block;'>";
                    }
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

                    // Project Budget
                    echo "<div class='grid-item'>";
                    echo "<h3>Project Budget</h3>";
                    echo "<label>Initial Budget:</label>";
                    echo "<input type='text' value='{$row['initial_budget']}' readonly>";
                    echo "</div>";

                    // Project Dates
                    echo "<div class='grid-item'>";
                    echo "<h3>Project Dates</h3>";
                    echo "<label>Start Date:</label>";
                    echo "<input type='text' value='{$row['start_date']}' readonly>";
                    echo "<label>End Date:</label>";
                    echo "<input type='text' value='{$row['end_date']}' readonly>";
                    echo "</div>";

                    // More Details
                    echo "<div class='grid-item'>";
                    echo "<h3>More Details</h3>";
                    echo "<textarea readonly>{$row['more_details']}</textarea>";
                    echo "</div>";

                    echo "<div class='grid-item' style='grid-column: span 3;'>";
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
                        ?>
                        <tr>
                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo htmlspecialchars($material_row['part']); ?></td>
                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo htmlspecialchars($material_row['materials']); ?></td>
                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo htmlspecialchars($material_row['name']); ?></td>
                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo htmlspecialchars($material_row['quantity']); ?></td>
                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo htmlspecialchars($material_row['price']); ?></td>
                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo htmlspecialchars($material_row['total']); ?></td>
                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo htmlspecialchars($material_row['estimated_cost']); ?></td>
                        </tr>
                        <?php
                    }
                    echo "</tbody>";
                    echo "</table>";
                    echo "</div>";

                    // Plan Photos (spanning full width)
                    echo "<div class='grid-item' style='grid-column: span 3;'>";
                    echo "<h3>Plan Photos</h3>";
                    
                    // Fetch plan photos
                    $photoQuery = "SELECT Photo FROM plan WHERE plan_ID = ?";
                    $photoStmt = mysqli_prepare($conn, $photoQuery);
                    mysqli_stmt_bind_param($photoStmt, "i", $plan_ID);
                    mysqli_stmt_execute($photoStmt);
                    $photoResult = mysqli_stmt_get_result($photoStmt);
                    
                    echo "<div style='display: flex; gap: 20px; flex-wrap: wrap; justify-content: center;'>";
                    while ($photo = mysqli_fetch_assoc($photoResult)) {
                        echo "<img src='{$photo['Photo']}' style='width: 300px; height: 300px; object-fit: cover; border-radius: 8px; margin-bottom: 20px;'>";
                    }
                    echo "</div>";
                    
                    echo"<h2>Evaluation Form</h2>";
                    echo"<form method='post' action='approveplan.php'>";
                    echo"<!-- Fix: Ensure correct plan_ID input field -->";
                    echo "<input type='hidden' name='plan_ID' value='{$plan_ID}'>";

                    echo "<label>Were the details of the project suitable for your scope of work?</label>";
                    echo "<div class='radio-group'>";
                    echo "<label><input type='radio' name='q1' value='Yes' required> Yes</label>";
                    echo "<label><input type='radio' name='q1' value='No' required> No</label>";
                    echo "</div>";

                    echo "<label>Are you able to finish the project even if there is an overlapping budget?</label>";
                    echo "<div class='radio-group'>";
                    echo "<label><input type='radio' name='q2' value='Yes' required> Yes</label>";
                    echo "<label><input type='radio' name='q2' value='No' required> No</label>";
                    echo "</div>";

                    echo "<label>Are you willing to accept an additional task with additional payment?</label>";
                    echo "<div class='radio-group'>";
                    echo "<label><input type='radio' name='q3' value='Yes' required> Yes</label>";
                    echo "<label><input type='radio' name='q3' value='No' required> No</label>";
                    echo "</div>";

                    echo "<label>Can you finish the project on time?</label>";
                    echo "<div class='radio-group'>";
                    echo "<label><input type='radio' name='q4' value='Yes' required> Yes</label>";
                    echo "<label><input type='radio' name='q4' value='No' required> No</label>";
                    echo "</div>";

                    echo "<label>Will you accept the project?</label>";
                    echo "<div class='radio-group'>";
                    echo "<label><input type='radio' name='q5' value='Yes' required> Yes</label>";
                    echo "<label><input type='radio' name='q5' value='No' required> No</label>";
                    echo "</div>";

                    echo "<label>If Yes/No, why?</label><br>";
                    echo "<textarea name='comment' rows='4' cols='50'></textarea><br>";

                    echo "<label>Approved By: </label>";
                    echo "<input type='text' name='approved_by' value='" . htmlspecialchars($carpenterDetails['First_Name'] . ' ' . $carpenterDetails['Last_Name']) . "' readonly><br>";

                    echo "<label>Status:</label>";
                    echo "<select name='status' style='width: 200px; height: 40px; margin-bottom: 20px;'>";
                    echo "<option value='' disabled selected>Select an option</option>";
                    echo "<option value='approve'>Approve</option>";
                    echo "<option value='decline'>Decline</option>";
                    echo "</select><br>";

                    echo "<button type='submit'>Submit</button>";
                    echo "</form>";

                    echo "<button onclick=\"history.back()\">Go back</button>";
                    echo "</div>";
                } else {
                    echo "<div class='main'><p>No client's plan found with Plan ID: " . htmlspecialchars($plan_ID) . "</p></div>";
                }
            ?>
        </div>
    </div>


</body>
<script>
    function openModal(photoPath) {
        var modal = document.createElement('div');
        modal.style.display = 'block';
        modal.style.position = 'fixed';
        modal.style.zIndex = '1';
        modal.style.paddingTop = '100px';
        modal.style.left = '0';
        modal.style.top = '0';
        modal.style.width = '100%';
        modal.style.height = '100%';
        modal.style.overflow = 'auto';
        modal.style.backgroundColor = 'rgb(0,0,0)';
        modal.style.backgroundColor = 'rgba(0,0,0,0.9)';
        
        var img = document.createElement('img');
        img.src = photoPath;
        img.style.margin = 'auto';
        img.style.display = 'block';
        img.style.width = '80%';
        img.style.maxWidth = '700px';
        
        modal.appendChild(img);
        
        modal.onclick = function() {
            modal.style.display = 'none';
        }
        
        document.body.appendChild(modal);
    }
</script>
<script>
function submitForm(action) {
    document.getElementById('planForm').action = action;
    document.getElementById('planForm').submit();
}
</script>
</html>
