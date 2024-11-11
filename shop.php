<!DOCTYPE html>
<html lang="en">
<head>
<title>Shop</title>
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

.image-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  grid-gap: 20px;
  margin-bottom: 50px;
}

.card {
  margin: 50px; /* Adjust this margin value as needed */
}

.card img {
    width: 50%;
    max-height: 200px; /* Set the maximum height as needed */
}

.button {
    background-color: #FF8C00;
    text-decoration: none;
    color: black;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

.button a {
    text-decoration: none;
    color: black;
}

.button:hover {
    background-color: #FFA500;
}


.image-item {
  position: relative;
  flex: 0 0 calc(25% - 20px); /* Adjust the width as needed */
  margin-bottom: 20px;
}

.image-item img {
  width: 100%;
  display: block;
}

.image-text {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #ff8c0073;
  padding: 10px;
  text-align: center;
  max-width: 80%;
  transition: background-color 0.5s ease;
}

.image-text:hover {
    background-color: #0000003b;
}

.image-text a {
  color: #FFFFFF;
  font-size: 14px;
  text-decoration: none;
  overflow: hidden;
  text-overflow: ellipsis;
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
    <a href="about/index.html">About</a>
    <a href="#contact">Contact</a>
</div>

<div class="shop">
    <div class=""><h1>Partnered shops</h1></div>
    <div class="image-grid">
        <?php
        include('config.php');

        $sql = "SELECT * FROM shop";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
            echo '<div class="row">
            <div class="column">
              <div class="card">
                <img src="'. $row['Logo'] .'" alt="'. $row['Name'] .'">
                <div class="container">
                  <h2>'. $row['Name'] .'</h2>
                  <p class="title">'. $row['Shop_Description'] .'</p>
                  <p>'. $row['Email'] .'</p>
                  <p><button class="button"><a href="inventory.php">Contact '. $row['Name'] .'</a></button></p>
                </div>
              </div>
            </div>
          </div>';
          }
        } else {
          echo "No shop";
        }
        ?>
        <?php 

        ?>
    </div>
</div>
</body>
</html>
  