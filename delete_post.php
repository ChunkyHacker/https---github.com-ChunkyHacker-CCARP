<?php
include('config.php');
// Check if the form was submitted for deleting a post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deletePost']) && isset($_POST['postID'])) {
    // Get the post ID from the form submission
    if (isset($_POST['postID']) && is_numeric($_POST['postID'])) {
        $postIdToDelete = $_POST['postID'];
    } else {
        echo "Invalid post ID";
        exit;
    }

    // Prepare and execute the SQL query to delete the post
    $deleteSql = "DELETE FROM posts WHERE post_ID = ?";
    $deleteStmt = mysqli_prepare($connection, $deleteSql);

    if ($deleteStmt === false) {
        echo "Error preparing statement: " . mysqli_error($connection);
        mysqli_close($connection);
        exit;
    }

    mysqli_stmt_bind_param($deleteStmt, "i", $postIdToDelete);

    if (mysqli_stmt_execute($deleteStmt)) {
        echo "Post deleted successfully";
        mysqli_stmt_close($deleteStmt);
        mysqli_close($connection);
        header("Location: comment.php");
        exit;
    } else {
        echo "Error deleting post: " . mysqli_error($connection);
    }

    mysqli_stmt_close($deleteStmt);
    mysqli_close($connection);
}
