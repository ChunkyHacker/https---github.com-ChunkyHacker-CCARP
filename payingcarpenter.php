<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contract_id = $_POST["Contract_ID"];
    $carpenter_id = $_POST["Carpenter_ID"];
    $user_id = $_POST["User_ID"];
    $labor_cost = $_POST["Labor_Cost"];
    $duration = $_POST["Duration"];
    $payment_method = $_POST["Payment_Method"];
    $payment_date = date("Y-m-d");

    // 🔍 Check if payment already exists for this contract and user
    $check_query = "SELECT * FROM payment WHERE Contract_ID = ? AND User_ID = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $contract_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // 🚫 Payment already exists, show an alert and redirect
        echo "<script>alert('You have already paid for this contract.'); window.location.href='userpaycarpenter.php?contract_ID=$contract_id&sucess=true';</script>";
        exit();
    }

    // ✅ Insert new payment if no previous record
    $sql = "INSERT INTO payment (Contract_ID, Carpenter_ID, User_ID, Labor_Cost, Duration, Payment_Method, Payment_Date) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iiidiss", $contract_id, $carpenter_id, $user_id, $labor_cost, $duration, $payment_method, $payment_date);

        if ($stmt->execute()) {
            $payment_id = $conn->insert_id;
            header("Location: payrollreceipt.php?Payment_ID=$payment_id&success=true");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Error: The form was not submitted.";
}
?>
