<?php

// Database connection parameters
$servername = "localhost"; // Change this if your database is hosted elsewhere
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$database = "ccarpcurrentsystem"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Prepare SQL statement to insert selected items into the database
$sql = "INSERT INTO prematerials (materials, part, name, quantity, price, total) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Check if the form is submitted and items are selected
if (!empty($_POST["materials"])) {
    // Bind parameters and execute the statement for each selected item
    foreach ($_POST["materials"] as $key => $materials) {
        // Assuming other values are retrieved from $_POST as well
        $part = isset($_POST["part"][$key]) ? $_POST["part"][$key] : "";
        $name = $_POST["name"][$key];
        $quantity = $_POST["quantity"][$key];
        $price = $_POST["price"][$key];
        $total = $_POST["total"][$key];

        // Bind parameters and execute the statement
        $stmt->bind_param("sssidi", $materials, $part, $name, $quantity, $price, $total);
        $stmt->execute();
    }

    // Close statement
    $stmt->close();

    // Redirect to user profile after successful insertion
    header("Location: userprofile.php");
} else {
    // If no items are selected, display a message
    echo "No items selected.";
}

// Close connection
$conn->close();
?>
