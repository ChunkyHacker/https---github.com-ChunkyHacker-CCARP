<?php
include('config.php');
session_start(); // Siguraduhon nga nagsugod ang session

if (!isset($_SESSION['Carpenter_ID'])) {
    die("Error: No Carpenter ID found in session. Please log in.");
}

$Carpenter_ID = $_SESSION['Carpenter_ID'];
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
            padding-top: 200px; /* Adjust this value to ensure content is pushed below the fixed header */
            font-size: 20px; /* Set base font size to 20px */
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
            font-size: 40px; /* Keep h1 larger */
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
            margin-bottom: 10px; /* Reduced from original */
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

        .comments-container {
            margin-top: 15px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .comment {
            padding: 8px;
            margin: 5px 0;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .comment strong {
            color: #365899;
        }

        .comment-date {
            font-size: 12px;
            color: #666;
            margin-left: 10px;
        }

        .like-btn.liked {
            background-color: #365899;
            color: white;
        }

        .delete-comment {
            float: right;
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            padding: 0 5px;
        }

        .delete-comment:hover {
            color: #c82333;
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

        .like-btn.liked {
            background-color: #365899;
            color: white;
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

        .no-projects {
            text-align: center;
            font-size: 50px;
            font-weight: bold;
            color: black;
            margin-top: 50px;
        }

        .footer {
            padding: 10px;
            text-align: center;
            background: #FF8C00;
            position: relative;
            width: 100%;
        }

        /* Update spacing to match profile.php */
        .sidebar {
            background-color: #FF8600;
            height: 100vh;
            padding: 15px;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            overflow-y: auto;
        }
        
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            margin: 10px auto;
            display: block;
        }
        
        .nav-link {
            color: #000000;
            padding: 8px 15px;
            margin: 2px 0;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: #000000;
            border-radius: 5px;
        }
        
        .nav-link i {
            width: 25px;
            margin-right: 10px;
            text-align: center;
        }
        
        .profile-section {
            text-align: center;
            padding-bottom: 10px;
            margin-bottom: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: #000000;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        /* Update main content positioning */
        .main-content {
            margin-left: 270px;
            padding: 15px;
            max-width: 800px;
        }

        /* Remove header and topnav */
        .header, .topnav {
            display: none;
        }

        /* Keep original button styles */
        .like-btn, .view-plan-btn, .comment-btn {
            background-color: #FF8C00;
            color: #000;
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
        <a href="profile.php" style="text-decoration: none; color: black; margin-left: 20px;">Profile</a>
        <a href="logout.php" style="text-decoration: none; color: black; margin-left: auto; margin-right: 20px;">Log Out</a>
    </div>

    <div class="topnav"></div>

    <!-- Add new sidebar -->
    <div class="sidebar">
        <div class="profile-section">
            <?php
            $sql = "SELECT * FROM carpenters WHERE Carpenter_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $Carpenter_ID);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (isset($user['Photo']) && !empty($user['Photo'])) {
                echo '<img src="' . $user['Photo'] . '" class="profile-image" alt="Profile Picture">';
            } else {
                echo '<img src="assets/img/default-avatar.png" class="profile-image" alt="Default Profile Picture">';
            }
            ?>
            <h5><?php echo $user['First_Name'] . ' ' . $user['Last_Name']; ?></h5>
            <p>Carpenter ID: <?php echo $Carpenter_ID; ?></p>
        </div>
        
        <div class="nav">
            <a href="comment.php" class="nav-link">
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
            <a href="plan.php" class="nav-link">
                <i class="fa fa-clipboard"></i>
                <span>Project</span>
            </a>
            <a href="profile.php" class="nav-link">
                <i class="fa fa-user"></i>
                <span>Profile</span>
            </a>
            <a href="logout.php" class="nav-link">
                <i class="fa fa-sign-out"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Wrap existing content in main-content div -->
    <div class="main-content">
        <?php
        $query = "SELECT * FROM plan";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Check if there are any rows returned
            if (mysqli_num_rows($result) > 0) {
                // Loop through each row if there are results
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='post' data-plan-id='{$row['plan_ID']}'>";
                    
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

                    // After the post-footer div, before the comments-section
                    echo "<div class='post-footer'>";
                    
                    // Check if carpenter already liked this post
                    $likeCheck = mysqli_prepare($conn, "SELECT * FROM likes WHERE plan_id = ? AND carpenter_id = ?");
                    mysqli_stmt_bind_param($likeCheck, "ii", $row['plan_ID'], $Carpenter_ID);
                    mysqli_stmt_execute($likeCheck);
                    $likeResult = mysqli_stmt_get_result($likeCheck);
                    $isLiked = mysqli_num_rows($likeResult) > 0;
                    
                    // Get total likes
                    $likesCount = mysqli_prepare($conn, "SELECT COUNT(*) FROM likes WHERE plan_id = ?");
                    mysqli_stmt_bind_param($likesCount, "i", $row['plan_ID']);
                    mysqli_stmt_execute($likesCount);
                    $totalLikes = mysqli_stmt_get_result($likesCount)->fetch_row()[0];
                    
                    echo "<button class='like-btn " . ($isLiked ? 'liked' : '') . "'>" . ($isLiked ? 'Unlike' : 'Like') . " (" . $totalLikes . ")</button>";
                    echo "<a href='clientsplan.php?plan_ID={$row['plan_ID']}' class='view-plan-btn'>View client plan</a>";
                    echo "</div>";
                
                    // Display existing comments
                    echo "<div class='comments-container'>";
                    $commentQuery = mysqli_prepare($conn, "SELECT c.*, cp.First_Name, cp.Last_Name FROM comments c 
                                                 JOIN carpenters cp ON c.carpenter_id = cp.Carpenter_ID 
                                                 WHERE c.plan_id = ? ORDER BY c.comment_date DESC");
                    mysqli_stmt_bind_param($commentQuery, "i", $row['plan_ID']);
                    mysqli_stmt_execute($commentQuery);
                    $comments = mysqli_stmt_get_result($commentQuery);
                    
                    while ($comment = mysqli_fetch_assoc($comments)) {
                        echo "<div class='comment'>";
                        echo "<strong>{$comment['First_Name']} {$comment['Last_Name']}</strong>: ";
                        echo htmlspecialchars($comment['comment_text']);
                        echo "<span class='comment-date'>" . date('M d, Y H:i', strtotime($comment['comment_date'])) . "</span>";
                        
                        // Add delete button if comment is from current carpenter
                        if ($comment['carpenter_id'] == $Carpenter_ID) {
                            echo "<button class='delete-comment' data-comment-id='{$comment['comment_id']}'><i class='fa fa-trash'></i></button>";
                        }
                        
                        echo "</div>";
                    }
                    echo "</div>";
                
                    echo "<div class='comments-section'>";
                    echo "<input type='text' class='comment-input' placeholder='Write a comment...'>";
                    echo "<button class='comment-btn'>Comment</button>";
                    echo "</div>";
                
                    echo "</div>"; // Close post div
                }
            } else {
                // Display a message when there are no projects
                echo "<div class='no-projects'>No projects available.</div>";
            }
            mysqli_free_result($result);
        } else {
            echo "<div class='error'>Error fetching data: " . mysqli_error($conn) . "</div>";
        }

        mysqli_close($conn);
        ?>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Handle likes
    $('.like-btn').click(function() {
        const btn = $(this);
        const postContainer = btn.closest('.post');
        const planId = postContainer.data('plan-id');
        
        $.ajax({
            url: 'handle_like.php',
            type: 'POST',
            data: {
                plan_id: planId,
                carpenter_id: <?php echo json_encode($Carpenter_ID); ?>
            },
            success: function(response) {
                try {
                    const data = JSON.parse(response);
                    if (data.success) {
                        if (data.liked) {
                            btn.addClass('liked').text(`Unlike (${data.totalLikes})`);
                        } else {
                            btn.removeClass('liked').text(`Like (${data.totalLikes})`);
                        }
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
            }
        });
    });

    // Handle comments
    $('.comment-btn').click(function() {
        const btn = $(this);
        const postContainer = btn.closest('.post');
        const commentInput = postContainer.find('.comment-input');
        const planId = postContainer.data('plan-id');
        const comment = commentInput.val().trim();

        if (comment) {
            $.ajax({
                url: 'handle_comment.php',
                type: 'POST',
                data: {
                    plan_id: planId,
                    carpenter_id: <?php echo json_encode($Carpenter_ID); ?>,
                    comment: comment
                },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        // Inside your comment success handler:
                        if (data.success) {
                            commentInput.val('');
                            const commentsContainer = postContainer.find('.comments-container');
                            commentsContainer.prepend(`
                                <div class="comment">
                                    <strong>${data.carpenter_name}</strong>: ${data.comment}
                                    <span class="comment-date">${data.date}</span>
                                    <button class="delete-comment" data-comment-id="${data.comment_id}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            `);
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                }
            });
        }
    });

    // Add this inside your $(document).ready(function() { ... });
    // Handle comment deletion
    $('.comments-container').on('click', '.delete-comment', function() {
        const btn = $(this);
        const commentId = btn.data('comment-id');
        const commentDiv = btn.closest('.comment');
        
        if (confirm('Are you sure you want to delete this comment?')) {
            $.ajax({
                url: 'handle_delete_comment.php',
                type: 'POST',
                data: {
                    comment_id: commentId,
                    carpenter_id: <?php echo json_encode($Carpenter_ID); ?>
                },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.success) {
                            commentDiv.fadeOut(300, function() { $(this).remove(); });
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e);
                    }
                }
            });
        }
    });
});
</script>
</html>

