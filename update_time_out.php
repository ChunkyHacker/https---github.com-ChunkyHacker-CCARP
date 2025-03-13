<?php
include('config.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the attendance_id and Time_out from the POST request
    $attendance_id = $_POST["attendance_id"];
    $Time_out = $_POST["Time_out"];  // Changed to lowercase to match form field name

    // Use prepared statement to update only the specific attendance record
    $sql = "UPDATE attendance SET Time_out = ? WHERE attendance_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $Time_out, $attendance_id);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "&success=true&action=Time_out");
        exit();
    } else {
        echo "Error updating time out: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
