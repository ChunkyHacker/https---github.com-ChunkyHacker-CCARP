<?php
include('config.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $progressname = $_POST["Name"];
    $progresstype = $_POST["Status"];
    $materials = $_POST["Materials"];
    $materialcost = $_POST["Material_cost"];
    $totalcost = $_POST["Total_cost"];
    
    // Ensure the existence of the requirement_ID field in the form data
    if (isset($_POST["requirement_ID"])) {
        $requirementID = $_POST["requirement_ID"];
    } else {
        // Handle the case where requirement_ID is not provided
        echo "Error: requirement_ID is missing.";
        exit();
    }

    // Prepare the SQL query to insert the item into the database
    $sql = "INSERT INTO report (Name, Status, Materials, Material_cost, Total_cost, requirement_ID) 
            VALUES ('$progressname', '$progresstype', '$materials', '$materialcost', '$totalcost', '$requirementID')";
    
    if ($conn->query($sql) === TRUE) {
        // Item added successfully, redirect back to progress.php with a success message
        $conn->close();
        header("Location: progress.php?requirement_ID=$requirementID&success=true&message=" . urlencode("Report added successfully!"));
        exit();
    } else {
        // Display the error message
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "Error: The form was not submitted correctly.";
}
?>
