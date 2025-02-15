<?php
include 'config.php'; // Database connection
session_start(); // Para ma-access ang user session

if (!isset($_SESSION['user_ID'])) {
    echo "<script>
            alert('Error: User not logged in.');
            window.location.href = 'login.php'; // Redirect to login page
          </script>";
    exit;
}

$signed_by = $_SESSION['user_ID']; // Get logged-in user ID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requirement_ID = $_POST['requirement_ID'];

    // Check if the contract is already signed
    $check_query = "SELECT signed_contract_ID FROM signed_contracts WHERE requirement_ID = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $requirement_ID);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);

    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        echo "<script>
                alert('Contract has already been signed for this requirement.');
                window.location.href = 'usercomputebudget.php?requirement_ID={$requirement_ID}';
              </script>";
        exit;
    }
    mysqli_stmt_close($check_stmt);

    // Get contract details
    $query = "SELECT * FROM contracts WHERE requirement_ID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $requirement_ID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($contract = mysqli_fetch_assoc($result)) {
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
        $photo_path = $contract['photo_path'];
        $start_date = $contract['start_date'];
        $end_date = $contract['end_date'];

        // Insert into signed_contracts
        $insert_query = "INSERT INTO signed_contracts (
            contract_ID, requirement_ID, client_ID, client_name, contractor_name, 
            length_lot_area, width_lot_area, square_meter_lot, 
            length_floor_area, width_floor_area, square_meter_floor, 
            type, initial_budget, photo_path, start_date, end_date, signed_by
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $insert_stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "iiissdddddddsdssi", 
            $contract_ID, $requirement_ID, $client_ID, $client_name, $contractor_name, 
            $length_lot_area, $width_lot_area, $square_meter_lot, 
            $length_floor_area, $width_floor_area, $square_meter_floor, 
            $type, $initial_budget, $photo_path, $start_date, $end_date, $signed_by
        );

        if (mysqli_stmt_execute($insert_stmt)) {
            echo "<script>
                    alert('Contract signed successfully!');
                    window.location.href = 'usercomputebudget.php?requirement_ID={$requirement_ID}';
                  </script>";
        } else {
            echo "<script>alert('Error signing contract.');</script>";
        }

        mysqli_stmt_close($insert_stmt);
    } else {
        echo "<script>alert('Contract not found.');</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo "<script>alert('Invalid request.');</script>";
}
?>
