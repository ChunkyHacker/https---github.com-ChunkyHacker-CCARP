<?php
include('config.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Save form data in session
    $_SESSION['formData'] = $_POST;
    header("Location: availablecarpenters.php");
    exit();
}

// Initialize $formData with an empty array
$formData = array();

// Check if form data is set in the session
if (isset($_SESSION['formData'])) {
    // Retrieve form data from the session
    $formData = $_SESSION['formData'];
}

?>
<!DOCTYPE html>
<html lang="en">
<style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: Verdana, sans-serif;
      margin: 0;
      background-color: #FF8C00;
    }

  .container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
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

  .header a{
    font-size: 25px;
    font-weight: bold;
    text-decoration: none;
    color: #000000;
    margin-right: 15px;
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


  /* CSS styles for the modal */
  /* CSS styles for the modal */
  .modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(255, 140, 0, 0.4); /* Updated background color */
  }

  .modal-content {
    background-color: #FF8C00; /* Updated background color */
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-height: 80%;
    overflow-y: auto;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }

  /* Header */
  .header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    padding: 10px;
    text-align: left;
    background: #FF8C00; /* Updated background color */
    color: #000000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-decoration: none;
    z-index: 100;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }



  .modal-content {
      background-color: #fefefe;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }

    h2 {
      font-size: 24px;
      margin-bottom: 20px;
      color: #FF8C00;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    label {
      font-size: 16px;
      margin-bottom: 5px;
      color: #000000;
    }

    input,
    select,
    textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

  .post-btn, .cancel-btn {
    margin-bottom: 10px; /* Adjust the margin as needed */
  }

  .cancel-btn {
    background-color: red;
    color: #fff;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 10px;
    text-decoration: none;
    display: inline-block;
    text-align: center; /* Center the text */
    width: 100%; /* Make the button full-width */
  }

  .cancel-btn:hover {
    background-color: #000000;
  }

  button {
    background-color: #FF8C00;
    color: #fff;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
  }

    button:hover {
      background-color: #000000;
    }

    @media screen and (max-width: 600px) {
      .modal-content {
        width: 100%;
      }
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Construction Plan</title>
</head>
<div class="header">
        <a href="comment.php">
            <h1>
                <img src="assets/img/logos/logo.png" alt="" style="width: 75px; margin-right: 10px;">
            </h1>
        </a>
        <div class="right">
            <a href="login.html" style="text-decoration: none; color: black; margin-right: 20px;">Log Out</a>
            <a class="active" href="comment.php">Home</a>
            <a href="about/index.html">About</a>
            <a href="#contact">Get Ideas</a>
            <a href="plan.php">Project</a>
        </div>
</div>
<body>

<div class="container">
  <div class="modal-content">
    <h2>Create Construction Plan</h2>
    <form id="postForm" enctype="multipart/form-data" method="POST" action="plan.php">

    <label for="carpenter">Carpenter:</label>
      <select id="carpenter" name="carpenter">
          <!-- Check if carpenter is selected from availablecarpenters.php -->
          <?php
          if (isset($_GET['selected_carpenter'])) {
              $selectedCarpenter = $_GET['selected_carpenter'];
              // Fetch carpenters from the database and populate the dropdown
              require_once "config.php";
              $query = "SELECT Carpenter_ID, First_Name, Last_Name FROM carpenters";
              $result = mysqli_query($connection, $query);

              while ($row = mysqli_fetch_assoc($result)) {
                  $selected = ($row['Carpenter_ID'] == $selectedCarpenter) ? 'selected' : '';
                  echo "<option value='{$row['Carpenter_ID']}' $selected>{$row['First_Name']} {$row['Last_Name']}</option>";
              }

              mysqli_close($connection);
          } else {
              echo "No carpenter selected.";
          }
          ?>
    </select><br>

      <label for="name">Name:</label>
      <input type="text" id="name" name="name"><br>

      <label for="details">Details:</label>
      <textarea id="details" name="details" rows="4"></textarea><br>

      <label for="materials">Materials:</label>
      <textarea id="materials" name="materials" rows="4"></textarea><br>

      <label for="project_location">Project Location:</label>
      <input type="text" id="project_location" name="project_location"><br>

      <label for="photo">Upload Photo: (Optional)</label>
      <input type="file" id="Photo" name="Photo"><br>

      <button type="button" class="post-btn" onclick="location.href='availablecarpenters.php';">Select Available Carpenters</button>
      <button type="submit" class="post-btn">Submit</button>
        <a href="comment.php" class="cancel-btn">Cancel</a>
    </form>
  </div>
</div>

</body>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Check if there is saved form data in local storage
   const storedFormData = localStorage.getItem('formData');
    
   if (storedFormData) {
      // Parse the stored JSON data and populate the form
     const parsedData = JSON.parse(storedFormData);

    for (const key in parsedData) {
    if (parsedData.hasOwnProperty(key)) {
     const element = document.getElementById(key);

      if (element) {
          Set the value of the corresponding form element
       element.value = parsedData[key];
         }
       }
     }
  }

    // Add an event listener to the form for input changes
    const form = document.getElementById('postForm');
    form.addEventListener('input', function () {
      // Serialize the form data and save it in local storage
      const formData = {};
      const elements = form.elements;

      for (let i = 0; i < elements.length; i++) {
        const element = elements[i];

        if (element.name && !element.disabled) {
          formData[element.name] = element.value;
        }
      }

      localStorage.setItem('formData', JSON.stringify(formData));
    });
  });
</script>

</html>
