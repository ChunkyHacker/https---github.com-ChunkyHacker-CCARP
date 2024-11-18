<?php
    include('config.php');
?>

<!DOCTYPE html>
<html lang="en">
<script src="process1\materialscheckbox.js"></script>
<script src="process1\housetype.js"></script>
<script src="process1\estimatedcost.js"></script>
<script src="process1\squaremeter.js"></script>
<script src="process1\projectdate.js"></script>
<style>
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  body {
    font-family: Verdana, sans-serif;
    margin: 0;
    background-color: #FF8C00;
    font-size: 20px; /* Default font size */
  }

  table {
    display: none;
    border-collapse: collapse;
    width: 100%;
  }

  th, td {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
    font-size: 20px; /* Updated font size */
  }

  /* Header */
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
    align-items: center;
    text-decoration: none;
    z-index: 100;
  }

  /* Increase the font size of the heading */
  .header h1 {
    font-size: 20px; /* Updated font size */
    border-left: 20px solid transparent; 
    text-decoration: none;
  }

  .right {
    margin-right: 20px;
  }

  .header a {
    font-size: 20px; /* Updated font size */
    font-weight: bold;
    text-decoration: none;
    color: #000000;
    margin-right: 15px;
  }

  .row-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
  }

  .input-container {
    flex: 1;
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
      font-size: 20px; /* Updated font size */
    }
  }

  /* CSS styles for the modal */
  .modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(255, 140, 0, 0.4); /* Updated background color */
  }

  .modal-content {
    background-color: #FF8C00; /* Updated background color */
    margin: 15% auto;
    padding: 20px;
    width: 80%;
    overflow-y: auto;
    border-radius: 8px;
  }

  /* Header */
  .header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    padding: 10px;
    text-align: left;
    background: #FF8C00; /* Updated background color */
    color: #000000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-decoration: none;
    z-index: 100;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }

  .modal-content {
    background-color: #fefefe;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }

  h2 {
    font-size: 20px; /* Updated font size */
    margin-bottom: 20px;
    color: #FF8C00;
  }

  form {
    display: flex;
    flex-direction: column;
  }

  label {
    font-size: 20px; /* Updated font size */
    margin-bottom: 5px;
    color: #000000;
  }

  input,
  select,
  textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 20px; /* Updated font size */
  }

  .post-btn, .cancel-btn {
    margin-bottom: 10px; /* Adjust the margin as needed */
  }

  .cancel-btn {
    background-color: red;
    color: #fff;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 10px;
    text-decoration: none;
    display: inline-block;
    text-align: center; /* Center the text */
    width: 100%; /* Make the button full-width */
    font-size: 20px; /* Updated font size */
  }

  .cancel-btn:hover {
    background-color: #000000;
  }

  button {
    background-color: #FF8C00;
    color: #fff;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 20px; /* Updated font size */
  }

  button:hover {
    background-color: #000000;
  }

  @media screen and (max-width: 600px) {
    .modal-content {
      width: 100%;
    }
  }
</style>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Construction Plan</title>
</head>
<div class="header">
        <a href="comment.php">
            <h1>
                <img src="assets/img/logos/logo.png" alt="" style="width: 75px; margin-right: 10px;">
            </h1>
        </a>
        <div class="right">
            <a href="login.html" style="text-decoration: none; color: black; margin-right: 20px;">Log Out</a>
            <a class="active" href="usercomment.php">Home</a>
            <a href="about/index.html">About</a>
            <a href="#contact">Get Ideas</a>
            <a href="plan.php">Project</a>
        </div>
</div>
<body>

