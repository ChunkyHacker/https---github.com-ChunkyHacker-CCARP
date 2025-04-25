<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contract_id = $_POST["Contract_ID"];
    $carpenter_id = $_POST["Carpenter_ID"];
    $user_id = $_POST["User_ID"];
    $labor_cost = $_POST["Labor_Cost"];
    $Type_of_work = $_POST["type_of_work"];
    $duration = $_POST["Duration"];
    $payment_method = $_POST["Payment_Method"];
    $payment_date = date("Y-m-d");

    // 🔍 Check if payment already exists
    $check_query = "SELECT * FROM payment WHERE Contract_ID = ? AND User_ID = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $contract_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
            Swal.fire({
                title: 'Warning!',
                text: 'You have already paid for this contract.',
                icon: 'warning',
                timer: 3000,
                showConfirmButton: true
            }).then(function() {
                window.location.href = 'userpaycarpenter.php?contract_ID=$contract_id&success=true';
            });
        </script>";
        exit();
    }

    // ✅ Insert new payment
    $sql = "INSERT INTO payment (Contract_ID, Carpenter_ID, User_ID, Labor_Cost, Duration, type_of_work, Payment_Method, Payment_Date) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iiidisss", $contract_id, $carpenter_id, $user_id, $labor_cost, $duration, $Type_of_work, $payment_method, $payment_date);
        if ($stmt->execute()) {
            $payment_id = $conn->insert_id;
            echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Payment processed successfully!',
                    icon: 'success',
                    timer: 3000,
                    showConfirmButton: true
                }).then(function() {
                    window.location.href = 'payrollreceipt.php?Payment_ID=$payment_id&success=true';
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
                });
            </script>";
        }
        $stmt->close();
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Database error: " . addslashes($conn->error) . "',
                icon: 'error',
                timer: 3000,
                showConfirmButton: true
            });
        </script>";
    }
    $conn->close();
} else {
    echo "<script>
        Swal.fire({
            title: 'Error!',
            text: 'The form was not submitted properly.',
            icon: 'error',
            timer: 3000,
            showConfirmButton: true
        });
    </script>";
}
?>
</body>
</html>
