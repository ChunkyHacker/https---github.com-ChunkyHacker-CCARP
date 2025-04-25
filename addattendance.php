<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $timein = $_POST["Time_in"];
    $type_of_work = $_POST["type_of_work"];
    $timeout = isset($_POST["Time_out"]) ? $_POST["Time_out"] : '';

    if (!isset($_POST["contract_ID"])) {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Contract ID is missing.',
                icon: 'error',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.history.back();
            });
        </script>";
        exit();
    }

    $requirementID = $_POST["contract_ID"];
    $action = empty($timeout) ? 'time_in' : 'time_out';

    $sql = "INSERT INTO attendance (Time_in, Time_out, contract_ID, type_of_work) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $timein, $timeout, $requirementID, $type_of_work);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Attendance recorded successfully!',
                icon: 'success',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.location.href = 'progress.php?contract_ID=$requirementID&action=$action&success=true';
            });
        </script>";
        exit();
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Database error: " . addslashes($conn->error) . "',
                icon: 'error',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.history.back();
            });
        </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'Invalid request method.',
            icon: 'error',
            timer: 3000,
            showConfirmButton: true
        }).then(function() {
            window.history.back();
        });
    </script>";
}
?>
</body>
</html>
