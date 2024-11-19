<?php
// Function to delete the declined plan based on declined_plan_ID
function deleteDeclinedPlan($declined_plan_ID) {
    include('config.php'); // Include database connection

    // Prepare query to delete from declinedplan table
    $deleteQuery = "DELETE FROM declinedplan WHERE declined_plan_ID = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteQuery);
    
    // Bind the declined_plan_ID parameter
    mysqli_stmt_bind_param($deleteStmt, "i", $declined_plan_ID);

    // Execute the query
    if (mysqli_stmt_execute($deleteStmt)) {
        // Redirect to userprofile.php with success message in URL
        header("Location: userprofile.php?success=true&message=" . urlencode("Declined Plan has been successfully deleted."));
        exit(); // Make sure to stop further code execution
    } else {
        echo "Error deleting the plan: " . mysqli_error($conn);
    }

    // Close the statement and connection
    mysqli_stmt_close($deleteStmt);
    mysqli_close($conn);
}

// Check if the form is submitted and if declined_plan_ID is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['declined_plan_ID'])) {
    $declined_plan_ID = $_POST['declined_plan_ID'];
    deleteDeclinedPlan($declined_plan_ID);
}
?>
