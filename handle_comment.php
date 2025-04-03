<?php
include('config.php');
session_start();

if (!isset($_SESSION['Carpenter_ID']) || !isset($_POST['plan_id']) || !isset($_POST['comment'])) {
    die(json_encode(['success' => false, 'message' => 'Invalid request']));
}

$carpenter_id = $_SESSION['Carpenter_ID'];
$plan_id = $_POST['plan_id'];
$comment = trim($_POST['comment']);

if (empty($comment)) {
    die(json_encode(['success' => false, 'message' => 'Comment cannot be empty']));
}

// Get carpenter name first
$nameQuery = "SELECT First_Name, Last_Name FROM carpenters WHERE Carpenter_ID = ?";
$nameStmt = $conn->prepare($nameQuery);
$nameStmt->bind_param("i", $carpenter_id);
$nameStmt->execute();
$carpenterData = $nameStmt->get_result()->fetch_assoc();

$query = "INSERT INTO comments (plan_id, carpenter_id, comment_text, comment_date) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param("iis", $plan_id, $carpenter_id, $comment);

if ($stmt->execute()) {
    $comment_id = $conn->insert_id; // Get the newly inserted comment ID
    echo json_encode([
        'success' => true,
        'comment_id' => $comment_id,
        'carpenter_name' => $carpenterData['First_Name'] . ' ' . $carpenterData['Last_Name'],
        'comment' => htmlspecialchars($comment),
        'date' => date('M d, Y H:i')
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

$stmt->close();
$conn->close();