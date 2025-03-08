<?php
// Include database connection from config.php
include 'config.php';

session_start();
if (!isset($_SESSION['User_ID'])) {
    die(json_encode(['success' => false, 'message' => 'Session user ID not found.']));
}
// Check if connection is established
if (!isset($conn) || !$conn) {
    file_put_contents('contract_update_log.txt', date('Y-m-d H:i:s') . ' - Error: Database connection failed' . "\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log the request for debugging
file_put_contents('contract_update_log.txt', date('Y-m-d H:i:s') . ' - Request: ' . print_r($_POST, true) . "\n", FILE_APPEND);

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the plan ID and status from the request
    $plan_id = isset($_POST['plan_id']) ? intval($_POST['plan_id']) : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    
    // Check if we're using 'reason' or 'rejection_reason' parameter
    $reason = '';
    if (isset($_POST['reason']) && !empty($_POST['reason'])) {
        $reason = $_POST['reason'];
    } elseif (isset($_POST['rejection_reason']) && !empty($_POST['rejection_reason'])) {
        $reason = $_POST['rejection_reason'];
    }
    
    // Get logged-in User_ID
    $User_ID = isset($_SESSION['User_ID']) ? $_SESSION['User_ID'] : null;

    // Log the extracted values
    file_put_contents('contract_update_log.txt', date('Y-m-d H:i:s') . ' - Extracted values: plan_id=' . $plan_id . ', status=' . $status . ', reason=' . $reason . "\n", FILE_APPEND);
    
    // Validate inputs
    if ($plan_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid plan ID: ' . $plan_id]);
        exit;
    }
    
    if (!in_array($status, ['accepted', 'rejected', 'pending'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid status value: ' . $status]);
        exit;
    }
    
    // Check if a contract record already exists for this plan
    $checkQuery = "SELECT contract_ID, status FROM contracts WHERE plan_ID = ?";
    $checkStmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, "i", $plan_id);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);
    
    if (mysqli_num_rows($checkResult) > 0) {
        // Get the contract ID and current status
        $contractData = mysqli_fetch_assoc($checkResult);
        $contract_id = $contractData['contract_ID'];
        
        // **Update Contract Status & User_ID**
        if ($status === 'accepted' && $User_ID !== null) {
            $query = "UPDATE contracts SET status = ?, rejection_reason = ?, User_ID = ? WHERE contract_ID = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssii", $status, $reason, $User_ID, $contract_id);

            file_put_contents('contract_update_log.txt', date('Y-m-d H:i:s') . ' - Updating contract ID: ' . $contract_id . ' with User_ID: ' . $User_ID . "\n", FILE_APPEND);
        } else {
            $query = "UPDATE contracts SET status = ?, rejection_reason = ? WHERE contract_ID = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssi", $status, $reason, $contract_id);

            file_put_contents('contract_update_log.txt', date('Y-m-d H:i:s') . ' - Updating contract ID: ' . $contract_id . "\n", FILE_APPEND);
        }
    } else {
        // Insert new contract record
        $query = "INSERT INTO contracts (plan_ID, status, rejection_reason, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iss", $plan_id, $status, $reason);

        file_put_contents('contract_update_log.txt', date('Y-m-d H:i:s') . ' - Creating new contract record\n', FILE_APPEND);
    }
    
    // Log the query
    file_put_contents('contract_update_log.txt', date('Y-m-d H:i:s') . ' - Query: ' . (isset($query) ? $query : 'No query') . "\n", FILE_APPEND);
    
    if (mysqli_stmt_execute($stmt)) {
        file_put_contents('contract_update_log.txt', date('Y-m-d H:i:s') . ' - Success: Contract status updated to ' . $status . "\n", FILE_APPEND);
        echo json_encode(['success' => true]);
    } else {
        $error = mysqli_error($conn);
        file_put_contents('contract_update_log.txt', date('Y-m-d H:i:s') . ' - Error: ' . $error . "\n", FILE_APPEND);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $error]);
    }
    
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($checkStmt);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

// Close the database connection
mysqli_close($conn);
?>
