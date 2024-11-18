<?php 
  session_start();
  include('config.php');

  $requirement_ID = $_GET['requirement_ID'];
  $User_ID = $_GET['User_ID'];

  // Fetch user data
  $query1 = "SELECT * FROM users WHERE User_ID = '$User_ID'";
  $result1 = mysqli_query($conn, $query1);
  $userData = mysqli_fetch_assoc($result1);

  // Fetch labor data
  $query = "SELECT * FROM labor WHERE requirement_ID = '$requirement_ID'";
  $result = mysqli_query($conn, $query);
  $laborData = mysqli_fetch_assoc($result);
?>


<!DOCTYPE html>
<html lang="en">
<head>
<title>Pay Carpenter</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
  * {
    box-sizing: border-box;
  }

  /* Style the body */
  body {
    font-family: Arial, Helvetica, sans-serif;
    margin: 0;
    padding-top: 170px;
    font-size: 20px; /* Adjusted font size to 20px */
  }

  /* Header*/
  .header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    text-align: left;
    background: #FF8C00;
    color: #000000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-decoration: none;
  }

  /* Increase the font size of the heading */
  .header h1 {
    font-size: 40px;
    border-left: 20px solid transparent; 
    padding-left: 20px; /* Adjust padding */
    text-decoration: none;
  }

  .right {
    margin-right: 20px;
  }

  .header a{
    font-size: 20px; /* Adjusted font size to 20px */
    font-weight: bold;
    text-decoration: none;
    color: #000000;
  }

  .topnav {
    position: fixed;
    top: 120px; /* Adjust the top position as per your needs */
    width: 100%;
    overflow: hidden;
    background-color: #505050;
    z-index: 100;
  }

  /* Style the links inside the navigation bar */
  .topnav a {
    position: sticky;
    float: left;
    display: block;
    color: black;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
    font-size: 20px; /* Adjusted font size to 20px */
  }

  .topnav a,
  .topnav a.active {
    color: black;
  }

  .topnav a:hover,
  .topnav a.active:hover {
    background-color: #FF8C00;
    color: black;
  }

  /* When the screen is less than 600px wide, stack the links and the search field vertically instead of horizontally */
  @media screen and (max-width: 600px) {
    .topnav a, .topnav input[type=number], [type=text], select {
      float: none;
      display: block;
      text-align: left;
      width: 100%;
      margin: 0;
      padding: 14px;
    }
    .topnav input[type=number], [type=text], select {
      border: 1px solid #ccc;
    }
    body {
      font-family: Arial, Helvetica, sans-serif;
      margin: 0;
      padding-top: 300px;
    }
  }

  /* Main column */
  .main {   
    -ms-flex: 70%; /* IE10 */
    flex: 70%;
    background-color: white;
    padding: 20px;
    text-align: center;
  }

  .signup {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  form {
    border: 1px solid #ccc;
    padding: 20px;
    width: 600px;
  }

  label {
    display: block;
    margin-top: 10px;
    font-size: 20px; /* Adjusted font size to 20px */
  }

  input[type=number], [type=text], input[type=password], select {
    width: 100%;
    padding: 15px;
    margin: 5px 0 22px 0;
    display: inline-block;
    border: none;
    background: #f1f1f1;
    appearance: none; /* Remove default arrow */
    -webkit-appearance: none; /* Remove default arrow for Safari */
    -moz-appearance: none; /* Remove default arrow for Firefox */
    cursor: pointer; /* Show pointer cursor */
    border-radius: 5px; /* Add some border-radius for a softer look */
    font-size: 20px; /* Adjusted font size to 20px */
  }

  select option {
    background-color: #f1f1f1;
    padding: 12px;
  }

  input[type=number]:focus, [type=text], [type=text], input[type=password]:focus {
    background-color: #ddd;
    outline: none;
  }

  hr {
    border: 1px solid #f1f1f1;
    margin-bottom: 25px;
  }

  /* Set a style for all buttons */
  button {
    background-color: #04AA6D;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
    opacity: 0.9;
    font-size: 20px; /* Adjusted font size to 20px */
  }

  button:hover {
    opacity:1;
  }

  /* Extra styles for the cancel button */
  .cancelbtn {
    padding: 14px 20px;
    background-color: #f44336;
    font-size: 20px; /* Adjusted font size to 20px */
  }

  /* Float cancel and signup buttons and add an equal width */
  .cancelbtn, .signupbtn {
    float: left;
    width: 50%;
  }

  /* Add padding to container elements */
  .container {
    padding: 16px;
  }

  /* Clear floats */
  .clearfix::after {
    content: "";
    clear: both;
    display: table;
  }

  /* Change styles for cancel button and signup button on extra small screens */
  @media screen and (max-width: 300px) {
    .cancelbtn, .signupbtn {
      width: 100%;
    }
  }

  /* Footer */
  .footer {
    padding: 10px;
    text-align: center;
    background: #FF8C00;
    position: relative;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    font-size: 20px; /* Adjusted font size to 20px */
  }

  /* Responsive layout - when the screen is less than 700px wide, make the two columns stack on top of each other instead of next to each other */
  @media screen and (max-width: 700px) {
    .row {   
      flex-direction: column;
    }
  }

  /* Responsive layout - when the screen is less than 400px wide, make the navigation links stack on top of each other instead of next to each other */
  @media screen and (max-width: 400px) {
    .navbar a {
      float: none;
      width: 100%;
    }
  }
</style>


</head>
<body>
  <div class="header">
      <a href="comment.php">
          <h1>
              <img src="assets/img/logos/logo.png" alt=""  style="width: 75px; margin-right: 10px;">
          </h1>
      </a>
      <div class="right">
          <a href="logout.php" style="text-decoration: none; color: black; margin-right: 20px;">Log Out</a>
      </div>
  </div>

  <div class="topnav">
      <a class="active" href="comment.php">Home</a>
      <a href="#about">About</a>
      <a href="#contact">Contact</a>
      <a href="#">Project</a>
  </div>

    <div class="signup">
        <form action="payingcarpenter.php" method="post" enctype="multipart/form-data">
            <div class="container">
                <hr>
                <label for="carpenter_name"><b>Carpenter Name</b></label>
                <input type="text" name="carpenter_name"  
                      value="<?php echo isset($laborData['carpenter_name']) ? $laborData['carpenter_name'] : ''; ?>" 
                      readonly>

                <label for="Netpay"><b>Net pay (Pesos)</b></label>
                <input type="text" name="Netpay" 
                      value="<?php echo isset($laborData['total_of_laborcost']) ? $laborData['total_of_laborcost'] : ''; ?>" 
                      readonly>

                <label for="Days_Of_Work"><b>Days of Work</b></label>
                <input type="text"  name="Days_Of_Work"  
                      value="<?php echo isset($laborData['days_of_work']) ? $laborData['days_of_work'] : ''; ?>" 
                      readonly>

                <label for="Rate_per_day"><b>Rate per day (Pesos)</b></label>
                <input type="text"  name="Rate_per_day"  
                      value="<?php echo isset($laborData['rate']) ? $laborData['rate'] : ''; ?>" 
                      readonly>

                <label for="overall_cost"><b>Overall Cost</b></label>
                <input type="text"  name="overall_cost"  
                      value="<?php echo isset($laborData['overall_cost']) ? $laborData['overall_cost'] : ''; ?>" 
                      readonly>

                <label for="payment_method"><b>Payment Method</b></label><br>
                <select id="payment_method" name="payment_method" required>
                    <option value="" disabled selected>Select a payment method</option> <!-- Default option prompting selection -->
                    <option value="cash on hand">Cash on hand</option>
                    <option value="creditcard">Credit Card</option>
                    <option value="Gcash">Gcash</option>
                </select>

                <!-- Hidden input field for requirement_ID -->
                <label for="sender"><b>Sender</b></label>
                <input type="text" name="sender" value="<?php echo isset($userData['First_Name']) ? $userData['First_Name'] : ''; ?> <?php echo isset($userData['Last_Name']) ? $userData['Last_Name'] : ''; ?>" readonly>

                <div class="clearfix">
                <button type="button" class="cancelbtn" onclick="history.back()">Go Back</button>
                    <button type="submit" class="signupbtn">Submit</button>
                </div>
            </div>
        </form>
    </div>


  <div class="footer">
      <h2>E-Panday all rights reserved @2023</h2>
      <a href="#">Link 1</a> |
      <a href="#">Link 1</a> |
      <a href="#">Link 1</a> |
      <a href="#">Link 1</a>
  </div>
</body>
</html>
  