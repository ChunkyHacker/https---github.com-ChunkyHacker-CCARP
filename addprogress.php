<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $progressname = $_POST["Name"];
    $progresstype = $_POST["Status"];
    $materials = $_POST["Materials"];
    $materialcost = $_POST["Material_cost"];
    $totalcost = $_POST["Total_cost"];
    
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
    $sql = "INSERT INTO progress (Name, Status, Materials, Material_cost, Total_cost, requirement_ID) 
            VALUES ('$progressname', '$progresstype', '$materials', '$materialcost', '$totalcost', '$requirementID')";
    
    if ($conn->query($sql) === TRUE) {
        // Item added successfully, redirect back to progress.php with requirement_ID
        $conn->close();
        header("Location: progress.php?requirement_ID=$requirementID");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "Error: There was an error uploading the file.";
}
?>
