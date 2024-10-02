<?php
// edituser.php

session_start();
if (!isset($_SESSION['username']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit; // Add the missing semicolon and exit after redirection
}

require_once "config.php"; // Add the missing semicolon

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize the input data
    $logInID = $_POST['LogInID'] ?? '';
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Perform additional validation as needed (e.g., checking if any required fields)

    // Prepare and execute the UPDATE query using parameterized statements
    $query = "UPDATE credentials SET firstname = ?, lastname = ?, email = ?, username = ?, password = ?, role = ? WHERE logInID = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ssssssi", $firstname, $lastname, $email, $username, $password, $role, $logInID);

    $response = array();

    if (mysqli_stmt_execute($stmt)) {
        // Update successful
        $response['success'] = true;
        
    } else {
        // Update failed
        $response['success'] = false;
        $response['message'] = "Error: " . mysqli_error($connection);
    }

    // Close the statement
    mysqli_stmt_close($stmt);

    // Send the JSON response back to the client
    header("Content-Type: application/json");
    echo json_encode($response);
}
?>
