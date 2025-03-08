<?php
include('config.php');

if (isset($_GET['transaction_ID'])) {
    $transactionID = $_GET['transaction_ID'];
    error_log("Transaction ID: " . $transactionID);

    $sql = "SELECT transaction_ID, contract_ID, receipt_photo FROM transaction WHERE transaction_ID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("SQL prepare failed: " . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, "i", $transactionID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        die("SQL execute failed: " . mysqli_error($conn));
    }

    if ($transactionDetails = mysqli_fetch_assoc($result)) {
        // Log the fetched details
        error_log("Fetched transaction details: " . print_r($transactionDetails, true));
        // Convert the receipt photo to base64
        if ($transactionDetails['receipt_photo']) {
            $transactionDetails['receipt_photo'] = base64_encode($transactionDetails['receipt_photo']);
        }
        echo json_encode($transactionDetails);
    } else {
        error_log("No transaction found for ID: " . $transactionID);
        echo json_encode(['error' => 'Status: PAID']);
    }
} else {
    echo json_encode(['error' => 'No transaction ID provided']);
}
?> 