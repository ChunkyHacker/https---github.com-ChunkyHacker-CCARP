<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['postID']) && is_numeric($_GET['postID'])) {
    $postIdToEdit = $_GET['postID'];

    // Connect to the database
    $connection = mysqli_connect("localhost", "root", "", "ccarpcurrentsystem");
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Retrieve the post data from the database
    $sql = "SELECT * FROM posts WHERE post_ID = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $postIdToEdit);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $postToEdit = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    mysqli_close($connection);
} else {
    // Redirect to the comment.php page if postID is not provided or invalid
    header("Location: comment.php");
    exit;
}

// Handle edit post form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editPost'])) {
    $editedPostText = isset($_POST['editPostText']) ? $_POST['editPostText'] : '';

    // Process the edited post text and image if uploaded
    $editedImagePath = '';

    // Check if a new image is uploaded
    if ($_FILES['editPostImage']['error'] === UPLOAD_ERR_OK) {
        $targetDir = 'C:\Users\Acer\OneDrive\Desktop\XAMPP Directory\htdocs\itewms\uploads\posts';
        $editedFileName = $_FILES['editPostImage']['name'];
        $editedFilePath = $targetDir . $editedFileName;
        move_uploaded_file($_FILES['editPostImage']['tmp_name'], $editedFilePath);
        $editedImagePath = $editedFilePath;
    } else {
        // If no new image is uploaded, retain the existing image
        $editedImagePath = $postToEdit['post_image'];
    }

    // Connect to the database (You can reuse the previous connection code here)
    $connection = mysqli_connect("localhost", "root", "", "epanday");
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare and execute the SQL query to update the post data
    $updateSql = "UPDATE posts SET post_text = ?, post_image = ? WHERE post_ID = ?";
    $updateStmt = mysqli_prepare($connection, $updateSql);
    mysqli_stmt_bind_param($updateStmt, "ssi", $editedPostText, $editedImagePath, $postIdToEdit);

    if (mysqli_stmt_execute($updateStmt)) {
        $updateSuccess = "Post updated successfully";
    } else {
        $updateSuccess = "Error updating post: " . mysqli_error($connection);
    }

    mysqli_stmt_close($updateStmt);
    mysqli_close($connection);

    // Redirect back to the comment.php page after update
    header("Location: comment.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
</head>
<body>
    <h2>Edit Post</h2>
    <form method="post" enctype="multipart/form-data" action="edit_post.php">
        <input type="hidden" name="postID" value="<?php echo $postToEdit['post_ID']; ?>">

        <div>
            <label for="editPostText">Edit Post Text</label>
            <input type="text" id="editPostText" name="editPostText" value="<?php echo htmlspecialchars($postToEdit['post_text']); ?>">
        </div>

        <div>
            <label for="editPostImage">Edit Post Image</label>
            <input type="file" id="editPostImage" name="editPostImage">
        </div>

        <button type="submit" name="editPost">Save Changes</button>
    </form>

    <?php if (!empty($updateSuccess)) : ?>
        <script>
            alert("<?php echo $updateSuccess; ?>");
        </script>
    <?php endif; ?>
    
</body>
</html>
