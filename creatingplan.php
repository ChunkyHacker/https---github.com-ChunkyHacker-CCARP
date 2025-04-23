<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if 'User_ID' is set in the session
    if (!isset($_SESSION['User_ID'])) {
        die("Error: User ID not set.");
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

            $response['success'] = true;
            $response['message'] = "Your plan has been successfully submitted.";

            // Redirect with success message
            header("Location: selectmaterials.php?success=true&message=" . urlencode($response['message']));
            exit;
        } else {
            $response['success'] = false;
            $response['message'] = "Error: " . mysqli_error($conn);
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Error: Database error.";
    }

    header("Content-Type: application/json");
    echo json_encode($response);
} else {
    $response['success'] = false;
    $response['message'] = "Error: Invalid request method.";

    header("Content-Type: application/json");
    echo json_encode($response);
}
?>
