<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Available Carpenters</title>
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

    .header {
      /* Remove position: fixed; to make the header not sticky */
      /* position: fixed; */
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
      margin-right: 15px;
    }

    @media screen and (max-width: 600px) {
      .topnav a,
      .topnav input[type=text] {
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

    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(255, 140, 0, 0.4);
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
      flex-direction: row;
      align-items: center;
      margin-bottom: 20px;
    }

    label {
      font-size: 16px;
      margin-right: 10px;
      color: #000000;
    }

    input {
      width: 300px;
      padding: 10px;
      margin-right: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    select {
      padding: 10px;
      margin-right: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .search-btn {
      background-color: #FF8C00;
      color: #fff;
      border: none;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    .search-btn:hover {
      background-color: #000000;
    }

    .search-btn,
    .filter-btn,
    .reset-btn {
      background-color: #000000;
      color: #fff;
      border: none;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }

    .search-btn:hover,
    .filter-btn:hover,
    .reset-btn:hover {
      background-color: #555;
    }

    /* Updated styles for the table */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background-color: #fff; /* Set the table background color to white */
    }

    table, th, td {
      border: 1px solid #ddd;
    }

    th, td {
      padding: 12px;
      text-align: left;
    }

    th {
      background-color: #FF8C00;
      color: #fff;
    }
  </style>
</head>

<body>

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

  <?php
  // Include the configuration file
  require_once "config.php";

  // Function to generate sorting links
  function generateSortLink($column, $currentColumn, $currentDirection)
  {
      $direction = 'asc';
      if ($column === $currentColumn && $currentDirection === 'asc') {
          $direction = 'desc';
      }

      return "?sort=$column&order=$direction";
  }

  // Sorting parameters
  $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'First_Name';
  $sortOrder = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc';

  // Search parameter
  $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

  // Filter parameters
  $specializationFilter = isset($_GET['specialization']) ? $_GET['specialization'] : '';

  // Fetch carpenters from the database with the sorting, search, and filter conditions
  $query = "SELECT * FROM carpenters";
  $conditions = array();

  // Search condition
  if (!empty($searchTerm)) {
      $conditions[] = "(First_Name LIKE '%$searchTerm%' OR Last_Name LIKE '%$searchTerm%' OR Phone_Number LIKE '%$searchTerm%' OR Email LIKE '%$searchTerm%' OR Address LIKE '%$searchTerm%' OR Date_Of_Birth LIKE '%$searchTerm%' OR Experiences LIKE '%$searchTerm%' OR Project_Completed LIKE '%$searchTerm%' OR Specialization LIKE '%$searchTerm%')";
  }

  // Filter condition
  if (!empty($specializationFilter)) {
      $conditions[] = "Specialization = '$specializationFilter'";
  }

  // Apply conditions to the query
  if (!empty($conditions)) {
      $query .= " WHERE " . implode(' AND ', $conditions);
  }

  $query .= " ORDER BY $sortColumn $sortOrder";

  $result = mysqli_query($conn, $query);

  // Check if there are rows in the result set
  if (mysqli_num_rows($result) > 0) {
      // Search form
      echo "<form action='' method='GET'>";
      echo "<label for='search'>Search Carpenter:</label>";
      echo "<input type='text' name='search' value='" . htmlspecialchars($searchTerm) . "'>";
      echo "<button type='submit' class='search-btn'>Search</button>";
      echo "</form>";

      // Filter and Reset buttons
      echo "<form action='' method='GET'>";
      echo "<label for='specialization'>Filter by Specialization:</label>";
      echo "<select name='specialization'>";
      echo "<option value='' " . ($specializationFilter === '' ? 'selected' : '') . ">All</option>";

      // Fetch specialization names from the database
      $specializationQuery = "SELECT DISTINCT Specialization FROM carpenters";
      $specializationResult = mysqli_query($conn, $specializationQuery);

      while ($specializationRow = mysqli_fetch_assoc($specializationResult)) {
          echo "<option value='{$specializationRow['Specialization']}' " . ($specializationFilter === $specializationRow['Specialization'] ? 'selected' : '') . ">{$specializationRow['Specialization']}</option>";
      }

      echo "</select>";

      echo "<button type='submit' class='filter-btn' name='action' value='filter'>Filter</button>";
      echo "<button type='button' class='reset-btn' onclick=\"window.location.href='{$_SERVER['PHP_SELF']}'\">Reset</button>";
      echo "</form>";

      // Table header with sorting links
      echo "<table>";
      echo "<tr>
              <th><a href='" . generateSortLink('First_Name', $sortColumn, $sortOrder) . "'>First Name</a></th>
              <th><a href='" . generateSortLink('Last_Name', $sortColumn, $sortOrder) . "'>Last Name</a></th>
              <th><a href='" . generateSortLink('Phone_Number', $sortColumn, $sortOrder) . "'>Phone Number</a></th>
              <th><a href='" . generateSortLink('Email', $sortColumn, $sortOrder) . "'>Email</a></th>
              <th><a href='" . generateSortLink('Address', $sortColumn, $sortOrder) . "'>Address</a></th>
              <th><a href='" . generateSortLink('Date_Of_Birth', $sortColumn, $sortOrder) . "'>Date of Birth</a></th>
              <th><a href='" . generateSortLink('Experiences', $sortColumn, $sortOrder) . "'>Experiences</a></th>
              <th><a href='" . generateSortLink('Project_Completed', $sortColumn, $sortOrder) . "'>Projects Completed</a></th>
              <th><a href='" . generateSortLink('Specialization', $sortColumn, $sortOrder) . "'>Specialization</a></th>
            </tr>";

      // Output data of each row
      while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>{$row['First_Name']}</td>";
          echo "<td>{$row['Last_Name']}</td>";
          echo "<td>{$row['Phone_Number']}</td>";
          echo "<td>{$row['Email']}</td>";
          echo "<td>{$row['Address']}</td>";
          echo "<td>{$row['Date_Of_Birth']}</td>";
          echo "<td>{$row['Experiences']}</td>";
          echo "<td>{$row['Project_Completed']}</td>";
          echo "<td>{$row['specialization']}</td>";
          echo "<td>
                  <form action='usercreateplan.php' method='GET'>
                      <input type='hidden' name='selected_carpenter' value='{$row['Carpenter_ID']}'>
                      <button type='submit'>Select Carpenter</button>
                  </form>
                </td>";
          echo "</tr>";
      }

      echo "</table>";
  } else {
      echo "No carpenters available.";
  }

  // Close the database conn
  mysqli_close($conn);
  ?>

  </div>
  </div>

</body>

</html>
