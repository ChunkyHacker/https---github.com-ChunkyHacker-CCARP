<?php
include('config.php');
session_start();

if (!isset($_POST['plan_id'])) {
    die(json_encode(['success' => false, 'message' => 'Invalid request']));
}

$plan_id = $_POST['plan_id'];

// Update views count
$updateQuery = "UPDATE plan SET views = views + 1 WHERE plan_ID = ?";
$stmt = $conn->prepare($updateQuery);
$stmt->bind_param("i", $plan_id);

if ($stmt->execute()) {
    // Get updated view count
    $viewQuery = "SELECT views FROM plan WHERE plan_ID = ?";
    $viewStmt = $conn->prepare($viewQuery);
    $viewStmt->bind_param("i", $plan_id);
    $viewStmt->execute();
    $result = $viewStmt->get_result();
    $views = $result->fetch_assoc()['views'];
    
    echo json_encode(['success' => true, 'views' => $views]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

$stmt->close();
$conn->close();