<?php
    session_start(); 
    include('config.php');

    if (!isset($_SESSION['Carpenter_ID'])) {
    header('Location: login.html');
    exit();
    }
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
            font-size: 17px;
            margin: 0;
            padding-top: 170px;
        }

        .container {
            font-size: 20px;
            background-color: #f2f2f2;
            margin: 20px 50px;
            padding: 20px 30px 20px;
            border: 1px solid lightgrey;
            border-radius: 3px;
            text-align: center;
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

        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 20px;
            background: #FF8C00;
            color: #000000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 100;
        }

        .header .nav-container {
            display: flex;
            align-items: center;
        }

        .header img {
            width: 75px;
            margin-right: 20px;
        }

        .topnav {
            display: flex;
            align-items: center;
        }

        .topnav a {
            color: black;
            padding: 14px 20px;
            text-decoration: none;
            display: inline-block;
            font-weight: bold;
        }

        .topnav a:hover {
            background-color: #ddd;
            color: black;
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
          max-width: 80vw; /* Limit width to 80% of screen */
          max-height: 60vh; /* Limit height to 60% of screen */
          width: auto;
          height: auto;
          display: block;
          margin: 10px auto; /* Centers the image horizontally */
          object-fit: contain; /* Ensures proper scaling */
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
    </style>
</head>
<body>
    <div class="header">
        <div class="nav-container">
            <a href="comment.php">
                <img src="assets/img/logos/logo.png" alt="">
            </a>
            <div class="topnav">
                <a class="active" href="index.html">Home</a>
                <a href="#about">About</a>
                <a href="#contact">Contact</a>
            </div>
        </div>
        <div class="right">
            <a href="logout.php" style="text-decoration: none; color: black; margin-right: 20px; font-weight: bold;">Log Out</a>
        </div>
    </div>

    <div class="container">
        <h2>Upload Receipt</h2>
        <form action="transaction.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="receipt" id="receipt-upload" accept="image/*" required>
            <button type="submit" class="btn">Upload</button>
        </form>
        
        <img id="receipt-preview" src="#" alt="Receipt Preview">
        
        <!-- PHP to display uploaded receipt -->
        <?php
        $receiptPath = ""; // Initialize empty receipt path
        if (isset($_GET['receipt'])) {
            $receiptPath = htmlspecialchars($_GET['receipt']); // Get receipt path from URL
        }
        ?>

        <?php if (!empty($receiptPath)): ?>
            <img src="<?php echo $receiptPath; ?>" alt="Uploaded Receipt" class="uploaded-receipt">
        <?php endif; ?>
    </div>
    <div>
    <button class="back-btn" onclick="history.back()">Go Back</button>
    </div>

    <div class="footer"> 
        <h2>CCarp all rights reserved @2023</h2>
        <a href="#">Home</a> |
        <a href="#">About</a> |
        <a href="#">Contact</a> |
        <a href="#">Report</a>
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
                preview.style.maxWidth = '80vw'; // Resize width
                preview.style.maxHeight = '60vh'; // Resize height
                preview.style.objectFit = 'contain'; // Ensure it scales properly
                preview.style.margin = '10px auto'; // Center the image
            }
            reader.readAsDataURL(file);
        }
    });
    </script>
</html>
