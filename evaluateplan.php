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
        background-color:rgb(0, 0, 0);
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

        /* Evaluation Form Styles */
        /* Update the evaluation section styles */
        .evaluation-section {
            margin-bottom: 30px;
            padding: 25px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .evaluation-section:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }

        .evaluation-section h3 {
            color:rgb(0, 0, 0);
            border-bottom: 2px solid #FF8C00;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .eval-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 15px;
            border-radius: 8px;
            overflow: hidden;
        }

        .eval-table th, .eval-table td {
            padding: 15px;
            border: 1px solid #eee;
            color: #000000;  /* Add this line for black text */
        }

        .eval-table th {
            background-color: #000000;
            color:rgb(0, 0, 0);
            font-weight: bold;
            padding: 15px;
            font-size: 20px;
        }

        .eval-table td {
            background-color: #f9f9f9;
            color: #000000;
            padding: 15px;
            font-size: 20px;
        }

        .eval-table tr:nth-child(even) td {
            background-color: #f0f0f0;
        }

        .eval-table tr:hover td {
            background-color: #e0e0e0;
        }

        .eval-table input[type='number'] {
            width: 120px;
            height: 45px;
            padding: 10px;
            border: 2px solid #FF8C00;
            border-radius: 8px;
            font-size: 22px;
            text-align: center;
            background-color: #fff;
            color: #000000;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: block;
            margin: 0 auto;
        }

        .eval-table input[type='number']:focus {
            border-color: #FF8C00;
            box-shadow: 0 0 8px rgba(255,140,0,0.4);
            outline: none;
        }

        .eval-table td:last-child {
            text-align: center;
            min-width: 150px;
        }

        .total-score {
            background: linear-gradient(to right, #FF8C00, #FFA500);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            font-size: 24px;
            margin-top: 20px;
        }

        .total-score label {
            color: #000000;
            font-weight: bold;
        }

        .total-score input {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            font-size: 24px;
            font-weight: bold;
            width: 100px;
            text-align: center;
            color: #000000;
        }

        .total-score span {
            color: #000000;
            font-weight: bold;
        }
        
        .eval-table th {
            background-color: #f8f8f8;
        }
        
        .eval-table input[type='number'] {
            width: 80px;
            padding: 5px;
        }
        
        .decision-group {
            margin: 15px 0;
        }
        
        .decision-group label {
            display: block;
            margin: 10px 0;
        }
        
        .submit-btn {
            background-color: #FF8C00;
            padding: 12px 24px;
            font-size: 18px;
            margin-right: 10px;
        }
        
        .back-btn {
            background-color: #666;
            padding: 12px 24px;
            font-size: 18px;
        }

        /* New styling for the radio button groups */
        .radio-group {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 5px; /* Space between radio button and text */
            cursor: pointer;
        }

        /* Yes/No Button Styles */
        .yes-no-btn {
            padding: 8px 16px;
            border: 2px solid #ddd;
            border-radius: 4px;
            background: white;
            color: #000;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s;
            margin: 0 2px;
        }

        .yes-no-btn.active {
            background: #FF8C00;
            color: white;
            border-color: #FF8C00;
        }

        .yes-no-btn:hover:not(.active) {
            background-color: #FF8C00;
            color: white;
            border-color: #FF8C00;
        }

        .total-score {
            font-size: 24px;
            font-weight: bold;
            padding: 15px;
            background: #f8f8f8;
            border-radius: 5px;
            margin-top: 20px;
            text-align: right;
        }
        
        .eval-table th {
            background-color: #f8f8f8;
        }
        
        .eval-table input[type='number'] {
            width: 80px;
            padding: 5px;
        }
        
        .evaluation-section h4 {
            color: #000000;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .evaluation-section ul {
            margin-bottom: 15px;
        }

        .evaluation-section ul li {
            color: #000000;
            font-size: 18px;
            margin-bottom: 8px;
            padding: 5px 0;
        }

        .decision-result {
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
        }

        .decision-result h4 {
            margin-bottom: 10px;
            font-size: 20px;
        }

        .decision-result p {
            font-size: 18px;
            margin: 0;
        }

        .decision-result.accept {
            background-color: #4CAF50;
            color: white;
        }

        .decision-result.accept-conditions {
            background-color: #FFA500;
            color: white;
        }

        .decision-result.revise {
            background-color: #FF8C00;
            color: white;
        }

        .decision-result.reject {
            background-color: #f44336;
            color: white;
        }
        
        /* Add floating summary styles */
        .floating-summary {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.98);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            min-width: 300px;
            max-width: 400px;
            border: 2px solid #FF8C00;
            cursor: move; /* Add this line */
            user-select: none; /* Add this line */
        }

        .floating-summary .total-score {
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f8f8;
            border-radius: 8px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #000000;
        }

        .floating-summary .decision-result {
            margin: 0;
            font-size: 0.9em;
        }

        .floating-summary .decision-result h4 {
            font-size: 18px;
            margin-bottom: 8px;
        }

        .floating-summary .decision-result p {
            font-size: 16px;
        }
        
        .submit-btn {
            background-color: #FF8C00;
            padding: 12px 24px;
            font-size: 18px;
            margin-right: 10px;
        }
        
        .back-btn {
            background-color: #666;
            padding: 12px 24px;
            font-size: 18px;
        }

        /* New styling for the radio button groups */
        .radio-group {
            display: flex;
            gap: 20px;
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
                    
                    echo"<h2>Project Evaluation Form</h2>";
                    echo"<form method='post' action='approveplan.php'>";
                        echo"<input type='hidden' name='plan_ID' value='{$plan_ID}'>";
                        
                        // Project Scope & Feasibility
                        echo "<div class='evaluation-section'>";
                            echo "<h3>1. Project Scope & Feasibility</h3>";
                            echo "<table class='eval-table'>";
                                echo "<tr><th>Criteria</th><th>Score (1-5)</th></tr>";
                                echo "<tr><td>Project size aligns with our capacity</td><td><input type='number' name='scope_1' min='0' max='5' value='0' required></td></tr>";
                                echo "<tr><td>Availability of required materials</td><td><input type='number' name='scope_2' min='0' max='5' value='0' required></td></tr>";
                                echo "<tr><td>Timeline is realistic and achievable</td><td><input type='number' name='scope_3' min='0' max='5' value='0' required></td></tr>";
                                echo "<tr><td>Budget is sufficient for project completion</td><td><input type='number' name='scope_4' min='0' max='5' value='0' required></td></tr>";
                                echo "<tr><td>Client expectations are clear and defined</td><td><input type='number' name='scope_5' min='0' max='5' value='0' required></td></tr>";
                            echo "</table>";
                        echo "</div>";
                    
                        // Site & Environmental Considerations
                        echo "<div class='evaluation-section'>";
                            echo "<h3>2. Site & Environmental Considerations</h3>";
                            echo "<table class='eval-table'>";
                                echo "<tr><th>Criteria</th><th>Score (1-5)</th></tr>";
                                echo "<tr><td>Accessibility of the project site</td><td><input type='number' name='site_1' min='0' max='5' value='0' required></td></tr>";
                                echo "<tr><td>Environmental impact considerations</td><td><input type='number' name='site_2' min='0' max='5' value='0' required></td></tr>";
                                echo "<tr><td>Legal and zoning requirements met</td><td><input type='number' name='site_3' min='0' max='5' value='0' required></td></tr>";
                                echo "<tr><td>Safety concerns addressed</td><td><input type='number' name='site_4' min='0' max='5' value='0' required></td></tr>";
                                echo "<tr><td>Potential hazards identified and manageable</td><td><input type='number' name='site_5' min='0' max='5' value='0' required></td></tr>";
                            echo "</table>";
                        echo "</div>";
                    
                        // Client Readiness & Commitment
                        echo "<div class='evaluation-section'>";
                            echo "<h3>3. Client Readiness & Commitment</h3>";
                            echo "<table class='eval-table'>";
                                echo "<tr><th>Criteria</th><th>Yes/No</th><th>Comments</th></tr>";
                                echo "<tr>
                                    <td>Client has secured necessary permits</td>
                                    <td>
                                        <input type='hidden' name='client_1' id='client_1' value='No'>
                                        <button type='button' class='yes-no-btn' onclick='toggleYesNo(\"client_1\", \"yes\", event)'>Yes</button>
                                        <button type='button' class='yes-no-btn active' onclick='toggleYesNo(\"client_1\", \"no\", event)'>No</button>
                                    </td>
                                    <td><input type='text' name='client_1_comment'></td>
                                </tr>";
                                echo "<tr>
                                    <td>Client is financially prepared</td>
                                    <td>
                                        <input type='hidden' name='client_3' id='client_3' value='No'>
                                        <button type='button' class='yes-no-btn' onclick='toggleYesNo(\"client_3\", \"yes\", event)'>Yes</button>
                                        <button type='button' class='yes-no-btn active' onclick='toggleYesNo(\"client_3\", \"no\", event)'>No</button>
                                    </td>
                                    <td><input type='text' name='client_3_comment'></td>
                                </tr>";
                                echo "<tr>
                                    <td>Client has realistic expectations</td>
                                    <td>
                                        <input type='hidden' name='client_4' id='client_4' value='No'>
                                        <button type='button' class='yes-no-btn' onclick='toggleYesNo(\"client_4\", \"yes\", event)'>Yes</button>
                                        <button type='button' class='yes-no-btn active' onclick='toggleYesNo(\"client_4\", \"no\", event)'>No</button>
                                    </td>
                                    <td><input type='text' name='client_4_comment'></td>
                                </tr>";
                                echo "<tr>
                                    <td>Client is cooperative and responsive</td>
                                    <td>
                                        <input type='hidden' name='client_5' id='client_5' value='No'>
                                        <button type='button' class='yes-no-btn' onclick='toggleYesNo(\"client_5\", \"yes\", event)'>Yes</button>
                                        <button type='button' class='yes-no-btn active' onclick='toggleYesNo(\"client_5\", \"no\", event)'>No</button>
                                    </td>
                                    <td><input type='text' name='client_5_comment'></td>
                                </tr>";
                                echo "<tr>
                                    <td>Client has a clear design or blueprint</td>
                                    <td>
                                        <input type='hidden' name='client_5' id='client_5' value='No'>
                                        <button type='button' class='yes-no-btn' onclick='toggleYesNo(\"client_5\", \"yes\", event)'>Yes</button>
                                        <button type='button' class='yes-no-btn active' onclick='toggleYesNo(\"client_5\", \"no\", event)'>No</button>
                                    </td>
                                    <td><input type='text' name='client_5_comment'></td>
                                </tr>";
                            echo "</table>";
                        echo "</div>";
                            // Workforce & Resource Availability
                            echo "<div class='evaluation-section'>";
                            echo "<h3>4. Workforce & Resource Availability</h3>";
                            echo "<table class='eval-table'>";
                                echo "<tr><th>Criteria</th><th>Score (1-5)</th></tr>";
                                echo "<tr><td>Skilled labor is available</td><td><input type='number' name='workforce_1' min='0' max='5' value='0' required></td></tr>";
                                echo "<tr><td>Equipment and tools are available</td><td><input type='number' name='workforce_2' min='0' max='5' value='0' required></td></tr>";
                                echo "<tr><td>Project workload is manageable</td><td><input type='number' name='workforce_3' min='0' max='5' value='0' required></td></tr>";
                                echo "<tr><td>Subcontractors (if needed) are available</td><td><input type='number' name='workforce_4' min='0' max='5' value='0' required></td></tr>";
                            echo "</table>";
                        echo "</div>";
                        
                        // Replace the total score section
                        echo "<div class='evaluation-section'>";
                            echo "<div class='total-score'>";
                                echo "<label>Total Evaluation Score: </label>";
                                echo "<input type='number' id='totalScore' name='total_score' value='0' readonly>";
                                echo "<span>/70</span>";
                            echo "</div>";
                        echo "</div>";
                    
                        // Evaluation Summary section
                        echo "<div class='evaluation-section'>";
                            echo "<h3>Evaluation Summary</h3>";
                            echo "<h4>Scoring System:</h4>";
                            echo "<ul style='list-style-type: none; padding-left: 0;'>";
                                echo "<li>5 - Excellent (Meets all requirements)</li>";
                                echo "<li>4 - Good (Minor issues, manageable)</li>";
                                echo "<li>3 - Fair (Requires adjustments)</li>";
                                echo "<li>2 - Poor (Major concerns, needs discussion)</li>";
                                echo "<li>1 - Unacceptable (Not feasible)</li>";
                            echo "</ul>";
                            
                            echo "<h4 style='margin-top: 20px;'>Decision Criteria:</h4>";
                            echo "<ul style='list-style-type: none; padding-left: 0;'>";
                                echo "<li>40-50: Highly Acceptable - Proceed with the project.</li>";
                                echo "<li>30-39: Acceptable - Minor adjustments needed.</li>";
                                echo "<li>20-29: Conditional - Requires significant changes.</li>";
                                echo "<li>Below 20: Not Acceptable - Recommend project revision or rejection.</li>";
                            echo "</ul>";
                        echo "</div>";
                    
                        // Final Decision section
                        echo "<div class='evaluation-section'>";
                            echo "<h3>Final Decision</h3>";
                            echo "<div class='decision-group'>";
                            echo "<div id='autoDecision'></div>";  // Container for automated decision
                            echo "<input type='hidden' name='final_decision' id='finalDecisionInput'>";
                            echo "</div>";
                        echo "</div>";
                    
                        // Evaluator Information
                        echo "<div class='evaluation-section'>";
                            echo "<label>Evaluator Name: </label>";
                            echo "<input type='text' name='evaluator_name' value='" . htmlspecialchars($carpenterDetails['First_Name'] . ' ' . $carpenterDetails['Last_Name']) . "' readonly><br>";
                            
                            echo "<label>Date: </label>";
                            echo "<input type='text' name='evaluation_date' value='" . date('Y-m-d') . "' readonly><br>";
                        echo "</div>";
                    
                        echo "<button type='submit' class='submit-btn'>Submit Evaluation</button>";
                    echo "</form>";

                    echo "<div class='floating-summary' id='floatingSummary'>
                            <div class='total-score'>
                                <label>Total Score: </label>
                                <input type='number' id='floatingTotalScore' value='0' readonly>
                                <span>/70</span>
                            </div>
                            <div id='floatingDecision'></div>
                        </div>";
                    
                    // Remove the duplicate styles and keep only the back button
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
function submitForm(action) {
    document.getElementById('planForm').action = action;
    document.getElementById('planForm').submit();
}
</script>
<script>
function toggleYesNo(inputId, value, event) {
    document.getElementById(inputId).value = value;
    
    // Get all buttons inside the parent td
    const buttons = event.target.parentElement.getElementsByClassName('yes-no-btn');
    
    // Remove 'active' class from all buttons
    Array.from(buttons).forEach(btn => {
        btn.classList.remove('active');
    });

    // Add 'active' class to the clicked button
    event.target.classList.add('active');
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function calculateTotalScore() {
        let total = 0;

        // Calculate Project Scope scores (5 items)
        for (let i = 1; i <= 5; i++) {
            let input = document.getElementsByName('scope_' + i)[0];
            total += parseInt(input.value) || 0;
        }

        // Calculate Site Considerations scores (5 items)
        for (let i = 1; i <= 5; i++) {
            let input = document.getElementsByName('site_' + i)[0];
            total += parseInt(input.value) || 0;
        }

        // Calculate Workforce scores (4 items)
        for (let i = 1; i <= 4; i++) {
            let input = document.getElementsByName('workforce_' + i)[0];
            total += parseInt(input.value) || 0;
        }

        // Update both main and floating total scores
        document.getElementById('totalScore').value = total;
        document.getElementById('floatingTotalScore').value = total;
        
        // Update the decisions
        updateFinalDecision(total);
    }

    function updateFinalDecision(totalScore) {
        const decisionContainer = document.getElementById('autoDecision');
        const floatingDecision = document.getElementById('floatingDecision');
        const decisionInput = document.getElementById('finalDecisionInput');
        let decision = '';
        let decisionText = '';
        let decisionClass = '';

        if (totalScore >= 40) {
            decision = 'accept';
            decisionText = 'Highly Acceptable - Proceed with the project';
            decisionClass = 'accept';
        } else if (totalScore >= 30) {
            decision = 'accept_conditions';
            decisionText = 'Acceptable - Minor adjustments needed';
            decisionClass = 'accept-conditions';
        } else if (totalScore >= 20) {
            decision = 'revise';
            decisionText = 'Conditional - Requires significant changes';
            decisionClass = 'revise';
        } else {
            decision = 'reject';
            decisionText = 'Not Acceptable - Project revision or rejection recommended';
            decisionClass = 'reject';
        }

        decisionInput.value = decision;
        
        const decisionHTML = `
            <div class="decision-result ${decisionClass}">
                <h4>Automated Decision Based on Score:</h4>
                <p>${decisionText}</p>
            </div>
        `;
        
        decisionContainer.innerHTML = decisionHTML;
        floatingDecision.innerHTML = decisionHTML;
    }

    // Add event listeners to all number inputs
    const numberInputs = document.querySelectorAll('input[type="number"]');
    numberInputs.forEach(input => {
        input.addEventListener('input', calculateTotalScore);
    });

    // Run initial calculation
    calculateTotalScore();
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const floatingSummary = document.getElementById('floatingSummary');
    let isDragging = false;
    let currentX;
    let currentY;
    let initialX;
    let initialY;
    let xOffset = 0;
    let yOffset = 0;

    floatingSummary.addEventListener('mousedown', dragStart);
    document.addEventListener('mousemove', drag);
    document.addEventListener('mouseup', dragEnd);

    function dragStart(e) {
        initialX = e.clientX - xOffset;
        initialY = e.clientY - yOffset;

        if (e.target === floatingSummary) {
            isDragging = true;
        }
    }

    function drag(e) {
        if (isDragging) {
            e.preventDefault();
            currentX = e.clientX - initialX;
            currentY = e.clientY - initialY;

            xOffset = currentX;
            yOffset = currentY;

            setTranslate(currentX, currentY, floatingSummary);
        }
    }

    function setTranslate(xPos, yPos, el) {
        el.style.transform = `translate3d(${xPos}px, ${yPos}px, 0)`;
    }

    function dragEnd() {
        initialX = currentX;
        initialY = currentY;
        isDragging = false;
    }
});
</script>