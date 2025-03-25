<?php
session_start();
include('config.php');

if (!isset($_SESSION['User_ID'])) {
    header('Location: login.html');
    exit();
}

$User_ID = $_SESSION['User_ID'];

// Get user details
$sql = "SELECT * FROM users WHERE User_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $User_ID);
$stmt->execute();
$result = $stmt->get_result();
$userDetails = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Plan Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Verdana, sans-serif;
            margin: 0;
            background-color: white;
            font-size: 20px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin-left: 250px;
            padding: 20px;
            height: auto;
            background-color: white;
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
            padding: 20px;
            margin-bottom: 20px;
        }

        .sidenav .profile-section img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .sidenav a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #000000;
            display: block;
            transition: 0.3s;
        }

        .sidenav a:hover {
            background-color: #000000;
            color: #FF8C00;
        }

        /* Grid styles */
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
        }

        h1 {
            font-size: 48px;
            margin-bottom: 30px;
            font-weight: bold;
        }

        h3 {
            font-size: 32px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 24px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 22px;
        }

        textarea {
            width: 100%;
            min-height: 150px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 22px;
        }

        .client-photo {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            display: block;
        }

                /* Evaluation Form Styles */
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
            color: rgb(0, 0, 0);
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
            color: #000000;
        }

        .eval-table th {
            background-color: #FF8C00;
            color: white;
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

        .total-score span {
            color: #000000;
            font-weight: bold;
        }

        .decision-group {
            margin: 15px 0;
            padding: 15px;
            background-color: #f8f8f8;
            border-radius: 8px;
        }

        .decision-group label {
            display: block;
            margin: 10px 0;
            font-weight: bold;
        }

        .evaluation-section h4 {
            color: #000000;
            font-size: 24px;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #FF8C00;
        }

    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidenav">
        <div class="profile-section">
            <?php
            if (isset($userDetails['Photo']) && !empty($userDetails['Photo'])) {
                echo '<img src="' . $userDetails['Photo'] . '" alt="Profile Picture">';
            } else {
                echo '<img src="assets/img/default-avatar.png" alt="Default Profile Picture">';
            }
            echo "<h3>" . $userDetails['First_Name'] . ' ' . $userDetails['Last_Name'] . "</h3>";
            echo "<p>User ID: " . $User_ID . "</p>";
            ?>
        </div>
        <a href="usercomment.php"><i class="fas fa-home"></i> Home</a>
        <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
        <a href="getideas.php"><i class="fas fa-lightbulb"></i> Get Ideas</a>
        <a href="project.php"><i class="fas fa-project-diagram"></i> Project</a>
        <a href="userprofile.php"><i class="fas fa-user"></i> Profile</a>
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
                $userQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
                $userStmt = mysqli_prepare($conn, $userQuery);
                mysqli_stmt_bind_param($userStmt, "i", $userId);
                mysqli_stmt_execute($userStmt);
                $userResult = mysqli_stmt_get_result($userStmt);
                $userRow = mysqli_fetch_assoc($userResult);
                $clientName = ($userRow) ? "{$userRow['First_Name']} {$userRow['Last_Name']}" : "Unknown Client";

                // Grid container for plan details
                echo "<div class='grid-container'>";
                
                // Client Info
                echo "<div class='grid-item'>";
                echo "<h3>Client Information</h3>";
                echo "<img src='" . (isset($userDetails['Photo']) ? $userDetails['Photo'] : 'assets/img/default-avatar.png') . "' class='client-photo'>";
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

                echo "<div class='grid-item' style='grid-column: span 3;'>";
                echo "<h3>Evaluation Form Answers</h3>";
                
                // Fetch evaluation answers for the specific plan
                $evalQuery = "SELECT c.carpenter_ID, c.First_Name, c.Last_Name, pa.* 
                            FROM plan_approval pa 
                            JOIN carpenters c ON pa.carpenter_ID = c.carpenter_ID 
                            WHERE pa.plan_ID = ?";
                $evalStmt = mysqli_prepare($conn, $evalQuery);
                mysqli_stmt_bind_param($evalStmt, "i", $plan_ID);
                mysqli_stmt_execute($evalStmt);
                $evalResult = mysqli_stmt_get_result($evalStmt);
                
                if (mysqli_num_rows($evalResult) > 0) {
                    while ($evalRow = mysqli_fetch_assoc($evalResult)) {
                        echo "<div class='evaluation-section'>";
                        echo "<h4>Carpenter: {$evalRow['First_Name']} {$evalRow['Last_Name']}</h4>";
                        
                        // Project Scope & Feasibility
                        echo "<div class='evaluation-section'>";
                        echo "<h3>1. Project Scope & Feasibility</h3>";
                        echo "<table class='eval-table'>";
                        echo "<tr><th>Criteria</th><th>Score</th></tr>";
                        
                        $scope_scores = json_decode($evalRow['scope_scores'], true);
                        $scope_criteria = [
                            "Project size aligns with our capacity",
                            "Availability of required materials",
                            "Timeline is realistic and achievable",
                            "Budget is sufficient for project completion",
                            "Client expectations are clear and defined"
                        ];
                        
                        for ($i = 0; $i < count($scope_scores); $i++) {
                            echo "<tr><td>{$scope_criteria[$i]}</td><td>{$scope_scores[$i]}/5</td></tr>";
                        }
                        echo "</table></div>";
                        
                        // Site & Environmental Considerations
                        echo "<div class='evaluation-section'>";
                        echo "<h3>2. Site & Environmental Considerations</h3>";
                        echo "<table class='eval-table'>";
                        echo "<tr><th>Criteria</th><th>Score</th></tr>";
                        
                        $site_scores = json_decode($evalRow['site_scores'], true);
                        $site_criteria = [
                            "Accessibility of the project site",
                            "Environmental impact considerations",
                            "Legal and zoning requirements met",
                            "Safety concerns addressed",
                            "Potential hazards identified and manageable"
                        ];
                        
                        for ($i = 0; $i < count($site_scores); $i++) {
                            echo "<tr><td>{$site_criteria[$i]}</td><td>{$site_scores[$i]}/5</td></tr>";
                        }
                        echo "</table></div>";
                        
                        // Client Readiness & Commitment
                        echo "<div class='evaluation-section'>";
                        echo "<h3>3. Client Readiness & Commitment</h3>";
                        echo "<table class='eval-table'>";
                        echo "<tr><th>Criteria</th><th>Response</th><th>Comments</th></tr>";
                        
                        $client_responses = json_decode($evalRow['client_responses'], true);
                        $client_comments = json_decode($evalRow['client_comments'], true);
                        $client_criteria = [
                            "Client has secured necessary permits",
                            "Client is financially prepared",
                            "Client has realistic expectations",
                            "Client is cooperative and responsive",
                            "Client has a clear design or blueprint"
                        ];
                        
                        for ($i = 0; $i < count($client_responses); $i++) {
                            echo "<tr>";
                            echo "<td>{$client_criteria[$i]}</td>";
                            echo "<td>{$client_responses[$i]}</td>";
                            echo "<td>{$client_comments[$i]}</td>";
                            echo "</tr>";
                        }
                        echo "</table></div>";
                        
                        // Workforce & Resource Availability
                        echo "<div class='evaluation-section'>";
                        echo "<h3>4. Workforce & Resource Availability</h3>";
                        echo "<table class='eval-table'>";
                        echo "<tr><th>Criteria</th><th>Score</th></tr>";
                        
                        $workforce_scores = json_decode($evalRow['workforce_scores'], true);
                        $workforce_criteria = [
                            "Skilled labor is available",
                            "Equipment and tools are available",
                            "Project workload is manageable",
                            "Subcontractors (if needed) are available"
                        ];
                        
                        for ($i = 0; $i < count($workforce_scores); $i++) {
                            echo "<tr><td>{$workforce_criteria[$i]}</td><td>{$workforce_scores[$i]}/5</td></tr>";
                        }
                        echo "</table></div>";
                        
                        // Total Score and Final Decision
                        echo "<div class='evaluation-section'>";
                        echo "<div class='total-score'>";
                        echo "<label>Total Evaluation Score: {$evalRow['total_score']}/70</label>";
                        echo "</div>";
                        
                        // Decision with color coding
                        $decisionClass = '';
                        switch($evalRow['final_decision']) {
                            case 'accept':
                                $decisionClass = 'background-color: #4CAF50; color: white; padding: 10px; border-radius: 5px; display: inline-block;';
                                break;
                            case 'accept_conditions':
                                $decisionClass = 'background-color: #FFA500; color: white; padding: 10px; border-radius: 5px; display: inline-block;';
                                break;
                            case 'revise':
                                $decisionClass = 'background-color: #FF8C00; color: white; padding: 10px; border-radius: 5px; display: inline-block;';
                                break;
                            case 'reject':
                                $decisionClass = 'background-color: #f44336; color: white; padding: 10px; border-radius: 5px; display: inline-block;';
                                break;
                        }
                        
                        echo "<p><strong>Final Decision: </strong><span style='{$decisionClass}'>" . ucfirst($evalRow['final_decision']) . "</span></p>";
                        echo "<p><strong>Evaluator:</strong> {$evalRow['evaluator_name']}</p>";
                        echo "<p><strong>Date:</strong> {$evalRow['evaluation_date']}</p>";
                        echo "</div>";
                        
                        echo "</div>"; // Close evaluation-section
                    }
                } else {
                    echo "<p>No evaluations found for this plan.</p>";
                }
                echo "</div>";
                echo "</div>"; // Close grid-container

                // Go back button
                echo "<div style='text-align: center; margin-top: 30px; margin-bottom: 30px;'>";
                echo "<button onclick=\"window.location.href='userprofile.php'\" 
                      style='width: 150px; height: 50px; background-color: #FF8C00; color: white; 
                      border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>
                      Go back</button>";
                echo "</div>";

                echo "</div>"; // Close main div
            } else {
                echo "<div class='main'>";
                echo "<p>No plan found with Plan ID: $plan_ID</p>";
                echo "</div>";
            }

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            ?>
        </div>
    </div>
</body>
</html>