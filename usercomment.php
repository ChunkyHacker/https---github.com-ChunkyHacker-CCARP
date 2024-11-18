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
            font-size: 20px; /* Default font size */
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
            font-size: 20px; /* Updated font size */
            padding-left: 20px;
        }

        .header a {
            font-size: 20px; /* Updated font size */
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

        .makepost {
            display: flex;
            align-items: center; /* Center content horizontally */
            background-color: #fff;
            border-radius: 8px;
            padding: 20px; /* Add space inside the container */
            width: 100%; /* Take up the full width of its parent */
            max-width: 700px; /* Maximum width of the container */
            height: auto; /* Allow the height to grow based on the content */
            margin: 150px auto 20px; /* Center horizontally and add top margin */
            z-index: 1;
        }

        .avatar-container {
            margin-right: 10px;
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
            font-size: 20px; /* Updated font size */
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
            font-size: 20px; /* Updated font size */
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
            font-size: 20px; /* Updated font size */
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
            font-size: 20px; /* Updated font size */
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
            font-size: 20px; /* Updated font size */
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
            font-size: 20px; /* Updated font size */
            margin-right: 10px; /* Adds space between the input and the button */
        }

        .error {
            color: red;
            font-weight: bold;
            font-size: 20px; /* Updated font size */
        }

        .no-projects {
        text-align: center;
        font-size: 50px;
        font-weight: bold;
        color: #333;
        padding: 100px;
        margin-top: 20px;
        }

        .footer {
            padding: 10px;
            text-align: center;
            background: #FF8C00;
            position: relative;
            width: 100%;
            font-size: 20px; /* Updated font size */
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
        // Check if the user is logged in
        if (isset($_SESSION['User_ID'])) {
            $userId = $_SESSION['User_ID'];

            // Fetch the user's profile photo and first name from the database
            $userQuery = "SELECT Photo, First_Name FROM users WHERE User_ID = ?";
            $userStmt = mysqli_prepare($conn, $userQuery);
            mysqli_stmt_bind_param($userStmt, "i", $userId);
            mysqli_stmt_execute($userStmt);
            $userResult = mysqli_stmt_get_result($userStmt);

            // Check if the query returns a row and get the data
            if ($userRow = mysqli_fetch_assoc($userResult)) {
                $profilePicture = $userRow['Photo'];  // User's photo
                $firstName = $userRow['First_Name'];  // User's first name
            } else {
                // If no photo or first name is found, use default values
                $profilePicture = '';
                $firstName = 'there'; // Default to a generic greeting
            }
        } else {
            // If no user is logged in, use default values
            $profilePicture = '';
            $firstName = ''; // Default to a generic greeting
        }

        // Output the HTML structure with profile picture check and customized placeholder
        echo '<div class="container my-3">';
        echo '    <div class="makepost">';

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

        echo '    <a href="usercreateplan.php" class="input-link">';
        echo '        <input type="text" class="input-container" placeholder="What\'s on your mind' . htmlspecialchars($firstName) . '?" readonly>';
        echo '    </a>';
        echo '    </div>';
        echo '</div>';
    ?>




    <?php
        $query = "SELECT * FROM plan";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Check if there are any rows in the result set
            if (mysqli_num_rows($result) > 0) {
                // Fetch and display the rows if they exist
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

                    // Display "More Details" above the photo
                    $moreDetails = $row['more_details']; // Assuming 'more_details' is the field name in your database
                    if (!empty($moreDetails)) {
                        echo "<div class='more-details'>";
                        echo "<p>" . htmlspecialchars($moreDetails) . "</p>"; // Escape for security
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
                    echo "<a href='userclientsplan.php?plan_id={$row['plan_ID']}' class='view-plan-btn'>View your plan</a>";
                    echo "</div>";

                    echo "<div class='comments-section'>";
                    echo "<input type='text' class='comment-input' placeholder='Write a comment...'>";
                    echo "<button class='comment-btn'>Comment</button>";
                    echo "</div>";

                    echo "</div>";
                }
            } else {
                // Display the "No projects available" message if no rows are found
                echo "<div class='no-projects'>Need some carpenter to build your house? Post now!</div>";
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
