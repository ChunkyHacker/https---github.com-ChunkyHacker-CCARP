<?php
session_start();
include('config.php');

if (!isset($_SESSION['User_ID']) || !isset($_POST['contract_ID'])) {
    header('Location: login.html');
    exit();
}

$User_ID = $_SESSION['User_ID'];
$contract_ID = $_POST['contract_ID'];
$approval_status = $_POST['approval_status'];
$comments = $_POST['comments'];

// Update project_turnover table
$sql = "UPDATE project_turnover 
        SET confirmation_status = ?, 
            client_feedback = ?
        WHERE contract_ID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $approval_status, $comments, $contract_ID);

if ($stmt->execute()) {
    // If approved, update both project_turnover and contracts tables
    if ($approval_status === 'approved') {
        // Update project_turnover confirmation status
        $update_turnover = "UPDATE project_turnover 
                           SET confirmation_status = 'confirmed', 
                               client_feedback = ?,
                               approved_by = ?,
                               approved_at = CURRENT_TIMESTAMP 
                           WHERE contract_ID = ?";
        $stmt = $conn->prepare($update_turnover);
        $stmt->bind_param("sii", $comments, $User_ID, $contract_ID);
        $stmt->execute();

        // Update contract status
        $update_contract = "UPDATE contracts SET status = 'completed' WHERE contract_ID = ?";
        $stmt = $conn->prepare($update_contract);
        $stmt->bind_param("i", $contract_ID);
        $stmt->execute();
        
        header("Location: userprofile.php?success=true&message=Project approved successfully!");
    } else {
        // If revision requested, update project_turnover
        $update_turnover = "UPDATE project_turnover 
                           SET confirmation_status = 'revision', 
                               client_feedback = ? 
                           WHERE contract_ID = ?";
        $stmt = $conn->prepare($update_turnover);
        $stmt->bind_param("si", $comments, $contract_ID);
        $stmt->execute();

        // Update contract status back to accepted
        $update_contract = "UPDATE contracts SET status = 'accepted' WHERE contract_ID = ?";
        $stmt = $conn->prepare($update_contract);
        $stmt->bind_param("i", $contract_ID);
        $stmt->execute();
        
        header("Location: userprofile.php?success=true&message=Revision requested successfully!");
    }
} else {
    header("Location: viewturnover.php?contract_ID=" . $contract_ID . "&error=true&message=Failed to process approval");
}

exit();
?>