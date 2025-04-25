<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Prepare delete query
    $deleteSql = "DELETE FROM prematerialsinventory WHERE prematerialsinventory_id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $id);
    
    if ($deleteStmt->execute()) {
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
                          text: 'Item deleted successfully!',
                          confirmButtonColor: '#FF8C00',
                          timer: 3000,
                          timerProgressBar: true
                      }).then(function() {
                          window.location.href = 'admin_view_inventory.php';
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
                          text: 'Error deleting item.',
                          confirmButtonColor: '#FF8C00',
                          timer: 3000,
                          timerProgressBar: true
                      }).then(function() {
                          window.location.href = 'admin_view_inventory.php';
                      });
                  </script>
              </body>
              </html>";
        exit();
    }
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
                      text: 'Invalid request.',
                      confirmButtonColor: '#FF8C00',
                      timer: 3000,
                      timerProgressBar: true
                  }).then(function() {
                      window.location.href = 'admin_view_inventory.php';
                  });
              </script>
          </body>
          </html>";
    exit();
}

$conn->close();
?>
