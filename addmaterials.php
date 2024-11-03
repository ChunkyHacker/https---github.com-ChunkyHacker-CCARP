<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $materialname = $_POST["material_name"];
    $type = $_POST["type"];
    $quantity = $_POST["quantity"];
    $cost = $_POST["cost"];
    $totalcost = $_POST["total_cost"];

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
    $sql = "INSERT INTO constructionmaterials (material_name, type, quantity, cost, total_cost, requirement_ID) 
    VALUES ('$materialname', '$type', '$quantity', '$cost', '$totalcost', '$requirementID')";
    
    if ($conn->query($sql) === TRUE) {
        // Item added successfully, redirect back to products.php
        $conn->close();
        header("Location: usercomputebudget.php?requirement_ID=$requirementID");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "Error: There was an error uploading the file.";
}
?>
