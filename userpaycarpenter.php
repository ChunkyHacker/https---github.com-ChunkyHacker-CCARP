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

    // Check if we have a valid contract_ID
    if (isset($_GET['contract_ID'])) {
        $contract_ID = $_GET['contract_ID'];
    } else {
        echo "Contract ID is not set.";
        exit();
    }

    // Debugging: Display User_ID and Contract_ID
    echo "<script>console.log('Debug: User_ID = $user_ID, Contract_ID = $contract_ID');</script>";

    // Fetch contract details including Duration & Carpenter_ID
    $contractQuery = "SELECT labor_cost, duration, Carpenter_ID FROM contracts WHERE Contract_ID = ? AND (User_ID = ? OR Carpenter_ID = ?)";
    $contractStmt = mysqli_prepare($conn, $contractQuery);
    mysqli_stmt_bind_param($contractStmt, "iii", $contract_ID, $user_ID, $user_ID);
    mysqli_stmt_execute($contractStmt);
    $contractResult = mysqli_stmt_get_result($contractStmt);
    $contract = mysqli_fetch_assoc($contractResult);

    // Check if contract exists
    if (!$contract) {
        echo "<script>console.log('Debug: No matching contract found for User_ID = $user_ID, Contract_ID = $contract_ID');</script>";
        echo "<p>No contract found for this user.</p>";
        exit();
    }

    // Get labor cost & duration
    $labor_cost = $contract['labor_cost'];
    $duration = $contract['duration'];
    $carpenter_ID = $contract['Carpenter_ID'];

    // Fetch carpenter details
    $carpenterQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
    $carpenterStmt = $conn->prepare($carpenterQuery);
    $carpenterStmt->bind_param("i", $carpenter_ID);
    $carpenterStmt->execute();
    $carpenterResult = $carpenterStmt->get_result();
    $carpenter = $carpenterResult->fetch_assoc();
    $carpenter_name = $carpenter ? $carpenter['First_Name'] . " " . $carpenter['Last_Name'] : "Unknown Carpenter";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Pay Carpenter</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
<!-- Add Sidebar Navigation -->
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


    <div class="signup">
      <form action="payingcarpenter.php" method="post" enctype="multipart/form-data">
        <div class="container">
            <hr>

            <!-- Contract ID (Hidden) -->
            <input type="hidden" name="Contract_ID" value="<?php echo $contract_ID; ?>">

            <!-- Carpenter ID (Hidden) -->
            <input type="hidden" name="Carpenter_ID" value="<?php echo $carpenter_ID; ?>">

            <label for="carpenter_name"><b>Carpenter Name</b></label>
            <input type="text" name="carpenter_name" value="<?php echo $carpenter_name; ?>" readonly>

            <label for="Days_Of_Work"><b>Duration</b></label>
            <input type="text" name="Duration" value="<?php echo $duration; ?>" readonly>

            <label for="overall_cost"><b>Labor Cost</b></label>
            <input type="text" name="Labor_Cost" value="<?php echo $labor_cost; ?>" readonly>

            <label for="Payment_Method"><b>Payment Method</b></label><br>
            <select id="Payment_Method" name="Payment_Method" required>
                <option value="" disabled selected>Select a payment method</option>
                <option value="cash on hand">Cash on hand</option>
                <option value="creditcard">Credit Card</option>
                <option value="Gcash">Gcash</option>
            </select>

            <label for="Payment_Date"><b>Payment Date</b></label>
            <input type="text" name="Payment_Date" value="<?php echo date('Y-m-d'); ?>" readonly>

            <!-- Sender (User) -->
            <input type="hidden" name="User_ID" value="<?php echo $user_ID; ?>">

            <label for="sender"><b>Sender</b></label>
            <input type="text" value="<?php echo $userDetails['First_Name'] . ' ' . $userDetails['Last_Name']; ?>" readonly>

            <div class="clearfix">
                <button type="button" class="cancelbtn" onclick="history.back()">Go Back</button>
                <button type="submit" class="signupbtn">Submit</button>
            </div>
        </div>
      </form>
    </div>
</body>
</html>
  