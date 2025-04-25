<?php

include('config.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $phonenumber = $_POST['number'] ?? '';  
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $dateofbirth = $_POST['dateofbirth'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Simple file upload handling
    $targetDir = "assets/user/";
    $targetFile = $targetDir . basename($_FILES["userPhoto"]["name"]);

    if (move_uploaded_file($_FILES["userPhoto"]["tmp_name"], $targetFile)) {
        // File successfully uploaded
        $photoPath = $targetFile;

        // Debug: Log $photoPath to a file
        error_log("Photo Path: " . $photoPath);

    } else {
        // Error uploading file
        $photoPath = "";
    }

    $query = "INSERT INTO users (First_Name, Last_Name, Phone_Number, Email, Address, Date_Of_Birth, Username, Password, Photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssssss", $firstname, $lastname, $phonenumber, $email, $address, $dateofbirth, $username, $password, $photoPath);

        echo "<!DOCTYPE html>
              <html>
              <head>
                  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              </head>
              <body>";

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Account created successfully!',
                        icon: 'success',
                        timer: 3000,
                        showConfirmButton: true
                    }).then(function() {
                        window.location.href = 'login.html';
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Failed to create account: " . mysqli_error($conn) . "',
                        icon: 'error',
                        timer: 3000,
                        showConfirmButton: true
                    }).then(function() {
                        window.history.back();
                    });
                  </script>";
        }

        echo "</body></html>";
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        exit;
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
                          text: 'Database error: " . mysqli_error($conn) . "',
                          icon: 'error',
                          timer: 3000,
                          showConfirmButton: true
                      }).then(function() {
                          window.history.back();
                      });
                  </script>
              </body>
              </html>";
        mysqli_close($conn);
        exit;
    }
}
?>
