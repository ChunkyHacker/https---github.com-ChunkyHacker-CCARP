<?php
    session_start(); 
    include('config.php');

    // Prepare SQL statement to insert selected items into the database
    $sql = "INSERT INTO prematerials (materials, part, name, quantity, price, total) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        // Output an error message if the statement couldn't be prepared
        die("Error preparing the SQL statement: " . $conn->error);
    }

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
