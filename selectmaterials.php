<?php
    session_start(); 
    include('config.php');

    // Get user data from database
    if (isset($_SESSION['User_ID'])) {
        $user_id = $_SESSION['User_ID'];
        $query = "SELECT * FROM users WHERE User_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $userDetails = $result->fetch_assoc();
    } else {
        header('Location: login.php');
        exit();
    }

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Select Materials</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 200px;
            background: #FF8C00;
            padding: 20px;
            color: black;
            overflow-y: auto;
        }

        .profile-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-section img {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }

        .profile-section h3 {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .profile-section p {
            font-size: 20px;
        }

        .sidebar a {
            display: block;
            color: black;
            text-decoration: none;
            padding: 10px 0;
            font-size: 20px;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 20px;
            background: white;
            font-size: 20px;
        }

        .content-box {
            background: white;
            padding: 20px;
            border: 15px solid #FF8C00;
            height: calc(120vh - 40px);
            overflow-y: auto;
            font-size: 20px;
        }

        h2 {
            color: black;
            margin-bottom: 20px;
            font-size: 20px;
        }

        p {
            margin-bottom: 20px;
            font-size: 20px;
        }

        /* Checkbox Styles */
        .checkbox-group {
            margin-bottom: 15px;
        }

        .checkbox-group label {
            display: block;
            font-size: 20px;
        }

        /* Estimated Cost Section */
        .estimated-cost {
            margin-top: 30px;
        }

        .estimated-cost h3 {
            margin-bottom: 10px;
            font-size: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
        }

        /* Submit Button */
        button[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #FF8C00;
            color: white;
            border: none;
            cursor: pointer;
        }

        /* Keep your existing table styles but hidden by default */
        table {
            display: none;
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: none;
            padding: 8px;
            text-align: left;
            font-size: 20px;
        }

        th {
            background-color: #f2f2f2;
        }

        .table-scroll {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-section">
            <?php
            // Display profile picture
            if (isset($userDetails['Photo']) && !empty($userDetails['Photo'])) {
                echo '<img src="' . $userDetails['Photo'] . '" alt="Profile Picture">';
            } else {
                echo '<img src="assets/img/default-avatar.png" alt="Default Profile Picture">';
            }
            
            // Display name and ID
            echo "<h3>" . $userDetails['First_Name'] . ' ' . $userDetails['Last_Name'] . "</h3>";
            echo "<p>User ID: " . $user_id . "</p>";
            ?>
        </div>
        <a href="usercomment.php" class="nav-link">
            <i class="fa fa-home"></i>
            <span>Home</span>
        </a>
        <a href="about/index.html" class="nav-link">
                <i class="fa fa-info-circle"></i>
                <span>About</span>
        </a>
        <a href="#contact" class="nav-link">
                <i class="fa fa-lightbulb-o"></i>
                <span>Get Ideas</span>
        </a>
        <a href="plan.php" class="nav-link"> 
                </i><i class="fa fa-file"></i>
                <span> Project</span>
        </a>
        <a href="userprofile.php" class="nav-link">
                <i class="fa fa-user"></i>
                <span>Profile</span>
        </a>
        <a href="logout.php" class="nav-link">
                <i class="fa fa-sign-out"></i>
                <span>Logout</span>
        </a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-box">
            <form id="postForm" enctype="multipart/form-data" method="POST" action="selectingmaterials.php">
                <!-- Add hidden input for User_ID -->
                <input type="hidden" name="user_ID" value="<?php echo $_SESSION['User_ID']; ?>">
                
                <h2>Parts of the House</h2>
                <p>What part of the house do you want to build or to renovate?</p>

                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" id="checkboxBedroom" name="part[]" value="Bedroom" onclick="toggleTable('Bedroom')">
                        Bedroom
                    </label>
                </div>

                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" id="checkboxDining" name="part[]" value="Dining Room" onclick="toggleTable('Dining')">
                        Dining Room
                    </label>
                </div>

                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" id="checkboxLiving" name="part[]" value="Living Room" onclick="toggleTable('Living')">
                        Living Room
                    </label>
                </div>

                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" id="checkboxKitchen" name="part[]" value="Kitchen" onclick="toggleTable('Kitchen')">
                        Kitchen
                    </label>
                </div>

                <div class="checkbox-group">
                    <label>
                        <input type="checkbox" id="checkboxBathroom" name="part[]" value="Bathroom" onclick="toggleTable('Bathroom')">
                        Bathroom
                    </label>
                </div>

                <!-- Bedroom Table -->
                <div class="table-scroll">
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
                                            <td>
                                                <input type='number' class='quantity' name='quantity[]' value='1' min='1' oninput='calculateTotal(this)'>
                                            </td>
                                            <td><span class='price'>{$row['price']}</span></td>
                                            <td class='total'>{$row['price']}</td>
                                            <input type='hidden' class='totalInput' name='total[]' value='{$row['price']}'>
                                            <input type='hidden' name='name[]' value='{$row['name']}'>
                                            <input type='hidden' name='price[]' value='{$row['price']}'>
                                        </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No materials found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Dining Room Table -->
                <div class="table-scroll">
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
                        </tbody>
                    </table>
                </div>

                <!-- Living Room Table -->
                <div class="table-scroll">
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
                        </tbody>
                    </table>
                </div>

                <!-- Kitchen Table -->
                <div class="table-scroll">
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
                        </tbody>
                    </table>
                </div>

                <!-- Bathroom Table -->
                <div class="table-scroll">
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
                                <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="400.00" value="Vanity" onclick="updateEstimatedCost()"></td>
                                <td>Vanity</td>
                                <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                                <td><span class="price">400.00</span></td>
                                <td><span class="total">400.00</span></td>
                                <input type="hidden" class="totalInput" name="total[]" value="400.00">
                                <input type="hidden" name="name[]" value="Vanity">
                                <input type="hidden" name="price[]" value="400.00">
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="estimated-cost">
                    <h3>Estimated Cost</h3>
                    <label for="estimated_cost">Estimated Cost:</label>
                    <input type="text" name="estimated_cost" id="estimated_cost" readonly>
                </div>

                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <script>
        function toggleTable(room) {
            document.getElementById("table" + room).style.display = 
                document.getElementById("checkbox" + room).checked ? "table" : "none";
        }

        function calculateTotal(input) {
            var row = input.closest("tr");
            var quantity = parseFloat(input.value) || 0; // Ensure quantity is a number
            var price = parseFloat(row.querySelector(".price").textContent) || 0; // Ensure price is a number
            var total = quantity * price;
            row.querySelector(".total").textContent = total.toFixed(2); // Update total display
            row.querySelector(".totalInput").value = total.toFixed(2); // Update hidden input
            updateEstimatedCost(); // Update overall estimated cost
        }

        function updateEstimatedCost() {
            var checkboxes = document.querySelectorAll('.item-checkbox:checked');
            var totalCost = 0;

            checkboxes.forEach(function(checkbox) {
                var row = checkbox.closest('tr');
                var total = parseFloat(row.querySelector('.totalInput').value) || 0;
                totalCost += total;
            });

            document.getElementById("estimated_cost").value = "₱" + totalCost.toFixed(2);
        }
    </script>
</body>
</html>