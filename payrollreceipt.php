<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            font-size: 20px; /* Set font size to 20px */
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            font-size: 24px; /* Optional, if you want to adjust the heading font size */
        }

        .payroll-details {
            margin-top: 20px;
        }

        .payroll-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .payroll-details table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .payroll-details table th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: left;
        }

        .download-btn {
            text-align: center;
            margin-top: 20px;
        }

        .download-btn button {
            background-color: #FF8C00;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 20px;
        }

        .download-btn button:hover {
            background-color: #FFA500;
        }
    </style>

    <?php
    // Check if the success parameter is passed in the URL
    if (isset($_GET['success']) && $_GET['success'] == 'true') {
        echo "<script>alert('Payroll data added successfully!');</script>";
    }
    ?>

    <!-- Rest of your HTML content -->
</head>
<body>
    <div class="container">
        <h2>Payroll Receipt</h2>
        <form action="copyexpenses.php?Payroll_ID=<?php echo $_GET['Payroll_ID']; ?>" method="POST">
            <div class="payroll-details">
                <?php
                    include('config.php');
                    // Check if the Payroll_ID parameter is set in the URL
                    if(isset($_GET["Payroll_ID"])) {
                        $Payroll_ID = $_GET["Payroll_ID"];
                        $sql = "SELECT * FROM payment WHERE Payroll_ID = $Payroll_ID";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            ?>
                            <table>
                                <tr>
                                    <th>Carpenter Name</th>
                                    <td><?php echo htmlspecialchars($row["carpenter_name"]); ?></td>
                                </tr>
                                <tr>
                                    <th>Net Pay</th>
                                    <td><?php echo htmlspecialchars($row["Netpay"]); ?></td>
                                </tr>
                                <tr>
                                    <th>Days of Work</th>
                                    <td><?php echo htmlspecialchars($row["Days_Of_Work"]); ?></td>
                                </tr>
                                <tr>
                                    <th>Rate per Day</th>
                                    <td><?php echo htmlspecialchars($row["Rate_per_day"]); ?></td>
                                </tr>
                                <tr>
                                    <th>Overall cost</th>
                                    <td><?php echo htmlspecialchars($row["overall_cost"]); ?></td>
                                </tr>
                                <tr>
                                    <th>Payment Method</th>
                                    <td><?php echo htmlspecialchars($row["payment_method"]); ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>Paid</td>
                                </tr>
                                <tr>
                                    <th>Sender</th>
                                    <td><?php echo htmlspecialchars($row["sender"]); ?></td>
                                </tr>
                            </table>
                            <?php
                        } else {
                            echo "<p>No results found for Payroll ID: $Payroll_ID</p>";
                        }

                        // Close connection
                        $conn->close();
                    } else {
                        echo "<p>Error: Payroll_ID parameter is missing in the URL.</p>";
                    }
                ?>
                <div class="download-btn">
                    <!-- Submit button to transfer data to expenses -->
                    <button type="submit" name="copy_to_expenses">Copy to expenses</button>
                </div>
            </div>
        </form>



        <div class="download-btn">
            <button onclick="downloadReceipt()">Download Receipt</button>
            <button onclick="window.print()">Print Receipt</button>
        </div>
        <div>
        <a href="userprofile.php">Go back</a>
        </div>
    </div>

    <script>
        function downloadReceipt() {
            // Get the HTML content of the payroll details section
            var payrollContent = document.querySelector('.payroll-details').innerHTML;
            // Create a new blob containing the HTML content
            var blob = new Blob([payrollContent], { type: 'text/html' });
            // Create a temporary anchor element
            var a = document.createElement('a');
            // Set the href attribute to a URL created from the blob
            a.href = window.URL.createObjectURL(blob);
            // Set the download attribute with a filename
            a.download = 'payroll_receipt.html';
            // Append the anchor element to the body
            document.body.appendChild(a);
            // Programmatically trigger a click event on the anchor element
            a.click();
            // Remove the anchor element from the body
            document.body.removeChild(a);
        }
    </script>
</body>
</html>
