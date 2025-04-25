<?php
// Include the database configuration file
include 'config.php';

// Start session to check if the admin is logged in
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.html");
    exit();
}

// Get the carpenter ID from the URL
if (isset($_GET['id'])) {
    $carpenter_id = $_GET['id'];

    // Fetch the carpenter details from the database
    $sql = "SELECT * FROM carpenters WHERE Carpenter_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $carpenter_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $carpenter = $result->fetch_assoc();
} else {
    die("Carpenter ID not specified.");
}   

// Handle the form submission for editing carpenter details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $dob = trim($_POST['dob']);
    $experiences = trim($_POST['experiences']);
    $projects = trim($_POST['projects']);
    $specialization = trim($_POST['specialization']);

    // Validate inputs
    if (empty($firstname) || empty($lastname) || empty($phone) || empty($email) || empty($address) || empty($dob) || empty($experiences) || empty($projects) || empty($specialization)) {
        die("All fields are required.");
    }

    // Prepare and execute the SQL query to update carpenter details
    $sql_update = "UPDATE carpenters 
                   SET First_Name = ?, Last_Name = ?, Phone_Number = ?, Email = ?, Address = ?, Date_Of_Birth = ?, Experiences = ?, Project_Completed = ?, specialization = ? 
                   WHERE Carpenter_ID = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssssssssi", $firstname, $lastname, $phone, $email, $address, $dob, $experiences, $projects, $specialization, $carpenter_id);

    // Add SweetAlert2 in head section
    echo "<!DOCTYPE html>
          <html>
          <head>
              <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          </head>
          <body>";

    if ($stmt_update->execute()) {
        echo "<script>
                  Swal.fire({
                      icon: 'success',
                      title: 'Success!',
                      text: 'Carpenter updated successfully!',
                      confirmButtonColor: '#FF8C00',
                      timer: 3000,
                      timerProgressBar: true
                  }).then(function() {
                      window.location.href = 'manage_users.php';
                  });
              </script>";
        exit();
    } else {
        echo "<script>
                  Swal.fire({
                      icon: 'error',
                      title: 'Error!',
                      text: 'Failed to update carpenter: " . $stmt_update->error . "',
                      confirmButtonColor: '#FF8C00',
                      timer: 3000,
                      timerProgressBar: true
                  });
              </script>";
        exit();
    }
    echo "</body></html>";
    $stmt_update->close();
}

// Close the database connection
$conn->close();
?>

<!-- HTML Form for editing carpenter details -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Carpenter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Edit Carpenter Details</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $carpenter['First_Name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $carpenter['Last_Name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $carpenter['Phone_Number']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $carpenter['Email']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?php echo $carpenter['Address']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $carpenter['Date_Of_Birth']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="experiences" class="form-label">Experiences</label>
                        <input type="text" class="form-control" id="experiences" name="experiences" value="<?php echo $carpenter['Experiences']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="projects" class="form-label">Projects Completed</label>
                        <input type="text" class="form-control" id="projects" name="projects" value="<?php echo $carpenter['Project_Completed']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="specialization" class="form-label">Specialization</label>
                        <input type="text" class="form-control" id="specialization" name="specialization" value="<?php echo $carpenter['specialization']; ?>" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="manage_users.php" class="btn btn-danger ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
