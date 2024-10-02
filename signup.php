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
  padding: 10px;
  text-align: center;
  background: #FF8C00;
  position: relative;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 100%;
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
    <a href="index.html">
        <h1>
            <img src="assets/img/logos/logo.png" alt=""  style="width: 75px; margin-right: 10px;">
        </h1>
    </a>
</div>



<div class="signup">
      <form action="adduser.php" method="post" enctype="multipart/form-data">
        <div class="container">
            <h1>Sign Up</h1>
            <p>Please fill in this form to create an account.</p>
            <hr>

            <label for="firstname"><b>First Name</b></label>
            <input type="text" placeholder="Enter First Name" name="firstname" required>

            <label for="lastname"><b>Last Name</b></label>
            <input type="text" placeholder="Enter Last Name" name="lastname" required>

            <label for="phonenumber"><b> Phone Number</b></label>
            <input type="text" placeholder="Enter Number" name="number" required>

            <label for="email"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" name="email" required>

            <label for="address"><b>Address</b></label>
            <input type="text" placeholder="Address" name="address" required>

            <label for="DOB"><b>Date of Birth</b></label>
            <input type="date" placeholder="" name="dateofbirth" id="dateofbirth"required>

            <label for="username"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="username" required>

            <label for="password"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" required>

            <label for="userPhoto">Upload Photo:</label>
            <input type="file" id="userPhoto" name="userPhoto" required>
            <label>
                <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px"> Remember me
            </label>

            <p>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms & Privacy</a>.</p>

            <div class="clearfix">
                <button type="button" class="cancelbtn" onclick="window.location.href='accountselect.html'">Cancel</button>
                <button type="submit" class="signupbtn">Sign Up</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
