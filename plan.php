<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if 'User_ID' is set in the session
    if (!isset($_SESSION['User_ID'])) {
        // Handle the case when 'User_ID' is not set (you may redirect the user or display an error)
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


    // Simple file upload handling
    $targetDir = "assets/plan/";
    $targetFile = $targetDir . basename($_FILES["Photo"]["name"]);

    if (move_uploaded_file($_FILES["Photo"]["tmp_name"], $targetFile)) {
        // File successfully uploaded
        $photoPath = $targetFile;
    } else {
        // Error uploading file
        $photoPath = "";
    }

    // Continue with database insertion
    $query = "INSERT INTO plan (User_ID, length_lot_area, width_lot_area, square_meter_lot, length_floor_area, width_floor_area, square_meter_floor, initial_budget, estimated_cost, start_date, end_date, type, more_details, Photo) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {        
        mysqli_stmt_bind_param($stmt, "isssssssssssss", $user_ID, $length_lot_area, $width_lot_area, $square_meter_lot, $length_floor_area, $width_floor_area, $square_meter_floor, $initial_budget, $estimated_cost, $start_date, $end_date, $type, $more_details, $photoPath);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: selectmaterials.php");
            exit;
        } else {
            // Check if the error is due to a duplicate entry
            if (mysqli_errno($conn) == 1062) {
                // Plan with the same user_ID already exists
                $response['success'] = false;
                $response['message'] = "Error: A plan for this user already exists.";
            } else {
                // Handle other errors
                $response['success'] = false;
                $response['message'] = "Error: " . mysqli_error($conn);
            }
        }
    } else {
        // Handle NULL values
        $response['success'] = false;
        $response['message'] = "Error: Quantity, price, or total is NULL.";
    }

    header("Content-Type: application/json");
    echo json_encode($response);
} else {
    $response['success'] = false;
    $response['message'] = "Error: " . mysqli_error($conn);

    header("Content-Type: application/json");
    echo json_encode($response);
}
?>
