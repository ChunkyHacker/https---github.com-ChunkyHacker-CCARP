<!DOCTYPE html>
<html lang="en">
<head>
    <title> Receipt</title>
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

    <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
        <script>
            window.onload = function() {
                alert("✔ Transaction successful!");
            };
        </script>
    <?php endif; ?>

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
        
        <img id="receipt-preview" src="#" alt="Receipt Preview" style="display: none;">

        <?php if (!empty($_GET['receiptPath'])): ?>
            <img class="uploaded-receipt" src="<?php echo $_GET['receiptPath']; ?>" alt="Uploaded Receipt">
            <br>
            <button class="back-btn" onclick="history.back()">Go Back</button>
        <?php endif; ?>
    </div>

    <div class="footer"> 
        <h2>CCarp all rights reserved @2023</h2>
        <a href="#">Home</a> |
        <a href="#">About</a> |
        <a href="#">Contact</a> |
        <a href="#">Report</a>
    </div>

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
    </script>
</body>
</html>
