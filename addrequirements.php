<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $approved_plan_id = $_POST['approved_plan_id'];

    $query = "SELECT * FROM approvedplan WHERE approved_plan_ID = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $approved_plan_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['User_ID'] !== null) {
            $user_ID = $row['User_ID'];
            $length_lot_area = $row['length_lot_area'];
            $width_lot_area = $row['width_lot_area'];
            $square_meter_lot = $row['square_meter_lot'];
            $length_floor_area = $row['length_floor_area'];
            $width_floor_area = $row['width_floor_area'];
            $square_meter_floor = $row['square_meter_floor'];
            $initial_budget = $row['initial_budget'];
            $estimated_cost = $row['estimated_cost'];
            $start_date = $row['start_date'];
            $end_date = $row['end_date'];
            $type = $row['type'];
            $approved_by = $row['approved_by'];
            $photoPath = $row['Photo'];

            // Fetching remaining form data
            $labor_cost = $_POST['labor_cost'];

            // Insert data into requirements table
            $insertQuery = "INSERT INTO requirements (User_ID, length_lot_area, width_lot_area, square_meter_lot, length_floor_area, width_floor_area, square_meter_floor, initial_budget, estimated_cost, start_date, end_date, type, Photo, approved_by, labor_cost) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($connection, $insertQuery);

            mysqli_stmt_bind_param($insertStmt, "issssssssssssss", $user_ID, $length_lot_area, $width_lot_area, $square_meter_lot, $length_floor_area, $width_floor_area, $square_meter_floor, $initial_budget, $estimated_cost, $start_date, $end_date, $type, $photoPath, $approved_by, $labor_cost);

            if (mysqli_stmt_execute($insertStmt)) {
                echo "Data inserted into requirements table successfully.";
                header("Location: selectmaterialsrequirement.php");
                exit();
            } else {
                echo "Error inserting data into requirements table: " . mysqli_error($connection);
            }

            mysqli_stmt_close($insertStmt);
        } else {
            echo "No user data found for the Approved Plan ID: $approved_plan_id";
        }
    } else {
        echo "No data found for the Approved Plan ID: $approved_plan_id";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($connection);
} else {
    echo "Invalid request method. Please use POST.";
}
?>
