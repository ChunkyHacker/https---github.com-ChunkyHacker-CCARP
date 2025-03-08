<?php
session_start();
include('config.php'); // Ensure this file contains your database connection

// Check if the user is logged in
if (!isset($_SESSION['Carpenter_ID'])) {
    header('Location: login.html');
    exit();
}

// Fetch carpenter information from the database
$carpenterId = $_SESSION['Carpenter_ID'];
$query = "SELECT First_Name, Last_Name, Phone_Number, Email, specialization, Photo FROM carpenters WHERE Carpenter_ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $carpenterId);
$stmt->execute();
$result = $stmt->get_result();
$carpenter = $result->fetch_assoc();
$stmt->close();

if (!empty($_GET['receiptPath']) && !empty($_GET['transaction_ID'])) {
    $transactionID = $_GET['transaction_ID'];

    // Query to get transaction details
    $sql = "SELECT * FROM transaction WHERE transaction_ID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $transactionID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Fetch the transaction details
    $transactionDetails = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Receipt</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 17px;
            margin: 0;
            display: flex;
        }
        .sidebar {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 250px;
            background-color: #FF8C00;
            padding: 20px;
            color: black;
            height: 100vh;
        }
        .sidebar img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }
        .sidebar h2, .sidebar p, .sidebar a {
            align-self: flex-start;
            margin: 5px 0;
        }
        .sidebar h2 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
            color: black;
        }
        .sidebar p {
            font-size: 16px;
            color: black;
        }
        .sidebar a {
            color: black;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin: 5px 0;
            border-radius: 3px;
            font-size: 16px;
        }
        .sidebar a:hover {
            background-color: #d9534f;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #f2f2f2;
        }
        .container {
            font-size: 20px;
            background-color: #f2f2f2;
            margin: 20px auto;
            padding: 20px 30px;
            border: 1px solid lightgrey;
            border-radius: 3px;
            text-align: center;
            width: 50%;
        }
        .btn {
            background-color: #FF8C00;
            color: white;
            padding: 12px;
            margin: 10px 0;
            border: none;
            width: 100%;
            border-radius: 3px;
            cursor: pointer;
            font-size: 17px;
        }
        .btn:hover {
            background-color: #000000;
        }
        .back-btn {
            background-color: #d9534f;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
            display: inline-block;
            margin-top: 15px;
        }
        .back-btn:hover {
            background-color: #c9302c;
        }
        #receipt-preview, .uploaded-receipt {
            margin-top: 20px;
            max-width: 80%;
            height: auto;
            display: block;
            margin-left: auto;
            margin-right: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <img src="<?php echo $_SESSION['Photo'] ?? 'default-profile.jpg'; ?>" alt="Profile Picture" class="profile-image">
        <h2><?php echo htmlspecialchars($carpenter['First_Name'] . ' ' . $carpenter['Last_Name']); ?></h2>
        <p>Carpenter ID: <?php echo htmlspecialchars($carpenterId); ?></p>
        <a href="index.html">Home</a>
        <a href="requirements.php">Requirements</a>
        <a href="progress.php">Progress</a>
        <a href="contract.php">View Contract</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <div class="container">
            <h2>Upload Receipt</h2>
            
            <img id="receipt-preview" src="#" alt="Receipt Preview" style="display: none;">

            <?php if (!empty($_GET['receiptPath'])): ?>
                <img class="uploaded-receipt" src="<?php echo $_GET['receiptPath']; ?>" alt="Uploaded Receipt">
                <br>
                <!-- Display receipt details -->
                <div class="receipt-details">
                    <h3>Receipt Details</h3>
                    <?php if ($transactionDetails): ?>
                        <p><strong>Details:</strong> <?php echo htmlspecialchars($transactionDetails['details']); ?></p>
                    <?php else: ?>
                        <p>No transaction details found.</p>
                    <?php endif; ?>
                </div>
                <button class="back-btn" onclick="history.back()">Go Back</button>
            <?php endif; ?>
        </div>
    </div>

    <script>
        <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
            window.onload = function() {
                alert("✔ Transaction successful!");
            };
        <?php endif; ?>
    </script>
</body>
</html>