<div class="modal-content">
    <h2>Create Construction Plan</h2>
    <form id="postForm" enctype="multipart/form-data" method="POST" action="plan.php">
      <input type="hidden" id="User_ID" name="User_ID" value="<?php echo $_SESSION['User_ID']; ?>">

        <h3>Lot Area</h3>
        <div class="row-container">
          <div class="input-container">
              <label for="square_meter">Length</label>
              <input type="number" id="length_lot_area" name="length_lot_area" placeholder="Enter square meter" oninput="calculateLotArea()">
          </div>

          <div class="input-container">
              <label for="square_meter">Width</label>
              <input type="number" id="width_lot_area" name="width_lot_area" placeholder="Enter square meter" oninput="calculateLotArea()">
          </div>

          <div class="input-container">
              <label for="square_meter">Square Meter(Sq):</label>
              <input type="number" id="square_meter_lot" name="square_meter_lot" placeholder="" readonly>
          </div>
      </div>

      <h3>Floor Area</h3>
      <div class="row-container">
          <div class="input-container">
              <label for="square_meter">Length</label>
              <input type="number" id="length_floor_area" name="length_floor_area" placeholder="Enter square meter" oninput="calculateFloorArea()">
          </div>

          <div class="input-container">
              <label for="floor_area">Width</label>
              <input type="number" id="width_floor_area" name="width_floor_area" placeholder="Enter square meter" oninput="calculateFloorArea()">
          </div>

          <div class="input-container">
              <label for="square_meter">Square Meter(Sq):</label>
              <input type="number" id="square_meter_floor" name="square_meter_floor" placeholder="" readonly>
          </div>
      </div>
      <div class="row-container">
          <div class="input-container">
              <div id="estimated_budget">
                  <h3>Initial Budget</h3>
                  <label for="initial_budget">Initial Budget (Pesos ₱):</label>
                  <input type="text" name="initial_budget" id="initial_budget" oninput="calculateEstimatedCost()">
              </div>
          </div>
      </div>
      <div class="row-container">
        <div class="input-container">
          <h3>Project Dates</h3>

          <label for="start_date">Start date:</label>
          <input type="date" id="start_date" name="start_date">
        </div>

        <div class="input-container">
          <h3>&nbsp;</h3> <!-- Empty space for visual separation -->

          <label for="end_date">End date:</label>
          <input type="date" id="end_date" name="end_date" oninput="validateDates()">
        </div>
      </div>

      <div id="house_type">
        <h2>Project Details</h2>
        <div>
          <p>Do you want to build a house? (Select One)</p>
          <select id="houseType" name="type">
            <option value="N/A" selected disabled>Select a House Type</option>
            <option value="The Modern House">The Modern House</option>
            <option value="Apartment House">Apartment House</option>
            <option value="Mansion" >Mansion</option>
            <option value="Bungalow">Bungalow</option>
            <option value="Farm House">Farm House</option>
            <option value="Penthouse">Penthouse</option>
            <option value="Single Family">Single Family</option>
            <option value="Condominium">Condominium</option>
            <option value="Container House">Container House</option>
            <option value="Duplex">Duplex</option>
            <option value="Single Attached Two Storey">Single Attached Two Storey</option>
            <option value="Townhouse">Townhouse</option>
            <option value="Villa">Villa</option>
            <option value="Multi-Storey House">Multi-Storey House</option>
            <option value="Amakan House">Amakan House</option>
            <option value="Commercial house or building">Commercial house or building</option>
            <option value="Warehouse">Warehouse</option>
            <option value="custom">Other (Please Specify)</option>
          </select>
          <div id="customOptionContainer" style="display:none;">
            <label for="customOption">Specify Other:</label>
            <input type="text" id="customOption" name="type" placeholder="Type your option here">
          </div>
        </div>
      </div>


      <!-- Photo Upload -->
      <label for="photo">Upload Photo: (Optional)</label>
      <input type="file" id="Photo" name="Photo"><br>

      <!-- New "More Details" Field -->
      <label for="more_details">More Details:</label>
      <textarea id="more_details" name="more_details" rows="4" placeholder="Provide additional details about your project..."></textarea>

      <!-- Form Buttons -->
      <button type="submit" class="post-btn">Submit</button>
      <a href="usercomment.php" class="cancel-btn">Cancel</a>
      <!-- Materials -->
    </form>
  </div>
</div>
</body>
</html>

