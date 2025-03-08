<?php
include('config.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $progressname = $_POST["Name"];
    $progresstype = $_POST["Status"];
    
    // Ensure the existence of the contract_ID field in the form data
    if (isset($_POST["contract_ID"])) {
        $contract_ID = $_POST["contract_ID"];
    } else {
        // Handle the case where contract_ID is not provided
        echo "Error: contract_ID is missing.";
        exit();
    }

    // Prepare the SQL query to insert the item into the database
    $sql = "INSERT INTO report (Name, Status, contract_ID) 
            VALUES ('$progressname', '$progresstype','$contract_ID')";
    
    if ($conn->query($sql) === TRUE) {
        // Item added successfully, redirect back to progress.php with a success message
        $conn->close();
        header("Location: progress.php?contract_ID=$contract_ID&success=true&message=" . urlencode("Report added successfully!"));
        exit();
    } else {
        // Display the error message
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "Error: The form was not submitted correctly.";
}
?>
