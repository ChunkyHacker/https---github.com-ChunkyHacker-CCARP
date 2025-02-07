<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug: Check if $_POST data is received
    error_log("POST Data: " . print_r($_POST, true));
    
    if (!empty($_POST['id']) && !empty($_POST['structure']) && !empty($_POST['name']) && isset($_POST['price'])) {
        $id = $_POST['id'];
        $structure = $_POST['structure'];
        $name = $_POST['name'];
        $price = $_POST['price'];

        $updateSql = "UPDATE prematerialsinventory SET structure = ?, name = ?, price = ? WHERE prematerialsinventory_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssdi", $structure, $name, $price, $id);
        
        if ($updateStmt->execute()) {
            // Display success message and redirect
            echo "<script>alert('Item updated successfully!'); window.location.href='admin_view_inventory.php';</script>";
        } else {
            // Display error message and redirect
            echo "<script>alert('Error updating item.'); window.location.href='admin_view_inventory.php';</script>";
        }
    } else {
        // Display invalid input message and redirect
        echo "<script>alert('Invalid input.'); window.location.href='admin_view_inventory.php';</script>";
    }
} else {
    // Display invalid request method message and redirect
    echo "<script>alert('Invalid request method.'); window.location.href='admin_view_inventory.php';</script>";
}
?>
