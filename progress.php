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

        // Fetch Payment_ID for the logged-in carpenter
    $query_payment = "SELECT Payment_ID FROM payment WHERE Carpenter_ID = ? ORDER BY Payment_ID DESC LIMIT 1";
    $stmt = mysqli_prepare($conn, $query_payment);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['Carpenter_ID']);
    mysqli_stmt_execute($stmt);
    $result_payment = mysqli_stmt_get_result($stmt);

    if ($row_payment = mysqli_fetch_assoc($result_payment)) {
        $payment_ID = $row_payment['Payment_ID']; // Assign Payment_ID
    } else {
        $payment_ID = null; // No payment record found
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Progress</title>
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
            overflow: scroll;
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
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            z-index: 1001;
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

        /* Add this CSS to your existing styles */
        .styled-table {
            font-size: 20px; /* Adjusted font size for table */
        }

        .table-header {
            font-size: 20px; /* Adjusted font size for table headers */
        }

        .table-cell h3 {
            font-size: 20px; /* Adjusted font size for table cell content */
        }

        .view-purchased {
            position: relative; /* Ensure it can have a z-index */
            z-index: 1; /* Lower than the modal */
        }
    </style>
</head>
<body>
    <?php
    // Check if the success parameter is passed in the URL
    if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_GET['message'])) {
        // Sanitize the message to prevent XSS
        $message = htmlspecialchars($_GET['message']);
        // Output the alert with the message
        echo "<script>alert('$message');</script>";
    }

    //Attendance
    if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_GET['action'])) {
        $action = htmlspecialchars($_GET['action']);
        
        // Determine the message based on the action
        if ($action == 'time_in') {
            echo "<script>alert('Time In recorded successfully!');</script>";
        } elseif ($action == 'Time_out') {
            echo "<script>alert('Time Out recorded successfully!');</script>";
        }
    }

    if (isset($_GET['success']) && $_GET['success'] == 'true' && isset($_GET['action']) && $_GET['action'] == 'Time_out') {
        echo "<script>alert('Time Out recorded successfully!');</script>";
    }
    ?>

    <div class="sidebar">
        <div class="profile-section">
            <img src="<?php echo $_SESSION['Photo'] ?? 'default-profile.jpg'; ?>" alt="Profile Picture" class="profile-image">
            <div class="profile-name"><?php echo $_SESSION['First_Name'] . ' ' . $_SESSION['Last_Name']; ?></div>
            <div class="profile-id">Carpenter ID: <?php echo $_SESSION['Carpenter_ID']; ?></div>
        </div>
        <a href="profile.php">
            <i class="fas fa-home"></i> Home
        </a>
        <a href="#" id="addLaborBtn" onclick="openLaborModal(event)">
            <i class="fas fa-chart-line"></i> Add attendance
        </a>
        <a href="#" id="addTaskBtn" onclick="openTaskModal(event)">
            <i class="fas fa-tasks"></i> Add task
        </a>
        <a href="#" id="addMaterialsBtn" onclick="openMaterialsModal(event)">
            <i class="fas fa-chart-line"></i> Add progress
        </a>
        <a href="#" onclick="viewContract(<?php echo $contract_ID; ?>)">
            <i class="fas fa-file-contract"></i> View Contract
        </a>
        <a href="logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>

    <div id="successAlert" style="display: none; background-color: #28a745; color: white; padding: 10px; text-align: center; font-size: 18px; position: fixed; top: 0; left: 0; width: 100%; z-index: 1000;">
        Changes saved successfully!
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
                    <textarea readonly style="font-size: 18px; padding: 10px; width: 100%; height: 100px;"><?php echo $row['more_details'] ?? ''; ?></textarea>
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
                    <button class="view-purchased" onclick="openModal(<?php echo isset($transactionID) ? $transactionID : '0'; ?>)">View Purchased</button>
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

        <?php else: ?>
            <p>No contract found with Contract ID: <?php echo $contract_ID; ?></p>
        <?php endif; ?>
        <div>                
            <h2 style="text-align:center">Progress and Attendance</h2>
        </div>
        <!--Progress, Attendance and Task Section-->
        <div class="grid-container" style="grid-column: span 3;">
            <?php
                include('config.php');

                // Check if contract_ID is provided in the URL parameter
                if(isset($_GET['contract_ID'])) {
                    $contractID = $_GET['contract_ID'];

                    // Fetch contract details
                    $sqlContract = "SELECT * FROM contracts WHERE contract_ID = $contractID";
                    $resultContract = $conn->query($sqlContract);

                    if ($resultContract->num_rows > 0) {
                        $contract = $resultContract->fetch_assoc();
                        // Add any other relevant contract details here
                    } else {
                        echo '<p>Error: Contract not found.</p>';
                    }
                } else {
                    echo '<p>Error: contract_ID is missing.</p>';
                }
            ?>

            <div id="addMaterialsModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Add progress</h2>
                    <form id="addMaterialsForm" method="post" action="addprogress.php" enctype="multipart/form-data">
                        <div>
                            <label for="Name">Name</label>
                            <input type="text" id="material_name" name="Name" required>
                        </div>
                        <div>
                            <label for="Status">Status</label>
                            <select id="status" name="Status" required>
                                <option value="Not yet started">Not yet started</option>
                                <option value="Working">Working</option>
                                <option value="Done">Done</option>
                            </select>
                        </div>
                        <!-- Hidden input field for contract_ID -->
                        <input type="hidden" name="contract_ID" value="<?php echo isset($_GET['contract_ID']) ? $_GET['contract_ID'] : ''; ?>">
                        <button type="submit">Add Progress</button>
                    </form>
                </div>
            </div>

            <div id="addLaborModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Attendance</h2>
                    <form id="addLaborForm" method="post" action="addattendance.php" enctype="multipart/form-data">
                        <?php
                        // Fetch type_of_work from contracts table
                        $contract_ID = isset($_GET['contract_ID']) ? $_GET['contract_ID'] : '';
                        $type_query = "SELECT type_of_work FROM contracts WHERE contract_ID = ?";
                        $stmt = $conn->prepare($type_query);
                        $stmt->bind_param("i", $contract_ID);
                        $stmt->execute();
                        $type_result = $stmt->get_result();
                        $type_of_work = ($type_result->num_rows > 0) ? $type_result->fetch_assoc()['type_of_work'] : '';
                        ?>
                        <div>
                            <label for="type_of_work">Type of Work:</label>
                            <input type="text" id="type_of_work" name="type_of_work" value="<?php echo htmlspecialchars($type_of_work); ?>" readonly style="width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px; font-size: 16px;">
                        </div>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="flex: 1;">
                                <label for="Time_in">Time-in:</label>
                                <input required type="text" id="Time_in" name="Time_in" readonly style="width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px; font-size: 16px;">
                            </div>
                        </div>


                        <!-- Hidden input field for contract_ID -->
                        <input type="hidden" name="contract_ID" value="<?php echo isset($_GET['contract_ID']) ? $_GET['contract_ID'] : ''; ?>">
                        <!-- Modified buttons container -->
                        <div style="display: flex; gap: 10px; margin-top: 15px; justify-content: flex-start;">
                            <button type="button" onclick="addTimeStamp(event)" style="flex: 0 0 120px; padding: 8px;">Add Timestamp</button>
                            <button type="submit" id="addAttendanceBtn" disabled style="flex: 0 0 120px; padding: 8px; opacity: 0.5;">Add Attendance</button>
                            <button type="button" onclick="closeLaborModal()" style="flex: 0 0 120px; padding: 8px; background-color: #dc3545;">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="addTaskModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Add Task</h2>
                    <form id="addTaskForm" method="post" action="addtask.php" enctype="multipart/form-data">
                        <div>
                            <label for="task">Task:</label>
                            <input type="text" id="task" name="task" required>
                        </div>
                        <!-- Hidden timestamp field -->
                        <input type="hidden" id="timestamp" name="timestamp">
                        <!-- Hidden input field for contract_ID -->
                        <input type="hidden" name="contract_ID" value="<?php echo isset($_GET['contract_ID']) ? $_GET['contract_ID'] : ''; ?>">
                        <button type="submit" onclick="setTimestamp()">Add Task</button>
                    </form>
                </div>
            </div>



            <!--TABLE-->
            <div class="product-container">
                <!--Progress-->
                <h2 style="text-align:center; font-size: 24px;">Progress</h2>
                <?php
                    // Prepare the SQL query with a WHERE clause to filter by contract_ID
                    $sqlReports = "SELECT * FROM report WHERE contract_ID = $contractID";
                    $resultReports = $conn->query($sqlReports);
                    if ($resultReports->num_rows > 0) {
                        echo '<div class="table-container">';
                        echo '    <table class="styled-table">';
                        echo '        <tr>';
                        echo '            <th class="table-header">Name</th>';
                        echo '            <th class="table-header">Status</th>';
                        echo '        </tr>';

                        while ($row = $resultReports->fetch_assoc()) {
                            echo '        <tr>';
                            echo '            <td class="table-cell"><h3>' . $row["Name"] . '</h3></td>';
                            echo '            <td class="table-cell"><h3>' . $row["Status"] . '</h3></td>';
                            echo '        </tr>';
                        }

                        echo '</table>';
                        echo '</div>';
                    } else {
                        echo '<p>No Progress yet</p>';
                    }
                ?>
            </div>

            <div class="product-container">
                <!--Tasks-->
                <h2 style="text-align:center; font-size: 24px;">Tasks</h2>
                <?php
                include('config.php');

                if (isset($_GET['contract_ID'])) {
                    $contractID = $_GET['contract_ID'];

                    // Fetch pending tasks
                    $sql = "SELECT * FROM task WHERE contract_ID = $contractID AND task_id NOT IN (SELECT task_id FROM completed_task)";
                    $result = $conn->query($sql);

                    echo '<h3>Pending Tasks</h3>';
                    echo '<div class="table-container">';
                    echo '<table class="styled-table">';
                    echo '<tr>';
                    echo '<th>Status</th>';
                    echo '<th>Task</th>';
                    echo '<th>Time</th>';
                    echo '</tr>';

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td class="table-cell">
                                    <select name="status" class="task-status" data-task-id="' . $row["task_id"] . '" data-task-name="' . $row["task"] . '" data-task-details="' . $row["timestamp"] . '" 
                                        style="padding: 8px; font-size: 16px; border-radius: 4px; border: 1px solid #ccc; background-color: #fff; cursor: pointer;">
                                        <option value="" disabled selected>Choose</option>
                                        <option value="Not yet started">Not yet started</option>
                                        <option value="Working">Working</option>
                                        <option value="Done">Done</option>
                                    </select>
                                    </td>';
                        
                            echo '<td class="table-cell"><h3>' . $row["task"] . '</h3></td>';
                            echo '<td class="table-cell"><h3>' . $row["timestamp"] . '</h3></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="3">No pending tasks</td></tr>';
                    }

                    echo '</table>';
                    echo '</div>';

                    echo '<button id="saveChangesBtn" style="display:none; margin-top:10px; background-color:#FF8C00; color:#FFFFFF; border:none; padding:10px 20px; font-size:20px; cursor:pointer; margin:20px; border-radius:4px;">
                        Save Changes
                    </button>';

                    // Fetch completed tasks
                    // Completed Task
                    $sqlCompleted = "SELECT * FROM completed_task";
                    $resultCompleted = $conn->query($sqlCompleted);
                    
                    echo '<h3>Completed Tasks</h3>';
                    echo '<div class="table-container">';
                        echo '<table class="styled-table">';
                        echo '<tr>';
                        echo '<th>Task</th>';
                        echo '<th>Time</th>';
                        echo '<th>Status</th>';
                        echo '</tr>';

                        if ($resultCompleted->num_rows > 0) {
                            while ($row = $resultCompleted->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td class="table-cell"><h3>' . $row["name"] . '</h3></td>';
                                echo '<td class="table-cell"><h3>' . $row["timestamp"] . '</h3></td>';
                                echo '<td class="table-cell"><h3>' . ucfirst($row["status"]) . '</h3></td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="3">No completed tasks</td></tr>';
                        }

                        echo '</table>';
                    echo '</div>';
                } else {
                    echo '<p>Error: contract_ID is missing.</p>';
                }
                ?>
            </div>

            <div class="product-container">
                <!--Attendance-->
                <h2 style="text-align:center; font-size: 24px;">Attendance</h2>
                <?php
                    include('config.php');

                    // Check if contract_ID is provided in the URL parameter
                    if(isset($_GET['contract_ID'])) {
                        $contractID = $_GET['contract_ID'];

                        // Modified SQL query to include type_of_work from contracts table
                        $sql = "SELECT a.*, c.type_of_work, a.attendance_ID 
                                FROM attendance a 
                                LEFT JOIN contracts c ON a.contract_ID = c.contract_ID 
                                WHERE a.contract_ID = ?";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $contractID);
                        $stmt->execute();
                        $result = $stmt->get_result();


                        if ($result->num_rows > 0) {
                            echo '<div class="table-container">';
                            echo '    <table class="styled-table">';
                            echo '        <tr>';
                            echo '            <th class="table-header">Type of Work</th>';
                            echo '            <th class="table-header">Time in</th>';
                            echo '            <th class="table-header">Time out</th>';
                            echo '            <th class="table-header">Action</th>';
                            echo '        </tr>';

                            while ($row = $result->fetch_assoc()) {
                                echo '        <tr>';
                                echo '            <td class="table-cell"><h3>' . $row["type_of_work"] . '</h3></td>';
                                echo '            <td class="table-cell"><h3>' . $row["Time_in"] . '</h3></td>';
                                echo '            <td class="table-cell"><h3>' . $row["Time_out"] . '</h3></td>';
                                echo '            <td class="table-cell">';
                                echo '              <button type="button" onclick="openTimeOutModal(' . $row["attendance_ID"] . ', ' . $contractID . ')">Add Time out</button>';
                                echo '            </td>';           
                                echo '        </tr>';
                            }

                            echo '    </table>';
                            echo '</div>';
                        } else {
                            echo '<p>No attendance yet.</p>';
                        }
                    } else {
                        echo '<p>Error: contract_ID is missing.</p>';
                    }

                    $conn->close();
                ?>
                <!-- Rest of the modal code remains the same -->

            </div>
        <!-- Buttons -->
        <div style="display: flex; justify-content: flex-start; gap: 20px; margin-top: 30px; margin-left: 20px;">
            <?php if (!empty($payment_ID)): ?>
                <button onclick="window.location.href='carpenterpayment.php?Payment_ID=<?php echo urlencode($payment_ID); ?>'"
                        style="width: 150px; height: 50px; background-color: #FF8C00; color: white; 
                        border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                        View Payment
                </button>
            <?php else: ?>
                <button disabled
                        style="width: 150px; height: 50px; background-color: grey; color: white; 
                        border: none; border-radius: 5px; cursor: not-allowed; font-size: 16px;">
                        No Payment Available
                </button>
            <?php endif; ?>
            <button onclick="window.location.href='turnover.php?contract_ID=<?php echo $contract_ID; ?>'"
                    style="width: 150px; height: 50px; background-color: #FF8C00; color: white; 
                    border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                    Turnover Project
            </button>

        </div>
        <!-- Add Time Out Modal -->
        <div id="timeOutModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span class="close" onclick="closeTimeOutModal()">&times;</span>
                    <h2>Add Time Out</h2>
                    <form id="timeOutForm" method="post" action="update_time_out.php">
                        <div>
                            <label for="timeOutField">Time-out:</label>
                            <input type="text" id="timeOutField" name="Time_out" readonly required>
                            <button type="button" onclick="addTimeOutStamp()">Add Timestamp</button>
                        </div>
                        <input type="hidden" name="attendance_id" id="attendanceIdField">
                        <button type="submit" id="saveTimeOutBtn" disabled style="opacity: 0.5;">Save Time Out</button>
                    </form>
                </div>
        </div>
    </div>

    <script>
            function setTimestamp() {
                var currentDate = new Date();
                var formattedDateTime = currentDate.toLocaleString();
                document.getElementById('timestamp').value = formattedDateTime;
            }

            function openTaskModal(event) {
                event.preventDefault();
                document.getElementById('addTaskModal').style.display = 'block';
                setTimestamp(); // Set timestamp when modal opens
            }
    </script>

    <!-- JavaScript -->
    <script>
        // Function to open the modal
        function openAttendanceModal() {
            document.getElementById('timeOutModal').style.display = 'block';
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('timeOutModal').style.display = 'none';
        }

        function closeTimeOutModal() {
            document.getElementById('timeOutModal').style.display = 'none';
        }

        function openTimeOutModal(attendanceId, contractId) {
            var modal = document.getElementById('timeOutModal');
            modal.style.display = 'block';
            
            // Update the hidden field values
            document.getElementById('attendanceIdField').value = attendanceId;
            
            // Add contract_ID field if needed
            var form = document.getElementById('timeOutForm');
            if (!form.querySelector('input[name="contract_ID"]')) {
                var contractInput = document.createElement('input');
                contractInput.type = 'hidden';
                contractInput.name = 'contract_ID';
                contractInput.value = contractId;
                form.appendChild(contractInput);
            }
        }

        function closeTimeOutModal() {
            document.getElementById('timeOutModal').style.display = 'none';
        }

        function addTimeOutStamp() {
            var currentDate = new Date();
            var formattedDateTime = currentDate.toLocaleString();
            document.getElementById('timeOutField').value = formattedDateTime;
            
            // Enable the Save Time Out button after timestamp is added
            var saveButton = document.getElementById('saveTimeOutBtn');
            saveButton.disabled = false;
            saveButton.style.opacity = "1";
        }

        function openTimeOutModal(attendanceId, contractId) {
            var modal = document.getElementById('timeOutModal');
            modal.style.display = 'block';
            
            // Update the hidden field values
            document.getElementById('attendanceIdField').value = attendanceId;
            
            // Add contract_ID field if needed
            var form = document.getElementById('timeOutForm');
            if (!form.querySelector('input[name="contract_ID"]')) {
                var contractInput = document.createElement('input');
                contractInput.type = 'hidden';
                contractInput.name = 'contract_ID';
                contractInput.value = contractId;
                form.appendChild(contractInput);
            }

            // Reset and disable the save button when opening modal
            var saveButton = document.getElementById('saveTimeOutBtn');
            saveButton.disabled = true;
            saveButton.style.opacity = "0.5";
            document.getElementById('timeOutField').value = '';
        }
    </script>
    
    <script>
        function addTimeStamp(event) {
            event.preventDefault();
            var currentDate = new Date();
            var formattedDateTime = currentDate.toLocaleString();
            var timeInInput = document.getElementById("Time_in");
            var addAttendanceBtn = document.getElementById("addAttendanceBtn");
            
            timeInInput.value = formattedDateTime;
            // Enable the Add Attendance button after timestamp is added
            addAttendanceBtn.disabled = false;
            addAttendanceBtn.style.opacity = "1";
        }
    </script>
    
    <script>
    // labormaterialmodal.js
    var materialsModal = document.getElementById('addMaterialsModal');
    var laborModal = document.getElementById('addLaborModal');
    var taskModal = document.getElementById('addTaskModal');

    var closeBtns = document.getElementsByClassName('close');

    function openMaterialsModal(event) {
        event.preventDefault(); // Prevent the default anchor behavior
        materialsModal.style.display = 'block';
    }

    function openLaborModal(event) {
        event.preventDefault(); // Prevent the default anchor behavior
        laborModal.style.display = 'block';
    }

    function openTaskModal(event) {
        event.preventDefault(); // Prevent the default anchor behavior
        taskModal.style.display = 'block';
    }

    function closeLaborModal() {
        document.getElementById('addLaborModal').style.display = 'none';
    }

    for (var i = 0; i < closeBtns.length; i++) {
        closeBtns[i].onclick = function() {
            materialsModal.style.display = 'none';
            laborModal.style.display = 'none';
            taskModal.style.display = 'none';
        }
    }

    window.onclick = function(event) {
        if (event.target == materialsModal || event.target == laborModal || event.target == taskModal) {
            materialsModal.style.display = 'none';
            laborModal.style.display = 'none';
            taskModal.style.display = 'none';
        }
    }
