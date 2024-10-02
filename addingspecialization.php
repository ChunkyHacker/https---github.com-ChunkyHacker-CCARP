<?php
include('config.php');

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $specializationName = $_POST["specializationName"];

    $insertQuery = "INSERT INTO specialization (Specialization_Name) VALUES ('$specializationName')";
    $insertResult = mysqli_query($connection, $insertQuery);

    if ($insertResult) {
        $response['success'] = true;
        $response['message'] = 'Specialization added successfully';

        header("Location: carsignup.php");
        exit();
    } else {
        $response['success'] = false;
        $response['message'] = 'Error: ' . mysqli_error($connection);
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}

mysqli_close($connection);
?>
