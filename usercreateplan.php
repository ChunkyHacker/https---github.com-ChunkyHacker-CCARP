<?php
  session_start(); 
  include('config.php');

  if (!isset($_SESSION['User_ID'])) {
    header('Location: login.html');
    exit();
  }

  // Query to get user information
  $user_id = $_SESSION['User_ID'];
  $query = "SELECT First_Name, Last_Name, User_ID, Photo FROM users WHERE User_ID = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<script src="process1\materialscheckbox.js"></script>
<script src="process1\housetype.js"></script>
<script src="process1\estimatedcost.js"></script>
<script src="process1\squaremeter.js"></script>
<script src="process1\projectdate.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: Verdana, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    font-size: 18px;
  }

  /* Sidebar Styles */
  .sidebar {
    background-color: #FF8600;
    height: 100vh;
    padding: 15px;
    position: fixed;
    left: 0;
    top: 0;
    width: 250px;
    overflow-y: auto;
  }

  .profile-section {
    text-align: center;
    padding: 20px;
    border-bottom: 1px solid rgba(0,0,0,0.1);
  }

  .profile-section img {
    width: 150px;
        height: 150px;
        border-radius: 10px;
        margin: 10px auto;
        display: block;
  }

  .profile-section h3 {
    font-size: 18px;
    margin-bottom: 5px;
  }

  .profile-section p {
    font-size: 14px;
    color: #333;
  }

  .sidebar a {
    color: #000000;
    padding: 8px 15px;
    margin: 2px 0;
    text-decoration: none;
    display: flex;
    align-items: center;
    transition: background-color 0.3s;
  }

  .sidebar a:hover {
    background-color: #000000;
    color: white;
  }

  /* Main Content Styles */
  .main-content {
    margin-left: 250px;
    width: calc(100% - 250px);
    padding: 20px;
  }

  .modal-content {
    background-color: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin: 20px auto;
    max-width: 1200px;
  }

  /* Form Styles */
  h2 {
    color: #FF8C00;
    margin-bottom: 30px;
  }

  .row-container {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
  }

  .input-container {
    flex: 1;
  }

  label {
    display: block;
    margin-bottom: 8px;
    color: #333;
  }

  input, select, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
  }

  button, .cancel-btn {
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 20px;
  }

  .post-btn {
    background-color: #FF8C00;
    color: white;
  }

  .cancel-btn {
    background-color: #dc3545;
    color: white;
    text-decoration: none;
    display: inline-block;
    text-align: center;
  }

  button:hover, .cancel-btn:hover {
    opacity: 0.9;
  }

  @media screen and (max-width: 768px) {
    .sidebar {
      width: 100%;
      height: auto;
      position: relative;
    }
    
    .main-content {
      margin-left: 0;
      width: 100%;
    }
    
    .row-container {
      flex-direction: column;
    }
  }
</style>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Construction Plan</title>
</head>
<div class="sidebar">
    <div class="profile-section">
        <img src="<?php echo $user['Photo'] ? $user['Photo'] : 'assets/img/logos/logo.png'; ?>" alt="Profile Picture">
        <h3><?php echo $user['First_Name'] . ' ' . $user['Last_Name']; ?></h3>
        <p>User ID: <?php echo $user['User_ID']; ?></p>
    </div>
    <a href="usercomment.php" class="nav-link">
            <i class="fa fa-home"></i>
            <span>Home</span>
    </a>
    <a href="about/index.html" class="nav-link">
            <i class="fa fa-info-circle"></i>
            <span>About</span>
    </a>
    <a href="#contact" class="nav-link">
            <i class="fa fa-lightbulb-o"></i>
            <span>Get Ideas</span>
    </a>
    <a href="plan.php" class="nav-link"> 
            </i><i class="fa fa-file"></i>
            <span> Project</span>
    </a>
    <a href="userprofile.php" class="nav-link">
            <i class="fa fa-user"></i>
            <span>Profile</span>
    </a>
    <a href="logout.php" class="nav-link">
            <i class="fa fa-sign-out"></i>
            <span>Logout</span>
    </a>
</div>

<div class="main-content">
    <div class="modal-content">
        <h2>Create Construction Plan</h2>
        <form id="postForm" enctype="multipart/form-data" method="POST" action="creatingplan.php">
            <input type="hidden" id="User_ID" name="User_ID" value="<?php echo $_SESSION['User_ID']; ?>">

            <h3>Lot Area</h3>
            <div class="row-container">
                <div class="input-container">
                    <label for="square_meter">Length</label>
                    <input type="number" id="length_lot_area" name="length_lot_area" placeholder="Enter square meter" oninput="calculateLotArea()" required>
                </div>
                <div class="input-container">
                    <label for="square_meter">Width</label>
                    <input type="number" id="width_lot_area" name="width_lot_area" placeholder="Enter square meter" oninput="calculateLotArea()" required>
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
                    <input type="number" id="length_floor_area" name="length_floor_area" placeholder="Enter square meter" oninput="calculateFloorArea()" required>
                </div>
                <div class="input-container">
                    <label for="floor_area">Width</label>
                    <input type="number" id="width_floor_area" name="width_floor_area" placeholder="Enter square meter" oninput="calculateFloorArea()" required>
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
                        <input type="text" name="initial_budget" id="initial_budget" oninput="calculateEstimatedCost()" required>
                    </div>
                </div>
            </div>

            <div class="row-container">
                <div class="input-container">
                    <h3>Project Dates</h3>
                    <label for="start_date">Start date:</label>
                    <input type="date" id="start_date" name="start_date" required>
                </div>
                <div class="input-container">
                    <h3>&nbsp;</h3> <!-- Empty space for visual separation -->
                    <label for="end_date">End date:</label>
                    <input type="date" id="end_date" name="end_date" oninput="validateDates()" required>
                </div>
            </div>

            <div id="house_type">
                <div>
                    <p>Do you want to build a house? (Select One)</p>
                    <select id="houseType" name="type" required>
                        <option value="N/A" selected disabled>Select a House Type</option>
                        <option value="The Modern House">The Modern House</option>
                        <option value="Apartment House">Apartment House</option>
                        <option value="Mansion">Mansion</option>
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

            <!-- New "Carpenter Limit" Field -->
            <div class="row-container">
                <div class="input-container">
                    <h3>Carpenter Limit</h3>
                    <label for="carpenter_limit">Maximum Number of Carpenters Allowed:</label>
                    <input type="number" id="carpenter_limit" name="carpenter_limit" placeholder="Enter limit" required>
                </div>
            </div>

            <!-- Photo Upload -->
            <label for="photo">Upload Photo: (Optional)</label>
            <input type="file" id="Photo" name="Photo" required><br>

            <!-- New "More Details" Field -->
            <label for="more_details">More Details:</label>
            <textarea id="more_details" name="more_details" rows="4" placeholder="Provide additional details about your project..." required></textarea>

            <!-- Form Buttons -->
            <button type="submit" class="post-btn">Submit</button>
            <a href="usercomment.php" class="cancel-btn">Cancel</a>
        </form>
    </div>
</div>

</body>
<script>
document.getElementById("houseType").addEventListener("change", function() {
    var customContainer = document.getElementById("customOptionContainer");
    var customInput = document.getElementById("customOption");

    if (this.value === "custom") {
        customContainer.style.display = "block";
        customInput.setAttribute("required", "required"); // Make input required
    } else {
        customContainer.style.display = "none";
        customInput.removeAttribute("required"); // Remove required if not needed
    }
});
</script>
</html>

