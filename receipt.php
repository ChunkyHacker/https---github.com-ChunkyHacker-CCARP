<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <style>
        /* Basic styling for the receipt */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .receipt {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
        }
        .receipt h1 {
            text-align: center;
        }
        .receipt-info {
            margin-bottom: 20px;
        }
        .receipt-info p {
            margin: 5px 0;
        }
        .receipt-info p strong {
            margin-right: 10px;
        }
        .print-button,
        .download-button {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<?php
  session_start();
  include('config.php');

  // Retrieve data from URL parameters
  $totalprice = $_GET['total_price'] ?? '';
  $paymentmethod = $_GET['payment_method']?? '';
  $gcashnumber = $_GETT['gcashnumber']?? '';
  $creditcardnumber = $_GET['creditcardnumber']?? '';

?>

<body>
<div class="receipt">
    <h1>Receipt</h1>
    <div class="receipt-info">
        <p>Order Info</p>
        <?php
            require_once "config.php";

            // Query for prematerials table
            $query_materials = "SELECT * FROM prematerials";
            $stmt_materials = mysqli_prepare($connection, $query_materials);
            mysqli_stmt_execute($stmt_materials);
            $result_materials = mysqli_stmt_get_result($stmt_materials);

            $totalSum_materials = 0; // Initialize total sum variable for prematerials

            while ($material_row = mysqli_fetch_assoc($result_materials)) {
                // Add each total to the total sum for prematerials
                $totalSum_materials += $material_row['total'];
            }

            // Query for requiredmaterials table
            $query_required_materials = "SELECT * FROM requiredmaterials";
            $stmt_required_materials = mysqli_prepare($connection, $query_required_materials);
            mysqli_stmt_execute($stmt_required_materials);
            $result_required_materials = mysqli_stmt_get_result($stmt_required_materials);

            $totalSum_required = 0; // Initialize total sum variable for requiredmaterials

            while ($required_material_row = mysqli_fetch_assoc($result_required_materials)) {
                // Add each total to the total sum for requiredmaterials
                $totalSum_required += $required_material_row['total'];
            }

            // Calculate combined total sum
            $totalSum_combined = $totalSum_materials + $totalSum_required;
        ?>


        <div class="row">
            <div class="col-75">
                <div class="col-25">
                    <div class="container">
                        <!-- Table for prematerials -->
                        <h4>Cart <span class="price" style="color:black"><i class="fa fa-shopping-cart"></i></span></h4>
                        <table style="border-collapse: collapse; width: 100%;">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Part</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Materials</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Name</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Quantity</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Price</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">₱Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Rewind the result set to start from the beginning
                                mysqli_data_seek($result_materials, 0);

                                while ($material_row = mysqli_fetch_assoc($result_materials)) {
                                    echo '<tr>
                                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['part']) . '</td>
                                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['materials']) . '</td>
                                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['name']) . '</td>
                                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['quantity']) . '</td>
                                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['price']) . '</td>
                                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['total']) . '</td>
                                        </tr>';
                                }
                                ?>
                            </tbody>
                        </table>

                        <!-- Table for requiredmaterials -->
                        <h4>Required Materials</h4>
                        <table style="border-collapse: collapse; width: 100%;">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Material</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Type</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Image</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Quantity</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Price</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Loop through the results and populate the second table (requiredmaterials)
                                mysqli_data_seek($result_required_materials, 0);

                                while ($required_material_row = mysqli_fetch_assoc($result_required_materials)) {
                                    echo '<tr>
                                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['material']) . '</td>
                                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['type']) . '</td>
                                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><img src="' . htmlspecialchars($required_material_row['image']) . '" alt="' . htmlspecialchars($required_material_row['material']) . '" style="width: 100px; height: auto;"></td>
                                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['quantity']) . '</td>
                                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['price']) . '</td>
                                            <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['total']) . '</td>
                                        </tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                        <p><strong>Total Price:</strong> <?php echo $totalSum_combined; ?></p>
                        <p><strong>Payment Method:</strong> <?php echo $paymentmethod; ?></p>
                        <p><strong>Status:</strong> Paid</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="print-button">
        <!-- Print button for printing the receipt -->
        <button onclick="window.print()">Print Receipt</button>
    </div>
    <div class="download-button">
        <!-- Download button for downloading the receipt in PDF format -->
        <button onclick="downloadReceipt()">Download Receipt (PDF)</button>
    </div>
    <div>
        <a href="profile.php">Go back</a>
    </div>
</div>
</body>
<script>
    function downloadReceipt() {
        // Retrieve carpenter name
        const carpenterName = document.getElementById('carpenterName').textContent;
        
        // Create a new jsPDF instance
        const doc = new jsPDF();
        
        // Define receipt content using JavaScript template literals
        const receiptContent = `
            Order Info
            Carpenter Name: ${carpenterName}
            Credit Card Info
            Full Name: <?php echo $fullname; ?>
            Email: <?php echo $email; ?>
            Address: <?php echo $address; ?>
            City: <?php echo $city; ?>
            Province: <?php echo $province; ?>
            ZIP Code: <?php echo $zip; ?>
            Card Name: <?php echo $cardname; ?>
            Card Number: <?php echo $cardnumber; ?>
            Expiration Month: <?php echo $expmonth; ?>
            Expiration Year: <?php echo $expyear; ?>
            CVV: <?php echo $cvv; ?>
        `;
        
        // Set font size and add receipt content to the PDF
        doc.setFontSize(12);
        doc.text(receiptContent, 10, 10);
        
        // Save the PDF with a filename
        doc.save('receipt.pdf');
    }
</script>

</html>
