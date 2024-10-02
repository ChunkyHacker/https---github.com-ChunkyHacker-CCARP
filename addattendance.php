<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $typework = $_POST["Type_of_work"];
    $timein = $_POST["Time_in"];
    
    // Set Time_out to an empty string if it's not provided in the form data
    $timeout = isset($_POST["Time_out"]) ? $_POST["Time_out"] : '';

    // Ensure the existence of the requirement_ID field in the form data
    if(isset($_POST["requirement_ID"])) {
        $requirementID = $_POST["requirement_ID"];
    } else {
        // Handle the case where requirement_ID is not provided
        echo "Error: requirement_ID is missing.";
        exit();
    }

    // Database connection settings
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ccarpcurrentsystem";

    // Create a new connection
    $conn = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query to insert the item into the database
    $sql = "INSERT INTO attendance (Type_of_work, Time_in, Time_out, requirement_ID) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    // Bind parameters
    $stmt->bind_param("sssi", $typework, $timein, $timeout, $requirementID);

    if ($stmt->execute()) {
        // Item added successfully, redirect back to progress.php
        $stmt->close();
        $conn->close();
        header("Location: progress.php?requirement_ID=$requirementID");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Error: There was an error uploading the file.";
}
?>
