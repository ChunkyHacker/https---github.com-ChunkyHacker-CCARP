<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $progressname = $_POST["Name"];
    $progresstype = $_POST["Status"];
    
    if (isset($_POST["contract_ID"])) {
        $contract_ID = $_POST["contract_ID"];
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Contract ID is missing',
                icon: 'error',
                timer: 3000,
                showConfirmButton: false
            }).then(() => {
                window.history.back();
            });
        </script>";
        exit();
    }

    $sql = "INSERT INTO report (Name, Status, contract_ID) 
            VALUES ('$progressname', '$progresstype','$contract_ID')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Report added successfully!',
                icon: 'success',
                timer: 3000,
                showConfirmButton: true
            }).then(() => {
                window.location.href = 'progress.php?contract_ID=" . $contract_ID . "';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Failed to add report',
                icon: 'error',
                timer: 3000,
                showConfirmButton: true
            }).then(() => {
                window.history.back();
            });
        </script>";
    }

    $conn->close();
} else {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'The form was not submitted correctly',
            icon: 'error',
            timer: 3000,
            showConfirmButton: true
        }).then(() => {
            window.history.back();
        });
    </script>";
}
?>
</body>
</html>
