<?php
include('config.php');
session_start();

if (!isset($_SESSION['Carpenter_ID']) || !isset($_POST['plan_id'])) {
    die(json_encode(['success' => false, 'message' => 'Invalid request']));
}

$carpenter_id = $_SESSION['Carpenter_ID'];
$plan_id = $_POST['plan_id'];

// Check if like already exists
$checkQuery = "SELECT * FROM likes WHERE plan_id = ? AND carpenter_id = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("ii", $plan_id, $carpenter_id);
$stmt->execute();
$result = $stmt->get_result();

// Get total likes count
$countQuery = "SELECT COUNT(*) as total FROM likes WHERE plan_id = ?";
$countStmt = $conn->prepare($countQuery);
$countStmt->bind_param("i", $plan_id);
$countStmt->execute();
$totalLikes = $countStmt->get_result()->fetch_assoc()['total'];

if ($result->num_rows > 0) {
    // Unlike - remove the like
    $query = "DELETE FROM likes WHERE plan_id = ? AND carpenter_id = ?";
    $liked = false;
    $totalLikes--; // Decrease count
} else {
    // Like - add new like
    $query = "INSERT INTO likes (plan_id, carpenter_id) VALUES (?, ?)";
    $liked = true;
    $totalLikes++; // Increase count
}

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $plan_id, $carpenter_id);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true, 
        'liked' => $liked,
        'totalLikes' => $totalLikes
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}

$stmt->close();
$conn->close();