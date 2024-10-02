<!DOCTYPE html>
<html lang="en">
<head>
<title>Project Plan</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ccarpcurrentsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getProjects($conn)
{
    $sql = "SELECT * FROM projects";
    $result = $conn->query($sql);
    $projects = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
    }

    return $projects;
}

$projects = getProjects($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_plan"])) {
  $projectID = $_POST["projectID"];
  
  $query = "DELETE FROM projects WHERE projectID = ?";
  $stmt = mysqli_prepare($conn, $query);
  
  if (!$stmt) {
      die("Error in preparing the statement: " . mysqli_error($conn));
  }
  
  mysqli_stmt_bind_param($stmt, "i", $projectID);
  
  if (mysqli_stmt_execute($stmt)) {
      echo "Deletion successful";
  } else {
      echo "Deletion failed: " . mysqli_error($conn);
  }
  
  mysqli_stmt_close($stmt);
  
  header("Location: plan.php");
  exit;

  mysqli_close($conn);

}
?>
<style>
* {
  box-sizing: border-box;
}

body {font-family: Verdana, sans-serif; margin:0}
.mySlides {display: none}
img {vertical-align: middle;}

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

body {
  font-family: Arial, Helvetica, sans-serif;
  margin: 0;
  padding-top: 200px;
}


.main {
  flex: 70%;
  background-color: white;
  padding: 20px;
  text-align: center;
}

.container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}



/* Style for the modal */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto; /* Enable scroll if needed */
  background-color: rgba(0, 0, 0, 0.4); /* Black with transparency */
}

/* Modal content box */
.modal-content {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* Close button (x) */
.close {
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: red;
  cursor: pointer;
}

/* Additional CSS for the modal form fields */
.modal-content label {
  display: block;
  margin-top: 10px;
  font-weight: bold;
}

.modal-content input,
.modal-content textarea {
  width: 100%;
  padding: 8px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.modal-content textarea {
  resize: vertical; /* Allow vertical resizing of the textarea */
}

.modal-content input[type="submit"] {
  background-color: #4caf50;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.modal-content input[type="submit"]:hover {
  background-color: #45a049;
}






.planning-table {
  width: 400px;
  border-collapse: collapse;
  margin-top: 20px;
  text-align: center;
  margin: 0 auto;
  margin-bottom: 140px;
}

.planning-table th,
.planning-table td {
  padding: 10px;
  text-align: center;
  border: 1px solid #ccc;
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
        <a href="login.html" style="text-decoration: none; color: black; margin-right: 20px;">Log Out</a>
    </div>
</div>

<div class="topnav">
    <a class="active" href="comment.php">Home</a>
    <a href="about/index.html">About</a>
    <a href="#">Contact</a>
</div>

<div>
  <h2>Project Planner</h2>
  <button id="add-button">Add Plan</button>  
</div>

<div id="myModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <form id="plan-form" action="addplan.php" method="post">
      <label for="name">Name:</label>
      <input type="text" name="Name" required>


      <label for="StartDate">Start Date:</label>
      <input type="date" id="StartDate" name="StartDate" required>

      <label for="EndDate">End Date:</label>
      <input type="date" id="EndDate" name="EndDate" required>

      <label for="Description">Description:</label>
      <textarea id="Description" name="Description" required></textarea>

      <label for="Status">Status:</label>
      <select id="Status" name="Status" required>
        <option value="" disabled selected>Select status</option>
        <option value="Planning">Not Started</option>
        <option value="In Progress">In Progress</option>
        <option value="On hold">On hold</option>
        <option value="Completed">Completed</option>
        <option value="Delayed">Delayed</option>
        <option value="Cancelled">Cancelled</option>
        <option value="Pending Approval">Pending Approval</option>
        <option value="Under review">Under review</option>
        <option value="Behind Schedule">Behind Schedule</option>
        <option value="Over Budget">Over Budget</option>
        <option value="Finalizing Details">Finalizing Details</option>
        <option value="Ready for Inspection">Ready for Inspection</option>
      </select>

      <label for="Budget">Budget:</label>
      <input type="text" id="Budget" name="Budget" required>

      <label for="Location">Location:</label>
      <input type="text" id="Location" name="Location" required>

      <label for="WorkersDetails">Workers:</label>
      <input type="text" id="WorkersDetails" name="WorkersDetails" required>

      <label for="ClientDetails">Client:</label>
      <input type="text" id="ClientDetails" name="ClientDetails" required>

      <label for="SupplierDetails">Supplier:</label>
      <input type="text" id="SupplierDetails" name="SupplierDetails" required>

      <label for="ProjectRequest">Project Request:</label>
      <input type="text" id="ProjectRequest" name="ProjectRequest" required>

      <label for="SummaryText">Summary Text:</label>
      <textarea id="SummaryText" name="SummaryText" required></textarea>

      <input type="submit" value="Submit">
    </form>
  </div>
</div>

<table class="planning-table">
  <thead>
      <tr>
          <th>Date</th>
          <th>Name</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Description</th>
          <th>Status</th>
          <th>Budget</th>
          <th>Location</th>
          <th>Workers</th>
          <th>Client</th>
          <th>Supplier</th>
          <th>Project Request</th>
          <th>Summary Text</th>
          <th>Action</th>
      </tr>
  </thead>
  <tbody id="planning-results">
        <?php
        foreach ($projects as $project) {
            echo "<tr>";
            echo "<td>" . date('F j, Y, g:i a', strtotime($project['Datestamp'])) . "</td>";
            echo "<td>{$project['Name']}</td>";
            echo "<td>{$project['StartDate']}</td>";
            echo "<td>{$project['EndDate']}</td>";
            echo "<td>{$project['Description']}</td>";
            echo "<td>{$project['Status']}</td>";
            echo "<td>{$project['Budget']}</td>";
            echo "<td>{$project['Location']}</td>";
            echo "<td>{$project['WorkersDetails']}</td>";
            echo "<td>{$project['ClientDetails']}</td>";
            echo "<td>{$project['SupplierDetails']}</td>";
            echo "<td>{$project['ProjectRequest']}</td>";
            echo "<td>{$project['SummaryText']}</td>";
            echo "<td>
        <a href='editplan.php?projectID={$project['projectID']}' class='btn btn-sm btn-primary'>Edit</a>
        <form method='post' style='display: inline'>
            <input type='hidden' name='projectID' value='{$project['projectID']}'>
            <button type='submit' name='delete_plan' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this project?\")'>Delete</button>
        </form>
    </td>";
        
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<div class="footer">
    <h2>CCarp# all rights reserved @2023</h2>
    <a href="#">Link 1</a> |
    <a href="#">Link 1</a> |
    <a href="#">Link 1</a> |
    <a href="#">Link 1</a>
</div>
</body>
<script>
const modal = document.getElementById('myModal');

const addButton = document.getElementById('add-button');

const closeBtn = document.getElementsByClassName('close')[0];

addButton.addEventListener('click', () => {
  modal.style.display = 'block';
});

closeBtn.addEventListener('click', () => {
  modal.style.display = 'none';
});

window.addEventListener('click', (event) => {
  if (event.target === modal) {
    modal.style.display = 'none';
  }
});

</script>
</html>
  