</script>
<script>
    // quantityxcost.js
    // Get references to the input fields
    var quantityInput = document.getElementById('quantity');
    var costInput = document.getElementById('cost');
    var totalCostInput = document.getElementById('total_cost');

    // Add event listeners to quantity and cost input fields
    quantityInput.addEventListener('input', calculateTotalCost);
    costInput.addEventListener('input', calculateTotalCost);

    // Function to calculate total cost
    function calculateTotalCost() {
        // Parse quantity and cost inputs as numbers
        var quantity = parseFloat(quantityInput.value);
        var cost = parseFloat(costInput.value);

        // Calculate total cost
        var totalCost = quantity * cost;

        // Update total cost input field with the calculated value
        totalCostInput.value = totalCost.toFixed(2); // Displaying up to 2 decimal places
    }
</script>
<script>
    // daysofworkxlaborcost.js
    // Get references to the input fields
    var daysOfWorkInput = document.getElementById('days_of_work');
    var rateInput = document.getElementById('rate');
    var totalLaborCostInput = document.getElementById('total_of_laborcost');

    // Add event listeners to days of work and rate input fields
    daysOfWorkInput.addEventListener('input', calculateTotalLaborCost);
    rateInput.addEventListener('input', calculateTotalLaborCost);

    // Function to calculate total labor cost
    function calculateTotalLaborCost() {
        // Parse days of work and rate inputs as numbers
        var daysOfWork = parseFloat(daysOfWorkInput.value);
        var rate = parseFloat(rateInput.value);

        // Calculate total labor cost
        var totalLaborCost = isNaN(daysOfWork) ? rate : daysOfWork * rate;

        // Update total labor cost input field with the calculated value
        totalLaborCostInput.value = isNaN(totalLaborCost) ? '' : totalLaborCost.toFixed(2); // Displaying up to 2 decimal places
    }
