<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the timestamp for Time_out from the POST request
    $time_out = $_POST["Time_out"]; // Assuming the form field is named 'Time_out'

    // Database connection settings
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ccarpcurrentsystem";

    // Ensure the existence of the requirement_ID field in the form data
    if(isset($_POST["requirement_ID"])) {
        $requirementID = $_POST["requirement_ID"];
    } else {
        // Handle the case where requirement_ID is not provided
        echo "Error: requirement_ID is missing.";
        exit();
    }

    // Create a new connection
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query to update the Time_out field
    $sql = "UPDATE attendance SET Time_out = ?"; // SQL query to update Time_out field

    // Prepare and bind the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $time_out); // "s" indicates that the parameter is a string

    // Execute the statement
    if ($stmt->execute()) {
        // Time_out updated successfully, redirect to progress.php
        header("Location: progress.php?requirement_ID=$requirementID");
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
