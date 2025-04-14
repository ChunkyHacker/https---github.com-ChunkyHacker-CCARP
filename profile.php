<?php
session_start();
include('config.php');

if (!isset($_SESSION['Carpenter_ID'])) {
    die("Error: No Carpenter ID found in session.");
}

$Carpenter_ID = $_SESSION['Carpenter_ID'];

// Fetch carpenter details
$sql = "SELECT * FROM carpenters WHERE Carpenter_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $Carpenter_ID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carpenter Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            padding: 0;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            background-color: #FF8600;
            height: 100vh;
            padding: 15px;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            overflow-y: auto;
            font-size: 20px;
        }
        
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            margin: 10px auto;
            display: block;
        }
        
        .nav-link {
            color: #000000;
            padding: 8px 15px;
            margin: 2px 0;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: background-color 0.3s;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: #000000;
            border-radius: 5px;
        }

        
        .nav-link i {
            width: 25px;
            margin-right: 10px;
            text-align: center;
        }
        
        .profile-section {
            text-align: center;
            padding-bottom: 10px;
            margin-bottom: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: #000000;
        }
        
        .main-content {
            margin-left: 270px;
            padding: 15px;
            max-width: 800px;
        }
        
        .info-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .project-card {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        .close {
            position: absolute;
            right: 35px;
            top: 15px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sidebar .nav {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="profile-section">
            <?php
            if (isset($user['Photo']) && !empty($user['Photo'])) {
                echo '<img src="' . $user['Photo'] . '" class="profile-image" alt="Profile Picture">';
            } else {
                echo '<img src="assets/img/default-avatar.png" class="profile-image" alt="Default Profile Picture">';
            }
            ?>
            <h5><?php echo $user['First_Name'] . ' ' . $user['Last_Name']; ?></h5>
            <p>Carpenter ID: <?php echo $Carpenter_ID; ?></p>
        </div>
        
        <div class="nav">
            <a href="comment.php" class="nav-link">
                <i class="fa fa-home"></i>
                <span>Home</span>
            </a>
            <a href="about/index.html" class="nav-link">
                <i class="fa fa-info-circle"></i>
                <span>About</span>
            </a>
            <a href="#contact" class="nav-link">
                <i class="fa fa-lightbulb-o"></i>
                <span>Get Ideas</span>
            </a>
            <a href="plan.php" class="nav-link">
                <i class="fa fa-clipboard"></i>
                <span>Project</span>
            </a>
            <a href="profile.php" class="nav-link">
                <i class="fa fa-user"></i>
                <span>Profile</span>
            </a>
            <a href="logout.php" class="nav-link">
                <i class="fa fa-sign-out"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Carpenter Information -->
        <div class="info-card">
            <h2>Carpenter Profile</h2>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Email:</strong> <?php echo $user['Email']; ?></p>
                    <p><strong>Specialization:</strong> <?php echo $user['specialization']; ?></p>
                    <p><strong>Address:</strong> <?php echo $user['Address']; ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Date Of Birth:</strong> <?php echo $user['Date_Of_Birth']; ?></p>
                    <p><strong>Experiences:</strong> <?php echo $user['Experiences']; ?></p>
                    <p><strong>Project Completed:</strong> <?php echo $user['Project_Completed']; ?></p>
                </div>
            </div>
        </div>

        <!-- Approved Projects Section -->
        <div class="info-card">
            <h2>Approved Projects</h2>
            <div class="row">
                <?php
                $query = "SELECT pa.*, p.User_ID, p.Photo, p.plan_ID 
                         FROM plan_approval pa 
                         JOIN plan p ON pa.plan_ID = p.plan_ID 
                         WHERE pa.Carpenter_ID = ?";
                
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $Carpenter_ID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="col-md-4">';
                        echo '<div class="project-card">';
                        
                        // Fetch Client Name
                        $userId = $row['User_ID'];
                        $userQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
                        $userStmt = mysqli_prepare($conn, $userQuery);
                        mysqli_stmt_bind_param($userStmt, "i", $userId);
                        mysqli_stmt_execute($userStmt);
                        $userResult = mysqli_stmt_get_result($userStmt);
                        
                        if ($userRow = mysqli_fetch_assoc($userResult)) {
                            echo "<p><strong>Client:</strong> {$userRow['First_Name']} {$userRow['Last_Name']}</p>";
                        }

                        // Display Plan Photo
                        $photoPath = $row['Photo'];
                        if (!empty($photoPath) && file_exists($photoPath)) {
                            echo "<img src='{$photoPath}' alt='Plan Photo' style='width: 100%; height: auto; margin-bottom: 10px;' onclick='openModal(this.src)'>";
                        }

                        echo "<a href='viewapprovedplan.php?plan_ID={$row['plan_ID']}' class='btn btn-primary w-100'>View Details</a>";
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p class='text-center w-100'>No approved projects found.</p>";
                }
                ?>
            </div>
        </div>

        <!-- Ongoing Projects Section -->
        <div class="info-card">
            <h2>Ongoing Projects</h2>
            <div class="row">
                <?php
                // Fetch only projects with accepted contracts for the logged-in carpenter
                $query = "SELECT pa.*, p.User_ID, p.Photo, p.plan_ID, c.contract_ID, c.status 
                          FROM plan_approval pa 
                          JOIN plan p ON pa.plan_ID = p.plan_ID 
                          JOIN contracts c ON p.plan_ID = c.plan_ID
                          WHERE pa.Carpenter_ID = ? AND c.Carpenter_ID = ?"; // Remove status condition
                
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "ii", $Carpenter_ID, $Carpenter_ID); // Bind Carpenter_ID for both conditions
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="col-md-4">';
                        echo '<div class="project-card" style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px;">';
                        
                        // Fetch Client Name
                        $userId = $row['User_ID'];
                        $userQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
                        $userStmt = mysqli_prepare($conn, $userQuery);
                        mysqli_stmt_bind_param($userStmt, "i", $userId);
                        mysqli_stmt_execute($userStmt);
                        $userResult = mysqli_stmt_get_result($userStmt);
                        
                        if ($userRow = mysqli_fetch_assoc($userResult)) {
                            echo "<h5 style='margin-bottom: 15px;'>Client: {$userRow['First_Name']} {$userRow['Last_Name']}</h5>";
                        }

                        // Display Plan Photo
                        $photoPath = $row['Photo'];
                        if (!empty($photoPath) && file_exists($photoPath)) {
                            echo "<div style='margin-bottom: 15px;'>";
                            echo "<img src='{$photoPath}' alt='Plan Photo' style='width: 100%; height: 200px; object-fit: cover; border-radius: 8px;' onclick='openModal(this.src)'>";
                            echo "</div>";
                        }

                        // Display contract status badge
                        $status = $row['status']; // Get the actual status from the database
                        $color = ""; // Initialize color variable

                        // Set color based on status
                        if ($status === 'accepted') {
                            $color = "#4CAF50"; // Green for accepted
                        } elseif ($status === 'pending') {
                            $color = "#FF8C00"; // Orange for pending
                        } elseif ($status === 'rejected') {
                            $color = "#f44336"; // Red for rejected
                        } elseif ($status === 'completed') {
                            $color = "#4CAF50"; // Green for completed
                        } else {
                            $color = "#000"; // Default color if status is unknown
                        }

                        echo "<div style='margin-bottom: 15px; text-align: center;'>";
                        echo "<span style='display: inline-block; padding: 5px 10px; background-color: $color; color: white; font-weight: bold; font-size: 14px; border-radius: 3px;'>";
                        echo "Contract: " . ucfirst($status); // Display the actual status
                        echo "</span>";
                        echo "</div>";

                        // View Details button linking to viewaddedrequirements.php with contract_ID
                        echo "<a href='viewaddedrequirements.php?contract_ID={$row['contract_ID']}' 
                              style='display: block; width: 100%; text-align: center; padding: 8px 0; 
                                     background-color: #007bff; color: white; text-decoration: none; 
                                     border-radius: 5px; transition: background-color 0.3s;'>
                              View Details
                              </a>";
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p class='text-center w-100'>No ongoing projects found.</p>";
                }
                ?>
            </div>
        </div>
        
    <!-- Modal for Image Preview -->
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
    </div>

    <script>
        function openModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            modal.style.display = "block";
            modalImg.src = imageSrc;
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = "none";
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>