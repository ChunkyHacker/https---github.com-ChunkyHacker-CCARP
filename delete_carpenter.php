<?php
// Include the database configuration file
include 'config.php';

// Check if carpenter ID is provided in the URL
if (isset($_GET['id'])) {
    $carpenter_id = $_GET['id'];

    // Prepare the DELETE query to remove the carpenter from the database
    $sql = "DELETE FROM carpenters WHERE Carpenter_ID = ?";

    // Prepare the statement and execute it
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $carpenter_id);

    // Check if the query executed successfully
    if ($stmt->execute()) {
        // Show alert and redirect back to manage_users.php
        echo "<script>
            alert('Carpenter deleted successfully!');
            window.location.href = 'manage_users.php';
        </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No carpenter ID provided.";
}

// Close the database connection
$conn->close();
?>
