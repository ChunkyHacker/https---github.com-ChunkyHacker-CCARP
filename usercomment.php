<?php
    include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Verdana, sans-serif;
            margin: 0;
            text-align: center;
            padding-top: 160px;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px;
            text-align: left;
            background: #FF8C00;
            color: #000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
        }

        .header h1 {
            font-size: 40px;
            border-left: 20px solid transparent;
            padding-left: 20px;
        }

        .header a {
            font-size: 25px;
            font-weight: bold;
            text-decoration: none;
            color: #000;
            margin-right: 30px;
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
            float: left;
            display: block;
            color: black;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 30px;
        }

        .topnav a:hover {
            background-color: #FF8C00;
            color: black;
        }

        @media screen and (max-width: 600px) {
            .topnav a {
                float: none;
                display: block;
                text-align: left;
                width: 100%;
                padding: 14px;
            }

            .topnav input[type=text] {
                border: 1px solid #ccc;
            }
        }

        .main {
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
            max-width: 1000px;
            width: 100%;
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

        .makepost {
            max-width: 1000px;
            width: 100%;
            margin: 30px auto;
            background-color: #fff;
            border: 1px solid #dddfe2;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            align-items: flex-start;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
            margin-bottom: 10px;
        }

        .makepost button {
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
        }

        .modal .post-btn:hover {
            background-color: #000;
        }

        .post-container {
            max-width: 1000px;
            width: 100%;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
            margin: 20px auto;
            padding: 5px;
            background-color: #f1f1f1;
            border-radius: 5px;
            max-width: 700px;
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
</head>
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
        <a href="userprofile.php" style="text-decoration: none; color: black; margin-left: 20px;">Profile</a>
        <a href="logout.php" style="text-decoration: none; color: black; margin-left: auto; margin-right: 20px;">Log Out</a>
    </div>

    <div class="topnav"></div>

    <div class="container">
        <div class="makepost">
            <div class="avatar-container">
                <img src="blank-profile-picture-gb4f5f9059_640.png" alt="Avatar" class="avatar">
            </div>
            <a href="usercreateplan.php"><button>Create plan</button></a>
        </div>
    </div>

    <?php

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

            $photoPath = $row['Photo'];
            if (!empty($photoPath) && file_exists($photoPath)) {
                echo "<a href='#' onclick='openModal(\"{$photoPath}\")'>";
                echo "<img src='{$photoPath}' alt='Photo' style='width: 150px; height: auto;'>";
                echo "</a>";

                echo "<p><a href='userclientsplan.php?plan_id={$row['plan_ID']}'>View client plan</a></p>";
            }

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
