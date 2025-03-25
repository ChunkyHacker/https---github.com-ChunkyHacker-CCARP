<?php
include('config.php');
session_start();

if (!isset($_SESSION['Carpenter_ID'])) {
    echo "<script>alert('Error: Carpenter ID is not set. Please log in again.'); window.location.href = 'login.php';</script>";
    exit();
}

$Carpenter_ID = $_SESSION['Carpenter_ID'];
$plan_id = $_POST['plan_ID'] ?? null;

if (!$plan_id) {
    echo "<script>alert('Error: Plan ID is missing.'); window.location.href = 'profile.php';</script>";
    exit();
}

// ❌ Check if the evaluator has already evaluated this plan
$checkQuery = "SELECT * FROM plan_approval WHERE plan_ID = ? AND Carpenter_ID = ?";
$stmt = mysqli_prepare($conn, $checkQuery);
mysqli_stmt_bind_param($stmt, "ii", $plan_id, $Carpenter_ID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo "<script>alert('You have already evaluated this plan! You cannot evaluate again.'); window.location.href = 'profile.php';</script>";
    exit();
}

// ✅ Proceed with inserting evaluation data if not yet evaluated
$total_score = $_POST['total_score'] ?? 0;

// Collect scores
$scope_scores = [];
for ($i = 1; $i <= 5; $i++) {
    $scope_scores[] = intval($_POST["scope_$i"] ?? 0);
}

$site_scores = [];
for ($i = 1; $i <= 5; $i++) {
    $site_scores[] = intval($_POST["site_$i"] ?? 0);
}

$workforce_scores = [];
for ($i = 1; $i <= 4; $i++) {
    $workforce_scores[] = intval($_POST["workforce_$i"] ?? 0);
}

// Collect client responses and comments
$client_responses = [];
$client_comments = [];
for ($i = 1; $i <= 5; $i++) {
    $client_responses[] = $_POST["client_$i"] ?? 'No';
    $client_comments[] = $_POST["client_{$i}_comment"] ?? '';
}

// Get evaluator info
$evaluator_name = mysqli_real_escape_string($conn, $_POST['evaluator_name'] ?? '');
$evaluation_date = $_POST['evaluation_date'] ?? date('Y-m-d');
$final_decision = $_POST['final_decision'] ?? '';

// Determine status based on decision
$status = ($final_decision === 'accept' || $final_decision === 'accept_conditions') ? 'approve' : 'decline';

// Convert arrays to JSON for storage
$scope_json = json_encode($scope_scores);
$site_json = json_encode($site_scores);
$workforce_json = json_encode($workforce_scores);
$responses_json = json_encode($client_responses);
$comments_json = json_encode($client_comments);

// Insert evaluation data
$insertQuery = "INSERT INTO plan_approval (
    plan_ID, 
    Carpenter_ID, 
    total_score,
    scope_scores,
    site_scores,
    workforce_scores,
    client_responses,
    client_comments,
    evaluator_name,
    evaluation_date,
    final_decision,
    status
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $insertQuery);
mysqli_stmt_bind_param($stmt, "iiisssssssss",
    $plan_id,
    $Carpenter_ID,
    $total_score,
    $scope_json,
    $site_json,
    $workforce_json,
    $responses_json,
    $comments_json,
    $evaluator_name,
    $evaluation_date,
    $final_decision,
    $status
);

if (mysqli_stmt_execute($stmt)) {
    // Update carpenter limit after approval
    $updatePlanQuery = "UPDATE plan SET carpenter_limit = carpenter_limit - 1 WHERE plan_ID = ?";
    $updateStmt = mysqli_prepare($conn, $updatePlanQuery);
    mysqli_stmt_bind_param($updateStmt, "i", $plan_id);
    mysqli_stmt_execute($updateStmt);

    echo "<script>alert('Plan evaluation submitted successfully!'); window.location.href = 'profile.php';</script>";
} else {
    echo "<script>alert('Error submitting evaluation: " . mysqli_error($conn) . "'); window.location.href = 'profile.php';</script>";
}

mysqli_close($conn);
?>
