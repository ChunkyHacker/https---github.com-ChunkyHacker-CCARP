<?php
    session_start(); 
    include('config.php');

    if (!isset($_SESSION['Carpenter_ID'])) {
        header('Location: login.html');
        exit();
    }

    // Fetch carpenter information from the database
    $carpenterId = $_SESSION['Carpenter_ID'];
    $query = "SELECT Photo, First_Name, Last_Name, Carpenter_ID FROM carpenters WHERE Carpenter_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $carpenterId);
    $stmt->execute();
    $result = $stmt->get_result();
    $carpenter = $result->fetch_assoc();
    $stmt->close();

    // Fetch contract_ID from the database (assuming you have a way to get it)
    $contract_ID = $_GET['contract_ID'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Upload Receipt</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            display: flex;
            background-color: #f2f2f2; /* Light background for the body */
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            align-items: center; /* Center the image */
            width: 250px;
            background-color: #FF8C00;
            padding: 20px;
            color: black; /* Change text color to black */
            height: 100vh;
        }

        .sidebar img {
            width: 100px; /* Fixed width */
            height: 100px; /* Fixed height */
            border-radius: 50%; /* Circular shape */
            object-fit: cover; /* Ensures the image covers the area without distortion */
            margin-bottom: 20px; /* Space below the image */
        }

        .sidebar h2, .sidebar p, .sidebar a {
            align-self: flex-start; /* Align text to the left */
            margin: 5px 0; /* Space between items */
        }

        .sidebar h2 {
            margin: 0;
            font-size: 20px;
            color: black;
        }

        .sidebar p {
            color: black;
        }

        .sidebar a {
            color: black;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin: 5px 0;
            border-radius: 3px;
        }

        .sidebar a:hover {
            background-color: #d9534f;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #ffffff; /* White background for the main content */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            margin: 20px; /* Margin around the main content */
        }

        .main-content h2 {
            margin-bottom: 10px; /* Space below the heading */
        }

        .btn, .back-btn {
            background-color: #FF8C00; /* Button color */
            color: white;
            padding: 8px; /* Padding */
            margin: 10px auto; /* Center the buttons */
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 15px; /* Font size */
            transition: background-color 0.3s; /* Smooth transition */
            max-width: 200px; /* Set a maximum width */
            width: 100%; /* Full width up to max-width */
        }

        .btn:hover, .back-btn:hover {
            background-color: #d9534f; /* Change color on hover */
        }

        .footer {
            padding: 10px;
            text-align: center;
            background: #FF8C00;
            position: relative;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
        }

        .uploaded-receipt, #receipt-preview {
            max-width: 80vw;
            max-height: 60vh;
            width: auto;
            height: auto;
            display: block;
            margin: 10px auto;
            object-fit: contain;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
        }

        #receipt-preview {
            margin-top: 20px;
            max-width: 100%;
            height: auto;
            display: none;
        }

        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            display: none; /* Hidden by default */
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .loading {
            display: none;
            font-size: 16px;
            color: #FF8C00;
        }

        /* Card style for the upload form */
        .upload-card {
            background-color: #ffffff; /* White background for the card */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            margin-top: 20px; /* Space above the card */
        }

        input[type="file"] {
            margin: 10px 0; /* Space around the file input */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%; /* Full width */
            background-color: #ffffff; /* White background */
            cursor: pointer; /* Pointer cursor on hover */
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="<?php echo $_SESSION['Photo'] ?? 'default-profile.jpg'; ?>" alt="Profile Picture" class="profile-image">
        <h2><?php echo htmlspecialchars($carpenter['First_Name'] . ' ' . $carpenter['Last_Name']); ?></h2>
        <p>Carpenter ID: <?php echo htmlspecialchars($carpenter['Carpenter_ID']); ?></p>
        <a href="index.html">Home</a>
        <a href="requirements.php">Requirements</a>
        <a href="progress.php">Progress</a>
        <a href="contract.php">View Contract</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="main-content">
        <h2>Upload Receipt</h2>
        <p>Please upload your receipt in image format (JPEG, PNG).</p>
        
        <div class="message success" id="success-message">Receipt uploaded successfully!</div>
        <div class="message error" id="error-message">Error uploading receipt. Please try again.</div>
        
        <div class="upload-card">
            <form action="transaction.php" method="POST" enctype="multipart/form-data" id="upload-form">
                <input type="file" name="receipt" id="receipt-upload" accept="image/*" required>
                <input type="text" name="details" id="receipt-details" required>
                <input type="hidden" name="contract_ID" value="<?php echo isset($_GET['contract_ID']) ? $_GET['contract_ID'] : ''; ?>">
                <br>
                <button type="submit" class="btn">Upload</button>
                <div class="loading" id="loading-message">Uploading...</div>
            </form>
            
            <img id="receipt-preview" src="#" alt="Receipt Preview">
            
            <?php
            $receiptPath = ""; // Initialize empty receipt path
            if (isset($_GET['receipt'])) {
                $receiptPath = htmlspecialchars($_GET['receipt']); // Get receipt path from URL
            }
            ?>

            <?php if (!empty($receiptPath)): ?>
                <img src="<?php echo $receiptPath; ?>" alt="Uploaded Receipt" class="uploaded-receipt">
            <?php endif; ?>
            
            <button class="back-btn" onclick="history.back()">Go Back</button>
        </div>
    </div>
</body>
<script>
    document.getElementById('receipt-upload').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('receipt-preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('upload-form').addEventListener('submit', function() {
        document.getElementById('loading-message').style.display = 'block';
    });
</script>
</html>
