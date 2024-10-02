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
    </style>
</head>
<body>
    <div class="container">
        <h2>Payroll Receipt</h2>
        <div class="payroll-details">
            <?php
            // Check if the Payroll_ID parameter is set in the URL
            if(isset($_GET["Payroll_ID"])) {
                // Get the Payroll_ID from the URL
                $Payroll_ID = $_GET["Payroll_ID"];

                // Database connection parameters
                $servername = "localhost";
                $username = "root"; // Change this to your MySQL username
                $password = ""; // Change this to your MySQL password
                $database = "ccarpcurrentsystem";

                // Create a new connection
                $conn = new mysqli($servername, $username, $password, $database);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Prepare SQL query to fetch the data based on Payroll_ID
                $sql = "SELECT * FROM payroll WHERE Payroll_ID = $Payroll_ID";

                // Execute SQL query
                $result = $conn->query($sql);

                // Check if there is a result
                if ($result->num_rows > 0) {
                    // Output data of each row
                    $row = $result->fetch_assoc();
                    ?>
                    <table>
                        <tr>
                            <th>Carpenter Name</th>
                            <td><?php echo $row["carpenter_name"]; ?></td>
                        </tr>
                        <tr>
                            <th>Net Pay</th>
                            <td><?php echo $row["Netpay"]; ?></td>
                        </tr>
                        <tr>
                            <th>Days of Work</th>
                            <td><?php echo $row["Days_Of_Work"]; ?></td>
                        </tr>
                        <tr>
                            <th>Rate per Day</th>
                            <td><?php echo $row["Rate_per_day"]; ?></td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td><?php echo $row["payment_method"]; ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>Paid</td>
                        </tr>
                        <tr>
                            <th>Sender</th>
                            <td><?php echo $row["sender"]; ?></td>
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
        </div>
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
