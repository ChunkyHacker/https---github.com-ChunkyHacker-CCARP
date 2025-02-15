<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $approved_plan_id = $_POST['approved_plan_id'];

    $query = "SELECT * FROM approvedplan WHERE approved_plan_ID = ?";
    $stmt = mysqli_prepare($conn, $query);
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
            $start_date = $row['start_date'];
            $end_date = $row['end_date'];
            $type = $row['type'];
            $approved_by = $row['approved_by'];
            $photoPath = $row['Photo'];

            // Insert data into projectrequirements table
            $insertQuery = "INSERT INTO projectrequirements (User_ID, length_lot_area, width_lot_area, square_meter_lot, length_floor_area, width_floor_area, square_meter_floor, initial_budget, start_date, end_date, type, Photo, approved_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($conn, $insertQuery);

            mysqli_stmt_bind_param($insertStmt, "issssssssssss", $user_ID, $length_lot_area, $width_lot_area, $square_meter_lot, $length_floor_area, $width_floor_area, $square_meter_floor, $initial_budget, $start_date, $end_date, $type, $photoPath, $approved_by);

            if (mysqli_stmt_execute($insertStmt)) {
                // Get the last inserted requirement_ID
                $requirement_ID = mysqli_insert_id($conn);
            
                // Redirect to generate_contract.php with the correct requirement_ID
                header("Location: generate_contract.php?id=$requirement_ID&success=true&message=" . urlencode("Data inserted into projectrequirements table successfully."));
                exit();
            } else {
                echo "Error inserting data into projectrequirements table: " . mysqli_error($conn);
            }

            mysqli_stmt_close($insertStmt);
        } else {
            echo "No user data found for the Approved Plan ID: $approved_plan_id";
        }
    } else {
        echo "No data found for the Approved Plan ID: $approved_plan_id";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo "Invalid request method. Please use POST.";
}
?>
