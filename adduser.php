<?php

session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $phonenumber = $_POST['number'] ?? '';  
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $dateofbirth = $_POST['dateofbirth'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Simple file upload handling
    $targetDir = "assets/user/";
    $targetFile = $targetDir . basename($_FILES["userPhoto"]["name"]);

    if (move_uploaded_file($_FILES["userPhoto"]["tmp_name"], $targetFile)) {
        // File successfully uploaded
        $photoPath = $targetFile;

        // Debug: Log $photoPath to a file
        error_log("Photo Path: " . $photoPath);

    } else {
        // Error uploading file
        $photoPath = "";
    }

    $query = "INSERT INTO users (First_Name, Last_Name, Phone_Number, Email, Address, Date_Of_Birth, Username, Password, Photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssssss", $firstname, $lastname, $phonenumber, $email, $address, $dateofbirth, $username, $password, $photoPath);

        $response = array();

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($connection);
            header("Location: success.php");
        } else {
            $response['success'] = false;
            $response['message'] = "Error: " . mysqli_error($connection);
        }

        mysqli_stmt_close($stmt);

        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        $response['success'] = false;
        $response['message'] = "Error: " . mysqli_error($connection);

        header("Content-Type: application/json");
        echo json_encode($response);
    }
}
?>
