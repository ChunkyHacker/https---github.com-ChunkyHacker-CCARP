<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['copy_to_expenses'])) {
    // Get Payment_ID from URL
    $Payment_ID = $_GET['Payment_ID'] ?? null;

    if ($Payment_ID) {
        // Fetch payment details
        $sql_payment = "SELECT p.*, u.First_Name, u.Last_Name 
                        FROM payment p
                        JOIN users u ON p.Carpenter_ID = u.User_ID
                        WHERE p.Payment_ID = ?";
        $stmt = $conn->prepare($sql_payment);
        $stmt->bind_param("i", $Payment_ID);
        $stmt->execute();
        $result_payment = $stmt->get_result();

        // Fetch required materials data
        $sql_materials = "SELECT * FROM requiredmaterials";
        $result_materials = $conn->query($sql_materials);

        if ($result_payment->num_rows > 0 && $result_materials->num_rows > 0) {
            // Get payment data
            $row_payment = $result_payment->fetch_assoc();

            $carpenter_name = $conn->real_escape_string($row_payment['First_Name'] . " " . $row_payment['Last_Name']);
            $labor_cost = floatval($row_payment['Labor_Cost']);
            $duration = intval($row_payment['Duration']);
            $payment_method = $conn->real_escape_string($row_payment['Payment_Method']);

            // Loop through each material in requiredmaterials to insert into expenses
            while ($row_material = $result_materials->fetch_assoc()) {
                $materials = $conn->real_escape_string($row_material['material']);
                $type = $conn->real_escape_string($row_material['type']);
                $quantity = intval($row_material['quantity']);
                $price = floatval($row_material['price']);
                $total = floatval($row_material['total']); // Assuming already calculated

                // Validate data before inserting
                if (empty($materials) || empty($type) || $quantity <= 0 || $price <= 0 || $total <= 0) {
                    echo "Invalid data for materials or price. Skipping this entry.";
                    continue;
                }

                // Insert into the expenses table
                $insert_sql = "INSERT INTO expenses (materials, type, quantity, price, total, carpenter_name, labor_cost, duration, payment_method) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt_insert = $conn->prepare($insert_sql);
                $stmt_insert->bind_param("ssiddssds", $materials, $type, $quantity, $price, $total, $carpenter_name, $labor_cost, $duration, $payment_method);

                if (!$stmt_insert->execute()) {
                    echo "Error inserting data: " . $stmt_insert->error;
                    break;
                }
                $stmt_insert->close();
            }

            // Success message and redirect
            echo "<script>alert('Data successfully transferred to the expenses table!'); window.location.href='payrollreceipt.php?Payment_ID=" . $Payment_ID . "';</script>";
        } else {
            echo "No data found for the given Payment ID or required materials.";
        }

        $stmt->close();
    } else {
        echo "Payment ID is missing.";
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>
