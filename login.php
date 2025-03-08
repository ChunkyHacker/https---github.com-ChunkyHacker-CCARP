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
            echo "<script>alert('User not found. Please try again.');</script>";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'login.html';
                    }, 1000);
                </script>";
            exit;
        }

        // Check if password matches
        if ($password === $row['password'] || (isset($row['Password']) && $password === $row['Password'])) {
            if (isset($row['Carpenter_ID'])) {
                $_SESSION['Carpenter_ID'] = $row['Carpenter_ID'];
                $_SESSION['First_Name'] = $row['First_Name'];
                $_SESSION['Last_Name'] = $row['Last_Name'];
                $_SESSION['Phone_Number'] = $row['Phone_Number'];
                $_SESSION['Email'] = $row['Email'];
                $_SESSION['Address'] = $row['Address'];
                $_SESSION['Date_Of_Birth'] = $row['Date_Of_Birth'];
                $_SESSION['Project_Completed'] = $row['Project_Completed'];
                $_SESSION['Specialization'] = $row['Specialization'];
                $_SESSION['username'] = $row['username'];

                header("Location: profile.php");
                exit;
            } elseif (isset($row['User_ID'])) {
                $_SESSION['User_ID'] = $row['User_ID'];
                $_SESSION['First_Name'] = $row['First_Name'];
                $_SESSION['Last_Name'] = $row['Last_Name'];
                $_SESSION['Phonenumber'] = $row['Phonenumber'];
                $_SESSION['Email'] = $row['Email'];
                $_SESSION['Address'] = $row['Address'];
                $_SESSION['Date_Of_Birth'] = $row['Date_Of_Birth'];
                $_SESSION['username'] = $row['username'];

                header("Location: userprofile.php");
                exit;
            }
        } else {
            echo "<script>alert('Invalid password. Please try again.');</script>";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'login.html';
                    }, 1000);
                </script>";
            exit;
        }
    }

    mysqli_close($conn);
?>
