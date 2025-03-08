<?php
session_start();
include('config.php');

if (!isset($_SESSION['User_ID'])) {
    header('Location: login.html');
    exit();
}
?>

<script>
    alert("Plan has been successfully deleted!");
    window.location.href = 'userprofile.php';
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['plan_ID'])) {
    $plan_ID = intval($_GET['plan_ID']);
    
    // Delete the plan
    $sql = "DELETE FROM plan WHERE plan_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $plan_ID);
    
    if ($stmt->execute()) {
        // Redirect happens after alert
        exit();
    } else {
        // Error in deletion
        echo "Error deleting plan: " . $conn->error;
    }
} else {
    // Invalid request
    header('Location: userprofile.php');
    exit();
}

$conn->close();
?> 