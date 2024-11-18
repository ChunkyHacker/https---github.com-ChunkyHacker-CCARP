<?php
// declineplan.php

include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['plan_id'])) {
    $plan_id = $_POST['plan_id'];

    // Retrieve the original plan data from the 'plan' table
    $query = "SELECT User_ID, length_lot_area, width_lot_area, square_meter_lot, length_floor_area, width_floor_area, square_meter_floor, initial_budget, estimated_cost, start_date, end_date, type, part, materials, Photo FROM plan WHERE plan_ID = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $plan_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $user_ID = $row['User_ID'];

        // Get additional fields from the form (feedback questions and comment)
        $q1 = $_POST['q1'];
        $q2 = $_POST['q2'];
        $q3 = $_POST['q3'];
        $q4 = $_POST['q4'];
        $q5 = $_POST['q5'];
        $comment = $_POST['comment'];

        // Prepare and execute the insert query to move data to 'declinedplan' table
        $insertQuery = "INSERT INTO declinedplan (User_ID, length_lot_area, width_lot_area, square_meter_lot, length_floor_area, width_floor_area, square_meter_floor, initial_budget, estimated_cost, start_date, end_date, type, part, materials, Photo, q1, q2, q3, q4, q5, comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = mysqli_prepare($connection, $insertQuery);
        
        // Bind parameters for the insert statement
        mysqli_stmt_bind_param($insertStmt, "issssssssssssssssssss", 
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
            $row['part'], 
            $row['materials'], 
            $row['Photo'], 
            $q1, 
            $q2, 
            $q3, 
            $q4, 
            $q5, 
            $comment
        );

        // Execute the insert query
        if (mysqli_stmt_execute($insertStmt)) {
            // If successful, delete the original plan from the 'plan' table
            $deleteQuery = "DELETE FROM plan WHERE plan_ID = ?";
            $deleteStmt = mysqli_prepare($connection, $deleteQuery);
            mysqli_stmt_bind_param($deleteStmt, "i", $plan_id);
            mysqli_stmt_execute($deleteStmt);

            // Redirect or display a success message
            echo "Plan declined successfully.";
            header("Location: profile.php");
            exit();
        } else {
            echo "Error declining plan: " . mysqli_error($connection);
        }

        // Close the insert statement
        mysqli_stmt_close($insertStmt);
    } else {
        echo "No plan found with Plan ID: $plan_id";
    }

    // Close the select statement
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
} else {
    echo "Invalid request.";
}
?>
