<?php
// Include your existing database connection
include('config.php');

// Check if the form is submitted and the file is uploaded
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['signedcontract']) && isset($_POST['requirement_ID'])) {
    
    // Get the file details
    $signedContractFile = $_FILES['signedcontract'];
    $requirementID = $_POST['requirement_ID']; // The requirement ID to associate with the signed contract

    // Check if there was an error during the file upload
    if ($signedContractFile['error'] != 0) {
        echo "Error uploading file. Please try again.";
        exit();
    }

    // Get the file details
    $fileName = $signedContractFile['name'];
    $fileTmpName = $signedContractFile['tmp_name'];
    $fileSize = $signedContractFile['size'];
    $fileType = $signedContractFile['type'];

    // Define the folder where the file will be uploaded
    $uploadDir = 'uploads/signedcontracts/';  // Path to the folder where files will be stored
    $uploadPath = $uploadDir . basename($fileName);  // Full path to the file

    // Check if the file already exists in the upload directory
    if (file_exists($uploadPath)) {
        echo "Sorry, file already exists.";
        exit();
    }

    // Move the uploaded file to the desired directory
    if (move_uploaded_file($fileTmpName, $uploadPath)) {
        // Prepare the SQL query to insert the file path and requirement_ID into the 'signedcontract' table
        $insertQuery = "INSERT INTO signedcontract (signedcontract, requirement_ID) VALUES (?, ?)";
        
        // Prepare the statement
        $stmt = mysqli_prepare($conn, $insertQuery);
        
        // Bind the parameters (file path and requirement_ID)
        mysqli_stmt_bind_param($stmt, "si", $uploadPath, $requirementID);
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to the 'usercomputebudget.php' page with a success message
            header("Location: usercomputebudget.php?requirement_ID=$requirementID&success=true&message=" . urlencode("Signed contract uploaded successfully!"));
            exit(); // Ensure no further code is executed after the redirect
        } else {
            echo "Error uploading signed contract: " . mysqli_error($conn);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error moving the uploaded file.";
    }
} else {
    echo "Please provide a valid signed contract file and requirement ID.";
}

// Close the connection
mysqli_close($conn);
?>
