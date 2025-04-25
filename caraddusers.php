<?php
include('config.php');
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
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
            mysqli_stmt_close($stmtCarpenter);
            mysqli_close($conn);
            
            echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Carpenter account created successfully!',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: true
                }).then(function() {
                    window.location.href = 'login.html';
                });
            </script>";
            exit();
        } else {
            mysqli_rollback($conn);
            mysqli_stmt_close($stmtCarpenter);
            
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to create carpenter account: " . mysqli_error($conn) . "',
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: true
                }).then(function() {
                    window.history.back();
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Database error: " . mysqli_error($conn) . "',
                icon: 'error',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.history.back();
            });
        </script>";
    }
    mysqli_close($conn);
}
?>
</body>
</html>
