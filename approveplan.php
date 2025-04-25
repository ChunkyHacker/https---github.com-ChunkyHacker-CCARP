<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
include('config.php');
session_start();

if (!isset($_SESSION['Carpenter_ID'])) {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Carpenter ID is not set. Please log in again.',
            icon: 'error',
            timer: 3000,
            showConfirmButton: true
        }).then(function() {
            window.location.href = 'login.php';
        });
    </script>";
    exit();
}

$Carpenter_ID = $_SESSION['Carpenter_ID'];
$plan_id = $_POST['plan_ID'] ?? null;

if (!$plan_id) {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Plan ID is missing.',
            icon: 'error',
            timer: 3000,
            showConfirmButton: true
        }).then(function() {
            window.location.href = 'profile.php';
        });
    </script>";
    exit();
}

// Check if already evaluated
$checkQuery = "SELECT * FROM plan_approval WHERE plan_ID = ? AND Carpenter_ID = ?";
$stmt = mysqli_prepare($conn, $checkQuery);
mysqli_stmt_bind_param($stmt, "ii", $plan_id, $Carpenter_ID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo "<script>
        Swal.fire({
            title: 'Warning!',
            text: 'You have already evaluated this plan! You cannot evaluate again.',
            icon: 'warning',
            timer: 3000,
            showConfirmButton: true
        }).then(function() {
            window.location.href = 'profile.php';
        });
    </script>";
    exit();
}

// Check carpenter limit before proceeding
$checkLimitQuery = "SELECT carpenter_limit FROM plan WHERE plan_ID = ?";
$limitStmt = mysqli_prepare($conn, $checkLimitQuery);
mysqli_stmt_bind_param($limitStmt, "i", $plan_id);
mysqli_stmt_execute($limitStmt);
$limitResult = mysqli_stmt_get_result($limitStmt);
$limitData = mysqli_fetch_assoc($limitResult);

if ($limitData['carpenter_limit'] <= 0) {
    echo "<script>
        Swal.fire({
            title: 'Warning!',
            text: 'This plan has reached its carpenter limit. No more approvals allowed.',
            icon: 'warning',
            timer: 3000,
            showConfirmButton: true
        }).then(function() {
            window.location.href = 'profile.php';
        });
    </script>";
    exit();
}

// Collect all scores
$total_score = $_POST['total_score'] ?? 0;

// Project Scope & Feasibility (5 criteria)
$scope_scores = [];
for ($i = 1; $i <= 5; $i++) {
    $score = isset($_POST["scope_$i"]) ? intval($_POST["scope_$i"]) : 0;
    $scope_scores[] = $score;
}

// Site Assessment (5 criteria)
$site_scores = [];
for ($i = 1; $i <= 5; $i++) {
    $score = isset($_POST["site_$i"]) ? intval($_POST["site_$i"]) : 0;
    $site_scores[] = $score;
}

// Workforce & Resource Availability (4 criteria)
$workforce_scores = [];
for ($i = 1; $i <= 4; $i++) {
    $score = isset($_POST["workforce_$i"]) ? intval($_POST["workforce_$i"]) : 0;
    $workforce_scores[] = $score;
}

// Client Readiness & Commitment (5 criteria)
$client_responses = [];
$client_comments = [];
for ($i = 1; $i <= 5; $i++) {
    // Get the response and comment values using isset check
    $response = isset($_POST["client_$i"]) ? $_POST["client_$i"] : '';
    $comment = isset($_POST["client_{$i}_comment"]) ? $_POST["client_{$i}_comment"] : '';
    
    // Add to arrays
    $client_responses[] = $response;
    $client_comments[] = $comment;
}

// Get evaluator info
$evaluator_name = $_SESSION['First_Name'] . ' ' . $_SESSION['Last_Name'];
$evaluation_date = date('Y-m-d');
$final_decision = $_POST['final_decision'] ?? '';

// Calculate total score
$calculated_total = array_sum($scope_scores) + array_sum($site_scores) + array_sum($workforce_scores);

// Determine status based on decision and scores
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
    $calculated_total, // Using calculated total instead of form total
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
    if ($status === 'approve') {
        // Double check limit again before updating
        $checkAgainStmt = mysqli_prepare($conn, $checkLimitQuery);
        mysqli_stmt_bind_param($checkAgainStmt, "i", $plan_id);
        mysqli_stmt_execute($checkAgainStmt);
        $checkAgainResult = mysqli_stmt_get_result($checkAgainStmt);
        $currentLimit = mysqli_fetch_assoc($checkAgainResult)['carpenter_limit'];

        if ($currentLimit > 0) {
            $updatePlanQuery = "UPDATE plan SET carpenter_limit = carpenter_limit - 1 WHERE plan_ID = ? AND carpenter_limit > 0";
            $updateStmt = mysqli_prepare($conn, $updatePlanQuery);
            mysqli_stmt_bind_param($updateStmt, "i", $plan_id);
            mysqli_stmt_execute($updateStmt);
        }
    }
    
    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Plan evaluation submitted successfully!',
            icon: 'success',
            timer: 3000,
            showConfirmButton: true
        }).then(function() {
            window.location.href = 'profile.php';
        });
    </script>";
} else {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Error submitting evaluation: " . mysqli_error($conn) . "',
            icon: 'error',
            timer: 3000,
            showConfirmButton: true
        }).then(function() {
            window.location.href = 'profile.php';
        });
    </script>";
}

mysqli_close($conn);
?>
</body>
</html>
