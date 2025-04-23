<?php
    include('config.php');
    session_start();

    if (!isset($_SESSION['User_ID'])) {
        echo "<script>alert('You need to log in first.'); window.location.href='login.php';</script>";
        exit();
    }

    $user_ID = $_SESSION['User_ID']; // Get logged-in user ID

    // Get user details
    $sql = "SELECT * FROM users WHERE User_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $userDetails = $result->fetch_assoc();


    // Check if the success parameter is passed in the URL
    if (isset($_GET['success']) && $_GET['success'] == 'true') {
        echo "<script>alert('Payroll data added successfully!');</script>";
    }
    

    // Check if Payment_ID is set in the URL
    if (isset($_GET["Payment_ID"])) {
        $Payment_ID = $_GET["Payment_ID"];

        // Secure the query with a prepared statement
        $sql = "SELECT p.*, 
                c.First_Name AS Carpenter_First, c.Last_Name AS Carpenter_Last, 
                u.First_Name AS Sender_First, u.Last_Name AS Sender_Last 
                FROM payment p
                JOIN carpenters c ON p.Carpenter_ID = c.Carpenter_ID
                JOIN users u ON p.User_ID = u.User_ID
                WHERE p.Payment_ID = ?";
                    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $Payment_ID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            echo "<p>No results found for Payment ID: $Payment_ID</p>";
            exit();
        }

        // Close statement
        $stmt->close();
    } else {
        echo "<p>Error: Payment_ID parameter is missing in the URL.</p>";
        exit();
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
        if (isset($userDetails['Photo']) && !empty($userDetails['Photo'])) {
            echo '<img src="' . $userDetails['Photo'] . '" alt="Profile Picture">';
        } else {
            echo '<img src="assets/img/default-avatar.png" alt="Default Profile Picture">';
        }
        
        // Display name and ID
        echo "<h3>" . $userDetails['First_Name'] . ' ' . $userDetails['Last_Name'] . "</h3>";
        echo "<p>User ID: " . $user_ID . "</p>";
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
        <form action="copyexpenses.php?Payment_ID=<?php echo $_GET['Payment_ID']; ?>" method="POST">
            <div class="container">
                <h2>Payroll Receipt</h2>
                <form action="copyexpenses.php?Payment_ID=<?php echo $Payment_ID; ?>" method="POST">
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
        </form>
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
