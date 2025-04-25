<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($firstname) || empty($lastname) || empty($username) || empty($password)) {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'All fields are required',
                icon: 'error',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.history.back();
            });
        </script>";
        exit();
    }

    $sql = "INSERT INTO admin (firstname, lastname, username, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $firstname, $lastname, $username, $password);

        if ($stmt->execute()) {
            echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Admin registered successfully!',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: true
                }).then(function() {
                    window.location.href = 'adminlogin.html';
                });
            </script>";
            exit();
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Database error: " . $stmt->error . "',
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: true
                }).then(function() {
                    window.history.back();
                });
            </script>";
        }
        $stmt->close();
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Failed to prepare statement',
                icon: 'error',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.history.back();
            });
        </script>";
    }
    $conn->close();
}
?>
</body>
</html>
