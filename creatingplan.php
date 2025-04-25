<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION['User_ID'])) {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'User ID not set. Please login again.',
                icon: 'error',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.location.href = 'login.html';
            });
        </script>";
        exit();
    }

    $user_ID = $_SESSION['User_ID'];
    $length_lot_area = $_POST['length_lot_area'] ?? '';
    $width_lot_area = $_POST['width_lot_area'] ?? '';
    $square_meter_lot = $_POST['square_meter_lot'] ?? '';
    $length_floor_area = $_POST['length_floor_area'] ?? '';
    $width_floor_area = $_POST['width_floor_area'] ?? '';
    $square_meter_floor = $_POST['square_meter_floor'] ?? '';
    $initial_budget = $_POST['initial_budget'] ?? '';
    $estimated_cost = $_POST['estimated_cost'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $type = $_POST['type'] ?? '';
    $more_details = $_POST['more_details'] ?? '';
    $carpenter_limit = $_POST['carpenter_limit'] ?? 1; // Default to 1 if not set

    // Simple file upload handling
    $targetDir = "assets/plan/";
    $targetFile = $targetDir . basename($_FILES["Photo"]["name"]);

    if (move_uploaded_file($_FILES["Photo"]["tmp_name"], $targetFile)) {
        $photoPath = $targetFile;
    } else {
        $photoPath = "";
    }

    // Continue with database insertion
    $query = "INSERT INTO plan (User_ID, length_lot_area, width_lot_area, square_meter_lot, length_floor_area, width_floor_area, square_meter_floor, initial_budget, estimated_cost, start_date, end_date, type, more_details, Photo, carpenter_limit) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "isssssssssssssi", $user_ID, $length_lot_area, $width_lot_area, $square_meter_lot, $length_floor_area, $width_floor_area, $square_meter_floor, $initial_budget, $estimated_cost, $start_date, $end_date, $type, $more_details, $photoPath, $carpenter_limit);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            
            echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Your plan has been successfully submitted',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: true
                }).then(function() {
                    window.location.href = 'selectmaterials.php';
                });
            </script>";
            exit();
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Database error: " . mysqli_error($conn) . "',
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: true
                }).then(function() {
                    window.history.back();
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Failed to prepare database statement',
                icon: 'error',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.history.back();
            });
        </script>";
    }
} else {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Invalid request method',
            icon: 'error',
            timer: 3000,
            showConfirmButton: true
        }).then(function() {
            window.history.back();
        });
    </script>";
}
?>
</body>
</html>
