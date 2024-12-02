<?php
// Include the database configuration file
include 'config.php';

// Check if user ID is provided in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prepare the DELETE query to remove the user from the database
    $sql = "DELETE FROM users WHERE User_ID = ?";

    // Prepare the statement and execute it
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    // Check if the query executed successfully
    if ($stmt->execute()) {
        // Show alert and redirect back to manage_users.php
        echo "<script>
            alert('User deleted successfully!');
            window.location.href = 'manage_users.php';
        </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No user ID provided.";
}

// Close the database connection
$conn->close();
?>
