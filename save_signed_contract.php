<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
include 'config.php';
session_start();

if (!isset($_SESSION['User_ID'])) {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'You need to log in first.',
            icon: 'error',
            timer: 3000,
            showConfirmButton: true
        }).then(function() {
            window.location.href = 'login.php';
        });
    </script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requirement_ID = $_POST['requirement_ID'];
    $user_ID = $_SESSION['User_ID'];

    // Check if contract already exists
    $check_query = "SELECT * FROM signed_contracts WHERE requirement_ID = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $requirement_ID);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        echo "<script>
            Swal.fire({
                title: 'Warning!',
                text: 'You have already signed this contract.',
                icon: 'warning',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.history.back();
            });
        </script>";
        exit();
    }

    // Get contract details
    $query = "SELECT * FROM contracts WHERE requirement_ID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $requirement_ID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($contract = mysqli_fetch_assoc($result)) {
        // Prepare data
        $contract_ID = $contract['contract_ID'];
        $client_ID = $contract['client_ID'];
        $client_name = $contract['client_name'];
        $contractor_name = $contract['contractor_name'];
        $length_lot_area = $contract['length_lot_area'];
        $width_lot_area = $contract['width_lot_area'];
        $square_meter_lot = $contract['square_meter_lot'];
        $length_floor_area = $contract['length_floor_area'];
        $width_floor_area = $contract['width_floor_area'];
        $square_meter_floor = $contract['square_meter_floor'];
        $type = $contract['type'];
        $initial_budget = $contract['initial_budget'];
        $Photo = $contract['Photo'];
        $start_date = $contract['start_date'];
        $end_date = $contract['end_date'];
        $labor_cost = $contract['labor_cost']; // Fetching labor cost from the contract

        // Set the photo parameter (ensure it's not NULL)
        $photo_param = !empty($Photo) ? $Photo : 'default.jpg'; // Use a default value if no photo

        // Insert signed contract with labor cost
        $insert_query = "INSERT INTO signed_contracts 
            (contract_ID, 
            requirement_ID, 
            client_ID, 
            client_name, 
            contractor_name, 
            length_lot_area, 
            width_lot_area, 
            square_meter_lot, 
            length_floor_area, 
            width_floor_area, 
            square_meter_floor, 
            type, 
            initial_budget, 
            labor_cost, 
            Photo, 
            start_date, 
            end_date, 
            signed_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $insert_stmt = mysqli_prepare($conn, $insert_query);

        // Bind parameters for the query
        mysqli_stmt_bind_param($insert_stmt, "iiissdddddddsdssis", 
            $contract_ID, 
            $requirement_ID, 
            $client_ID, 
            $client_name, 
            $contractor_name, 
            $length_lot_area, 
            $width_lot_area, 
            $square_meter_lot, 
            $length_floor_area, 
            $width_floor_area, 
            $square_meter_floor, 
            $type, $initial_budget, 
            $labor_cost, 
            $photo_param, 
            $start_date, 
            $end_date, 
            $user_ID
        );

        if (mysqli_stmt_execute($insert_stmt)) {
            echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Contract signed successfully!',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: true
                }).then(function() {
                    window.location.href = 'usercomputebudget.php?requirement_ID=$requirement_ID';
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Error signing contract.',
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: true
                }).then(function() {
                    window.history.back();
                });
            </script>";
        }

        // Close connections
        mysqli_stmt_close($stmt);
        mysqli_stmt_close($insert_stmt);
        mysqli_close($conn);
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Contract not found.',
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
            text: 'Invalid request.',
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
