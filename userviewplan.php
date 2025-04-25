<?php
session_start();
include('config.php');

if (!isset($_SESSION['User_ID'])) {
    header('Location: login.html');
    exit();
}

$User_ID = $_SESSION['User_ID'];

// Get user details
$sql = "SELECT * FROM users WHERE User_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $User_ID);
$stmt->execute();
$result = $stmt->get_result();
$userDetails = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Plan Details</title>
<style>
    * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: Verdana, sans-serif;
    margin: 0;
    background-color: white;
    font-size: 20px;
}

.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin-left: 250px;
    padding: 20px;
    height: auto;
    background-color: white;
}

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

.header h1 {
    font-size: 20px; /* Adjusted font size */
    border-left: 20px solid transparent;
    text-decoration: none;
}

.right {
    margin-right: 20px;
}

.header a {
    font-size: 20px; /* Adjusted font size */
    font-weight: bold;
    text-decoration: none;
    color: #000000;
    margin-right: 15px;
}

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
        font-size: 20px; /* Adjusted font size */
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
    background-color: rgba(255, 140, 0, 0.4);
}

.modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-height: 80%;
    overflow-y: auto;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

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
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.modal-content {
    background-color: white;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

h2 {
    font-size: 20px; /* Adjusted font size */
    margin-bottom: 20px;
    color: #FF8C00;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    font-size: 20px; /* Adjusted font size */
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
    font-size: 20px; /* Adjusted font size for form elements */
}

.post-btn, .cancel-btn {
    margin-bottom: 10px;
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
    text-align: center;
    width: 100%;
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
    font-size: 20px; /* Adjusted font size */
}

button:hover {
    background-color: #000000;
}

@media screen and (max-width: 600px) {
    .modal-content {
        width: 100%;
    }
}

/* Add new navigation styles */
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
    padding: 20px;
    margin-bottom: 20px;
}

.sidenav .profile-section img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin-bottom: 10px;
}

.sidenav a {
    padding: 10px 15px;
    text-decoration: none;
    font-size: 18px;
    color: #000000;
    display: block;
    transition: 0.3s;
}

.sidenav a:hover {
    background-color: #000000;
    color: #FF8C00;
}

/* Modify main content area */
.main-content {
    margin-left: 250px;
    padding: 20px;
}

.grid-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    padding: 20px;
}