</script>
<script>
    // Example of showing the button when changes are made
    function showSaveButton() {
        document.getElementById('saveChangesBtn').style.display = 'block';
    }
</script>
<script>
function redirectToMoveTask() {
    // You can pass any necessary data here, for example, the contract ID
    var contractID = <?php echo isset($_GET['contract_ID']) ? $_GET['contract_ID'] : 'null'; ?>;
    
    // Redirect to move_task.php with the contract ID as a query parameter
    if (contractID) {
        window.location.href = 'move_task.php?contract_ID=' + contractID;
    } else {
        alert('Error: Contract ID is missing.');
    }
}
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let updatedTasks = [];

        document.querySelectorAll(".task-status").forEach(function (dropdown) {
            dropdown.addEventListener("change", function () {
                let taskID = this.getAttribute("data-task-id");
                let taskName = this.getAttribute("data-task-name");
                let status = this.value;
                let currentTimestamp = new Date().toLocaleString();

                let taskIndex = updatedTasks.findIndex(task => task.task_id === taskID);
                if (taskIndex !== -1) {
                    updatedTasks[taskIndex] = {
                        task_id: taskID,
                        task_name: taskName,
                        task_details: status === "Done" ? currentTimestamp : this.getAttribute("data-task-details"),
                        status: status
                    };
                } else {
                    updatedTasks.push({
                        task_id: taskID,
                        task_name: taskName,
                        task_details: status === "Done" ? currentTimestamp : this.getAttribute("data-task-details"),
                        status: status
                    });
                }

                if (updatedTasks.length > 0) {
                    document.getElementById("saveChangesBtn").style.display = "block";
                }
            });
        });
                
        document.getElementById("saveChangesBtn").addEventListener("click", function () {
            if (updatedTasks.length === 0) {
                alert("No tasks selected!");
                return;
            }

            fetch("move_task.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(updatedTasks)
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                location.reload(); // Reload the page to reflect changes
            })
            .catch(error => console.error("Error:", error));
        });
    });
