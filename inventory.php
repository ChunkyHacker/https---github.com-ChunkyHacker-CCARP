<!DOCTYPE html>
<html lang="en">
<head>
<title>Products</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="process4\search.js"></script>
<script src="process4\edititem.js"></script>
<style>
  * {
    box-sizing: border-box;
  }

  /* Style the body */
  body {
    font-family: Arial, Helvetica, sans-serif;
    margin: 0;
    padding-top: 180px;
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
  }

  #addItemBtn {
    background-color: #FF8C00;
    color: #FFFFFF;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    margin: 20px;
    border-radius: 4px;
  }

  #addItemBtn:hover {
    background-color: #FFA500;
  }

  /* AddItemModal Styles */
  #addItemModal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
  }

  .modal-content {
    background-color: #f2f2f2;
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 70%;
    border-radius: 5px;
  }

  .close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
  }

  .close:hover,
  .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }

  /* Center the modal form */
  .modal-content {
    position: relative;
    text-align: center;
  }

  .modal-content h2 {
    margin-bottom: 20px;
  }

  .modal-content form div {
    margin-bottom: 15px;
  }

  .modal-content form label {
    display: block;
    font-size: 16px;
    font-weight: bold;
    text-align: left;
    margin-bottom: 5px;
  }

  .modal-content form input,
  .modal-content form textarea,
  .modal-content form select {
    width: 100%;
    padding: 8px;
    font-size: 16px;
    color: #000;
    background-color: #fff;
    border-radius: 4px;
    border: 1px solid #ccc;
  }

  .modal-content form select {
    width: 100%;
  }

  .modal-content form button {
    background-color: #FF8C00;
    color: #FFFFFF;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 4px;
  }

  .modal-content form button:hover {
    background-color: #FFA500;
  }

  /* Modal Styles */
  .editModal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: none;
    z-index: 1001; /* Ensure a higher z-index than the table */
  }

  .edit-modal-content {
    background-color: #f2f2f2;
    padding: 30px;
    border: 1px solid #888;
    max-width: 70%; /* Use max-width instead of width */
    width: auto; /* Adjusted width property */
    border-radius: 5px;
    position: absolute; /* Use absolute positioning */
    top: 50%; /* Set top to 50% */
    left: 50%; /* Set left to 50% */
    transform: translate(-50%, -50%); /* Center the modal */
  }

  /* Main column */
  .main {   
    -ms-flex: 70%; /* IE10 */
    flex: 70%;
    background-color: white;
    padding: 20px;
    text-align: center;
  }

  /*Sort*/
  .sort {
    display: inline-block;
    margin-bottom: 10px;
    margin-left: 40px;
  }

  .sort select {
    padding: 8px;
    font-size: 16px;
    color: #000000;
    background-color: #ffffff;
    border-radius: 4px;
  }

  .sort select:focus {
    outline: none;
    box-shadow: 0 0 8px 0 rgba(0, 0, 0, 0.2);
  }

  /* Table styling */
  .table-container {
    border-radius: 8px;
    overflow: hidden;
    margin-top: 20px; /* Adjusted margin-top for spacing */
    margin-bottom: 40px; /* Added margin-bottom for space below the table */
    padding:50px;
  }

  .styled-table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid #333;
  }

  .table-header {
    padding: 12px;
    text-align: center;
    background-color: #FF8C00; /* Orange color */
    color: black; /* Text color */
    font-weight: bold;
    border-bottom: 1px solid #333;
  }

  .table-cell {
    padding: 2px;
    text-align: center;
    background-color: #f2f2f2;
    border-bottom: 1px solid #333;
  }

  .table-cell img {
    max-width: 100px;
    max-height: 100px;
    border-radius: 4px;
  }

  .styled-table tr {
    border-bottom: 1px solid #333; /* Added border for table rows */
  }

  .styled-table th, .styled-table td {
    border-right: 1px solid #333; /* Added border for table columns */
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
    body {
    font-family: Arial, Helvetica, sans-serif;
    margin: 0;
    padding-top: 300px;
  }
  }

  /* Responsive layout - when the screen is less than 400px wide, make the navigation links stack on top of each other instead of next to each other */
  @media screen and (max-width: 400px) {
    .navbar a {
      float: none;
      width: 100%;
    }
  }

  @media (max-width: 768px) {
    .product-card {
      width: calc(50% - 20px);
    }
  }

  @media (max-width: 480px) {
    .product-card {
      width: calc(100% - 20px);
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

  <h2 style="text-align:center">Inventory</h2>

  <div class="search-container">
      <input type="text" id="searchInput" oninput="handleSearch()" placeholder="Search...">
      <button onclick="handleSearch()">Search</button>
  </div>

  <button id="addItemBtn">Add Item</button>

  <div id="addItemModal" class="modal">
          <div class="modal-content">
              <span class="close">&times;</span>
              <h2>Add Item</h2>
              <form id="addItemForm" method="post" action="additem.php" enctype="multipart/form-data" >
                  <div>
                      <label for="item_name">Item</label>
                      <input type="text" id="itemname" name="itemname" required>
                  </div>
                  <div>
                      <label for="image">Image</label>
                      <input type="file" id="itemimage" name="image" accept="image/*" required>
                  </div>
                  <div>
                      <label for="quantity">Quantity</label>
                      <input type="number" id="quantity" name="quantity" required>
                  </div>
                  <div>
                      <label for="price">Price:</label>
                      <input type="number" id="price" name="price" step="0.01" min="0" required>
                  </div>
                  <div>
                      <label for="type">Type</label>
                      <select id="type" name="type">
                          <option value="Equipment">Equipment</option>
                          <option value="Tools">Tools</option>
                          <option value="Materials">Materials</option>
                      </select>
                  </div>
                  <button type="submit">Add Item</button>
              </form>
          </div>
      </div>

  <div class="sort">
    <label for="filterType">Filter by Type:</label>
    <select id="filterType" onchange="handleSortChange(this)">
      <option value="select">All</option>
      <option value="equipment">Equipment</option>
      <option value="tools">Tools</option>
      <option value="materials">Materials</option>
    </select>
  </div>



  <div class="product-container">
  <?php
  include('config.php');

    // Fetch the search query
    $searchQuery = isset($_GET['searchInput']) ? $_GET['searchInput'] : '';

    $sql = "SELECT * FROM items";

    // Add a WHERE clause to filter results based on the search query
    if (!empty($searchQuery)) {
        $sql .= " WHERE itemname LIKE '%$searchQuery%' OR type LIKE '%$searchQuery%'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<div class="table-container">';
        echo '    <table class="styled-table">';
        echo '        <tr>';
        echo '            <th class="table-header">Image</th>';
        echo '            <th class="table-header">Item Name</th>';
        echo '            <th class="table-header">Type</th>';
        echo '            <th class="table-header">Price</th>';
        echo '            <th class="table-header">Quantity</th>';
        echo '            <th class="table-header">Action</th>';
        echo '        </tr>';

        while ($row = $result->fetch_assoc()) {
            $itemname = $row["itemname"];
            $quantity = $row["quantity"];
            $price = $row["price"];
            $type = $row["type"];
            $itemimage = $row["itemimage"];

            echo '        <tr>';
            echo '            <td class="table-cell"><img src="assets/items/' . $itemimage . '" alt="' . $itemname . '"></td>';
            echo '            <td class="table-cell"><h3>' . $itemname . '</h3></td>';
            echo '            <td class="table-cell"><h3>' . $type . '</h3></td>';
            echo '            <td class="table-cell"><h3>₱' . $price . '</h3></td>';
            echo '            <td class="table-cell"><h3>' . $quantity . '</h3></td>';
            echo '            <td class="table-cell">';
            echo '                <button onclick="openEditModal()">Edit</button>'; // Edit button
            echo '                <button onclick="deleteItem()">Delete</button>'; // Delete button
            echo '            </td>';            
            echo '        </tr>';
        }

        echo '    </table>';
        echo '</div>';
    } else {
        echo '<p>No items found.</p>';
    }

    $conn->close();
  ?>
  </div>

  <!-- Modal for Edit -->
  <div id="editModal" class="editModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1001;">
    <div class="edit-modal-content">
      <span class="close" onclick="closeEditModal()">&times;</span>
      <h2>Edit Item</h2>
      <form id="editForm" action="edit_item.php" method="post">
        <!-- Edit form fields go here -->
        <input type="hidden" name="item_id" id="editItemId" value="">
        <label for="editItemName">Item Name:</label>
        <input type="text" name="editItemName" id="editItemName" required>
        <label for="editPrice">Price:</label>
        <input type="text" name="editPrice" id="editPrice" required>
        <label for="editType">Type:</label>
        <input type="text" name="editType" id="editType" required>
        <label for="editQuantity">Quantity:</label>
        <input type="text" name="editQuantity" id="editQuantity" required>
        <button type="button" onclick="saveChanges()">Save Changes</button>
      </form>
    </div>
  </div>

  <div class="footer">
      <h2>E-Panday all rights reserved @2023</h2>
      <a href="#">Link 1</a> |
      <a href="#">Link 1</a> |
      <a href="#">Link 1</a> |
      <a href="#">Link 1</a>
  </div>
</body>

<script>
        var modal = document.getElementById('addItemModal');
        var btn = document.getElementById('addItemBtn');
        var span = document.getElementsByClassName('close')[0];

        btn.onclick = function() {
            modal.style.display = 'block';
        }

        span.onclick = function() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
</script>
</html>
  