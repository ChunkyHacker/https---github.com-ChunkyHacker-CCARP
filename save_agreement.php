<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requirement_ID = $_POST['requirement_ID'];
    $labor_cost = isset($_POST['labor_cost']) ? $_POST['labor_cost'] : '0.00'; // Get the labor cost from the form, default to 0 if not set

    // Remove 'PHP' string if it exists and convert to float
    $labor_cost = (float) str_replace('PHP ', '', $labor_cost);

    // Get project details from `projectrequirements` table
    $query = "SELECT * FROM projectrequirements WHERE requirement_ID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $requirement_ID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$contract = mysqli_fetch_assoc($result)) {
        die("Project requirements not found.");
    }

    // Get client details from `users` table
    $client_ID = $contract['User_ID'];
    $client_name = "Unknown Client"; 
    if (!empty($client_ID)) {
        $userQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
        $userStmt = mysqli_prepare($conn, $userQuery);
        mysqli_stmt_bind_param($userStmt, "i", $client_ID);
        mysqli_stmt_execute($userStmt);
        $userResult = mysqli_stmt_get_result($userStmt);

        if ($user = mysqli_fetch_assoc($userResult)) {
            $client_name = $user['First_Name'] . " " . $user['Last_Name'];
        }
    }

    // Get contractor name directly from `approved_by` column
    $contractor_name = !empty($contract['approved_by']) ? $contract['approved_by'] : "Unknown Contractor";

    // Handle file upload
    $uploadDir = "C:/xampp/htdocs/SIA/uploads/contracts/";
    $PhotoPath = "";

    if (!empty($_FILES["Photo"]["name"])) {
        $fileName = basename($_FILES["Photo"]["name"]);
        $targetFilePath = $uploadDir . $fileName;
        
        // Move uploaded file to the directory
        if (move_uploaded_file($_FILES["Photo"]["tmp_name"], $targetFilePath)) {
            $PhotoPath = "uploads/contracts/" . $fileName; // Relative path for easier access
        } else {
            die("Error uploading the file.");
        }
    }

    // Insert contract into `contracts` table
    $sql = "INSERT INTO contracts 
    (requirement_ID, client_ID, client_name, contractor_name, length_lot_area, width_lot_area, square_meter_lot, length_floor_area, width_floor_area, square_meter_floor, type, initial_budget, Photo, start_date, end_date, labor_cost, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";

    $stmt = mysqli_prepare($conn, $sql);

    // Correct number of format specifiers (16)
    mysqli_stmt_bind_param($stmt, "iissdddddddsdsss", 
        $requirement_ID, 
        $client_ID, 
        $client_name, 
        $contractor_name, 
        $contract['length_lot_area'], 
        $contract['width_lot_area'], 
        $contract['square_meter_lot'],
        $contract['length_floor_area'], 
        $contract['width_floor_area'], 
        $contract['square_meter_floor'],
        $contract['type'], 
        $contract['initial_budget'], 
        $PhotoPath, 
        $contract['start_date'], 
        $contract['end_date'], 
        $labor_cost
    );

    if (mysqli_stmt_execute($stmt)) {
        $requirementID = $_POST['requirement_ID']; // Get the requirement ID
        echo "<script>
                alert('Contract successfully submitted to client.');
                window.location.href = 'viewaddedrequirements.php?requirement_ID=$requirementID';
            </script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
