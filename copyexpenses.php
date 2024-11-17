<?php
include('config.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['copy_to_expenses'])) {
    // Get Payroll_ID from the URL
    $Payroll_ID = $_GET['Payroll_ID'] ?? null;

    if ($Payroll_ID) {
        // Fetch data from the payment table based on Payroll_ID
        $sql_payment = "SELECT * FROM payment WHERE Payroll_ID = $Payroll_ID";
        $result_payment = $conn->query($sql_payment);

        // Fetch required materials data from the requiredmaterials table
        $sql_materials = "SELECT * FROM requiredmaterials";
        $result_materials = $conn->query($sql_materials);

        if ($result_payment->num_rows > 0 && $result_materials->num_rows > 0) {
            // Get payment data
            $row_payment = $result_payment->fetch_assoc();

            // Loop through each material in requiredmaterials to insert into expenses
            while ($row_material = $result_materials->fetch_assoc()) {
                $materials = $conn->real_escape_string($row_material['material']);
                $type = $conn->real_escape_string($row_material['type']);
                $quantity = intval($row_material['quantity']);
                $price = intval($row_material['price']);
                $total = intval($row_material['total']); // Assuming the total is already calculated

                // Payment details
                $carpenter_name = $conn->real_escape_string($row_payment['carpenter_name']);
                $net_pay = intval($row_payment['Netpay']);
                $days_of_work = intval($row_payment['Days_Of_Work']);
                $rate_per_day = intval($row_payment['Rate_per_day']);
                $overall_cost = intval($row_payment['overall_cost']);

                // Validate data before inserting (just in case)
                if (empty($materials) || empty($type) || $quantity <= 0 || $price <= 0 || $total <= 0) {
                    echo "Invalid data for materials or price. Skipping this entry.";
                    continue; // Skip this row and go to the next material
                }

                // Insert into the expenses table
                $insert_sql = "INSERT INTO expenses (materials, type, quantity, price, total, carpenter_name, Netpay, Days_Of_Work, Rate_per_day, overall_cost) 
                               VALUES ('$materials', '$type', $quantity, $price, $total, '$carpenter_name', $net_pay, $days_of_work, $rate_per_day, $overall_cost)";

                if ($conn->query($insert_sql) === FALSE) {
                    echo "Error inserting data: " . $conn->error;
                    break;
                }
            }

            // Success message and redirect
            echo "<script>alert('Data successfully transferred to the expenses table!'); window.location.href='payrollreceipt.php?Payroll_ID=" . $Payroll_ID . "';</script>";
        } else {
            echo "No data found for the given Payroll ID or required materials.";
        }
    } else {
        echo "Payroll ID is missing.";
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
