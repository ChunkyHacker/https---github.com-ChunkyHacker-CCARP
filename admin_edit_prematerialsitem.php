<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug: Check if $_POST data is received
    error_log("POST Data: " . print_r($_POST, true));
    
    if (!empty($_POST['id']) && !empty($_POST['structure']) && !empty($_POST['name']) && isset($_POST['price'])) {
        $id = $_POST['id'];
        $structure = $_POST['structure'];
        $name = $_POST['name'];
        $price = $_POST['price'];

        $updateSql = "UPDATE prematerialsinventory SET structure = ?, name = ?, price = ? WHERE prematerialsinventory_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssdi", $structure, $name, $price, $id);
        
        if ($updateStmt->execute()) {
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
                              text: 'Item updated successfully!',
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
                              text: 'Error updating item.',
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
                          text: 'Invalid input.',
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
                      text: 'Invalid request method.',
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
?>
