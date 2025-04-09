<?php
    // Include the database configuration file
    include 'config.php';

    // Start session to check if the admin is logged in
    session_start();

    // Check if the admin is logged in
    if (!isset($_SESSION['admin_id'])) {
        // Redirect to login page if not logged in
        header("Location: adminlogin.html");
        exit();
    }

    // Fetch all users from the database
    $sql_users = "SELECT * FROM users";
    $result_users = $conn->query($sql_users);

    // Fetch all carpenters from the database
    $sql_carpenters = "SELECT * FROM carpenters";
    $result_carpenters = $conn->query($sql_carpenters);

    // Fetch all admins from the database
    $sql_admins = "SELECT * FROM admin";
    $result_admins = $conn->query($sql_admins);
?>

<!DOCTYPE html>
<html lang="en">
<!-- Add this style in the head section -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table-responsive-container {
            max-height: 400px;
            overflow-x: auto;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        
        .table {
            width: 100%;
            table-layout: auto;
        }
        
        .table td, .table th {
            white-space: nowrap;
            min-width: 100px;
        }
        
        .table td.actions {
            width: 160px;  /* Increased width for buttons */
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="admindashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="adminlogout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Manage Header -->
        <h2 class="text-center">Manage Admins, Users & Carpenters</h2>

        <!-- Admin Table -->
        <div class="card">
            <div class="card-body">
                <h4>Admins</h4>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Loop through each admin and display in the table
                        if ($result_admins->num_rows > 0) {
                            while($admin = $result_admins->fetch_assoc()) {
                                echo "<tr>
                                    <td>" . $admin['admin_id'] . "</td>
                                    <td>" . $admin['firstname'] . "</td>
                                    <td>" . $admin['lastname'] . "</td>
                                    <td>" . $admin['username'] . "</td>
                                    <td>" . $admin['password'] . "</td>
                                    <td>
                                        <a href='edit_admin.php?id=" . $admin['admin_id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                        <a href='delete_admin.php?id=" . $admin['admin_id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No admins found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-body">
                <h4>Users</h4>
                <div class="table-responsive-container">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Date of Birth</th>
                                <th>Username</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Loop through each user and display in the table
                            if ($result_users->num_rows > 0) {
                                while($user = $result_users->fetch_assoc()) {
                                    echo "<tr>
                                        <td>" . $user['User_ID'] . "</td>
                                        <td>" . $user['First_Name'] . "</td>
                                        <td>" . $user['Last_Name'] . "</td>
                                        <td>" . $user['Phone_Number'] . "</td>
                                        <td>" . $user['Email'] . "</td>
                                        <td>" . $user['Address'] . "</td>
                                        <td>" . $user['Date_Of_Birth'] . "</td>
                                        <td>" . $user['Username'] . "</td>
                                        <td>
                                            <a href='edit_user.php?id=" . $user['User_ID'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                            <a href='delete_user.php?id=" . $user['User_ID'] . "' class='btn btn-danger btn-sm'>Delete</a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9' class='text-center'>No users found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Carpenters Table -->
        <div class="card">
            <!-- In each card-body div, wrap the table with a responsive container -->
            <div class="card-body">
                <h4>Carpenters</h4>
                <div class="table-responsive-container">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Date of Birth</th>
                                <th>Experience</th>
                                <th>Projects Completed</th>
                                <th>Specialization</th>
                                <th>Username</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Loop through each carpenter and display in the table
                            if ($result_carpenters->num_rows > 0) {
                                while($carpenter = $result_carpenters->fetch_assoc()) {
                                    echo "<tr>
                                        <td>" . $carpenter['Carpenter_ID'] . "</td>
                                        <td>" . $carpenter['First_Name'] . "</td>
                                        <td>" . $carpenter['Last_Name'] . "</td>
                                        <td>" . $carpenter['Phone_Number'] . "</td>
                                        <td>" . $carpenter['Email'] . "</td>
                                        <td>" . $carpenter['Address'] . "</td>
                                        <td>" . $carpenter['Date_Of_Birth'] . "</td>
                                        <td>" . $carpenter['Experiences'] . "</td>
                                        <td>" . $carpenter['Project_Completed'] . "</td>
                                        <td>" . $carpenter['specialization'] . "</td>
                                        <td>" . $carpenter['username'] . "</td>
                                        <td class='actions'>
                                            <a href='edit_carpenter.php?id=" . $carpenter['Carpenter_ID'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                            <a href='delete_carpenter.php?id=" . $carpenter['Carpenter_ID'] . "' class='btn btn-danger btn-sm'>Delete</a>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='12' class='text-center'>No carpenters found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>

    <!-- Go Back Button -->
    <div class="container mb-4">
        <a href="admindashboard.php" class="btn btn-danger">Go Back</a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
