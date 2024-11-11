<?php
include('config.php');

$totalprice = $_POST['total_price'];
$paymentmethod = $_POST['payment_method'];

// Prepare the SQL statement for inserting transaction data
$stmt = $conn->prepare("INSERT INTO transaction (total_price, payment_method) VALUES (?, ?)");

// Check if the statement was prepared successfully
if ($stmt) {
    // Bind parameters for transaction insertion
    $stmt->bind_param("ss", $totalprice, $paymentmethod);

    // Execute the transaction insertion
    if ($stmt->execute()) {
        // Fetch data from the 'requirements' table
        $query = "SELECT * FROM prematerials";
        $result = $conn->query($query);

        // Check if the query was successful
        if ($result) {
            $prematerials = $result->fetch_all(MYSQLI_ASSOC);
            // Encode the requirements data as JSON
            $prematerials_json = json_encode($prematerials);
            // Redirect to receipt.php with necessary data
            header("Location: receipt.php?total_price=$totalprice&payment_method=$paymentmethod&prematerials=$prematerials_json");
            exit;
        } else {
            echo "Error fetching requirements: " . $conn->error;
        }
    } else {
        echo "Error executing transaction: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;
}

?>
