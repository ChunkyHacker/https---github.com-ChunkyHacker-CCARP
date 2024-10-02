<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "ccarpcurrentsystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare SQL statement to insert selected items into the database
    $sql = "INSERT INTO requiredmaterials (material, type, image, quantity, price, total) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Check if selected materials exist
    if (!empty($_POST["rawmaterials"])) {
        // Bind parameters and execute the statement for each selected material
        foreach ($_POST["rawmaterials"] as $key => $material) {
            // Assuming other values are retrieved from $_POST as well
            $type = isset($_POST["type"][$key]) ? $_POST["type"][$key] : ""; // Corrected name here
            $image = isset($_POST["image"][$key]) ? $_POST["image"][$key] : "";
            $quantity = isset($_POST["itemQuantity"][$key]) ? $_POST["itemQuantity"][$key] : 0;
            $price = isset($_POST["price"][$key]) ? $_POST["price"][$key] : 0;
            $total = isset($_POST["total"][$key]) ? $_POST["total"][$key] : 0;
        
            // Bind parameters and execute the statement
            $stmt->bind_param("ssssid", $material, $type, $image, $quantity, $price, $total);
            $stmt->execute();
        }        

        // Close statement
        $stmt->close();

        // Redirect to profile after successful insertion
        header("Location: profile.php");
        exit(); // Terminate script after redirection
    } else {
        // If no materials are selected, display a message or perform appropriate action
        echo "No materials selected.";
    }
}

// Close connection
$conn->close();
?>
