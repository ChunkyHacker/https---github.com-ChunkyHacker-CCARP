<?php
session_start(); 
include('config.php');

if (!isset($_SESSION['User_ID'])) {
  header('Location: login.html');
  exit();
}

if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']); // Sanitize the message to prevent XSS
    echo "<script>alert('$message');</script>";
}

// Check if success parameter is passed in the URL
if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_GET['message'])) {
    // Sanitize the message to prevent XSS
    $message = htmlspecialchars($_GET['message']);
    // Output the alert with the message
    echo "<script>alert('$message');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        html {
            box-sizing: border-box;
        }

        *, *:before, *:after {
            box-sizing: inherit;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0 auto;
            max-width: auto;
            padding: 5px;
            padding-top: 170px;
            font-size: 20px; /* Updated font size */
        }

        .header {
            text-align: left;
            background: #FF8C00;
            color: #000;
            display: flex;
            align-items: center;
            text-decoration: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px;
            z-index: 100;
        }

        .header h1 {
            font-size: 20px; /* Updated font size */
            border-left: 20px solid transparent;
            text-decoration: none;
        }

        .header a {
            font-size: 20px; /* Updated font size */
            font-weight: bold;
            text-decoration: none;
            color: #000;
            margin-right: 45px;
        }

        .header .right {
            margin-right: 20px;
        }

        .main {
            flex: 70%;
            background-color: white;
            text-align: center;
            margin: auto;
        }

        h1 {
            font-size: 50px; /* Updated font size */
            word-break: break-all;
            padding-left: 50px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: left;
            margin-top: 40px;
        }

        .column {
            float: left;
            text-align: center;
            justify-content: center;
            margin: 10px;
        }

        .content {
            background-color: white;
            padding: 15px;
        }

        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }

        .container {
            padding: 30px;
        }

        .button {
            border: none;
            outline: 0;
            display: inline-block;
            padding: 8px;
            color: #000;
            background-color: #FF8C00;
            text-align: center;
            cursor: pointer;
            width: 100%;
            text-decoration: none;
        }

        .button:hover {
            background-color: #535353;
        }

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
                font-size: 50px; /* Updated font size */
            }
        }

        @media screen and (max-width: 700px) {
            .row {   
                flex-direction: column;
            }
        }

        @media screen and (max-width: 400px) {
            .navbar a {
                float: none;
                width: 100%;
            }
        }
    </style>

