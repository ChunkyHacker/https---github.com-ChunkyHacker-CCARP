<?php
// Include the database configuration file
include 'config.php';

// Check if admin ID is provided in the URL
if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];

    // Prepare the DELETE query to remove the admin from the database
    $sql = "DELETE FROM admin WHERE admin_id = ?";

    // Prepare the statement and execute it
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);

    // Check if the query executed successfully
    if ($stmt->execute()) {
        // Show alert and redirect back to manage_users.php
        echo "<script>
            alert('Admin deleted successfully!');
            window.location.href = 'manage_users.php';
        </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No admin ID provided.";
}

// Close the database connection
$conn->close();
?>
