<?php
    session_start(); 
    include('config.php');

    if (!isset($_SESSION['User_ID'])) {
    header('Location: login.html');
    exit();
}

$User_ID = $_SESSION['User_ID'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comments</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            background-color: #FF8600;
            height: 100vh;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px;
            font-size: 20px;
        }

        .profile-section {
            text-align: center;
            margin-bottom: 30px;
            font-size: 20px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .profile-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .user-id {
            font-size: 14px;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 10px;
            color: #000;
            text-decoration: none;
            margin-bottom: 5px;
        }

        .nav-link i {
            margin-right: 10px;
            width: 20px;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            border-radius: 5px;
        }

        .main-content {
            margin-left: 250px;
            padding: 10px;
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
            background-color: #fff;
            border-radius: 8px;
            padding: 15px;
            width: 100%;
            max-width: 700px;
            margin: 10px auto;
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
            margin: 10px auto;
            padding: 15px;
            max-width: 700px;
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
    <div class="sidebar">
        <div class="profile-section">
            <?php
            $sql = "SELECT * FROM users WHERE User_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $User_ID);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (isset($user['Photo']) && !empty($user['Photo'])) {
                echo '<img src="' . $user['Photo'] . '" class="profile-image" alt="Profile Picture">';
            } else {
                echo '<img src="assets/img/default-avatar.png" class="profile-image" alt="Default Profile Picture">';
            }
            ?>
            <div class="profile-name"><?php echo $user['First_Name'] . ' ' . $user['Last_Name']; ?></div>
            <div class="user-id">User ID: <?php echo $User_ID; ?></div>
        </div>

        <a href="usercomment.php" class="nav-link">
            <i class="fa fa-home"></i>
            <span>Home</span>
        </a>
        <a href="about/index.html" class="nav-link">
            <i class="fa fa-info-circle"></i>
            <span>About</span>
        </a>
        <a href="#contact" class="nav-link">
            <i class="fa fa-lightbulb-o"></i>
            <span>Get Ideas</span>
        </a>
        <a href="usercreateplan.php" class="nav-link">
            <i class="fa fa-plus-circle"></i>
            <span>Create Plan</span>
        </a>
        <a href="userprofile.php" class="nav-link">
            <i class="fa fa-user"></i>
            <span>Profile</span>
        </a>
        <a href="logout.php" class="nav-link">
            <i class="fa fa-sign-out"></i>
            <span>Logout</span>
        </a>
    </div>

    <div class="main-content">
        <!-- Create post section -->
        <div class="makepost">
            <img src="<?php echo !empty($user['Photo']) ? $user['Photo'] : 'assets/img/default-avatar.png'; ?>" alt="Avatar" class="avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
            <a href="usercreateplan.php" style="text-decoration: none; flex: 1;">
                <div style="background: #f0f2f5; padding: 10px; border-radius: 20px; color: #65676b;">
                    What's on your mind?
                </div>
            </a>
        </div>

        <!-- Posts display -->
        <?php
        $query = "SELECT p.*, u.First_Name, u.Last_Name, u.Photo as UserPhoto 
                 FROM plan p 
                 JOIN users u ON p.User_ID = u.User_ID 
                 ORDER BY p.plan_ID DESC";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
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
            echo "<div style='text-align: center; margin-top: 20px;'>No plans available yet.</div>";
        }
        ?>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</html>
