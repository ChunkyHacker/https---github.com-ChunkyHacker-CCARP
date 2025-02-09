<?php
include('config.php');
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $carpentername = $_POST["carpenter_name"];
    $typework = $_POST["type_of_work"];
    $workdays = $_POST["days_of_work"];
    $rate = $_POST["rate"];
    $totallaborcost = $_POST["total_of_laborcost"];
    $additionalcost = $_POST["additional_cost"];
    $overallcost = $_POST["overall_cost"];
    $task = $_POST["task"];

    // Ensure the existence of the requirement_ID field in the form data
    if (isset($_POST["requirement_ID"])) {
        $requirementID = $_POST["requirement_ID"];
    } else {
        // Handle the case where requirement_ID is not provided
        echo "Error: requirement_ID is missing.";
        exit();
    }

    // Prepare the SQL query to insert the labor data into the database
    $sql = "INSERT INTO labor (carpenter_name, type_of_work, days_of_work, rate, total_of_laborcost, additional_cost, overall_cost, task, requirement_ID) 
    VALUES ('$carpentername', '$typework', '$workdays', '$rate', '$totallaborcost', '$additionalcost', '$overallcost','$task', '$requirementID')";
    
    // Attempt to execute the SQL query
    if ($conn->query($sql) === TRUE) {
        // If insertion is successful, redirect back to the page with a success message
        $conn->close();
        header("Location: usercomputebudget.php?requirement_ID=$requirementID&success=true&message=" . urlencode("Labor details added successfully!"));
        exit();
    } else {
        // If an error occurs during insertion, display the error message
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    // If the form was not submitted via POST method, display an error message
    echo "Error: The form was not submitted.";
}
?>
