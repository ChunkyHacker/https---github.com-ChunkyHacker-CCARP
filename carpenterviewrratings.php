<?php
include('config.php');
session_start();

if (!isset($_SESSION['Carpenter_ID'])) {
    echo "<script>alert('You need to log in first.'); window.location.href='login.php';</script>";
    exit();
}

$carpenter_ID = $_SESSION['Carpenter_ID'];
$contract_ID = isset($_GET['contract_ID']) ? $_GET['contract_ID'] : null;

// Verify if the carpenter has access to this contract
$verify_sql = "SELECT * FROM contracts WHERE contract_ID = ? AND Carpenter_ID = ?";
$verify_stmt = $conn->prepare($verify_sql);
$verify_stmt->bind_param("ii", $contract_ID, $carpenter_ID);
$verify_stmt->execute();
$verify_result = $verify_stmt->get_result();

if ($verify_result->num_rows === 0) {
    echo "<script>alert('You do not have permission to view this rating.'); window.history.back();</script>";
    exit();
}

// Fetch rating details
$rating_sql = "SELECT r.*, u.First_Name, u.Last_Name 
               FROM ratings r 
               JOIN contracts c ON r.contract_ID = c.contract_ID 
               JOIN users u ON r.user_ID = u.User_ID 
               WHERE r.contract_ID = ?";
$rating_stmt = $conn->prepare($rating_sql);
$rating_stmt->bind_param("i", $contract_ID);
$rating_stmt->execute();
$rating_result = $rating_stmt->get_result();
$rating = $rating_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Ratings - Carpenter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .rating-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
            border-bottom: 2px solid #FF8C00;
            padding-bottom: 10px;
        }
        .rating-item {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .rating-label {
            font-weight: bold;
            color: #555;
            font-size: 18px;
        }
        .rating-value {
            margin-left: 10px;
            color: #FF8C00;
            font-size: 18px;
        }
        .client-info {
            margin-bottom: 30px;
            padding: 15px;
            background-color: #fff8f0;
            border-radius: 5px;
            border-left: 4px solid #FF8C00;
        }
        .comments {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        button {
            background-color: #FF8C00;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        button:hover {
            background-color: #FFA500;
        }
    </style>
</head>
<body>
    <div class="rating-container">
        <h2>Rating Details</h2>
        
        <?php if ($rating): ?>
            <div class="client-info">
                <span class="rating-label">Rated by:</span>
                <span class="rating-value"><?php echo $rating['First_Name'] . ' ' . $rating['Last_Name']; ?></span>
            </div>
            
            <div class="rating-item">
                <span class="rating-label">The finished project matched the agreed design and measurements:</span>
                <span class="rating-value"><?php echo $rating['criteria1']; ?>/5</span>
            </div>
            
            <div class="rating-item">
                <span class="rating-label">There were no alignment issues:</span>
                <span class="rating-value"><?php echo $rating['criteria2']; ?>/5</span>
            </div>
            
            <div class="rating-item">
                <span class="rating-label">The finishing quality is smooth and professionally done:</span>
                <span class="rating-value"><?php echo $rating['criteria3']; ?>/5</span>
            </div>
            
            <div class="rating-item">
                <span class="rating-label">There were no visible defects:</span>
                <span class="rating-value"><?php echo $rating['criteria4']; ?>/5</span>
            </div>
            
            <div class="rating-item">
                <span class="rating-label">The carpenter followed proper carpentry techniques:</span>
                <span class="rating-value"><?php echo $rating['criteria5']; ?>/5</span>
            </div>
            
            <div class="rating-item">
                <span class="rating-label">The project was completed within the agreed timeline:</span>
                <span class="rating-value"><?php echo $rating['criteria6']; ?>/5</span>
            </div>
            
            <div class="rating-item">
                <span class="rating-label">The carpenter maintained a clean and organized work area:</span>
                <span class="rating-value"><?php echo $rating['criteria7']; ?>/5</span>
            </div>
            
            <div class="rating-item">
                <span class="rating-label">The carpenter was professional and communicated effectively:</span>
                <span class="rating-value"><?php echo $rating['criteria8']; ?>/5</span>
            </div>

            <div class="comments">
                <h3>Additional Comments:</h3>
                <p><?php echo nl2br(htmlspecialchars($rating['comments'])); ?></p>
            </div>

            <div class="rating-item">
                <span class="rating-label">Rating Date:</span>
                <span class="rating-value"><?php echo date('F j, Y, g:i a', strtotime($rating['rating_date'])); ?></span>
            </div>
        <?php else: ?>
            <p>No rating found for this contract.</p>
        <?php endif; ?>

        <button onclick="window.location.href='viewaddedrequirements.php?contract_ID=<?php echo $contract_ID; ?>'">Go Back</button>
    </div>
</body>
</html>