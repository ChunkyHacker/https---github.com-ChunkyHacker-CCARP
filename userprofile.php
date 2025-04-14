<?php
session_start(); 
include('config.php');

if (!isset($_SESSION['User_ID'])) {
    header('Location: login.html');
    exit();
}

$User_ID = $_SESSION['User_ID'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Profile</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            $sql = "SELECT * FROM users WHERE User_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $User_ID);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (isset($user['Photo']) && !empty($user['Photo'])) {
                echo '<img src="' . $user['Photo'] . '" class="profile-image" alt="Profile Picture">';
            } else {
                echo '<img src="assets/img/default-avatar.png" class="profile-image" alt="Default Profile Picture">';
            }
            ?>
            <h5><?php echo $user['First_Name'] . ' ' . $user['Last_Name']; ?></h5>
            <p>User ID: <?php echo $User_ID; ?></p>
        </div>
        
        <div class="nav">
            <a href="usercomment.php" class="nav-link">
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
            <a href="userprofile.php" class="nav-link">
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
        <!-- User Information -->
        <div class="info-card">
            <h2>User Profile</h2>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Email:</strong> <?php echo $user['Email']; ?></p>
                    <p><strong>Address:</strong> <?php echo $user['Address']; ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Contact:</strong> <?php echo $user['Phone_Number']; ?></p>
                    <p><strong>Date Of Birth:</strong> <?php echo $user['Date_Of_Birth']; ?></p>
                </div>
            </div>
        </div>

        <!-- Your Requests Section -->
        <div class="info-card">
            <h2>Your Requests</h2>
            <div class="row">
                <?php
                // Modified query to show all plans that are not yet closed
                $query = "SELECT * FROM plan WHERE User_ID = ? AND status != 'closed'";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $User_ID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="col-md-4">';
                        echo '<div class="project-card">';
                        
                        $photoPath = $row['Photo'];
                        if (!empty($photoPath) && file_exists($photoPath)) {
                            echo "<img src='{$photoPath}' alt='Plan Photo' style='width: 100%; height: auto; margin-bottom: 10px;' onclick='openModal(this.src)'>";
                        }

                        echo "<a href='userviewplan.php?plan_ID={$row['plan_ID']}' class='btn btn-primary w-100'>View Plan</a>";
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p class='text-center w-100'>No requests found. <a href='usercreateplan.php'>Create a plan</a></p>";
                }
                ?>
            </div>
        </div>

        <!-- Approved Plans Section -->
        <div class="info-card">
            <h2>Approved Plans</h2>
            <div class="row">
                <?php
                // Modified query to get only one entry per plan_ID
                $query = "SELECT p.*, pa.* FROM plan p 
                          JOIN plan_approval pa ON p.plan_ID = pa.plan_ID 
                          WHERE p.User_ID = ? 
                          GROUP BY p.plan_ID"; // Group by plan_ID
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $User_ID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="col-md-4">';
                        echo '<div class="project-card">';
                        
                        $photoPath = $row['Photo'];
                        if (!empty($photoPath) && file_exists($photoPath)) {
                            echo "<img src='{$photoPath}' alt='Plan Photo' style='width: 100%; height: auto; margin-bottom: 10px;' onclick='openModal(this.src)'>";
                        }

                        echo "<a href='userviewapprovedplan.php?plan_ID={$row['plan_ID']}' class='btn btn-primary w-100'>View Details</a>";
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p class='text-center w-100'>No approved plans found.</p>";
                }
                ?>
            </div>
        </div>

        <!-- Pending Contract Section -->
        <div class="info-card">
            <h2>Pending Contract</h2>
            <div class="row">
                <?php
                // Modified query to get only pending contracts along with carpenter details
                $query = "SELECT p.*, c.First_Name AS carpenter_first, c.Last_Name AS carpenter_last 
                          FROM plan p 
                          JOIN contracts co ON p.plan_ID = co.plan_ID 
                          JOIN carpenters c ON co.Carpenter_ID = c.Carpenter_ID 
                          WHERE p.User_ID = ? AND co.status = 'pending'"; // Ensure to filter by pending status

                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $User_ID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="col-md-4">';
                        echo '<div class="project-card">';
                        
                        // Display Carpenter Name
                        echo "<h5 style='margin-bottom: 5px;'>Carpenter: {$row['carpenter_first']} {$row['carpenter_last']}</h5>";

                        // Display Plan Photo
                        $photoPath = $row['Photo'];
                        if (!empty($photoPath) && file_exists($photoPath)) {
                            echo "<img src='{$photoPath}' alt='Plan Photo' style='width: 100%; height: auto; margin-bottom: 10px;' onclick='openModal(this.src)'>";
                        }

                        // Change the link to point to usercomputebudget.php
                        echo "<a href='usercomputebudget.php?plan_ID={$row['plan_ID']}' class='btn btn-primary w-100'>View Details</a>";
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p class='text-center w-100'>No pending contracts found.</p>";
                }
                ?>
            </div>
        </div>

        <!-- Accepted Contracts Section -->
        <div class="info-card">
            <h2>Accepted Contracts</h2>
            <div class="row">
                <?php
                // Query to get only accepted contracts along with carpenter details
                $query = "SELECT p.*, c.First_Name AS carpenter_first, c.Last_Name AS carpenter_last 
                          FROM plan p 
                          JOIN contracts co ON p.plan_ID = co.plan_ID 
                          JOIN carpenters c ON co.Carpenter_ID = c.Carpenter_ID 
                          WHERE p.User_ID = ? AND co.status = 'accepted'"; // Filter by accepted status

                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $User_ID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="col-md-4">';
                        echo '<div class="project-card">';
                        
                        // Display Carpenter Name
                        echo "<h5 style='margin-bottom: 5px;'>Carpenter: {$row['carpenter_first']} {$row['carpenter_last']}</h5>";

                        // Display Plan Photo
                        $photoPath = $row['Photo'];
                        if (!empty($photoPath) && file_exists($photoPath)) {
                            echo "<img src='{$photoPath}' alt='Plan Photo' style='width: 100%; height: auto; margin-bottom: 10px;' onclick='openModal(this.src)'>";
                        }

                        // Link to view details
                        echo "<a href='usercomputebudget.php?plan_ID={$row['plan_ID']}' class='btn btn-primary w-100'>View Details</a>";
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p class='text-center w-100'>No accepted contracts found.</p>";
                }
                ?>
            </div>
        </div>

        <!-- Pending turnover project Section -->
        <div class="info-card">
            <h2>Completed Projects</h2>
            <div class="row">
                <?php
                // Query to get completed contracts and turnover details
                $query = "SELECT p.*, c.First_Name AS carpenter_first, c.Last_Name AS carpenter_last,
                          co.contract_ID, pt.client_feedback, pt.confirmation_status
                          FROM plan p 
                          JOIN contracts co ON p.plan_ID = co.plan_ID 
                          JOIN carpenters c ON co.Carpenter_ID = c.Carpenter_ID 
                          JOIN project_turnover pt ON co.contract_ID = pt.contract_ID
                          WHERE p.User_ID = ? AND co.status = 'completed'";

                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $User_ID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="col-md-4">';
                        echo '<div class="project-card">';
                        
                        // Display Carpenter Name
                        echo "<h5 style='margin-bottom: 5px;'>Carpenter: {$row['carpenter_first']} {$row['carpenter_last']}</h5>";

                        // Display Plan Photo
                        $photoPath = $row['Photo'];
                        if (!empty($photoPath) && file_exists($photoPath)) {
                            echo "<img src='{$photoPath}' alt='Plan Photo' style='width: 100%; height: auto; margin-bottom: 10px;' onclick='openModal(this.src)'>";
                        }

                        // Check if project has been reviewed
                        if (!empty($row['client_feedback']) && $row['confirmation_status'] == 'confirmed') {
                            echo "<a href='viewcompletedproject.php?contract_ID={$row['contract_ID']}' class='btn btn-info w-100'>View Project Details</a>";
                        } else {
                            echo "<a href='viewturnover.php?contract_ID={$row['contract_ID']}' class='btn btn-success w-100'>Review Project</a>";
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo "<p class='text-center w-100'>No completed projects found.</p>";
                }
                ?>
            </div>
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

