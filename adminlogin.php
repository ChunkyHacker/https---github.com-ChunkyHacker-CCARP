<?php
// Include the database configuration file
include 'config.php';

// Start session to store admin login status
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        die("Username and Password are required.");
    }

    // Prepare SQL query to fetch the admin by username
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    // Check if admin exists and validate password
    if ($admin && $admin['password'] === $password) {
        // Store admin ID in session
        $_SESSION['admin_id'] = $admin['admin_id'];

        // Redirect to the admin dashboard
        header("Location: admindashboard.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
