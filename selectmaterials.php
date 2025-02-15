<?php
    session_start(); 
    include('config.php');

    if (!isset($_SESSION['User_ID'])) {
    header('Location: login.html');
    exit();
    }
?>

<?php

// Ensure the existence of the Plan_ID field in the session
$PlanID = isset($_SESSION['plan_ID']) ? $_SESSION['plan_ID'] : '';

if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_GET['message'])) {
    $message = htmlspecialchars($_GET['message']); // Sanitize the message
    echo "<script>alert('$message');</script>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Part</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Verdana, sans-serif;
            margin: 0;
            font-size: 20px; /* Default font size */
            line-height: 1.6;
            padding: 20px; /* Added padding to body */
        }

        table {
            display: none;
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 8px;
            font-size: 20px;
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px;
            background: #FF8C00;
            color: #000;
            display: flex;
            align-items: center;
            z-index: 100;
        }

        .header h1 {
            font-size: 20px;
            margin-left: 10px;
            border-left: 20px solid transparent;
        }

        .header a {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            text-decoration: none;
            margin-right: 15px;
        }

        .right {
            margin-left: auto;
        }

        /* Box container */
        .content-box {
            background-color: #f9f9f9; /* Light grey background */
            border: 20px solid #FF8600; 
            border-radius: 0px;
            padding: 50px; 
            margin-top: 20px; 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .row-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .input-container {
            flex: 1;
        }

        /* Media Queries */
        @media screen and (max-width: 600px) {
            body {
                font-family: Arial, Helvetica, sans-serif;
                padding-top: 60px; /* Adjusted for header */
            }

            .header {
                padding: 10px;
                flex-direction: column;
                text-align: center;
            }

            .header a {
                margin-bottom: 10px;
            }

            .topnav a, .topnav input[type="text"] {
                display: block;
                width: 100%;
                text-align: left;
                margin: 0;
                padding: 14px;
            }

            .topnav input[type="text"] {
                border: 1px solid #ccc;
            }

            .modal-content {
                width: 100%;
            }

            /* For mobile views, content box gets full width */
            .content-box {
                width: 100%;
                padding: 15px;
            }
        }

        /* CSS for Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 140, 0, 0.4);
            z-index: 1000;
            overflow: auto;
        }

        .modal-content {
            background-color: #FF8C00;
            margin: 10% auto;
            padding: 20px;
            width: 80%;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 20px;
            color: #FF8C00;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 20px;
            margin-bottom: 5px;
            color: #000;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 20px;
        }

        .post-btn, .cancel-btn {
            margin-bottom: 10px;
        }

        .cancel-btn {
            background-color: red;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            width: 100%;
            font-size: 20px;
        }

        .cancel-btn:hover {
            background-color: #000;
        }

        button {
            background-color: #FF8C00;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 20px;
        }

        button:hover {
            background-color: #000;
        }
    </style>



</head>
<body>
    <div class="content-box">
        <form id="postForm" enctype="multipart/form-data" method="POST" action="selectingmaterials.php">

            <h2>Parts of the House</h2>
            <p>What part of the house do you want to build or to renovate?</p>
        
            <label for="checkboxBedroom">Bedroom</label>
            <input type="checkbox" id="checkboxBedroom" name="part[]" value="Bedroom" onclick="toggleTable('Bedroom')">

            <label for="checkboxDining">Dining Room</label>
            <input type="checkbox" id="checkboxDining" name="part[]" value="Dining Room" onclick="toggleTable('Dining')">

            <label for="checkboxLiving">Living Room</label>
            <input type="checkbox" id="checkboxLiving" name="part[]"  value="Living Room" onclick="toggleTable('Living')">

            <label for="checkboxKitchen">Kitchen</label>
            <input type="checkbox" id="checkboxKitchen" name="part[]" value="Kitchen" onclick="toggleTable('Kitchen')">

            <label for="checkboxBathroom">Bathroom</label>
            <input type="checkbox" id="checkboxBathroom" name="part[]" value="Bathroom" onclick="toggleTable('Bathroom')">

            <div>
                <!-- Bedroom Table -->
                <table id="tableBedroom">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Materials</th>
                            <th>Quantity</th>
                            <th>Price (₱)</th>
                            <th>Total (₱)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'config.php';
                        $sql = "SELECT * FROM prematerialsinventory";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td><input type='checkbox' class='item-checkbox' name='materials[]' data-price='{$row['price']}' value='{$row['name']}' onclick='updateEstimatedCost()'></td>
                                        <td>{$row['name']}</td>
                                        <td><input type='number' class='quantity' name='quantity[]' value='1' min='0' oninput='calculateTotal(this)'></td>
                                        <td>{$row['price']}</td>
                                        <td class='total'>{$row['price']}</td>
                                        <input type='hidden' class='totalInput' name='total[]' value='{$row['price']}'>
                                        <input type='hidden' name='name[]' value='{$row['name']}'>
                                        <input type='hidden' name='price[]' value='{$row['price']}'>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>No materials found</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>


                <!-- Dining Room Table -->
                <table id="tableDining">
                    <thead>
                    <tr>
                        <th>Select</th>
                        <th>Materials</th>
                        <th>Quantity</th>
                        <th>Price (₱)</th>
                        <th>Total (₱)</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="2500.00" value="Dining Table" onclick="updateEstimatedCost()"></td>
                            <td>Dining Table</td>
                            <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                            <td><span class="price">400.00</span></td>
                            <td><span class="total">400.00</span></td>
                            <input type="hidden" class="totalInput" name="total[]" value="400.00">
                            <input type="hidden" name="name[]" value="Dining Table">
                            <input type="hidden" name="price[]" value="400.00">
                        </tr>
                        <!-- Add more rows as needed for dining room materials -->
                    </tbody>
                </table>

                <!-- Living Room Table -->
                <table id="tableLiving">
                    <thead>
                        <tr>
                        <th>Select</th>
                        <th>Materials</th>
                        <th>Quantity</th>
                        <th>Price (₱)</th>
                        <th>Total (₱)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="3000.00" value="Sofa" onclick="updateEstimatedCost()"></td>
                            <td>Sofa</td>
                            <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                            <td><span class="price">400.00</span></td>
                            <td><span class="total">400.00</span></td>
                            <input type="hidden" class="totalInput" name="total[]" value="400.00">
                            <input type="hidden" name="name[]" value="Sofa">
                            <input type="hidden" name="price[]" value="400.00">
                        </tr>
                        <!-- Add more rows as needed for living room materials -->
                    </tbody>
                </table>

                <!-- Kitchen Table -->
                <table id="tableKitchen">
                    <thead>
                    <tr>
                        <th>Select</th>
                        <th>Materials</th>
                        <th>Quantity</th>
                        <th>Price (₱)</th>
                        <th>Total (₱)</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="1000.00" value="Sink" onclick="updateEstimatedCost()"></td>
                            <td>Sink</td>
                            <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                            <td><span class="price">400.00</span></td>
                            <td><span class="total">400.00</span></td>
                            <input type="hidden" class="totalInput" name="total[]" value="400.00">
                            <input type="hidden" name="name[]" value="Sink">
                            <input type="hidden" name="price[]" value="400.00">
                        </tr>
                        <!-- Add more rows as needed for kitchen materials -->
                    </tbody>
                </table>

                <!-- Bathroom Table -->
                <table id="tableBathroom">
                    <thead>
                        <tr>
                        <th>Select</th>
                        <th>Materials</th>
                        <th>Quantity</th>
                        <th>Price (₱)</th>
                        <th>Total (₱)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="400.00" value = "Vanity" onclick="updateEstimatedCost()"></td>
                            <td>Vanity</td>
                            <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                            <td><span class="price">400.00</span></td>
                            <td><span class="total">400.00</span></td>
                            <input type="hidden" class="totalInput" name="total[]" value="400.00">
                            <input type="hidden" name="name[]" value="Vanity">
                            <input type="hidden" name="price[]" value="400.00">
                        </tr>
                        <!-- Add more rows as needed for bathroom materials -->
                    </tbody>
                </table>
            </div>
            <div class="input-container">
              <div id="selected-materials">
                  <h3>Estimated Cost</h3>
                  <label for="estimated_cost">Estimated Cost:</label>
                  <input type="text" name="estimated_cost" id="estimated_cost" readonly>
              </div>
            </div>
            <button>Submit</button>
        </form>
    </div>
</body>
<script>
        // your-script.js

    function toggleTable(room) {
        // Show the selected table
        document.getElementById("table" + room).style.display = document.getElementById("checkbox" + room).checked ? "table" : "none";
    }

    function calculateTotal(input) {
        var row = input.closest("tr");
        var quantity = parseFloat(row.querySelector(".quantity-input").value);
        var price = parseFloat(row.querySelector(".price").innerText);
        var total = quantity * price;

        // Update the total column in the same row
        row.querySelector(".total").innerText = total.toFixed(2);
    }
</script>
<script>
    function calculateTotal(input) {
        var quantity = input.value;
        var price = input.parentElement.nextElementSibling.textContent;
        var total = parseFloat(quantity) * parseFloat(price);
        input.parentElement.nextElementSibling.nextElementSibling.textContent = total.toFixed(2);
    }
</script>
<script>
    function calculateTotal(input) {
        var quantity = input.value;
        var price = input.parentElement.nextElementSibling.textContent;
        var total = parseFloat(quantity) * parseFloat(price);
        input.parentElement.nextElementSibling.nextElementSibling.textContent = total.toFixed(2);
        updateEstimatedCost();
    }

    function updateEstimatedCost() {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
        var totalCost = 0;

        checkboxes.forEach(function(checkbox) {
            var rowTotal = parseFloat(checkbox.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.textContent);
            totalCost += rowTotal;
        });

        var estimatedCostInput = document.getElementById('estimated_cost');
        estimatedCostInput.value = "₱" + totalCost.toFixed(2);
    }
</script>
<script>
        // Function to calculate the total for each item based on the quantity and update the estimated cost
    function calculateTotal(quantityInput) {
        const row = quantityInput.closest('tr');
        const price = parseFloat(row.querySelector('.item-checkbox').dataset.price);
        const quantity = parseInt(quantityInput.value, 10) || 0;
        const total = price * quantity;
        
        // Update the total for this row
        row.querySelector('.total').innerText = total.toFixed(2);
        row.querySelector('.totalInput').value = total.toFixed(2);
        
        // Update the estimated cost
        updateEstimatedCost();
    }

    // Function to update the estimated cost when an item is selected or deselected
    function updateEstimatedCost() {
        let estimatedCost = 0;

        // Loop through each checkbox
        document.querySelectorAll('.item-checkbox').forEach((checkbox) => {
            if (checkbox.checked) {
                const row = checkbox.closest('tr');
                const total = parseFloat(row.querySelector('.totalInput').value);
                estimatedCost += total;
            }
        });

        // Update the estimated cost input
        document.getElementById("estimated_cost").value = estimatedCost;
    }
</script>
<script>
    window.onload = function() {
        // Check if success and message are in the URL
        <?php if ($success == 'true' && $message != ''): ?>
            var message = "<?php echo $message; ?>";
            var alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.role = 'alert';
            alertDiv.innerHTML = message + 
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            document.body.prepend(alertDiv); // Add alert at the top of the body
        <?php elseif ($success == 'false' && $message != ''): ?>
            var message = "<?php echo $message; ?>";
            var alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show';
            alertDiv.role = 'alert';
            alertDiv.innerHTML = message + 
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            document.body.prepend(alertDiv); // Add alert at the top of the body
        <?php endif; ?>
    };
</script>

</html>

