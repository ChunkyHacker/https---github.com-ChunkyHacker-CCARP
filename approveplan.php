<?php
// approveplan.php

include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['plan_id']) && isset($_POST['status'])) {
    $plan_id = $_POST['plan_id'];
    $status = $_POST['status']; // Get the status value (approve or decline)

    // Retrieve the original plan data from the 'plan' table
    $query = "SELECT User_ID, length_lot_area, width_lot_area, square_meter_lot, length_floor_area, width_floor_area, square_meter_floor, initial_budget, estimated_cost, start_date, end_date, type, Photo FROM plan WHERE plan_ID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $plan_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $user_ID = $row['User_ID'];

        // Get additional fields from the form
        $q1 = $_POST['q1'];
        $q2 = $_POST['q2'];
        $q3 = $_POST['q3'];
        $q4 = $_POST['q4'];
        $q5 = $_POST['q5'];
        $comment = $_POST['comment'];
        $approved_by = $_POST['approved_by']; // Get the approved_by field

        // Prepare the photo data (handle blob as string for binary data)
        $photo = $row['Photo']; // Assuming the photo is retrieved as binary data

        // Check if the status is 'decline'
        if ($status === 'decline') {
            // Move the data to the 'declinedplan' table
            $insertQuery = "INSERT INTO declinedplan (User_ID, length_lot_area, width_lot_area, square_meter_lot, length_floor_area, width_floor_area, square_meter_floor, initial_budget, estimated_cost, start_date, end_date, type, Photo, q1, q2, q3, q4, q5, comment, status, approved_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $insertStmt = mysqli_prepare($conn, $insertQuery);

            mysqli_stmt_bind_param($insertStmt, "issssssssssssssssssssb", 
                $user_ID, 
                $row['length_lot_area'], 
                $row['width_lot_area'], 
                $row['square_meter_lot'], 
                $row['length_floor_area'], 
                $row['width_floor_area'], 
                $row['square_meter_floor'], 
                $row['initial_budget'], 
                $row['estimated_cost'], 
                $row['start_date'], 
                $row['end_date'], 
                $row['type'], 
                $photo, // Binary data for Photo column
                $q1, 
                $q2, 
                $q3, 
                $q4, 
                $q5, 
                $comment,
                $status, // Decline status
                $approved_by
            );

            // Execute the insert query
            if (mysqli_stmt_execute($insertStmt)) {
                // After successfully moving to declinedplan, delete the plan from 'plan' table
                $deleteQuery = "DELETE FROM plan WHERE plan_ID = ?";
                $deleteStmt = mysqli_prepare($conn, $deleteQuery);
                mysqli_stmt_bind_param($deleteStmt, "i", $plan_id);
                mysqli_stmt_execute($deleteStmt);

                // Redirect after successful operation
                echo "Plan declined successfully.";
                header("Location: profile.php");
                exit();
            } else {
                echo "Error declining plan: " . mysqli_error($conn);
            }

            mysqli_stmt_close($insertStmt);
        } else if ($status === 'approve') {
            // Handle the approve status (move to approvedplan table)
            $insertQuery = "INSERT INTO approvedplan (User_ID, length_lot_area, width_lot_area, square_meter_lot, length_floor_area, width_floor_area, square_meter_floor, initial_budget, estimated_cost, start_date, end_date, type, Photo, q1, q2, q3, q4, q5, comment, status, approved_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $insertStmt = mysqli_prepare($conn, $insertQuery);

            mysqli_stmt_bind_param($insertStmt, "issssssssssssssssssssb", 
                $user_ID, 
                $row['length_lot_area'], 
                $row['width_lot_area'], 
                $row['square_meter_lot'], 
                $row['length_floor_area'], 
                $row['width_floor_area'], 
                $row['square_meter_floor'], 
                $row['initial_budget'], 
                $row['estimated_cost'], 
                $row['start_date'], 
                $row['end_date'], 
                $row['type'], 
                $photo, // Binary data for Photo column
                $q1, 
                $q2, 
                $q3, 
                $q4, 
                $q5, 
                $comment,
                $status, // Approve status
                $approved_by
            );

            // Execute the insert query
            if (mysqli_stmt_execute($insertStmt)) {
                // Redirect after successful operation
                echo "Plan approved successfully.";
                header("Location: profile.php");
                exit();
            } else {
                echo "Error approving plan: " . mysqli_error($conn);
            }

            mysqli_stmt_close($insertStmt);
        }
    } else {
        echo "No plan found with Plan ID: $plan_id";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo "Invalid request.";
}
?>
