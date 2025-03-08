<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Account</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .signup-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 600px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 150px;
            height: 150px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="password"],
        input[type="tel"],
        input[type="date"],
        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 5px;
        }

        input[type="file"] {
            padding: 8px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.3s;
        }

        .btn-cancel {
            background-color: #dc3545;
            color: white;
        }

        .btn-signup {
            background-color: #FF8C00;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .terms {
            text-align: center;
            margin: 20px 0;
            font-size: 14px;
        }

        .terms a {
            color: #FF8C00;
            text-decoration: none;
        }

        .terms a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <div class="logo-container">
            <img src="assets/img/logos/logo.png" alt="CCARP Logo" class="logo">
        </div>
        
        <h1>Create User Account</h1>
        
        <form action="adduser.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>

            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>

            <div class="form-group">
                <label for="phonenumber">Phone Number</label>
                <input type="tel" id="phonenumber" name="number" required pattern="[0-9]{10}">
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" required>
            </div>

            <div class="form-group">
                <label for="dateofbirth">Date of Birth</label>
                <input type="date" id="dateofbirth" name="dateofbirth" required>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="userPhoto">Profile Photo</label>
                <input type="file" id="userPhoto" name="userPhoto" required>
            </div>

            <div class="terms">
                By creating an account you agree to our <a href="#">Terms & Privacy</a>.
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-cancel" onclick="window.location.href='accountselect.html'">Cancel</button>
                <button type="submit" class="btn btn-signup">Sign Up</button>
            </div>
        </form>
    </div>
</body>
</html>
