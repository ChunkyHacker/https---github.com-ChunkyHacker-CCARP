<?php
session_start();
if (!isset($_SESSION['username']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit;
}

require_once "config.php";

// Function to get all users or filtered users based on search query
function getUsers($search = '')
{
    global $connection;
    $query = "SELECT * FROM credentials";

    if (!empty($search)) {
        $search = mysqli_real_escape_string($connection, $search);
        $query .= " WHERE username LIKE '%$search%' OR email LIKE '%$search%' OR role LIKE '%$search%'";
    }

    $result = mysqli_query($connection, $query);
    $users = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }

    return $users;
}

// Handle delete user request
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_user"])) {
    $logInID = $_POST["logInID"];

    // Perform the deletion
    $query = "DELETE FROM credentials WHERE logInID = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt,"i",$logInID);

    if (mysqli_stmt_execute($stmt)) {
        echo "Deletion successful";
    } else {
        echo "Deletion failed";
    }

    mysqli_stmt_close($stmt);

    header("Location: manageusers.php");
    exit;
}
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <!-- Use online hosted versions of Bootstrap and CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</head>
<body>
     <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Manage user</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="users.php">Manage Users</a>
                    </li>
                    <!-- Add more navigation links as needed -->
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Manage Users</h2>
        
        <!-- Search functionality -->
        <form method="get">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search users" name="search">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </div>
        </form>
        
        <!-- Display user table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>First Name</th>
					<th>Last Name</th>
                    <th>Email</th>
                    <th>Username</th>
					<th>Password</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Handle search query
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                $users = getUsers($search);

                foreach ($users as $user) {
                    echo "<tr>";
					echo "<td>{$user['firstname']}</td>";
					echo "<td>{$user['lastname']}</td>";
                    echo "<td>{$user['email']}</td>";
                    echo "<td>{$user['username']}</td>";
                    echo "<td>{$user['password']}</td>";
                    echo "<td>{$user['role']}</td>";
                    echo "<td>
                            <button class='btn btn-sm btn-primary' data-bs-toggle='modal' data-bs-target='#editModal{$user['logInID']}'>Edit</button>
<form method='post' style='display: inline'>
    <input type='hidden' name='logInID' value='{$user['logInID']}'> <!-- Make sure this field exists and contains the correct user ID -->
    <button type='submit' name='delete_user' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</button>
</form>

                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        
        <!-- Add new user button -->
<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add User</button>

<!-- Modal for adding a user -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Add the form for adding a user here -->
                <form id="addUserForm" action="adduser.php" method="POST">
                    <div class="mb-3">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" required>
                    </div>
					 <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
						<label for="role" class="form-label">Role</label>
						<select class="form-select" id="role" name="role" required>
							<option value="Administrator">Admin</option>
							<option value="User">User</option>
						</select>
					</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="addUser()">Add User</button>
            </div>
        </div>
    </div>
</div>

<script>
    function addUser() {
        // Get the form data
        const form = document.getElementById('addUserForm');
        const formData = new FormData(form);

        // Send an AJAX request to add the user
        fetch('adduser.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show a success message to the user
                alert('User added successfully!');
                // Reload the page to see the updated user table
                location.reload();
            } else {
                // Show an error message if something went wrong
                alert(data.message);
            }
        })
        .catch(error => {
            alert('User added successfully!');
            location.reload();
        });
    }
</script>

<!-- Modals for editing users -->
<?php
// Retrieve the users again for the edit modals
$logInID = getUsers($search);

foreach ($logInID as $user) {
    $logInID = $user['logInID'];
?>
    <div class="modal fade" id="editModal<?php echo $logInID; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Add the form for editing the user here -->
                    <form id="editUserForm<?php echo $logInID; ?>">
                        <input type="hidden" name="LogInID" value="<?php echo $logInID; ?>">
                        <div class="mb-3">
                        <label for="firstname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $user['firstname']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $user['lastname']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="<?php echo $user['password']; ?>" required>
                        </div>

                        <div class="mb-3">
							<label for="edit_role" class="form-label">Role</label>
							<select class="form-select" id="role" name="edit_role" required>
								<option value="Admin" <?php if ($user['role'] === 'Admin') echo 'selected'; ?>>Admin</option>
								<option value="User" <?php if ($user['role'] === 'User') echo 'selected'; ?>>User</option>
								<!-- Display the current value from the database as the default option -->
								<?php if ($user['role'] !== 'Admin' && $user['role'] !== 'User') : ?>
									<option value="<?php echo htmlspecialchars($user['role']); ?>" selected><?php echo htmlspecialchars($user['role']); ?></option>
								<?php endif; ?>
							</select>
						</div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateUser(<?php echo $logInID; ?>)">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>

<script>
function updateUser(logInID) {
    const form = document.getElementById(`editUserForm${logInID}`);
    const formData = new FormData(form);

    // Use FormData.get() to retrieve the LogInID and pass it in the request body
    const logInIDValue = formData.get('LogInID');
    formData.set('password', formData.get('password')); // Fix the key for the password field

    fetch('edituser.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.success) {
            alert('User updated successfully!');
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        alert('An error occurred while updating the user.');
        console.error(error);
    });
}
</script>


</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
        <!-- Modals for add and edit user -->
        <!-- The modals code goes here... -->
        
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