</script>

<!-- Modal Structure -->
<div id="transactionModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Transaction Details</h2>
        <div id="modal-body">
            <!-- Transaction details will be populated here -->
        </div>
    </div>
</div>

<script>
function openModal(transactionID) {
    console.log("Opening modal for transaction ID:", transactionID); // Debugging line
    // Fetch transaction details using AJAX
    fetch(`get_transaction_details.php?transaction_ID=${transactionID}`)
        .then(response => response.json())
        .then(data => {
            // Populate modal with transaction details
            const modalBody = document.getElementById('modal-body');
            if (data.error) {
                modalBody.innerHTML = `<p>${data.error}</p>`;
            } else {
                modalBody.innerHTML = `
                    <p><strong>Transaction ID:</strong> ${data.transaction_ID}</p>
                    <p><strong>Contract ID:</strong> ${data.contract_ID}</p>
                    <p><strong>Receipt Photo:</strong></p>
                    <img src="data:image/jpeg;base64,${data.receipt_photo}" alt="Receipt Photo" style="max-width: 100%; height: auto;">
                    <p><strong>Status:</strong> Paid</p>
                `;
            }
            document.getElementById('transactionModal').style.display = "block";
        })
        .catch(error => console.error('Error fetching transaction details:', error));
}

function closeModal() {
    document.getElementById('transactionModal').style.display = "none";
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('transactionModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>
</html>
