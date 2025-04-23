<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $phonenumber = $_POST['phonenumber'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $dateofbirth = $_POST['dateofbirth'] ?? '';
    $experience = $_POST['experience'] ?? '';
    $projectcompleted = $_POST['projectcompleted'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $response = array();

    // Simple file upload handling
    $targetDir = "assets/carpenter/";
    $targetFile = $targetDir . basename($_FILES["CarpenterPhoto"]["name"]);

    if (move_uploaded_file($_FILES["CarpenterPhoto"]["tmp_name"], $targetFile)) {
        // File successfully uploaded
        $photoPath = $targetFile;

        // Debug: Log $photoPath to a file
        error_log("Photo Path: " . $photoPath);

    } else {
        // Error uploading file
        $photoPath = "";
    }

    $insertCarpenterQuery = "INSERT INTO carpenters (First_Name, Last_Name, Phone_Number, Email, Address, Date_Of_Birth, Experiences, Project_Completed, Username, Password, Photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtCarpenter = mysqli_prepare($conn, $insertCarpenterQuery);

    if ($stmtCarpenter) {
        mysqli_stmt_bind_param($stmtCarpenter, "sssssssssss", $firstname, $lastname, $phonenumber, $email, $address, $dateofbirth, $experience, $projectcompleted, $username, $password, $photoPath);

        mysqli_begin_transaction($conn);

        if (mysqli_stmt_execute($stmtCarpenter)) {
            mysqli_commit($conn);

            $response['success'] = true;
            $response['message'] = 'Carpenter added successfully';

            mysqli_stmt_close($stmtCarpenter);
            mysqli_close($conn);
            header("Location: login.html");
            exit();
        } else {
            mysqli_rollback($conn);

            $response['success'] = false;
            $response['message'] = "Error adding carpenter: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmtCarpenter);

        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        $response['success'] = false;
        $response['message'] = "Error: " . mysqli_error($conn);

        header("Content-Type: application/json");
        echo json_encode($response);
    }
}
?>
