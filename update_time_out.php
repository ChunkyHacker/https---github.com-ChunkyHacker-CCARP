<?php
include('config.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the timestamp for Time_out from the POST request
    $time_out = $_POST["Time_out"]; // Assuming the form field is named 'Time_out'

    // Ensure the existence of the contract_ID field in the form data
    if (isset($_POST["contract_ID"])) {
        $requirementID = $_POST["contract_ID"];
    } else {
        // Handle the case where contract_ID is not provided
        echo "Error: contract_ID is missing.";
        exit();
    }

    // Create a new connection
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query to update the Time_out field
    $sql = "UPDATE attendance SET Time_out = ? WHERE contract_ID = ?"; // Ensure the WHERE clause to target the specific record

    // Prepare and bind the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $time_out, $requirementID); // "si" indicates string and integer parameter types

    // Execute the statement
    if ($stmt->execute()) {
        // Time_out updated successfully, redirect to progress.php with a success message
        $stmt->close();
        $conn->close();
        header("Location: progress.php?contract_ID=$requirementID&action=time_out&success=true");
        exit();
    } else {
        echo "Error updating time out: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Error: No data received.";
}
?>
