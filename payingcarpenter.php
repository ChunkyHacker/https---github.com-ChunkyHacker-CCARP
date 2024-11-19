<?php
include('config.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $carpenter_name = $_POST["carpenter_name"];
    $net_pay = $_POST["Netpay"];
    $days_of_work = $_POST["Days_Of_Work"];
    $rate_per_day = $_POST["Rate_per_day"];
    $overall_cost = $_POST["overall_cost"];
    $payment_method = $_POST["payment_method"];
    $sender = $_POST["sender"];

    // Prepare the SQL query to insert the payroll data into the database
    $sql = "INSERT INTO payment (carpenter_name, Netpay, days_of_work, rate_per_day, overall_cost, payment_method, sender) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("sssssss", $carpenter_name, $net_pay, $days_of_work, $rate_per_day, $overall_cost, $payment_method, $sender);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Get the ID of the last inserted row
            $Payroll_ID = $conn->insert_id;

            // Redirect to the success page with the Payroll_ID
            header("Location: payrollreceipt.php?Payroll_ID=$Payroll_ID&success=true");
            exit();
        } else {
            // Display an error message if the execution fails
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Display an error message if statement preparation fails
        echo "Error: " . $conn->error;
    }

    // Close the connection
    $conn->close();
} else {
    // Display an error message if the form was not submitted via POST
    echo "Error: The form was not submitted.";
}
?>
