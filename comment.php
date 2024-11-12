<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments</title>
</head>
<style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Verdana, sans-serif;
            margin: 0;
            text-align: center;
            /* Center the content */
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
        }

        .header h1 {
            font-size: 40px;
            border-left: 20px solid transparent;
            padding-left: 20px;
            text-decoration: none;
        }

        .right {
            margin-right: 30px;
            margin-left: 30px;
        }

        .header a {
            font-size: 25px;
            font-weight: bold;
            text-decoration: none;
            color: #000000;
        }

        .topnav {
            position: fixed;
            top: 120px;
            width: 100%;
            overflow: hidden;
            background-color: #505050;
            z-index: 100;
        }

        .topnav a {
            position: sticky;
            float: left;
            display: block;
            color: black;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 30px;
        }

        .topnav a,
        .topnav a.active {
            color: black;
        }

        .topnav a:hover,
        .topnav a.active:hover {
            background-color: #FF8C00;
            color: black;
        }

        @media screen and (max-width: 600px) {
            .topnav a,
            .topnav input[type=text] {
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
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding-top: 160px;
        }

        .main {
            -ms-flex: 70%;
            flex: 70%;
            background-color: white;
            padding: 20px;
            text-align: center;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        * {
            box-sizing: border-box;
        }

        .makepost {
            max-width: 1000px;
            width: 100%;
            margin: 0 auto;
            margin-bottom: 30px;
            margin-top: 30px;
            background-color: #ffffff;
            border: 1px solid #dddfe2;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }

        .makepost .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .makepost textarea {
            flex: 1;
            resize: none;
            padding: 10px;
            border: 1px solid #dddfe2;
            border-radius: 20px;
            outline: none;
            font-size: 16px;
            font-family: Arial, sans-serif;
            margin-bottom: 10px;
        }

        .makepost a button {
            background-color: #FF8C00;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            max-width: 1000px;
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .modal textarea {
            width: 100%;
            height: 100px;
            resize: none;
            padding: 10px;
            border: 1px solid #dddfe2;
            border-radius: 5px;
            outline: none;
            font-size: 16px;
            font-family: Arial, sans-serif;
            margin-bottom: 10px;
        }

        .modal .icons-container {
            text-align: right;
        }

        .modal .icons-container i {
            margin-left: 10px;
            cursor: pointer;
            color: #606770;
            font-size: 20px;
        }

        .modal .icons-container i:hover {
            color: #1c1e21;
        }

        .modal .post-btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #FF8C00;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            line-height: 20px;
            box-sizing: border-box;
        }

        .modal .post-btn:hover {
            background-color: #000000;
        }

        .post-container {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .post {
            display: flex;
            align-items: center;
        }

        .post-container {
            max-width: 1000px;
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            margin-top: 30px;
            margin-bottom: 30px;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #ccc;
            font-family: Arial, sans-serif;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }

        .post {
            display: flex;
            align-items: center;
            background-color: #f5f5f5;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
        }

        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .username {
            font-size: 16px;
            margin: 0;
        }

        .post-content {
            margin-bottom: 10px;
        }

        .post-text {
            margin: 0;
        }

        .post-footer {
            margin-top: 10px;
            display: flex;
            align-items: center;
        }

        .post-image {
            width: 100%;
            max-height: 400px;
            overflow: hidden;
        }

        .post-image img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .comment {
            margin-bottom: 10px;
            margin-left: 300px;
            margin-right: 300px;
            padding: 5px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }

        .comment p {
            margin: 0;
        }

        .comment-form {
            display: flex;
            margin-top: 10px;
        }

        .comment-input {
            flex-grow: 1;
            margin-right: 10px;
        }

        .comment-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
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
<body>
<div class="header">
        <a href="usercomment.php">
            <h1>
                <img src="assets/img/logos/logo.png" alt="" style="width: 75px; margin-right: 10px;">
            </h1>
        </a>
        <div class="right">
          <a href="comment.php">Home</a>
          <a href="about/index.html">About</a>
          <a href="#contact">Get Ideas</a>
        </div>
        <a href="profile.php" style="text-decoration: none; color: black; margin-left: 20px;">Profile</a>
        <a href="logout.php" style="text-decoration: none; color: black; margin-left: auto; margin-right: 20px;">Log Out</a>
</div>
 
<div class="topnav">

</div>

<div class="container">
  <div class="makepost">
    <div class="avatar-container">
      <img src="blank-profile-picture-gb4f5f9059_640.png" alt="Avatar" class="avatar">
    </div>
  </div>
</div>

<?php
include('config.php');

$query = "SELECT * FROM plan";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<li class='comment'>";
        $userId = $row['User_ID'];
        $userQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
        $userStmt = mysqli_prepare($conn, $userQuery);
        mysqli_stmt_bind_param($userStmt, "i", $userId);
        mysqli_stmt_execute($userStmt);
        $userResult = mysqli_stmt_get_result($userStmt);

        if ($userRow = mysqli_fetch_assoc($userResult)) {
            echo "<p>Client Name: {$userRow['First_Name']} {$userRow['Last_Name']}</p>";
        }

        // Display the resized photo with a link to open the modal
        $photoPath = $row['Photo'];
        if (!empty($photoPath) && file_exists($photoPath)) {
            echo "<a href='#' onclick='openModal(\"{$photoPath}\")'>";
            echo "<img src='{$photoPath}' alt='Photo' style='width: 150px; height: auto;'>";
            echo "</a>";

        }
        // Add a button to go to clientsplan.php
        echo "<p><a href='clientsplan.php?plan_id={$row['plan_ID']}'>View client plan</a></p>";

        echo "</li>";
    }

    mysqli_free_result($result);
} else {
    echo "Error fetching data: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
</body>
</html>
