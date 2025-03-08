<?php
include('config.php');
session_start();

// Ensure the carpenter is logged in
if (!isset($_SESSION['Carpenter_ID'])) {
    echo "<script>alert('Error: Carpenter ID is not set. Please log in again.'); window.location.href = 'login.php';</script>";
    exit();
}

$Carpenter_ID = $_SESSION['Carpenter_ID']; // Get the Carpenter ID
$plan_id = $_POST['plan_ID'] ?? null; // Ensure correct key case
$comment = $_POST['comment'] ?? null;
$status = $_POST['status'] ?? 'approve'; // Allow status to be "decline"

// Validate if plan_id exists
if (!$plan_id) {
    echo "<script>alert('Error: Plan ID is missing.'); window.location.href = 'profile.php';</script>";
    exit();
}

// Check if the plan exists in the database
$checkPlanQuery = "SELECT User_ID, carpenter_limit FROM plan WHERE plan_ID = ?";
$stmt = mysqli_prepare($conn, $checkPlanQuery);
mysqli_stmt_bind_param($stmt, "i", $plan_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Error: Plan ID $plan_id not found in database.");
}

$User_ID = $row['User_ID'];
$remaining_limit = $row['carpenter_limit'];

// Ensure carpenter limit is not exceeded
if ($remaining_limit <= 0) {
    echo "<script>alert('Error: Carpenter limit reached. No more approvals allowed.'); window.location.href = 'profile.php';</script>";
    exit();
}

// Check if carpenter already approved the plan
$checkQuery = "SELECT * FROM plan_approval WHERE plan_ID = ? AND Carpenter_ID = ?";
$stmt = mysqli_prepare($conn, $checkQuery);
mysqli_stmt_bind_param($stmt, "ii", $plan_id, $Carpenter_ID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    echo "<script>alert('You have already approved this plan!'); window.location.href = 'profile.php';</script>";
    exit();
}

// Insert into `plan_approval` table with status and comment
$insertQuery = "INSERT INTO plan_approval (plan_ID, Carpenter_ID, status, comment, approved_by) 
                VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $insertQuery);
mysqli_stmt_bind_param($stmt, "iissi", $plan_id, $Carpenter_ID, $status, $comment, $Carpenter_ID);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// Reduce `carpenter_limit` by 1
$updateQuery = "UPDATE plan SET carpenter_limit = carpenter_limit - 1 WHERE plan_ID = ?";
$stmt = mysqli_prepare($conn, $updateQuery);
mysqli_stmt_bind_param($stmt, "i", $plan_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// If limit reaches 0, update `plan` status to 'closed' (no need to insert into `approvedplan`)
$checkLimitQuery = "SELECT carpenter_limit FROM plan WHERE plan_ID = ?";
$stmt = mysqli_prepare($conn, $checkLimitQuery);
mysqli_stmt_bind_param($stmt, "i", $plan_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if ($row['carpenter_limit'] == 0) {
    // Close the plan in the `plan` table
    $closeQuery = "UPDATE plan SET status = 'closed' WHERE plan_ID = ?";
    $stmt = mysqli_prepare($conn, $closeQuery);
    mysqli_stmt_bind_param($stmt, "i", $plan_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

echo "<script>alert('Plan Approved!'); window.location.href = 'profile.php';</script>";
exit();

mysqli_close($conn);
?>
