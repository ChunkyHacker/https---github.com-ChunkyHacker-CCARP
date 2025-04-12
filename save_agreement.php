<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check kung naa ang mga required fields
    if (!isset($_POST['plan_ID'], $_POST['approval_ID'], $_POST['Carpenter_ID'])) {
        die("Error: Missing required fields.");
    }

    $Plan_ID = $_POST['plan_ID'];
    $approval_ID = !empty($_POST['approval_ID']) ? $_POST['approval_ID'] : die("Error: Missing approval_ID.");
    $Carpenter_ID = !empty($_POST['Carpenter_ID']) ? $_POST['Carpenter_ID'] : die("Error: Missing Carpenter_ID.");

    // Check if carpenter already has an active contract
    $check_sql = "SELECT COUNT(*) as count FROM contracts WHERE Carpenter_ID = ? AND transaction_ID IS NULL";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $Carpenter_ID);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        echo "<script>
                alert('This carpenter already has an active agreement. Cannot create multiple agreements.');
                window.history.back();
              </script>";
        exit();
    }

    // Continue with existing code
    $labor_cost = isset($_POST['labor_cost']) ? $_POST['labor_cost'] : '0.00';
    $duration = isset($_POST['duration']) ? $_POST['duration'] : 0;
    $type_of_work = isset($_POST['type_of_work']) ? $_POST['type_of_work'] : 'select type of work';

    // Convert labor cost to float after removing 'PHP '
    $labor_cost = (float) str_replace('PHP ', '', $labor_cost);

    // Updated INSERT query to include type_of_work
    $sql = "INSERT INTO contracts (Plan_ID, approval_ID, Carpenter_ID, labor_cost, duration, transaction_ID, type_of_work) 
            VALUES (?, ?, ?, ?, ?, NULL, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    
    mysqli_stmt_bind_param($stmt, "iiiids", 
        $Plan_ID, 
        $approval_ID, 
        $Carpenter_ID, 
        $labor_cost,
        $duration,
        $type_of_work
    );

    if (mysqli_stmt_execute($stmt)) {
        // Kuhaa ang last inserted contract_ID
        $contract_ID = mysqli_insert_id($conn);

        echo "<script>
                alert('Contract successfully submitted.');
                window.location.href = 'viewaddedrequirements.php?contract_ID=$contract_ID';
              </script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
