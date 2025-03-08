<?php
$host = "localhost";
$dbname = "ccarpcurrentsystem"; // Ilisi sa imong database name
$username = "root";
$password = ""; // Blank lang ni kung default sa XAMPP/MAMP

$conn = new mysqli ($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
