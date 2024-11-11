<!DOCTYPE html>
<html lang="en">
<head>
<title>Create Account</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $shopID = $_POST["Shop_ID"];
    $name = $_POST["Name"];
    $address = $_POST["Address"];
    $owner = $_POST["Owner"];
    $phone = $_POST["Phonenumber"];
    $email = $_POST["Email"];
    $description = $_POST["Shop_Description"];
    $username = $_POST ["username"];
    $password = $_POST ["password"];

    $uploadedImagePath = '';

    if (isset($_FILES['Logo']) && $_FILES['Logo']['error'] === UPLOAD_ERR_OK) {
    
        $targetDir = 'assets/shop';
        $uploadedFileName = uniqid() . '_' . basename($_FILES['Logo']['name']);
        $uploadedFilePath = $targetDir . '/' . $uploadedFileName;
    
        if (move_uploaded_file($_FILES['Logo']['tmp_name'], $uploadedFilePath)) {
            $uploadedImagePath = 'assets/shop/' . $uploadedFileName;
        } else {
            echo "There was an error uploading your file.";
        }
    }

    $sql = "INSERT INTO shop (Shop_ID, Name, Address, Owner, Phonenumber, Email, Shop_Description, Logo, username, password)
    VALUES ('$shopID', '$name', '$address', '$owner', '$phone', '$email', '$description', '$uploadedImagePath', '$username', '$password')"; 

    if ($conn->query($sql) === TRUE) {
        echo "Shop added successfully";
        header("Location: login.html");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<style>
* {
  box-sizing: border-box;
}

/* Style the body */
body {
  font-family: Arial, Helvetica, sans-serif;
  margin: 0;
  padding-top: 170px;
}

/* Header*/
.header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  padding: 10px;
  text-align: left;
  background: #FF8C00;
  color: #000000;
  display: flex;
  justify-content: space-between;
  align-items: center;
  text-decoration: none;
  z-index: 100;
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
  font-size: 25px;
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
  font-size: 30px;
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
  .topnav a, .topnav input[type=text] {
    float: none;
    display: block;
    text-align: left;
    width: 100%;
    margin: 0;
    padding: 14px;
  }
  .topnav input[type=text] {
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
  height: 100vh;
}

form {
  border: 1px solid #ccc;
  padding: 20px;
  width: 600px;
}



label {
  display: block;
  margin-top: 10px;
}

input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
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
}

button:hover {
  opacity:1;
}

/* Extra styles for the cancel button */
.cancelbtn {
  padding: 14px 20px;
  background-color: #f44336;
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
  padding: 20px;
  text-align: center;
  background: #FF8C00;
  left: 50%;
  transform: translateX(-50%);
  width: 100%;
  margin-left: -20px;
  margin-bottom: -20px;
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

<div class="signup">
  <form action="addshop.php" method="post" enctype="multipart/form-data">

    <label for="Name">Shop Name:</label>
    <input type="text" id="Name" name="Name" required><br>

    <label for="Logo">Logo:</label>
    <input type="file" id="Logo" name="Logo"><br>

    <label for="Address">Address:</label>
    <input type="text" id="Address" name="Address" required><br>

    <label for="Owner">Owner:</label>
    <input type="text" id="Owner" name="Owner" required><br>

    <label for="Phonenumber">Phone Number:</label>
    <input type="tel" id="Phonenumber" name="Phonenumber" required><br>

    <label for="Email">Email:</label>
    <input type="email" id="Email" name="Email" required><br>

    <label for="Shop_Description">Shop Description:</label>
    <textarea id="Shop_Description" name="Shop_Description" rows="4" required></textarea><br>

    <label for="username">Username</label>
    <input type="text" id="username" name="username" required><br>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" required><br>

    
    <input type="submit" value="Submit">
    <a href="index.html" class="cancel-button">Cancel</a>
  </form>
</div>
</body>
</html>
