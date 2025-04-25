<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task = $_POST["task"];
    $timestamp = $_POST["timestamp"];
    $contract_ID = $_POST["contract_ID"];

    $sql = "INSERT INTO task (task, timestamp, contract_ID) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $task, $timestamp, $contract_ID);

    if ($stmt->execute()) {
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Task added successfully!',
                icon: 'success',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.location.href = 'progress.php?contract_ID=$contract_ID&success=true';
            });
        </script>";
        exit();
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Database error: " . addslashes($stmt->error) . "',
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
