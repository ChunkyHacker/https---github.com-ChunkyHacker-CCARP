<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
    include('config.php');
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Queries for carpenters and users only
        $query_carpenters = "SELECT * FROM carpenters WHERE username = ?";
        $query_users = "SELECT * FROM users WHERE username = ?";

        // Prepare and execute query for carpenters
        $stmt_carpenters = mysqli_prepare($conn, $query_carpenters);
        mysqli_stmt_bind_param($stmt_carpenters, "s", $username);
        mysqli_stmt_execute($stmt_carpenters);
        $result_carpenters = mysqli_stmt_get_result($stmt_carpenters);

        // Prepare and execute query for users
        $stmt_users = mysqli_prepare($conn, $query_users);
        mysqli_stmt_bind_param($stmt_users, "s", $username);
        mysqli_stmt_execute($stmt_users);
        $result_users = mysqli_stmt_get_result($stmt_users);

        if (mysqli_num_rows($result_carpenters) > 0) {
            $row = mysqli_fetch_assoc($result_carpenters);
        } elseif (mysqli_num_rows($result_users) > 0) {
            $row = mysqli_fetch_assoc($result_users);
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'User not found. Please try again.',
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: true
                }).then(function() {
                    window.location.href = 'login.html';
                });
            </script>";
            exit;
        }

        // Check if password matches
        if (isset($row['Carpenter_ID'])) {
            $stored_password = $row['password']; // carpenter table uses lowercase
        } else {
            $stored_password = $row['Password']; // users table uses uppercase
        }

        if ($password === $stored_password) {
            if (isset($row['Carpenter_ID'])) {
                $_SESSION['Carpenter_ID'] = $row['Carpenter_ID'];
                $_SESSION['First_Name'] = $row['First_Name'];
                $_SESSION['Last_Name'] = $row['Last_Name'];
                $_SESSION['Phone_Number'] = $row['Phone_Number'];
                $_SESSION['Email'] = $row['Email'];
                $_SESSION['Address'] = $row['Address'];
                $_SESSION['Date_Of_Birth'] = $row['Date_Of_Birth'];
                $_SESSION['Project_Completed'] = $row['Project_Completed'];
                $_SESSION['username'] = $row['username'];  // Carpenter uses lowercase

                echo "<!DOCTYPE html>
                      <html>
                      <head>
                          <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                      </head>
                      <body>
                          <script>
                              Swal.fire({
                                  title: 'Welcome Carpenter!',
                                  text: 'Successfully logged in as " . $row['First_Name'] . " " . $row['Last_Name'] . "',
                                  icon: 'success',
                                  timer: 3000,
                                  showConfirmButton: true
                              }).then(function() {
                                  window.location.href = 'profile.php';
                              });
                          </script>
                      </body>
                      </html>";
                exit;
            } elseif (isset($row['User_ID'])) {
                $_SESSION['User_ID'] = $row['User_ID'];
                $_SESSION['First_Name'] = $row['First_Name'];
                $_SESSION['Last_Name'] = $row['Last_Name'];
                $_SESSION['Phone_Number'] = $row['Phone_Number'];
                $_SESSION['Email'] = $row['Email'];
                $_SESSION['Address'] = $row['Address'];
                $_SESSION['Date_Of_Birth'] = $row['Date_Of_Birth'];
                $_SESSION['username'] = $row['Username'];  // Changed to match users table

                echo "<!DOCTYPE html>
                      <html>
                      <head>
                          <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                      </head>
                      <body>
                          <script>
                              Swal.fire({
                                  title: 'Welcome User!',
                                  text: 'Successfully logged in as " . $row['First_Name'] . " " . $row['Last_Name'] . "',
                                  icon: 'success',
                                  timer: 3000,
                                  showConfirmButton: true
                              }).then(function() {
                                  window.location.href = 'userprofile.php';
                              });
                          </script>
                      </body>
                      </html>";
                exit;
            }
        } else {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Invalid password. Please try again.',
                    icon: 'error',
                    timer: 3000,
                    showConfirmButton: true
                }).then(function() {
                    window.location.href = 'login.html';
                });
            </script>";
            exit;
        }
    }

    mysqli_close($conn);
?>
</body>
</html>
