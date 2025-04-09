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

// Get the admin ID from the URL
if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];

    // Fetch the admin details from the database
    $sql = "SELECT * FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
} else {
    die("Admin ID not specified.");
}

// Handle the form submission for editing admin details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($firstname) || empty($lastname) || empty($username) || empty($password)) {
        die("All fields are required.");
    }

    // Prepare and execute the SQL query to update admin details
    $sql_update = "UPDATE admin SET firstname = ?, lastname = ?, username = ?, password = ? WHERE admin_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssi", $firstname, $lastname, $username, $password, $admin_id);

    if ($stmt_update->execute()) {
        echo "<script>alert('Admin updated successfully!'); window.location.href = 'manage_users.php';</script>";
    } else {
        echo "Error: " . $stmt_update->error;
    }
    $stmt_update->close();
}

// Close the database connection
$conn->close();
?>

<!-- HTML Form for editing admin details -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Edit Admin Details</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $admin['firstname']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $admin['lastname']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $admin['username']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" value="<?php echo $admin['password']; ?>" required>
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
