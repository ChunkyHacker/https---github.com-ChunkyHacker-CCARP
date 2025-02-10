<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task = $_POST["task"];
    $details = $_POST["details"];
    $requirement_ID = $_POST["requirement_ID"];

    // Prepare the SQL query with auto-increment task_id
    $sql = "INSERT INTO task (task, details, requirement_ID) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $task, $details, $requirement_ID);

    if ($stmt->execute()) {
        header("Location: progress.php?requirement_ID=$requirement_ID&success=true");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
