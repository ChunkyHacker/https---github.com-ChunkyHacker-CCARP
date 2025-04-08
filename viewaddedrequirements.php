<?php
    session_start(); 
    include('config.php');

    // Check if user is logged in
    if (!isset($_SESSION['Carpenter_ID'])) {
        header('Location: login.html');
        exit();
    }

    // Get carpenter's information
    $carpenter_query = "SELECT * FROM carpenters WHERE Carpenter_ID = ?";
    $stmt = mysqli_prepare($conn, $carpenter_query);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['Carpenter_ID']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($carpenter = mysqli_fetch_assoc($result)) {
        $_SESSION['Photo'] = $carpenter['Photo'];
        $_SESSION['First_Name'] = $carpenter['First_Name'];
        $_SESSION['Last_Name'] = $carpenter['Last_Name'];
    }

    // Get contract details
    $contract_ID = $_GET['contract_ID'];
    
    $query = "SELECT p.*, u.First_Name AS client_first, u.Last_Name AS client_last, u.Photo AS client_photo,
              c.*, pa.approval_ID, pa.Carpenter_ID,
              cr.First_Name AS carpenter_first, cr.Last_Name AS carpenter_last
              FROM contracts c
              JOIN plan p ON c.Plan_ID = p.Plan_ID
              JOIN users u ON p.User_ID = u.User_ID
              JOIN plan_approval pa ON p.Plan_ID = pa.Plan_ID
              JOIN carpenters cr ON c.Carpenter_ID = cr.Carpenter_ID
              WHERE c.contract_ID = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $contract_ID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Plan Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Verdana, sans-serif;
            background-color: rgb(255, 255, 255);
            font-size: 20px;
            margin: 0;
        }

        /* Sidebar Styles */
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #FF8C00;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .profile-section {
            text-align: center;
            padding: 20px;
            margin-bottom: 20px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 2px solid #000000;
            object-fit: cover;
        }

        .profile-name {
            font-size: 24px;
            font-weight: bold;
            color: #000000;
            margin-bottom: 5px;
        }

        .profile-id {
            font-size: 16px;
            color: #000000;
            margin-bottom: 20px;
        }

        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 20px;
            color: #000000;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #000000;
            color: #FF8C00;
        }

        .sidebar .active {
            background-color: #000000;
            color: #FF8C00;
        }

        /* Main Content Styles */
        .main {
            margin-left: 250px;
            padding: 20px;
        }

        .row-container {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        button {
            background-color: #FF8C00;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }

        button:hover {
            background-color: #000000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        /* Add grid layout styles */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .grid-item {
            background: white;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        h1 {
            font-size: 48px;
            margin-bottom: 30px;
            font-weight: bold;
            padding-left: 20px;
        }

        h3 {
            font-size: 32px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        /* Update input styles */
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 22px;
        }

        /* Update client photo style */
        .client-photo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 10px;
            display: block;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-height: 80vh;
            overflow-y: auto;
            position: relative;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-section">
            <img src="<?php echo $_SESSION['Photo'] ?? 'default-profile.jpg'; ?>" alt="Profile Picture" class="profile-image">
            <div class="profile-name"><?php echo $_SESSION['First_Name'] . ' ' . $_SESSION['Last_Name']; ?></div>
            <div class="profile-id">Carpenter ID: <?php echo $_SESSION['Carpenter_ID']; ?></div>
        </div>
        <a href="profile.php">
            <i class="fas fa-home"></i> Home
        </a>
        <a href="viewaddedrequirements.php" class="active">
            <i class="fas fa-tasks"></i> Requirements
        </a>
        <a href="progress.php?contract_ID=<?php echo $row['contract_ID']; ?>">
            <i class="fas fa-chart-line"></i> Progress
        </a>
        <a href="#" onclick="viewContract(<?php echo $contract_ID; ?>)">
            <i class="fas fa-file-contract"></i> View Contract
        </a>
        <a href="logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <?php if ($row = mysqli_fetch_assoc($result)): ?>
            <h1>Client's Plan Details</h1>
            
            <div class="grid-container">
                <!-- Client Info -->
                <div class="grid-item">
                    <h3>Client Information</h3>
                    <?php if (!empty($row['client_photo'])): ?>
                        <img src="<?php echo $row['client_photo']; ?>" alt="Client Photo" class="client-photo">
                    <?php endif; ?>
                    <label>Client Name:</label>
                    <input type="text" value="<?php echo $row['client_first'] . ' ' . $row['client_last']; ?>" readonly>
                </div>

                <!-- Lot Area -->
                <div class="grid-item">
                    <h3>Lot Area</h3>
                    <label>Length:</label>
                    <input type="text" value="<?php echo $row['length_lot_area']; ?>" readonly>
                    <label>Width:</label>
                    <input type="text" value="<?php echo $row['width_lot_area']; ?>" readonly>
                    <label>Square Meter:</label>
                    <input type="text" value="<?php echo $row['square_meter_lot']; ?>" readonly>
                </div>

                <!-- Floor Area -->
                <div class="grid-item">
                    <h3>Floor Area</h3>
                    <label>Length:</label>
                    <input type="text" value="<?php echo $row['length_floor_area']; ?>" readonly>
                    <label>Width:</label>
                    <input type="text" value="<?php echo $row['width_floor_area']; ?>" readonly>
                    <label>Square Meter:</label>
                    <input type="text" value="<?php echo $row['square_meter_floor']; ?>" readonly>
                </div>

                <!-- Project Budget -->
                <div class="grid-item">
                    <h3>Project Budget</h3>
                    <label>Initial Budget:</label>
                    <input type="text" value="<?php echo $row['initial_budget']; ?>" readonly>
                    <label>Labor Cost:</label>
                    <input type="text" value="PHP <?php echo $row['labor_cost']; ?>" readonly>
                </div>

                <!-- Project Dates -->
                <div class="grid-item">
                    <h3>Project Dates</h3>
                    <label>Start Date:</label>
                    <input type="text" value="<?php echo $row['start_date']; ?>" readonly>
                    <label>End Date:</label>
                    <input type="text" value="<?php echo $row['end_date']; ?>" readonly>
                    <label>Duration:</label>
                    <input type="text" value="<?php echo $row['duration']; ?> days" readonly>
                </div>

                <!-- More Details -->
                <div class="grid-item">
                    <h3>More Details</h3>
                    <textarea readonly><?php echo $row['more_details'] ?? ''; ?></textarea>
                </div>

                <!-- Materials Table - Full Width -->
                <div class="grid-item" style="grid-column: span 3;">
                    <h3>Materials</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Part</th>
                                <th>Materials</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_materials = "SELECT * FROM prematerials";
                            $stmt_materials = mysqli_prepare($conn, $query_materials);
                            mysqli_stmt_execute($stmt_materials);
                            $result_materials = mysqli_stmt_get_result($stmt_materials);

                            while ($material_row = mysqli_fetch_assoc($result_materials)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($material_row['part']) . "</td>";
                                echo "<td>" . htmlspecialchars($material_row['materials']) . "</td>";
                                echo "<td>" . htmlspecialchars($material_row['quantity']) . "</td>";
                                echo "<td>" . htmlspecialchars($material_row['price']) . "</td>";
                                echo "<td>" . htmlspecialchars($material_row['total']) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <button onclick="window.location.href='purchase.php?contract_ID=<?php echo $contract_ID; ?>'"
                            style="width: 150px; height: 50px; background-color: #FF8C00; color: white; 
                            border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                            Go to Purchase</button>
                </div>

                <!-- Project Photos - Full Width -->
                <?php if (!empty($row['Photo']) && file_exists($row['Photo'])): ?>
                <div class="grid-item" style="grid-column: span 3;">
                    <h3>Project Photos</h3>
                    <div style="display: flex; gap: 20px; flex-wrap: wrap; justify-content: center;">
                        <img src="<?php echo $row['Photo']; ?>" alt="Project Photo" style="width: 300px; height: 300px; object-fit: cover; border-radius: 8px;">
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Buttons -->
            <div style="display: flex; justify-content: flex-start; gap: 20px; margin-top: 30px; margin-left: 20px;">
                <button onclick="window.location.href='profile.php'" 
                        style="width: 150px; height: 50px; background-color: #FF8C00; color: white; 
                        border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                        Go back</button>
                <button onclick="window.location.href='progress.php?contract_ID=<?php echo $row['contract_ID']; ?>'"
                        style="width: 150px; height: 50px; background-color: #4CAF50; color: white; 
                        border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                        Check progress</button>
                <?php
                // Check if plan has been rated
                $check_rating_sql = "SELECT * FROM ratings WHERE contract_ID = ?";
                $check_rating_stmt = $conn->prepare($check_rating_sql);
                $check_rating_stmt->bind_param("i", $contract_ID);
                $check_rating_stmt->execute();
                $rating_result = $check_rating_stmt->get_result();
                
                if ($rating_result->num_rows > 0) {
                    echo "<button onclick=\"window.location.href='carpenterviewrratings.php?contract_ID=" . $contract_ID . "'\" 
                            style='width: 150px; height: 50px; background-color: #FF8C00; color: white; 
                            border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>
                            View Rating</button>";
                }
                ?>
            </div>

        <?php else: ?>
            <p>No contract found with Contract ID: <?php echo $contract_ID; ?></p>
        <?php endif; ?>
    </div>

    <!-- Add Modal -->
    <div id="contractModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="contractContent">
                <!-- Contract content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Add this before closing body tag -->
    <script>
        // Get the modal
        var modal = document.getElementById("contractModal");
        var span = document.getElementsByClassName("close")[0];

        // Function to view contract
        function viewContract(contractId) {
            // Fetch contract details using AJAX
            fetch('get_contract.php?contract_ID=' + contractId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('contractContent').innerHTML = data;
                    modal.style.display = "block";
                })
                .catch(error => console.error('Error:', error));
        }

        // Close modal when clicking (x)
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
<?php
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
?>