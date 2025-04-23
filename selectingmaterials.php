<?php
session_start(); 
include('config.php');

// Prepare SQL statement to insert selected items into the database
// Get user_ID from the form
$user_ID = $_POST['user_ID'];

// Modify SQL statement to include User_ID
$sql = "INSERT INTO prematerials (User_ID, materials, part, name, quantity, price, total, estimated_cost) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error preparing the SQL statement: " . $conn->error);
}

// Check if the form is submitted and items are selected
if (!empty($_POST["materials"])) {
    foreach ($_POST["materials"] as $key => $materials) {
        $part = isset($_POST["part"][$key]) ? $_POST["part"][$key] : "";
        $name = $_POST["name"][$key];
        $quantity = $_POST["quantity"][$key];
        $price = $_POST["price"][$key];
        $total = $_POST["total"][$key];
        $estimated_cost = (float)$_POST["estimated_cost"];

        // Modified bind_param to include User_ID
        $stmt->bind_param("isssiddi", 
            $user_ID,      // Add User_ID parameter
            $materials, 
            $part, 
            $name, 
            $quantity, 
            $price, 
            $total, 
            $estimated_cost
        );
        $stmt->execute();
    }

    // Close statement
    $stmt->close();

    // Prepare a success message
    $response['success'] = true;
    $response['message'] = "Your materials have been successfully submitted.";

    // Redirect to user profile with success message
    header("Location: userprofile.php?success=true&message=" . urlencode($response['message']));
    exit;
} else {
    // If no items are selected, display a message
    echo "No items selected.";
}

// Close connection
$conn->close();
?>