<?php
// Include the database configuration file
include 'config.php';

// Check if user ID is provided in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prepare the DELETE query to remove the user from the database
    $sql = "DELETE FROM users WHERE User_ID = ?";

    // Prepare the statement and execute it
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    // Check if the query executed successfully
    if ($stmt->execute()) {
        echo "<!DOCTYPE html>
              <html>
              <head>
                  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              </head>
              <body>
                  <script>
                      Swal.fire({
                          icon: 'success',
                          title: 'Success!',
                          text: 'User deleted successfully!',
                          confirmButtonColor: '#FF8C00',
                          timer: 3000,
                          timerProgressBar: true
                      }).then(function() {
                          window.location.href = 'manage_users.php';
                      });
                  </script>
              </body>
              </html>";
        exit();
    } else {
        echo "<!DOCTYPE html>
              <html>
              <head>
                  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              </head>
              <body>
                  <script>
                      Swal.fire({
                          icon: 'error',
                          title: 'Error!',
                          text: 'Error deleting user: " . $stmt->error . "',
                          confirmButtonColor: '#FF8C00',
                          timer: 3000,
                          timerProgressBar: true
                      }).then(function() {
                          window.location.href = 'manage_users.php';
                      });
                  </script>
              </body>
              </html>";
        exit();
    }
    $stmt->close();
} else {
    echo "No user ID provided.";
}

// Close the database connection
$conn->close();
?>
