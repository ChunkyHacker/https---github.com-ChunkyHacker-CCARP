<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <style>
        /* General styles for the receipt */
        .container {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            font-size: 20px; /* Set base font size */
            max-width: 1200px; /* Set max-width for the container */
            margin: 0 auto; /* Center the container */
            overflow-x: auto; /* Handle overflow horizontally */
        }

        h4 {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
        }

        .price {
            font-size: 20px;
            color: #1a73e8; /* Adjusted color for price */
            font-weight: bold;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
            font-size: 20px; /* Set font size of table content */
        }

        th {
            background-color: #f4f4f4;
            color: #333;
        }

        td {
            background-color: #fff;
            color: #555;
        }

        td img {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }

        /* Table row hover effect */
        tr:hover {
            background-color: #f1f1f1;
        }

        /* Total price and other summary information */
        p {
            font-size: 20px;
            color: #333;
            margin-top: 10px;
            font-weight: bold;
        }

        p strong {
            color: #000;
        }

        /* Button styling */
        .download-button {
            text-align: center;
            margin-top: 20px;
        }

        .download-button button {
            background-color: #FF8600;
            color: black;
            padding: 12px 20px;
            font-size: 20px; /* Set font size to 20px */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .download-button button:hover {
            background-color: #218838;
        }

        /* Receipt layout */
        .receipt {
            max-width: 100%; /* Allow the receipt to adjust its width */
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
        }

        .receipt h1 {
            text-align: center;
            font-size: 24px; /* Slightly larger header */
            color: #333;
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

        /* Button styling for print, download, and go back */
        .print-button button,
        .download-button button,
        .go-back-button a {
            background-color: #FF8600; /* Same color as download button */
            color: black;
            padding: 12px 20px;
            font-size: 20px; /* Match font size */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center;
            text-decoration: none; /* Remove underline from the link */
            display: inline-block; /* Make the link behave like a button */
        }

        /* Hover effect for all buttons */
        .print-button button:hover,
        .download-button button:hover,
        .go-back-button a:hover {
            background-color: #ffcb91; /* Darker shade for hover */
        }

        /* Adjust the 'Go back' button if needed */
        .go-back-button a {
            display: inline-block; /* Ensure it's styled like a button */
            padding: 12px 20px;
            font-size: 20px;
        }


        /* Responsive design for smaller screens */
        @media (max-width: 768px) {
            .container {
                padding: 15px; /* Less padding on smaller screens */
            }

            table {
                font-size: 18px; /* Slightly smaller table font on smaller screens */
            }

            th, td {
                padding: 10px; /* Adjust padding for smaller screens */
            }
        }
    </style>

</head>
<?php
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
            // Query for prematerials table
            $query_materials = "SELECT * FROM prematerials";
            $stmt_materials = mysqli_prepare($conn, $query_materials);
            mysqli_stmt_execute($stmt_materials);
            $result_materials = mysqli_stmt_get_result($stmt_materials);

            $totalSum_materials = 0; // Initialize total sum variable for prematerials

            while ($material_row = mysqli_fetch_assoc($result_materials)) {
                // Add each total to the total sum for prematerials
                $totalSum_materials += $material_row['total'];
            }

            // Query for requiredmaterials table
            $query_required_materials = "SELECT * FROM requiredmaterials";
            $stmt_required_materials = mysqli_prepare($conn, $query_required_materials);
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


        <form action="copyexpenses.php" method="POST">
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
                                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Total</th>
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
                                        <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Material Overall Cost</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Loop through the results and populate the second table (requiredmaterials)
                                    mysqli_data_seek($result_required_materials, 0);

                                        while ($required_material_row = mysqli_fetch_assoc($result_required_materials)) {
                                            echo '<tr>
                                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['material']) . '</td>
                                                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['type']) . '</td>';
                                        
                                            // Image column
                                            echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">';
                                            
                                            // Check if the image path exists and is valid
                                            $image_path = htmlspecialchars($required_material_row['image']);
                                            if (!empty($image_path) && file_exists($image_path)) {
                                                echo '<img src="' . $image_path . '" alt="Material Image" style="max-width: 100px; max-height: 100px;">';
                                            } else {
                                                // Display a default image if the path is invalid or empty
                                                echo '<img src="assets/default-product.png" alt="Default Image" style="max-width: 100px; max-height: 100px;">';
                                            }
                                            
                                            echo '</td>';
                                        
                                            // Remaining columns
                                            echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['quantity']) . '</td>
                                                <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['price']) . '</td>
                                                <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['total']) . '</td>
                                                <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($required_material_row['materials_overall_cost']) . '</td>
                                                </tr>';
                                            }                                    
                                    ?>
                                </tbody>
                            </table>

                            <p><strong>Total Price:</strong> <?php echo $totalSum_combined; ?></p>
                            <p><strong>Payment Method:</strong> <?php echo $paymentmethod; ?></p>
                            <p><strong>Status:</strong> Paid</p>

                            <div class="download-button">
                                <!-- Submit button to transfer data to the expenses table -->
                                <button type="submit" name="copyexpenses.php">Copy to expenses</button>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
    </div>
    <div class="print-button">
        <!-- Print button for printing the receipt -->
        <button onclick="window.print()">Print Receipt</button>
    </div>
    <div class="download-button">
        <!-- Download button for downloading the receipt in PDF format -->
        <button onclick="downloadReceipt()">Download Receipt (PDF)</button>
    </div>

    <div class="go-back-button">
        <!-- Go back button with similar style as download button -->
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
