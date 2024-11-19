<?php
session_start();
session_unset();
session_destroy();

// Store a message in the session to display on the login page
$_SESSION['message'] = 'You have successfully logged out.';

// Redirect to the login page
header("Location: login.html");
exit;
?>