.grid-item {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.placeholder-img {
    width: 100px;
    height: 100px;
    background-color: #ddd;
    border-radius: 50%;
    margin: 0 auto;
    display: block;
}

/* Remove the fixed header styles as we're using sidenav */
.header {
    display: none;
}
</style>
</head>
<body>
<!-- Add navigation sidebar -->
<div class="sidenav">
<div class="profile-section">
        <?php
        // Display profile picture
        if (isset($userDetails['Photo']) && !empty($userDetails['Photo'])) {
            echo '<img src="' . $userDetails['Photo'] . '" class="profile-image" alt="Profile Picture">';
        } else {
            echo '<img src="assets/img/default-avatar.png" class="profile-image" alt="Default Profile Picture">';
        }
        
        // Display name and ID
        echo "<h3>" . $userDetails['First_Name'] . ' ' . $userDetails['Last_Name'] . "</h3>";
        echo "<p>User ID: " . $User_ID . "</p>";
        ?>
    </div>
    <a href="usercomment.php"><i class="fas fa-home"></i> Home</a>
    <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
    <a href="getideas.php"><i class="fas fa-lightbulb"></i> Get Ideas</a>
    <a href="project.php"><i class="fas fa-project-diagram"></i> Project</a>
    <a href="userprofile.php"><i class="fas fa-user"></i> Profile</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="container">
    <div class="modal-content">
    <?php
include('config.php');

if (!isset($_GET['plan_ID']) || empty($_GET['plan_ID'])) {
    die("<div class='main'><p>Plan ID is missing.</p></div>");
}

$plan_ID = intval($_GET['plan_ID']); // Ensure it's a valid number

// Fetch Plan Details
$query = "SELECT * FROM plan WHERE plan_ID = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $plan_ID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    echo "<div class='main'>";
    echo "<h1>Client's Plan Details</h1>";

    // Fetch Client Name
    $userId = $row['User_ID'];
    $userQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
    $userStmt = mysqli_prepare($conn, $userQuery);
    mysqli_stmt_bind_param($userStmt, "i", $userId);
    mysqli_stmt_execute($userStmt);
    $userResult = mysqli_stmt_get_result($userStmt);
    $userRow = mysqli_fetch_assoc($userResult);

    $clientName = ($userRow) ? "{$userRow['First_Name']} {$userRow['Last_Name']}" : "Unknown Client";

    // Fetch user details
    $userDetailsQuery = "SELECT Photo FROM users WHERE User_ID = ?";
    $userDetailsStmt = mysqli_prepare($conn, $userDetailsQuery);
    mysqli_stmt_bind_param($userDetailsStmt, "i", $userId);
    mysqli_stmt_execute($userDetailsStmt);
    $userDetailsResult = mysqli_stmt_get_result($userDetailsStmt);
    $userDetails = mysqli_fetch_assoc($userDetailsResult);

    // Organize content in grid
    echo "<div class='grid-container'>";
    
    // Client Info
    echo "<div class='grid-item'>";
    echo "<h3>Client Information</h3>";

    // Display user photo
    if (!empty($userDetails['Photo'])) {
        echo "<img src='" . $userDetails['Photo'] . "' style='width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin: 10px auto; display: block;'>";
    } else {
        echo "<img src='assets/img/default-avatar.png' style='width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin: 10px auto; display: block;'>";
    }

    echo "<label>Client Name:</label>";
    echo "<input type='text' value='{$clientName}' readonly><br>";
    echo "</div>";

    // Lot Area
    echo "<div class='grid-item'>";
    echo "<h3>Lot Area</h3>";
    echo "<label>Length:</label> <input type='text' value='{$row['length_lot_area']}' readonly>";
    echo "<label>Width:</label> <input type='text' value='{$row['width_lot_area']}' readonly>";
    echo "<label>Square Meter:</label> <input type='text' value='{$row['square_meter_lot']}' readonly>";
    echo "</div>";

    // Floor Area
    echo "<div class='grid-item'>";
    echo "<h3>Floor Area</h3>";
    echo "<label>Length:</label> <input type='text' value='{$row['length_floor_area']}' readonly>";
    echo "<label>Width:</label> <input type='text' value='{$row['width_floor_area']}' readonly>";
    echo "<label>Square Meter:</label> <input type='text' value='{$row['square_meter_floor']}' readonly>";
    echo "</div>";

    echo "<h3>Project Budget</h3>";
    echo "<label>Initial Budget:</label> <input type='text' value='{$row['initial_budget']}' readonly>";

    // Project Dates section
    echo "<div class='grid-item'>";
    echo "<h3>Project Dates</h3>";
    echo "<div style='margin-bottom: 15px;'>";
    echo "<label>Start Date:</label>";
    echo "<input type='text' value='{$row['start_date']}' readonly style='margin-bottom: 10px;'>";
    echo "</div>";
    
    echo "<div style='margin-bottom: 15px;'>";
    echo "<label>End Date:</label>";
    echo "<input type='text' value='{$row['end_date']}' readonly>";
    echo "</div>";
    echo "</div>";

    // More Details section
    echo "<div class='grid-item'>";
    echo "<h3>More Details</h3>";
    echo "<textarea readonly style='width: 100%; min-height: 100px; padding: 10px; resize: none;'>{$row['more_details']}</textarea>";
    echo "</div>";

    echo "</div>"; // Close grid-container

    // Buttons container in a new section below the grid
    echo "<div style='display: flex; justify-content: flex-start; gap: 20px; margin-top: 30px; margin-left: 20px;'>";
    
    // Go back button
    echo "<button onclick=\"window.location.href='userprofile.php'\" 
          style='width: 150px; height: 50px; background-color: #FF8C00; color: white; 
          border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>
          Go back</button>";
    
    // Delete button with SweetAlert2 confirmation
    echo "<button onclick=\"Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to delete this plan?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#FF8C00',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Delete',
        timer:3000,
        showConfirmButton: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href='delete_approval.php?plan_ID=" . $plan_ID . "';
        }
    })\" style='width: 150px; height: 50px; background-color: red; color: white; 
        border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>
        Delete</button>";
    
    echo "</div>";
} else {
    echo "<div class='main'>";
    echo "<p>No approved plan found with Plan ID: $plan_ID</p>";
    echo "</div>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
</div>
</div>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</body>
<script>
function openModal(planID) {
    // Fetch the list of approved carpenters
    fetch("get_approved_carpenters.php?plan_id=" + planID)
    .then(response => response.text())
    .then(data => {
        document.getElementById("carpenterList").innerHTML = data;
        document.getElementById("carpenterModal").style.display = "block";
    });
}

function closeModal() {
    document.getElementById("carpenterModal").style.display = "none";
}

// Close the modal if the user clicks outside of it
window.onclick = function(event) {
    var modal = document.getElementById("carpenterModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>

</html>

