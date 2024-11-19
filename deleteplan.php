<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['plan_id'])) {
    $plan_id = $_POST['plan_id'];

    // Delete plan logic
    $deleteQuery = "DELETE FROM plan WHERE plan_ID = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteQuery);
    mysqli_stmt_bind_param($deleteStmt, "i", $plan_id);

    if (mysqli_stmt_execute($deleteStmt)) {
        // Echo JavaScript to show the alert and redirect after a successful deletion
        echo "<script>
                alert('Plan deleted successfully.');
                window.location.href = 'userprofile.php';
              </script>";
    } else {
        echo "Error deleting plan: " . mysqli_error($conn);
    }

    mysqli_stmt_close($deleteStmt);
} else {
    echo "Invalid request.";
}
?>
