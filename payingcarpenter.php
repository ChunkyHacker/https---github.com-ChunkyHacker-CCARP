<?php
include('config.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $carpenter_name = $_POST["carpenter_name"];
    $net_pay = $_POST["Netpay"];
    $days_of_work = $_POST["Days_Of_Work"];
    $rate_per_day = $_POST["Rate_per_day"];
    $payment_method = $_POST["payment_method"];
    $sender = $_POST["sender"];

    // Prepare the SQL query to insert the payroll data into the database
    $sql = "INSERT INTO payment (carpenter_name, Netpay, days_of_work, rate_per_day, payment_method, sender) 
    VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare statement
    if ($stmt = $connection->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("ssssss", $carpenter_name, $net_pay, $days_of_work, $rate_per_day, $payment_method, $sender);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Get the ID of the last inserted row
            $Payroll_ID = $stmt->insert_id;

            // If insertion is successful, redirect to a success page with the Payroll_ID
            header("Location: payrollreceipt.php?Payroll_ID=$Payroll_ID");
            exit();
        } else {
            // If an error occurs during insertion, display the error message
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        // If an error occurs during statement preparation, display the error message
        echo "Error: " . $connection->error;
    }

    // Close connection
    $connection->close();
} else {
    // If the form was not submitted via POST method, display an error message
    echo "Error: The form was not submitted.";
}
?>
