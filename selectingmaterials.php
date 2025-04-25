<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
session_start(); 
include('config.php');

$user_ID = $_POST['user_ID'];
$sql = "INSERT INTO prematerials (User_ID, materials, part, name, quantity, price, total, estimated_cost) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Error preparing the SQL statement: " . $conn->error . "',
            icon: 'error',
            timer: 3000,
            showConfirmButton: true
        }).then(function() {
            window.history.back();
        });
    </script>";
    exit();
}

if (!empty($_POST["materials"])) {
    foreach ($_POST["materials"] as $key => $materials) {
        $part = isset($_POST["part"][$key]) ? $_POST["part"][$key] : "";
        $name = $_POST["name"][$key];
        $quantity = $_POST["quantity"][$key];
        $price = $_POST["price"][$key];
        $total = $_POST["total"][$key];
        $estimated_cost = (float)$_POST["estimated_cost"];

        // Modified bind_param to include User_ID
        $stmt->bind_param("isssiddi", 
            $user_ID,      // Add User_ID parameter
            $materials, 
            $part, 
            $name, 
            $quantity, 
            $price, 
            $total, 
            $estimated_cost
        );
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Your materials have been successfully submitted',
            icon: 'success',
            timer: 3000,
            showConfirmButton: true
        }).then(function() {
            window.location.href = 'userprofile.php';
        });
    </script>";
    exit();
} else {
    echo "<script>
        Swal.fire({
            title: 'Warning!',
            text: 'No items selected',
            icon: 'warning',
            timer: 3000,
            showConfirmButton: true
        }).then(function() {
            window.history.back();
        });
    </script>";
}

$conn->close();
?>
</body>
</html>