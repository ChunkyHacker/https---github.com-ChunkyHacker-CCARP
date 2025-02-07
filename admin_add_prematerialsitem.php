<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $structure = $_POST['structure'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    $sql = "INSERT INTO prematerialsinventory (structure, name, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssd", $structure, $name, $price);

    if ($stmt->execute()) {
        echo "<script>alert('Item added successfully!'); window.location.href='admin_view_inventory.php';</script>";
    } else {
        echo "<script>alert('Error adding item.'); window.location.href='admin_view_inventory.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
