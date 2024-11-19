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
            $estimated_cost = $row['estimated_cost'];
            $start_date = $row['start_date'];
            $end_date = $row['end_date'];
            $type = $row['type'];
            $approved_by = $row['approved_by'];
            $photoPath = $row['Photo'];

            // Fetching other form data
            $labor_cost = isset($_POST['labor_cost']) ? $_POST['labor_cost'] : null;

            // Handling the file upload for 'contract'
            if (isset($_FILES['contract']) && $_FILES['contract']['error'] === UPLOAD_ERR_OK) {
                $contractTmpPath = $_FILES['contract']['tmp_name'];
                $contractName = basename($_FILES['contract']['name']);
                $contractDir = 'uploads/contracts/'; // Directory to save the file
                $contractPath = $contractDir . $contractName;

                // Create the directory if it doesn't exist
                if (!is_dir($contractDir)) {
                    mkdir($contractDir, 0777, true);
                }

                // Move the uploaded file to the desired location
                if (move_uploaded_file($contractTmpPath, $contractPath)) {
                    // File uploaded successfully
                } else {
                    die("Error moving the uploaded file.");
                }
            } else {
                die("Error uploading the file.");
            }

            // Insert data into projectrequirements table
            $insertQuery = "INSERT INTO projectrequirements (User_ID, length_lot_area, width_lot_area, square_meter_lot, length_floor_area, width_floor_area, square_meter_floor, initial_budget, estimated_cost, start_date, end_date, type, Photo, approved_by, labor_cost, contract) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($conn, $insertQuery);

            mysqli_stmt_bind_param($insertStmt, "isssssssssssssss", $user_ID, $length_lot_area, $width_lot_area, $square_meter_lot, $length_floor_area, $width_floor_area, $square_meter_floor, $initial_budget, $estimated_cost, $start_date, $end_date, $type, $photoPath, $approved_by, $labor_cost, $contractPath);

            if (mysqli_stmt_execute($insertStmt)) {
                // Redirect to selectmaterialsrequirement.php with a success message
                header("Location: selectmaterialsrequirement.php?success=true&message=" . urlencode("Data inserted into projectrequirements table successfully."));
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
