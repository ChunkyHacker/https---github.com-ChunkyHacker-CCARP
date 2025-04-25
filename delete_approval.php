<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
session_start();
include('config.php');

if (!isset($_SESSION['User_ID'])) {
    header('Location: login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['plan_ID'])) {
    $plan_ID = intval($_GET['plan_ID']);
    
    // Disable foreign key checks
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0");
    
    // Delete the plan
    $sql = "DELETE FROM plan WHERE plan_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $plan_ID);
    
    if ($stmt->execute()) {
        // Re-enable foreign key checks
        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");
        
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Plan has been successfully deleted!',
                icon: 'success',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.location.href = 'userprofile.php';
            });
        </script>";
        exit();
    } else {
        // Re-enable foreign key checks even on error
        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");
        
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Error deleting plan: " . $conn->error . "',
                icon: 'error',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.history.back();
            });
        </script>";
    }
} else {
    header('Location: userprofile.php');
    exit();
}

$conn->close();
?>
</body>
</html>