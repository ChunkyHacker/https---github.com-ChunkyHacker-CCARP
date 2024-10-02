<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query_carpenters = "SELECT * FROM carpenters WHERE username = ?";
    $query_users = "SELECT * FROM users WHERE username = ?";
    $query_shop = "SELECT * FROM shop WHERE username = ?";

    $stmt_carpenters = mysqli_prepare($connection, $query_carpenters);
    mysqli_stmt_bind_param($stmt_carpenters, "s", $username);
    mysqli_stmt_execute($stmt_carpenters);
    $result_carpenters = mysqli_stmt_get_result($stmt_carpenters);

    $stmt_users = mysqli_prepare($connection, $query_users);
    mysqli_stmt_bind_param($stmt_users, "s", $username);
    mysqli_stmt_execute($stmt_users);
    $result_users = mysqli_stmt_get_result($stmt_users);

    $stmt_shop = mysqli_prepare($connection, $query_shop);
    mysqli_stmt_bind_param($stmt_shop, "s", $username);
    mysqli_stmt_execute($stmt_shop);
    $result_shop = mysqli_stmt_get_result($stmt_shop);

    if (mysqli_num_rows($result_carpenters) > 0 || mysqli_num_rows($result_users) > 0 || mysqli_num_rows($result_shop) > 0) {
        if (mysqli_num_rows($result_carpenters) > 0) {
            $row = mysqli_fetch_assoc($result_carpenters);
        } elseif (mysqli_num_rows($result_users) > 0) {
            $row = mysqli_fetch_assoc($result_users);
        } else {
            $row = mysqli_fetch_assoc($result_shop);
        }

        if ($password === $row['password'] || $password === $row['Password']) {
            if (isset($row['Carpenter_ID'])) {
                $_SESSION['Carpenter_ID'] = $row['Carpenter_ID'];
                $_SESSION['User_ID'] = $row['User_ID'];
                $_SESSION['First_Name'] = $row['First_Name'];
                $_SESSION['Last_Name'] = $row['Last_Name'];
                $_SESSION['Phone_Number'] = $row['Phone_Number'];
                $_SESSION['Email'] = $row['Email'];
                $_SESSION['Address'] = $row['Address'];
                $_SESSION['Date_Of_Birth'] = $row['Date_Of_Birth'];
                $_SESSION['Project_Completed'] = $row['Project_Completed'];
                $_SESSION['Specialization'] = $row['Specialization'];

                header("Location: profile.php");
            } elseif (isset($row['User_ID'])) {
                $_SESSION['User_ID'] = $row['User_ID'];
                $_SESSION['First_Name'] = $row['First_Name'];
                $_SESSION['Last_Name'] = $row['Last_Name'];
                $_SESSION['Phonenumber'] = $row['Phonenumber'];
                $_SESSION['Email'] = $row['Email'];
                $_SESSION['Address'] = $row['Address'];
                $_SESSION['Date_Of_Birth'] = $row['Date_Of_Birth'];

                header("Location: userprofile.php");
            } elseif (isset($row['Shop_ID'])) {
                $_SESSION['Shop_ID'] = $row['Shop_ID'];
                $_SESSION['Name'] = $row['Name'];
                $_SESSION['Address'] = $row['Owner'];
                $_SESSION['Phonenumber'] = $row['Phonenumber'];
                $_SESSION['Email'] = $row['Email'];
                $_SESSION['Shop_Description'] = $row['Shop_Description'];
                $_SESSION['Date_Of_Birth'] = $row['Date_Of_Birth'];
                $_SESSION['Logo'] = $row['Logo'];

                header("Location: inventory.php");
            }

            $_SESSION['username'] = $row['username'];
            exit;
        } else {
            echo "<script>alert('Invalid password. Please try again.');</script>";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'login.html';
                    }, 1000); // Redirect after 1 second
                  </script>";
            exit;
        }
    } else {
        echo "<script>alert('User not found. Please try again.');</script>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'login.html';
                }, 1000); // Redirect after 1 second
              </script>";
        exit;
    }
}

mysqli_close($connection);
?>
