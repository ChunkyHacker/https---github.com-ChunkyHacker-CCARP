<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Prepare delete query
    $deleteSql = "DELETE FROM prematerialsinventory WHERE prematerialsinventory_id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $id);
    
    if ($deleteStmt->execute()) {
        echo "<script>alert('Item deleted successfully!'); window.location.href='admin_view_inventory.php';</script>";
    } else {
        echo "<script>alert('Error deleting item.'); window.location.href='admin_view_inventory.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='admin_view_inventory.php';</script>";
}

$conn->close();
?>
