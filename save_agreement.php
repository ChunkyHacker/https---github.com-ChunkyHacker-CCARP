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
    $labor_cost = isset($_POST['labor_cost']) ? $_POST['labor_cost'] : '0.00';
    $duration = isset($_POST['duration']) ? $_POST['duration'] : 0;

    // Convert labor cost to float after removing 'PHP '
    $labor_cost = (float) str_replace('PHP ', '', $labor_cost);

    // **Insert contract WITHOUT User_ID**
    $sql = "INSERT INTO contracts (Plan_ID, approval_ID, Carpenter_ID, labor_cost, duration, transaction_ID) 
            VALUES (?, ?, ?, ?, ?, NULL)";

    $stmt = mysqli_prepare($conn, $sql);
    
    mysqli_stmt_bind_param($stmt, "iiiid", 
        $Plan_ID, 
        $approval_ID, 
        $Carpenter_ID, 
        $labor_cost,
        $duration
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
