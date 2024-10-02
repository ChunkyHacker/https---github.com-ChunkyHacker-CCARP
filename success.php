<?php
session_start();
require_once "config.php";

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
	$username = $_POST["username"];
	$password = $_POST["password"];
    $role = $_POST["role"];

	$query = "INSERT INTO credentials (firstname, lastname, email, username, password, role) VALUES ('$firstname','$lastname','$email','$username','$password','$role')";
	
	if (mysqli_query($connection, $query)) {
        echo "Registration Successful!<br>";
        echo "Please <a href='index.php'>Sign In</a> to continue.";
} else {
    echo "ERROR: " . $query . "<br>" . mysqli_error($connection);
}
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In success</title>
    <p>Success!!</p>
    <a href="login.html">Sign In</a>
</head>
<body>
    
</body>
</html>