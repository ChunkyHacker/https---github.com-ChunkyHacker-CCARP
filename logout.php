<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
session_start();
session_unset();
session_destroy();

echo "<script>
    Swal.fire({
        title: 'Logged Out!',
        text: 'You have been successfully logged out',
        icon: 'success',
        timer: 3000,
        showConfirmButton: true
    }).then(function() {
        window.location.href = 'login.html';
    });
</script>";
exit;
?>
</body>
</html>
