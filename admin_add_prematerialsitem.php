<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["csv_file"])) {
    $file = $_FILES["csv_file"]["tmp_name"];

    if (($handle = fopen($file, "r")) !== FALSE) {
        fgetcsv($handle); // Skip CSV header row

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $structure = mysqli_real_escape_string($conn, $data[0]);
            $name = mysqli_real_escape_string($conn, $data[1]);
            $price = intval($data[2]);

            $sql = "INSERT INTO prematerialsinventory (structure, name, price) VALUES ('$structure', '$name', '$price')";
            mysqli_query($conn, $sql);
        }

        fclose($handle);
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
                          text: 'CSV file imported successfully!',
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
                          text: 'Error opening file.',
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
?>
