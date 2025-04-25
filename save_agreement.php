<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['plan_ID'], $_POST['approval_ID'], $_POST['Carpenter_ID'])) {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Missing required fields',
                icon: 'error',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.history.back();
            });
        </script>";
        exit();
    }

    $Plan_ID = $_POST['plan_ID'];
    $approval_ID = !empty($_POST['approval_ID']) ? $_POST['approval_ID'] : null;
    $Carpenter_ID = !empty($_POST['Carpenter_ID']) ? $_POST['Carpenter_ID'] : null;

    if (!$approval_ID || !$Carpenter_ID) {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Missing approval ID or Carpenter ID',
                icon: 'error',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.history.back();
            });
        </script>";
        exit();
    }

    // Check if carpenter already has an active contract
    $check_sql = "SELECT COUNT(*) as count FROM contracts WHERE Carpenter_ID = ? AND transaction_ID IS NULL";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $Carpenter_ID);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        echo "<script>
            Swal.fire({
                title: 'Warning!',
                text: 'This carpenter already has an active agreement. Cannot create multiple agreements.',
                icon: 'warning',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.history.back();
            });
        </script>";
        exit();
    }

    $labor_cost = isset($_POST['labor_cost']) ? $_POST['labor_cost'] : '0.00';
    $duration = isset($_POST['duration']) ? $_POST['duration'] : 0;
    $type_of_work = isset($_POST['type_of_work']) ? $_POST['type_of_work'] : 'select type of work';

    // Convert labor cost to float after removing 'PHP '
    $labor_cost = (float) str_replace('PHP ', '', $labor_cost);

    // Updated INSERT query to include type_of_work
    $sql = "INSERT INTO contracts (Plan_ID, approval_ID, Carpenter_ID, labor_cost, duration, transaction_ID, type_of_work) 
            VALUES (?, ?, ?, ?, ?, NULL, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    
    mysqli_stmt_bind_param($stmt, "iiiids", 
        $Plan_ID, 
        $approval_ID, 
        $Carpenter_ID, 
        $labor_cost,
        $duration,
        $type_of_work
    );

    if (mysqli_stmt_execute($stmt)) {
        $contract_ID = mysqli_insert_id($conn);
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Contract successfully submitted',
                icon: 'success',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.location.href = 'viewaddedrequirements.php?contract_ID=$contract_ID';
            });
        </script>";
        exit();
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Error: " . mysqli_error($conn) . "',
                icon: 'error',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.history.back();
            });
        </script>";
    }
}
?>
</body>
</html>
