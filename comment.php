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
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            padding-top: 200px; /* Adjust this value to ensure content is pushed below the fixed header */
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px;
            background: #FF8C00;
            color: #000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100; /* Ensures the header stays on top */
        }

        .header h1 {
            font-size: 40px;
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
            top: 60px; /* Adjust based on the header's height */
            width: 100%;
            background-color: #505050;
            z-index: 99; /* Ensures it's under the header but above other content */
        }


        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .input-link {
            flex: 1;
            display: flex;
            text-decoration: none;
        }

        .input-container {
            width: 100%; /* Make input container take full width */
            border: none;
            border-radius: 20px;
            padding: 10px 15px;
            font-size: 14px;
            background-color: #e4e6eb;
            outline: none;
            cursor: pointer;
        }

        .post {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 0; /* Remove margin-top */
            margin-bottom: 10px; /* Reduced bottom margin for less space */
            padding: 15px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .username {
            font-weight: bold;
            font-size: 16px;
        }

        .post-image {
            margin: 15px 0;
        }

        .client-photo {
            width: 100%;
            border-radius: 8px;
            object-fit: cover;
            max-height: 400px;
        }

        .post-footer {
            display: flex;
            justify-content: flex-start;
            margin-top: 15px;
        }

        .like-btn {
            background-color: #FF8C00; /* Blue color */
            color: #000;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-right: 10px; 
        }

        .view-plan-btn {
            background-color: #FF8C00; /* Same color as like button */
            color: #000;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none; /* Remove underline */
            display: inline-block; /* Make it behave like a button */
            cursor: pointer;
            margin-left: 10px; /* Add space between the like button and the link */
        }

        .view-plan-btn:hover {
            background-color: #365899; /* Darken the color on hover */
        }

        .comment-btn {
            background-color: #FF8C00; /* Teal color */
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 20px; /* Match the input's border radius */
            font-size: 14px;
            cursor: pointer;
        }

        .like-btn:hover {
            background-color: #365899;
        }

        .comment-btn:hover {
            background-color: #009e8f; /* Darker teal on hover */
        }

        .comments-section {
            display: flex;
            align-items: center; /* Vertically centers the button and input field */
            margin-top: 10px;
        }

        .comment-input {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 10px;
            outline: none;
            font-size: 14px;
            margin-right: 10px; /* Adds space between the input and the button */
        }

        .error {
            color: red;
            font-weight: bold;
        }

        .footer {
            padding: 10px;
            text-align: center;
            background: #FF8C00;
            position: relative;
            width: 100%;
        }   
    </style>
</head>
<body>
    <div class="header">
        <a href="usercomment.php">
            <h1>
                <img src="assets/img/logos/logo.png" alt="Logo" style="width: 75px; margin-right: 10px;">
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

    <?php
    $query = "SELECT * FROM plan";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='post'>";
            
            $userId = $row['User_ID'];
            $userQuery = "SELECT First_Name, Last_Name, Photo FROM users WHERE User_ID = ?";
            $userStmt = mysqli_prepare($conn, $userQuery);
            mysqli_stmt_bind_param($userStmt, "i", $userId);
            mysqli_stmt_execute($userStmt);
            $userResult = mysqli_stmt_get_result($userStmt);

            if ($userRow = mysqli_fetch_assoc($userResult)) {
                echo "<div class='post-header'>";
                
                // Get the profile picture path from the user record
                $profilePicture = $userRow['Photo'];

                // Check if the profile picture exists, otherwise set a default image
                if (!empty($profilePicture) && file_exists($profilePicture)) {
                    echo "<div class='avatar-container'>";
                    echo "<img src='{$profilePicture}' alt='Avatar' class='avatar'>";
                    echo "</div>";
                } else {
                    // Fallback to default avatar if no profile picture is found
                    echo "<div class='avatar-container'>";
                    echo "<img src='blank-profile-picture-gb4f5f9059_640.png' alt='Avatar' class='avatar'>";
                    echo "</div>";
                }

                echo "<div class='username'>{$userRow['First_Name']} {$userRow['Last_Name']}</div>";
                echo "</div>";
            }

            $photoPath = $row['Photo'];
            if (!empty($photoPath) && file_exists($photoPath)) {
                echo "<div class='post-image'>";
                echo "<a href='#' onclick='openModal(\"{$photoPath}\")'>";
                echo "<img src='{$photoPath}' alt='Photo' class='client-photo'>";
                echo "</a>";
                echo "</div>";
            } else {
                // If no photo or invalid photo path, display a placeholder image
                echo "<div class='post-image'>";
                echo "<img src='default-placeholder.jpg' alt='No photo available' class='client-photo'>";
                echo "</div>";
            }

            echo "<div class='post-footer'>";
            echo "<button class='like-btn'>Like</button>";
            echo "<a href='clientsplan.php?plan_id={$row['plan_ID']}' class='view-plan-btn'>View client plan</a>";
            echo "</div>";                

            echo "<div class='comments-section'>";
            echo "<input type='text' class='comment-input' placeholder='Write a comment...'>";
            echo "<button class='comment-btn'>Comment</button>";
            echo "</div>";

            echo "</div>";
        }

        mysqli_free_result($result);
    } else {
        echo "<div class='error'>Error fetching data: " . mysqli_error($conn) . "</div>";
    }

    mysqli_close($conn);
?>

</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</html>
