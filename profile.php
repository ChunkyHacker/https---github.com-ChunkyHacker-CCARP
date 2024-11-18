
<!DOCTYPE html>
<html lang="en">
<head>
<title>Profile</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query_carpenters = "SELECT * FROM carpenters WHERE username = ?";

    $stmt_carpenters = mysqli_prepare($conn, $query_carpenters);
    mysqli_stmt_bind_param($stmt_carpenters, "s", $username);
    mysqli_stmt_execute($stmt_carpenters);
    $result_carpenters = mysqli_stmt_get_result($stmt_carpenters);

    if (mysqli_num_rows($result_carpenters) > 0) {
        $row = mysqli_fetch_assoc($result_carpenters);

        if ($password === $row['password']) {
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
        } else {
            echo "<script>alert('Invalid password. Please try again.');</script>";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'cslogin.html';
                    }, 100); // Redirect after 1 second
                  </script>";
            exit;
        }
    } else {
        echo "<script>alert('User not found. Please try again.');</script>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'cslogin.html';
                }, 1000); // Redirect after 1 second
              </script>";
        exit;
    }
}

?>

<style>
  html {
    box-sizing: border-box;
  }

  *, *:before, *:after {
    box-sizing: inherit;
  }

  /* Style the body */
  body {
    font-family: Arial, Helvetica, sans-serif;
    margin: 0 auto;
    max-width: auto;
    padding: 5px;
    padding-top: 170px;
    font-size: 20px; /* Set base font size to 20px */
  }

  /* Header/logo Title */
  .header {
    text-align: left;
    background: #FF8C00;
    color: rgb(0, 0, 0);
    display: flex;
    align-items: center;
    text-decoration: none;
  }

  .heading1 {
    font-size: 25px;
    margin-right: 25px;
  }

  .fa {
    font-size: 25px;
  }

  .checked {
    color: #FF8C00;
  }

  /* Three column layout */
  .side {
    float: left;
    width: 15px;
    margin-top: 10px;
  }

  .middle {
    margin-top: 10px;
    float: left;
    width: 70%;
    flex-basis: 40%;
  }

  .right {
    text-align: right;
  }

  /* Clear floats after the columns */
  .row:after {
    content: "";
    display: table;
    clear: both;
  }

  /* The bar container */
  .bar-container {
    height: 18px;
    margin: 5px 0;
    width: 100%;
    background-color: #f1f1f1;
    text-align: center;
    color: white;
  }

  .side.right {
    text-align: left;
  }

  /* Individual bars */
  .bar-5 {width: 60%; height: 18px; background-color: #04AA6D;}
  .bar-4 {width: 30%; height: 18px; background-color: #2196F3;}
  .bar-3 {width: 10%; height: 18px; background-color: #00bcd4;}
  .bar-2 {width: 4%; height: 18px; background-color: #ff9800;}
  .bar-1 {width: 15%; height: 18px; background-color: #f44336;}

  /* Place text to the right */
  .right {
    text-align: right;
  }

  /* Header*/
  .header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    padding: 10px;
    text-align: left;
    background: #FF8C00;
    color: #000000;
    display: flex;
    align-items: center;
    text-decoration: none;
    z-index: 100;
  }

  /* Increase the font size of the heading */
  .header h1 {
    font-size: 40px;
    border-left: 20px solid transparent;
    text-decoration: none;
  }

  .right {
    margin-right: 20px;
  }

  .header a {
    font-size: 25px;
    font-weight: bold;
    text-decoration: none;
    color: #000000;
    margin-right: 45px;
  }

  /* When the screen is less than 600px wide, stack the links and the search field vertically instead of horizontally */
  @media screen and (max-width: 600px) {
    .topnav a, .topnav input[type=text] {
      float: none;
      display: block;
      text-align: left;
      width: 100%;
      margin: 0;
      padding: 14px;
    }
    .topnav input[type=text] {
      border: 1px solid #ccc;
    }
    body {
      font-family: Arial, Helvetica, sans-serif;
      margin: 0;
      padding-top: 300px;
    }
  }

  /* Main column */
  .main {
    -ms-flex: 70%;
    flex: 70%;
    background-color: white;
    text-align: center;
    margin: auto;
  }

  h1 {
    font-size: 50px; /* Keep h1 font size larger */
    word-break: break-all;
    padding-left: 50px;
  }

  .row {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    margin-top: 40px;
    height: auto;
  }

  .column {
    float: left;
    width: 25%;
    margin-bottom: 16px;
    margin-right: 16px;
    text-align: center;
    justify-content: center;
  }

  /* Clear floats after rows */
  .row1:after {
    content: "";
    display: table;
    clear: both;
  }

  .content {
    background-color: white;
    padding: 10px;
  }

  @media screen and (max-width: 650px) {
    .column {
      width: 50%;
      display: block;
    }
  }

  .card {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  }

  .container {
    padding: 0 16px;
  }

  .container::after, .row::after {
    content: "";
    clear: both;
    display: table;
  }

  .title {
    color: grey;
  }

  .button {
    border: none;
    outline: 0;
    display: inline-block;
    padding: 8px;
    color: rgb(0, 0, 0);
    background-color: #FF8C00;
    text-align: center;
    cursor: pointer;
    width: 100%;
    text-decoration: none;
    color: inherit;
  }

  .button:hover {
    background-color: #535353;
  }

  /* Footer */
  .footer {
    padding: 10px;
    text-align: center;
    background: #FF8C00;
    position: relative;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
  }

  /* Responsive layout - when the screen is less than 700px wide, make the two columns stack on top of each other instead of next to each other */
  @media screen and (max-width: 700px) {
    .row {
      flex-direction: column;
    }
  }

  /* Responsive layout - when the screen is less than 400px wide, make the navigation links stack on top of each other instead of next to each other */
  @media screen and (max-width: 400px) {
    .navbar a {
      float: none;
      width: 100%;
    }
  }
</style>

</head>
<body>

<div class="header">
    <a href="comment.php">
        <h1>
            <img src="assets/img/logos/logo.png" alt=""  style="width: 75px; margin-right: 10px;">
        </h1>
    </a>
    <div class="right">
        <a href="logout.php" style="text-decoration: none; color: black; margin-right: 20px;">Log Out</a>
        <a class="active" href="comment.php">Home</a>
        <a href="about/index.html">About</a>
        <a href="#contact">Get Ideas</a>
        <a href="plan.php">Project</a>
    </div>
</div>

<div class="header">
        <a href="comment.php">
            <h1>
                <img src="assets/img/logos/logo.png" alt="" style="width: 75px; margin-right: 10px;">
            </h1>
        </a>
        <div class="right">
          <a href="comment.php">Home</a>
          <a href="about/index.html">About</a>
          <a href="#contact">Get Ideas</a>
          <a href="plan.php">Project</a>
        </div>
        <a href="logout.php" style="text-decoration: none; color: black; margin-left: auto; margin-right: 20px;">Log Out</a>
</div>

<?php
if (isset($_SESSION['Carpenter_ID'])) {
    $Carpenter_ID = $_SESSION['Carpenter_ID'];

    $sql = "SELECT * FROM carpenters WHERE Carpenter_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $Carpenter_ID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Use the stored path directly
        $photoPath = $user['Photo'];


        echo '<div class="row">
        <div class="column">
            <div class="card">
            <img src="' . $photoPath . '" alt="Profile Picture" style="width: 300px; height: 300px;">
                <div class="container">
                    <h2><strong>' . $user['First_Name'] . ' ' . $user['Last_Name'] . '</strong></h2>
                    <p class="title"><strong>Carpenter</strong></p>
                    <p><strong>Email:</strong> ' . $user['Email'] . '</p>
                    <p><strong>Specialization:</strong> ' . $user['specialization'] . '</p>
                    <p><strong>Address:</strong> ' . $user['Address'] . '</p>
                    <p><strong>Date Of Birth:</strong> ' . $user['Date_Of_Birth'] . '</p>
                    <p><strong>Experiences:</strong> ' . $user['Experiences'] . '</p>
                    <p><strong>Project Completed:</strong> ' . $user['Project_Completed'] . '</p>
                    <p><button class="button"><a href="chat.html" style="text-decoration: none; color: black;">Contact Carpenter</a></button></p>
                </div>
            </div>
        </div>
    </div>';
    } else {
        echo "No user found with Carpenter_ID: $Carpenter_ID";
    }
} else {
    echo "Session Carpenter_ID not set.";
}
?>

<h1>Approved Projects:</h1>
  <div class="row">
  <?php
      $query = "SELECT * FROM approvedplan";
      $result = mysqli_query($conn, $query);

      if ($result) {
          // Check if there are rows in the result
          if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  echo '<div class="column" style="background-color: #FF8C00; margin: 10px; padding: 15px; border-radius: 10px;">';
                  echo "<div class='content' style='background-color: #FFCB8B; padding: 10px; border-radius: 5px;'>"; // Display project details

                  // Fetch user details from the 'users' table
                  $userId = $row['User_ID'];
                  $userQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
                  $userStmt = mysqli_prepare($conn, $userQuery);
                  mysqli_stmt_bind_param($userStmt, "i", $userId);
                  mysqli_stmt_execute($userStmt);
                  $userResult = mysqli_stmt_get_result($userStmt);

                  if ($userRow = mysqli_fetch_assoc($userResult)) {
                      echo "<p>Client Name: {$userRow['First_Name']} {$userRow['Last_Name']}</p>";
                  }


                  $photoPath = $row['Photo'];
                  if (!empty($photoPath) && file_exists($photoPath)) {
                      echo "<a href='#' onclick='openModal(\"{$photoPath}\")'>";
                      echo "<img src='{$photoPath}' alt='Carpenter Photo' style='width: 150px; height: auto;'>";
                      echo "</a>";
                  }

                  echo "<p><a href='viewapprovedplan.php?approved_plan_ID={$row['approved_plan_ID']}'>View Approved Plan</a></p>"; // Updated plan_id
                  echo "</div>";
                  echo "</div>";
              }

              mysqli_free_result($result);
          } else {
              // Display a message if there are no approved projects
              echo "<p>There is no approved projects.</p>";
          }
      } else {
          echo "Error fetching data: " . mysqli_error($conn);
      }

      ?></div>

  <h1>Listed Requirements</h1>
  <div class="row">
    <?php
    include('config.php');

    $query = "SELECT * FROM projectrequirements";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Check if there are rows in the result
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="column" style="background-color: #FF8C00; margin: 10px; padding: 15px; border-radius: 10px;">';
                echo "<div class='content' style='background-color: #FFCB8B; padding: 10px; border-radius: 5px;'>"; // Display project details

                $userId = $row['User_ID'];
                $userQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
                $userStmt = mysqli_prepare($conn, $userQuery);
                mysqli_stmt_bind_param($userStmt, "i", $userId);
                mysqli_stmt_execute($userStmt);
                $userResult = mysqli_stmt_get_result($userStmt);

                if ($userRow = mysqli_fetch_assoc($userResult)) {
                    echo "<p>Client Name: {$userRow['First_Name']} {$userRow['Last_Name']}</p>";
                }


                $photoPath = $row['Photo'];
                if (!empty($photoPath) && file_exists($photoPath)) {
                    echo "<a href='#' onclick='openModal(\"{$photoPath}\")'>";
                    echo "<img src='{$photoPath}' alt='Project Photo' style='width: 150px; height: auto;'>";
                    echo "</a>";
                }
                echo "</div>";
                echo "<p><a href='viewaddedrequirements.php?requirement_ID={$row['requirement_ID']}'>View Listed Requirements</a></p>"; 
                echo "</div>";
            }

            mysqli_free_result($result);
        } else {
            // Display a message if there are no approved projects
            echo "<p>There is no approved projects.</p>";
        }
    } else {
        echo "Error fetching data: " . mysqli_error($conn);
    }

    mysqli_close($conn);
    ?>
</div>
  </div>
</div>

<script>
function openModal(photoPath) {
    var modal = document.getElementById('myModal');
    var modalImg = document.getElementById('modalImg');

    modal.style.display = 'block';
    modalImg.src = photoPath;
}

function closeModal() {
    var modal = document.getElementById('myModal');
    modal.style.display = 'none';
}
</script>

<!-- Modal HTML -->
<div id="myModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img id="modalImg" class="modal-content">
</div>

</body>
  </html>
  