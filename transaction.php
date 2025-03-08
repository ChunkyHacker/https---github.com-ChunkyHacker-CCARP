<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['receipt'])) {
    $targetDir = "uploads/transaction/"; // Folder where receipts will be saved
    $fileName = basename($_FILES["receipt"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Check if file is an image
    $check = getimagesize($_FILES["receipt"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Allow only specific formats
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        echo "Only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // Create folder if it doesn't exist
        }

        if (move_uploaded_file($_FILES["receipt"]["tmp_name"], $targetFilePath)) {
            // Get details and contract_ID from the form
            $details = isset($_POST['details']) ? $_POST['details'] : '';
            $contractID = isset($_POST['contract_ID']) ? (int)$_POST['contract_ID'] : null; // Ensure it's an integer

            // Debugging: Check the value of contract_ID
            echo "Contract ID: " . $contractID; // Output the contract_ID

            // Insert file path, details, and contract_ID into the database
            $stmt = $conn->prepare("INSERT INTO transaction (receipt_photo, details, contract_ID) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $targetFilePath, $details, $contractID);
            
            if ($stmt->execute()) {
                $transactionID = $stmt->insert_id; // Get the last inserted transaction ID
                header("Location: receipt.php?success=true&receiptPath=" . urlencode($targetFilePath) . "&transaction_ID=" . $transactionID);
                exit;
            } else {
                echo "Error saving to database: " . $stmt->error; // Output any error
            }

            $stmt->close();
        } else {
            echo "Error uploading file.";
        }
    }
}
?>
