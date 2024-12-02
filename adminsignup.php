<?php
// Include the database configuration file
include 'config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($firstname) || empty($lastname) || empty($username) || empty($password)) {
        die("All fields are required.");
    }

    // Prepare and execute the SQL query
    $sql = "INSERT INTO admin (firstname, lastname, username, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $firstname, $lastname, $username, $password);

        // Execute the query and check if successful
        if ($stmt->execute()) {
            echo "<script>
                    alert('Admin registered successfully!');
                    window.location.href = 'adminlogin.html';
                  </script>";
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Failed to prepare the statement.";
    }

    // Close the database connection
    $conn->close();
}
?>
