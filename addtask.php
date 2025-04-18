<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task = $_POST["task"];
    $timestamp = $_POST["timestamp"];
    $contract_ID = $_POST["contract_ID"];

    // Prepare the SQL query with auto-increment task_id
    $sql = "INSERT INTO task (task, timestamp, contract_ID) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $task, $timestamp, $contract_ID);

    if ($stmt->execute()) {
        header("Location: progress.php?contract_ID=$contract_ID&success=true");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
