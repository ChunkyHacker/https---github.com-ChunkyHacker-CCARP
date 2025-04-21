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
$rating_sql = "SELECT r.*, u.First_Name, u.Last_Name,
               r.site_prep_score, r.materials_score, r.foundation_score,
               r.mep_score, r.exterior_score, r.interior_score,
               r.windows_score, r.insulation_score, r.landscaping_score,
               r.final_score, r.comments, r.rating_date
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
                <span class="rating-label">Site Preparation and Safety:</span>
                <span class="rating-value"><?php echo $rating['site_prep_score']; ?>/5</span>
            </div>
            
            <div class="rating-item">
                <span class="rating-label">Materials:</span>
                <span class="rating-value"><?php echo $rating['materials_score']; ?>/5</span>
            </div>
            
            <div class="rating-item">
                <span class="rating-label">Foundations and Structural Framing:</span>
                <span class="rating-value"><?php echo $rating['foundation_score']; ?>/5</span>
            </div>
            
            <div class="rating-item">
                <span class="rating-label">Mechanical, Electrical, and Plumbing (MEP):</span>
                <span class="rating-value"><?php echo $rating['mep_score']; ?>/5</span>
            </div>
            
            <div class="rating-item">
                <span class="rating-label">Exterior Cladding and Roofing:</span>
                <span class="rating-value"><?php echo $rating['exterior_score']; ?>/5</span>
            </div>
            
            <div class="rating-item">
                <span class="rating-label">Interior Finishes:</span>
                <span class="rating-value"><?php echo $rating['interior_score']; ?>/5</span>
            </div>
            
            <div class="rating-item">
                <span class="rating-label">Windows, Doors, and Hardware:</span>
                <span class="rating-value"><?php echo $rating['windows_score']; ?>/5</span>
            </div>
            
            <div class="rating-item">
                <span class="rating-label">Insulation and Ventilation:</span>
                <span class="rating-value"><?php echo $rating['insulation_score']; ?>/5</span>
            </div>

            <div class="rating-item">
                <span class="rating-label">Landscaping and External Works:</span>
                <span class="rating-value"><?php echo $rating['landscaping_score']; ?>/5</span>
            </div>

            <div class="rating-item">
                <span class="rating-label">Final Inspection:</span>
                <span class="rating-value"><?php echo $rating['final_score']; ?>/5</span>
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