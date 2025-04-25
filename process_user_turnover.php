<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
session_start();
include('config.php');

if (!isset($_SESSION['User_ID']) || !isset($_POST['contract_ID'])) {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Unauthorized access',
            icon: 'error',
            timer: 3000,
            showConfirmButton: true
        }).then(() => {
            window.location.href = 'login.html';
        });
    </script>";
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
        
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Project approved successfully!',
                icon: 'success',
                timer: 3000,
                showConfirmButton: true
            }).then(() => {
                window.location.href = 'userprofile.php';
            });
        </script>";
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
        
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Revision requested successfully!',
                icon: 'success',
                timer: 3000,
                showConfirmButton: true
            }).then(() => {
                window.location.href = 'userprofile.php';
            });
        </script>";
    }
} else {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Failed to process approval',
            icon: 'error',
            timer: 3000,
            showConfirmButton: true
        }).then(() => {
            window.location.href = 'viewturnover.php?contract_ID=" . $contract_ID . "';
        });
    </script>";
}

$conn->close();
?>
</body>
</html>