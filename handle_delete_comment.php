<?php
include('config.php');
session_start();

if (!isset($_SESSION['Carpenter_ID']) || !isset($_POST['comment_id'])) {
    die(json_encode(['success' => false, 'message' => 'Invalid request']));
}

$carpenter_id = $_SESSION['Carpenter_ID'];
$comment_id = $_POST['comment_id'];

// Verify the comment belongs to the carpenter
$checkQuery = "SELECT * FROM comments WHERE comment_id = ? AND carpenter_id = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("ii", $comment_id, $carpenter_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

// Delete the comment
$deleteQuery = "DELETE FROM comments WHERE comment_id = ? AND carpenter_id = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("ii", $comment_id, $carpenter_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

$stmt->close();
$conn->close();