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
                               WHERE pa.plan_ID = ?"; // Filter by plan_ID
                $evalStmt = mysqli_prepare($conn, $evalQuery);
                mysqli_stmt_bind_param($evalStmt, "i", $plan_ID); // Use plan_ID instead of carpenter_ID
                mysqli_stmt_execute($evalStmt);
                $evalResult = mysqli_stmt_get_result($evalStmt);
                
                if (mysqli_num_rows($evalResult) > 0) {
                    while ($evalRow = mysqli_fetch_assoc($evalResult)) {
                        echo "<div style='background-color: #f9f9f9; padding: 20px; margin-bottom: 20px; border-radius: 8px;'>";
                        echo "<h4 style='margin-bottom: 15px;'>Carpenter: {$evalRow['First_Name']} {$evalRow['Last_Name']}</h4>";
                        
                        // Evaluation Questions Table
                        echo "<table style='width: 100%; border-collapse: collapse; margin-bottom: 15px;'>";
                        echo "<tr style='background-color: #FF8C00; color: white;'>";
                        echo "<th style='padding: 10px; text-align: left;'>Question</th>";
                        echo "<th style='padding: 10px; text-align: left;'>Answer</th>";
                        echo "</tr>";
                
                        $questions = [
                            "Were the details of the project suitable for your scope of work?" => $evalRow['q1'],
                            "Are you able to finish the project even if there is an overlapping budget?" => $evalRow['q2'],
                            "Are you willing to accept an additional task with additional payment?" => $evalRow['q3'],
                            "Can you finish the project on time?" => $evalRow['q4'],
                            "Will you accept the project?" => $evalRow['q5']
                        ];
                
                        foreach ($questions as $question => $answer) {
                            echo "<tr style='background-color: white;'>";
                            echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . $question . "</td>";
                            echo "<td style='padding: 10px; border: 1px solid #ddd;'>" . $answer . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                
                        // Comment section
                        echo "<div style='margin-top: 15px;'>";
                        echo "<h4 style='margin-bottom: 10px;'>Carpenter's Comment:</h4>";
                        echo "<p style='padding: 10px; background-color: white; border-radius: 5px; border: 1px solid #ddd;'>{$evalRow['comment']}</p>";
                        echo "</div>";
                
                        echo "</div>";
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