</head>
<body>
    <div class="header">
        <a href="usercomment.php">
            <h1>
                <img src="assets/img/logos/logo.png" alt="" style="width: 75px; margin-right: 10px;">
            </h1>
        </a>
        <div class="right">
            <a href="usercomment.php">Home</a>
            <a href="about/index.html">About</a>
            <a href="#contact">Get Ideas</a>
            <a href="plan.php">Project</a>
        </div>
        <a href="logout.php" style="text-decoration: none; color: black; margin-left: auto; margin-right: 20px;">Log Out</a>
    </div>

    <?php
    if (isset($_SESSION['User_ID'])) {
        $User_ID = $_SESSION['User_ID'];

        $sql = "SELECT * FROM users WHERE User_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $User_ID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $photoPath = $user['Photo'];

            if (!empty($photoPath) && file_exists($photoPath)) {
                echo '<div class="row">
                        <div class="column">
                            <div class="card">
                                <img src="' . $photoPath . '" alt="Profile Picture" style="width: 300px; height: 300px; padding:10px; ">
                                <div class="container">
                                    <h2>' . $user['First_Name'] . ' ' . $user['Last_Name'] . '</h2>
                                    <p class="title">User</p>
                                    <p>' . $user['Email'] . '</p>
                                    <p><button class="button"><a href="chat.html" style="text-decoration: none; color: black;">Contact User</a></button></p>
                                </div>
                            </div>
                        </div>
                    </div>';
            } else {
                echo "Image file not found: $photoPath";
            }
        } else {
            echo "No user found with User_ID: $User_ID";
        }
    } else {
        header("Location: login.html");
        exit();
    }
    ?>

    <h1>Your Requests:</h1>
    <div class="row">
        <?php
        $query = "SELECT * FROM plan";
        $result = mysqli_query($conn, $query);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="column" style="background-color: #FF8C00; margin: 10px 0; padding: 15px; border-radius: 10px;">';
                echo "<div class='content' style='background-color: #FFCB8B; padding: 10px; border-radius: 5px;'>";
                echo "<ul>";

                $userId = $row['User_ID'];
                $userQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
                $userStmt = mysqli_prepare($conn, $userQuery);
                mysqli_stmt_bind_param($userStmt, "i", $userId);
                mysqli_stmt_execute($userStmt);
                $userResult = mysqli_stmt_get_result($userStmt);

                if ($userRow = mysqli_fetch_assoc($userResult)) {
                    echo "<p>Client Name: {$userRow['First_Name']} {$userRow['Last_Name']}</p>";
                }

                $photoPath = $row['Photo'];
                if (!empty($photoPath) && file_exists($photoPath)) {
                    echo "<a href='#' onclick='openModal(\"{$photoPath}\")'>";
                    echo "<img src='{$photoPath}' alt='Carpenter Photo' style='width: 150px; height: auto;'>";
                    echo "</a>";
                }

                echo "<p><a href='userclientsplan.php?plan_id={$row['plan_ID']}'>View your plan</a></p>";
                echo "</li>";
                echo "</ul>";
                echo "</div>";
                echo "</div>";
            }
            mysqli_free_result($result);
        } else {
            echo "Error fetching data: " . mysqli_error($conn);
        }
        ?>
    </div>

    <h1>Declined Plan:</h1>
    <div class="row">
        <?php
        $query = "SELECT * FROM declinedplan";
        $result = mysqli_query($conn, $query);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="column" style="background-color: #FF8C00; margin: 10px 0; padding: 15px; border-radius: 10px;">';
                echo "<div class='content' style='background-color: #FFCB8B; padding: 10px; border-radius: 5px;'>";
                echo "<ul>";

                $userId = $row['User_ID'];
                $userQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
                $userStmt = mysqli_prepare($conn, $userQuery);
                mysqli_stmt_bind_param($userStmt, "i", $userId);
                mysqli_stmt_execute($userStmt);
                $userResult = mysqli_stmt_get_result($userStmt);

                if ($userRow = mysqli_fetch_assoc($userResult)) {
                    echo "<p>Client Name: {$userRow['First_Name']} {$userRow['Last_Name']}</p>";
                }

                $photoPath = $row['Photo'];
                if (!empty($photoPath) && file_exists($photoPath)) {
                    echo "<a href='#' onclick='openModal(\"{$photoPath}\")'>";
                    echo "<img src='{$photoPath}' alt='Carpenter Photo' style='width: 150px; height: auto;'>";
                    echo "</a>";
                }

                echo "<p><a href='userviewdeclinedplan.php?declined_plan_ID={$row['declined_plan_ID']}'>View your plan</a></p>";
                echo "</li>";
                echo "</ul>";
                echo "</div>";
                echo "</div>";
            }
            mysqli_free_result($result);
        } else {
            echo "Error fetching data: " . mysqli_error($conn);
        }
        ?>
    </div>

<h1>Ongoing project:</h1>
<div class="row">
      <?php
      require_once "config.php";

      $query = "SELECT * FROM projectrequirements";
      $result = mysqli_query($conn, $query);

      if ($result) {
        while ($row = mysqli_fetch_assoc($result))  {
            echo '<div class="column" style="background-color: #FF8C00; margin: 10px 0; padding: 15px; border-radius: 10px;">';
            echo "<div class='content' style='background-color: #FFCB8B; padding: 10px; border-radius: 5px;'>";
            echo "<ul>";
    
            // Fetch user details from the 'users' table
            $userId = $row['User_ID'];
            $userQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
            $userStmt = mysqli_prepare($conn, $userQuery);
            mysqli_stmt_bind_param($userStmt, "i", $userId);
            mysqli_stmt_execute($userStmt);
            $userResult = mysqli_stmt_get_result($userStmt);
    
            if ($userRow = mysqli_fetch_assoc($userResult)) {
                echo "<p>Client Name: {$userRow['First_Name']} {$userRow['Last_Name']}</p>";
            }
      
            $photoPath = $row['Photo'];
            if (!empty($photoPath) && file_exists($photoPath)) {
                echo "<a href='#' onclick='openModal(\"{$photoPath}\")'>";
                echo "<img src='{$photoPath}' alt='Carpenter Photo' style='width: 150px; height: auto;'>";
                echo "</a>";
            }

    
            echo "<p><a href='userviewaddedrequirements.php?requirement_ID={$row['requirement_ID']}'>View your ongoing plan</a></p>";
    
            echo "</li>";
            echo "</ul>";
            echo "</div>";
            echo "</div>";
        }
          mysqli_free_result($result);
      } else {
          echo "Error fetching data: " . mysqli_error($conn);
      }

      ?>
</div>
</body>
</html>
