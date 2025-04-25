<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
// Include the database configuration file
include 'config.php';

// Check if admin ID is provided in the URL
if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];

    // Prepare the DELETE query to remove the admin from the database
    $sql = "DELETE FROM admin WHERE admin_id = ?";

    // Prepare the statement and execute it
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);

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
                          title: 'Success!',
                          text: 'Admin deleted successfully!',
                          icon: 'success',
                          timer: 3000,
                          showConfirmButton: false
                      }).then(function() {
                          window.location.href = 'manage_users.php';
                      });
                  </script>
              </body>
              </html>";
    } else {
        echo "<!DOCTYPE html>
              <html>
              <head>
                  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              </head>
              <body>
                  <script>
                      Swal.fire({
                          title: 'Error!',
                          text: 'Failed to delete admin: " . $stmt->error . "',
                          icon: 'error',
                          timer: 3000,
                          showConfirmButton: false
                      }).then(function() {
                          window.location.href = 'manage_users.php';
                      });
                  </script>
              </body>
              </html>";
    }

    $stmt->close();
} else {
    echo "<!DOCTYPE html>
          <html>
          <head>
              <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          </head>
          <body>
              <script>
                  Swal.fire({
                      title: 'Error!',
                      text: 'No admin ID provided',
                      icon: 'error',
                      timer: 3000,
                      showConfirmButton: false
                  }).then(function() {
                      window.location.href = 'manage_users.php';
                  });
              </script>
          </body>
          </html>";
}

// Close the database connection
$conn->close();
?>
