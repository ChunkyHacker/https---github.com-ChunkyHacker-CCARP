<!DOCTYPE html>
<html lang="en">
<head>
<title>Check out</title>
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
    font-size: 17px;
    margin: 0;
    padding-top: 170px;
  }

  .row {
    display: -ms-flexbox; /* IE10 */
    display: flex;
    -ms-flex-wrap: wrap; /* IE10 */
    flex-wrap: wrap;
    margin: 0 -16px;
  }

  .col-25 {
    -ms-flex: 25%; /* IE10 */
    flex: 25%;
  }

  .col-50 {
    -ms-flex: 50%; /* IE10 */
    flex: 50%;
  }

  .col-75 {
    -ms-flex: 75%; /* IE10 */
    flex: 75%;
  }

  .col-25,
  .col-50,
  .col-75 {
    padding: 0 16px;
  }

  .container {
    font-size: 20px;
    background-color: #f2f2f2;
    margin-top: 20px;
    margin-left: 50px;
    margin-bottom: 20px;
    padding: 20px 20px 2px 30px;
    border: 1px solid lightgrey;
    border-radius: 3px;
  }

  .payment-method {
    font-size: 20px;      /* Adjust the font size of the dropdown */
    padding: 10px;        /* Add padding for better clickability */
    width: 400px;         /* Adjust width of the dropdown */
    border-radius: 5px;   /* Rounded corners for the dropdown */
    border: 1px solid #ccc; /* Light border for the dropdown */
  }

  input[type=text] {
    margin-bottom: 20px;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 3px;
  }

  label {
    margin-bottom: 10px;
    display: block;
  }

  .icon-container {
    margin-bottom: 20px;
    padding: 7px 0;
    font-size: 24px;
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

  a {
    color: #2196F3;
  }

  hr {
    border: 1px solid lightgrey;
  }

  span.price {
    float: right;
    color: grey;
  }

  .coupon-container {
              text-align: center;
              padding: 20px;
          }

          .coupon-select {
              padding: 10px;
              font-size: 16px;
          }

          .coupon-input {
              padding: 10px;
              font-size: 16px;
          }

          .coupon-button {
              padding: 10px 20px;
              font-size: 16px;
              background-color: #4CAF50;
              color: white;
              border: none;
              cursor: pointer;
          }

  /* Responsive layout - when the screen is less than 800px wide, make the two columns stack on top of each other instead of next to each other (also change the direction - make the "cart" column go on top) */
  @media (max-width: 800px) {
    .row {
      flex-direction: column-reverse;
    }
    .col-25 {
      margin-bottom: 20px;
    }
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

  .total_price {
    font-size: 20px; /* Adjust the font size */
    color: black;    /* Text color */
    padding: 10px;   /* Add some padding to the input */
    width: 150px;    /* Adjust the width of the input field */
    text-align: right; /* Align text to the right */
    border: 1px solid #ccc; /* Add a border */
    border-radius: 5px; /* Optional: Rounded corners */
}

.total-price-container {
    margin-top: 20px; /* Adjust the top margin */
    padding-left: 45px;
    text-align: left; /* Center align the text */
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
      <a class="active" href="index.html">Home</a>
      <a href="#about">About</a>
      <a href="#contact">Contact</a>
  </div>


  <h2 style="margin-left: 20px;">Checkout</h2>
  <div>
    <div>
      <?php
          include('config.php');

          // Query for the first table (prematerials)
          $query_materials = "SELECT * FROM prematerials";
          $stmt_materials = mysqli_prepare($conn, $query_materials);
          mysqli_stmt_execute($stmt_materials);
          $result_materials = mysqli_stmt_get_result($stmt_materials);

          $totalprice_materials = 0;

          // Calculate total price from materials
          while ($material_row = mysqli_fetch_assoc($result_materials)) {
              // Add each total to the total sum
              $totalprice_materials += $material_row['total'];
          }

          // Query for the second table (requiredmaterials)
          $query_second_table = "SELECT * FROM requiredmaterials";
          $stmt_second_table = mysqli_prepare($conn, $query_second_table);
          mysqli_stmt_execute($stmt_second_table);
          $result_second_table = mysqli_stmt_get_result($stmt_second_table);

          $totalprice_second_table = 0;

          // Calculate total price from requiredmaterials
          while ($second_row = mysqli_fetch_assoc($result_second_table)) {
              // Add each total to the total sum
              $totalprice_second_table += $second_row['total'];
          }

          // Calculate combined total
          $totalprice_combined = $totalprice_materials + $totalprice_second_table;
      ?>
  <div>
      <div class="row">
          <div class="col-75">
              <form id="transactionform" enctype="multipart/form-data" method="POST" action="transaction.php">
                  <!-- First Table (prematerials) -->
                  <div class="col-25">
                      <div class="container">
                          <h4>Cart <span class="price" style="color:black"><i class="fa fa-shopping-cart"></i></span></h4>
                          <table style="border-collapse: collapse; width: 100%;">
                              <thead>
                                  <tr>
                                      <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Part</th>
                                      <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Materials</th>
                                      <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Name</th>
                                      <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Quantity</th>
                                      <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Price</th>
                                      <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Total</th>
                                      <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Estimated Cost</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php
                                  // Loop through the results and populate the first table (prematerials)
                                  mysqli_data_seek($result_materials, 0);

                                  while ($material_row = mysqli_fetch_assoc($result_materials)) {
                                      echo '<tr>
                                              <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['part']) . '</td>
                                              <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['materials']) . '</td>
                                              <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['name']) . '</td>
                                              <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['quantity']) . '</td>
                                              <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['price']) . '</td>
                                              <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['total']) . '</td>
                                              <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($material_row['estimated_cost']) . '</td>
                                          </tr>';
                                  }
                                  ?>
                              </tbody>
                          </table>
                      </div>
                  </div>

                  <!-- Second Table (requiredmaterials) -->
                  <div class="container">
                      <h4>Required Materials</h4>
                      <table style="border-collapse: collapse; width: 100%;">
                          <thead>
                              <tr>
                                  <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Material</th>
                                  <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Type</th>
                                  <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Image</th>
                                  <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Quantity</th>
                                  <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Price</th>
                                  <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Total</th>
                                  <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Material Overall Cost</th>
                              </tr>
                          </thead>
                          <tbody>
                              <?php
                              // Loop through the results and populate the second table (requiredmaterials)
                              mysqli_data_seek($result_second_table, 0);

                              while ($second_row = mysqli_fetch_assoc($result_second_table)) {
                                echo '<tr>
                                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($second_row['material']) . '</td>
                                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($second_row['type']) . '</td>';
                                
                                // Check if the image path exists and is valid
                                $image_path = htmlspecialchars($second_row['image']);
                                echo '<td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">';
                                
                                if (!empty($image_path) && file_exists($image_path)) {
                                    echo '<img src="' . $image_path . '" alt="Material Image" style="max-width: 100px; max-height: 100px;">';
                                } else {
                                    // Display a default image if the path is invalid or empty
                                    echo '<img src="assets/default-product.png" alt="Default Image" style="max-width: 100px; max-height: 100px;">';
                                }
                                
                                echo '</td>
                                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($second_row['quantity']) . '</td>
                                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($second_row['price']) . '</td>
                                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($second_row['total']) . '</td>
                                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($second_row['materials_overall_cost']) . '</td>
                                      </tr>';
                            }
                            
                              ?>
                          </tbody>
                      </table>
                  </div>

                  <!-- Total Price Section -->
                  <hr>
                  <div class="total-price-container">
                      <p>Total (₱)
                          <input type="number" class="total_price" name="total_price" value="<?php echo $totalprice_combined; ?>" readonly>
                      </p>
                  </div>

                  <!-- Payment Method Section -->
                  <div class="container">
                      <h4>Payment method</h4>
                      <select id="payment-method" class="payment-method" name="payment_method">
                          <option value="" selected disabled>Select Payment Method</option>
                          <option value="Cash">Cash</option>
                          <option value="Online Payment">Online Payment</option>
                      </select>
                      <br>
                      <br>
                      <p id="payment-message"></p>
                  </div>



                  <!-- Continue to Checkout Button -->
                  <input type="submit" value="Continue to checkout" class="btn">
              </form>
          </div>
      </div>
  </div>

  <div class="footer">
      <h2>CCarp all rights reserved @2023</h2>
      <a href="#">Home</a> |
      <a href="#">About</a> |
      <a href="#">Contact</a> |
      <a href="#">Report</a>
  </div>
</body>
</html>
  