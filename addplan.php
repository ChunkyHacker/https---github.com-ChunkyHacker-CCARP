<?php
// Get form data from POST request
$Name = $_POST['Name'];
$StartDate = $_POST['StartDate'];
$EndDate = $_POST['EndDate'];
$Description = $_POST['Description'];
$Status = $_POST['Status'];
$Budget = $_POST['Budget'];
$Location = $_POST['Location'];
$WorkersDetails = $_POST['WorkersDetails'];
$ClientDetails = $_POST['ClientDetails'];
$SupplierDetails = $_POST['SupplierDetails'];
$ProjectRequest = $_POST['ProjectRequest'];
$SummaryText = $_POST['SummaryText'];

// Database connection details
$host = "localhost";
$username = "root";
$password = "";
$dbname = "ccarpcurrentsystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL query
$sql = "INSERT INTO projects (Name, StartDate, EndDate, Description, Status, Budget, Location, WorkersDetails, ClientDetails, SupplierDetails, ProjectRequest, SummaryText)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Create a prepared statement
$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if (!$stmt) {
  echo "Error: " . $conn->error;
  exit();
}

// Bind parameters to the prepared statement
$stmt->bind_param(
  "ssssssssssss",
  $Name,
  $StartDate,
  $EndDate,
  $Description,
  $Status,
  $Budget,
  $Location,
  $WorkersDetails,
  $ClientDetails,
  $SupplierDetails,
  $ProjectRequest,
  $SummaryText
);

// Execute the query
if ($stmt->execute()) {
  echo "New record created successfully.";
} else {
  echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Redirect back to plan.php
header("Location: plan.php");
exit();
?>
