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
    $sql = "SELECT * FROM carpenter WHERE Carpenter_ID = ?";
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
    $skills = trim($_POST['skills']);

    // Validate inputs
    if (empty($firstname) || empty($lastname) || empty($phone) || empty($email) || empty($skills)) {
        die("All fields are required.");
    }

    // Prepare and execute the SQL query to update carpenter details
    $sql_update = "UPDATE carpenter SET First_Name = ?, Last_Name = ?, Phone = ?, Email = ?, Skills = ? WHERE Carpenter_ID = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssssi", $firstname, $lastname, $phone, $email, $skills, $carpenter_id);

    if ($stmt_update->execute()) {
        echo "<script>alert('Carpenter updated successfully!'); window.location.href = 'manage_carpenters.php';</script>";
    } else {
        echo "Error: " . $stmt_update->error;
    }
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
    <div class="container">
        <h2>Edit Carpenter Details</h2>
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
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $carpenter['Phone']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $carpenter['Email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="skills" class="form-label">Skills</label>
                <textarea class="form-control" id="skills" name="skills" rows="3" required><?php echo $carpenter['Skills']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
