<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['plan_id'])) {
    $plan_id = $_POST['plan_id'];

    // Retrieve the plan details based on the plan_id
    $query = "SELECT User_ID, length_lot_area, width_lot_area, square_meter_lot, length_floor_area, width_floor_area, square_meter_floor, initial_budget, estimated_cost, start_date, end_date, type, more_details, Photo FROM plan WHERE plan_ID = ?";
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
        $status = $_POST['status']; // Either "approve" or "decline"

        // Get the approved_by field from session
        session_start();
        $approved_by = isset($_SESSION['First_Name']) && isset($_SESSION['Last_Name']) ? $_SESSION['First_Name'] . ' ' . $_SESSION['Last_Name'] : '';

        if ($status === 'approve') {
            // Insert data into approvedplan table
            $insertQuery = "INSERT INTO approvedplan (User_ID, length_lot_area, width_lot_area, square_meter_lot, length_floor_area, width_floor_area, square_meter_floor, initial_budget, estimated_cost, start_date, end_date, type, more_details, Photo, q1, q2, q3, q4, q5, comment, status, approved_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($insertStmt, "isssssssssssssssssssss", $user_ID, $row['length_lot_area'], $row['width_lot_area'], $row['square_meter_lot'], $row['length_floor_area'], $row['width_floor_area'], $row['square_meter_floor'], $row['initial_budget'], $row['estimated_cost'], $row['start_date'], $row['end_date'], $row['type'], $row['more_details'], $row['Photo'], $q1, $q2, $q3, $q4, $q5, $comment, $status, $approved_by);
            
            if (mysqli_stmt_execute($insertStmt)) {
                // After successful insert, delete the plan from the 'plan' table
                $deleteQuery = "DELETE FROM plan WHERE plan_ID = ?";
                $deleteStmt = mysqli_prepare($conn, $deleteQuery);
                mysqli_stmt_bind_param($deleteStmt, "i", $plan_id);
                if (mysqli_stmt_execute($deleteStmt)) {
                    // Display the pop-up message
                    echo "<script>alert('Plan Approved and Moved to approvedplan table.'); window.location.href = 'profile.php';</script>";
                    exit();
                } else {
                    echo "Error deleting plan data: " . mysqli_error($conn);
                }
                mysqli_stmt_close($deleteStmt);
            } else {
                echo "Error moving plan data: " . mysqli_error($conn);
            }
            mysqli_stmt_close($insertStmt);
        } else if ($status === 'decline') {
            // Insert data into declinedplan table
            $insertQuery = "INSERT INTO declinedplan (User_ID, length_lot_area, width_lot_area, square_meter_lot, length_floor_area, width_floor_area, square_meter_floor, initial_budget, estimated_cost, start_date, end_date, type, more_details, Photo, q1, q2, q3, q4, q5, comment, status, approved_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($insertStmt, "isssssssssssssssssssss", $user_ID, $row['length_lot_area'], $row['width_lot_area'], $row['square_meter_lot'], $row['length_floor_area'], $row['width_floor_area'], $row['square_meter_floor'], $row['initial_budget'], $row['estimated_cost'], $row['start_date'], $row['end_date'], $row['type'], $row['more_details'], $row['Photo'], $q1, $q2, $q3, $q4, $q5, $comment, $status, $approved_by);

            if (mysqli_stmt_execute($insertStmt)) {
                // After successful insert, delete the plan from the 'plan' table
                $deleteQuery = "DELETE FROM plan WHERE plan_ID = ?";
                $deleteStmt = mysqli_prepare($conn, $deleteQuery);
                mysqli_stmt_bind_param($deleteStmt, "i", $plan_id);
                if (mysqli_stmt_execute($deleteStmt)) {
                    // Display the pop-up message
                    echo "<script>alert('Plan Declined and Moved to declinedplan table.'); window.location.href = 'profile.php';</script>";
                    exit();
                } else {
                    echo "Error deleting plan data: " . mysqli_error($conn);
                }
                mysqli_stmt_close($deleteStmt);
            } else {
                echo "Error moving plan data: " . mysqli_error($conn);
            }
            mysqli_stmt_close($insertStmt);
        } else {
            echo "Invalid status selected.";
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
