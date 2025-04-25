<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
// Start session to access session variables
session_start();

// Destroy all session variables
session_unset();

// Destroy the session
session_destroy();

echo "<script>
    Swal.fire({
        title: 'Logged Out!',
        text: 'You have been successfully logged out',
        icon: 'success',
        timer: 3000,
        showConfirmButton: true
    }).then(function() {
        window.location.href = 'adminlogin.html';
    });
</script>";
exit();
?>
</body>
</html>
