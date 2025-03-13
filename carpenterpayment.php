<?php
    session_start(); 
    include('config.php');

    if (!isset($_SESSION['Carpenter_ID'])) {
        header('Location: login.html');
        exit();
    }

    // Get Carpenter ID from session
    $Carpenter_ID = $_SESSION['Carpenter_ID'];

    // Fetch carpenter details from the database
    $query = "SELECT * FROM carpenters WHERE Carpenter_ID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $Carpenter_ID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result->num_rows > 0) {
        $carpenter = mysqli_fetch_assoc($result); // Fetch carpenter details
    } else {
        $carpenter = null; // Prevent error if no carpenter found
    }

    // Check if Payment_ID is set
    if (!isset($_GET['Payment_ID'])) {
        die("<p style='color:red;'>Error: Payment_ID parameter is missing in the URL.</p>");
    }

    $Payment_ID = $_GET['Payment_ID'];

    // Get payment details using Payment_ID
    $query = "SELECT p.*, 
              c.First_Name AS Carpenter_First, c.Last_Name AS Carpenter_Last, 
              u.First_Name AS Sender_First, u.Last_Name AS Sender_Last 
              FROM payment p
              JOIN carpenters c ON p.Carpenter_ID = c.Carpenter_ID
              JOIN users u ON p.User_ID = u.User_ID
              WHERE p.Payment_ID = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $Payment_ID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result->num_rows > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        die("<p style='color:red;'>Error: No payment record found for Payment_ID: $Payment_ID</p>");
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Payroll Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            font-size: 20px; /* Set font size to 20px */
        }

        .sidenav {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #FF8C00;
            overflow-x: hidden;
            padding-top: 20px;
        }

        .sidenav .profile-section {
            text-align: center;
            padding: 20px 10px;
            margin-bottom: 20px;
        }

        .sidenav .profile-section img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
            border: 3px solid white;
        }

        .sidenav h3 {
            font-size: 24px;
            margin-bottom: 5px;
            color: black;
        }

        .sidenav p {
            font-size: 18px;
            margin-bottom: 20px;
            color: black;
        }

        .sidenav a {
            padding: 12px 15px;
            text-decoration: none;
            font-size: 18px;
            color: black;
            display: block;
            transition: 0.3s;
        }

        .sidenav a:hover {
            background-color: rgba(0,0,0,0.1);
        }

        .sidenav a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
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
</head>
<div class="sidenav">
    <div class="profile-section">
        <?php
        // Display profile picture
        if (isset($carpenter['Photo']) && !empty($carpenter['Photo'])) {
            echo '<img src="' . htmlspecialchars($carpenter['Photo']) . '" alt="Profile Picture">';
        } else {
            echo '<img src="assets/img/default-avatar.png" alt="Default Profile Picture">';
        }
        
        // Display name and ID
        echo "<h3>" . (isset($carpenter['First_Name']) ? htmlspecialchars($carpenter['First_Name'] . " " . $carpenter['Last_Name']) : 'N/A') . "</h3>";
        echo "<p>Carpenter ID: " . htmlspecialchars($Carpenter_ID) . "</p>";
        ?>
    </div>
    <div class="sidebar-section">
        <a href="usercomment.php"><i class="fas fa-home"></i> Home</a>
        <a href="userprofile.php"><i class="fas fa-user"></i> Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>


<body>
    <div class="container">
        <h2>Payroll Receipt</h2>
        <div class="payroll-details">
            <table>
                <tr>
                    <th>Carpenter Name</th>
                    <td><?php echo htmlspecialchars($row["Carpenter_First"] . " " . $row["Carpenter_Last"]); ?></td>
                </tr>
                <tr>
                    <th>Labor Cost</th>
                    <td><?php echo htmlspecialchars($row["Labor_Cost"]); ?></td>
                </tr>
                <tr>
                    <th>Duration</th>
                    <td><?php echo htmlspecialchars($row["Duration"]); ?> days</td>
                </tr>
                <tr>
                    <th>Type of Work</th>
                    <td><?php echo htmlspecialchars($row["type_of_work"]); ?></td>
                </tr>
                <tr>
                    <th>Payment Method</th>
                    <td><?php echo htmlspecialchars($row["Payment_Method"]); ?></td>
                </tr>
                <tr>
                    <th>Payment Date</th>
                    <td><?php echo htmlspecialchars($row["Payment_Date"]); ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><b style="color: green;">Paid</b></td>
                </tr>
                <tr>
                    <th>Sender</th>
                    <td><?php echo htmlspecialchars($row["Sender_First"] . " " . $row["Sender_Last"]); ?></td>
                </tr>
            </table>
        </div>

        <div class="download-btn">
            <!-- Submit button to transfer data to expenses -->
            <form action="copyexpenses.php?Payment_ID=<?php echo $Payment_ID; ?>" method="POST">
                <button type="submit" name="copy_to_expenses">Copy to expenses</button>
            </form>
        </div>

        <div class="download-btn">
            <button onclick="window.print()">Print Receipt</button>
        </div>
        
        <div>
            <a href="profile.php">Go back</a>
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
