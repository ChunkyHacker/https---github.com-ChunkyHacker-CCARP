<?php
$host = "localhost";
$dbname = "ccarpcurrentsystem";
$username = "root";
$password = "";

$connection = mysqli_connect ($host, $username, $password, $dbname);

if (!$connection) {
	die("Connection failed: " . mysqli_connect_error());
}
?>