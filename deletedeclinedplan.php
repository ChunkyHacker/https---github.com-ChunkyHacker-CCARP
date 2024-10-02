<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['plan_id'])) {
    $plan_id = $_POST['plan_id'];

    // Delete plan logic
    $deleteQuery = "DELETE FROM declinedplan WHERE declined_plan_ID = ?";
    $deleteStmt = mysqli_prepare($connection, $deleteQuery);
    mysqli_stmt_bind_param($deleteStmt, "i", $plan_id);

    if (mysqli_stmt_execute($deleteStmt)) {
        echo "Plan deleted successfully.";
        // Redirect to userprofile.php after successful deletion
        header("Location: userprofile.php");
        exit();
    } else {
        echo "Error deleting plan: " . mysqli_error($connection);
    }

    mysqli_stmt_close($deleteStmt);
} else {
    echo "Invalid request.";
}
?>
