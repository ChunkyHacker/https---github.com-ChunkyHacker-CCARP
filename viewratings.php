<?php
include('config.php');
session_start();

if (!isset($_SESSION['User_ID'])) {
    echo "<script>alert('You need to log in first.'); window.location.href='login.php';</script>";
    exit();
}

$user_ID = $_SESSION['User_ID'];
$plan_ID = isset($_GET['plan_ID']) ? $_GET['plan_ID'] : null;
$contract_ID = isset($_GET['contract_ID']) ? $_GET['contract_ID'] : null;

// Fetch rating details
$rating_sql = "SELECT * FROM ratings WHERE plan_ID = ? AND contract_ID = ?";
$rating_stmt = $conn->prepare($rating_sql);
$rating_stmt->bind_param("ii", $plan_ID, $contract_ID);
$rating_stmt->execute();
$rating_result = $rating_stmt->get_result();
$rating = $rating_result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Ratings</title>
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
        }
        .rating-value {
            margin-left: 10px;
            color: #FF8C00;
            font-size: 18px;
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
            <!-- Site Preparation and Safety -->
            <h3>1. Site Preparation and Safety</h3>
            <div class="rating-item">
                <span class="rating-label">Average Score:</span>
                <span class="rating-value"><?php echo $rating['site_prep_score']; ?>/5</span>
            </div>

            <!-- Materials -->
            <h3>2. Materials</h3>
            <div class="rating-item">
                <span class="rating-label">Average Score:</span>
                <span class="rating-value"><?php echo $rating['materials_score']; ?>/5</span>
            </div>

            <!-- Foundations and Structural Framing -->
            <h3>3. Foundations and Structural Framing</h3>
            <div class="rating-item">
                <span class="rating-label">Average Score:</span>
                <span class="rating-value"><?php echo $rating['foundation_score']; ?>/5</span>
            </div>

            <!-- MEP -->
            <h3>4. Mechanical, Electrical, and Plumbing (MEP)</h3>
            <div class="rating-item">
                <span class="rating-label">Average Score:</span>
                <span class="rating-value"><?php echo $rating['mep_score']; ?>/5</span>
            </div>

            <!-- Exterior -->
            <h3>5. Exterior Cladding and Roofing</h3>
            <div class="rating-item">
                <span class="rating-label">Average Score:</span>
                <span class="rating-value"><?php echo $rating['exterior_score']; ?>/5</span>
            </div>

            <!-- Interior -->
            <h3>6. Interior Finishes</h3>
            <div class="rating-item">
                <span class="rating-label">Average Score:</span>
                <span class="rating-value"><?php echo $rating['interior_score']; ?>/5</span>
            </div>

            <!-- Windows -->
            <h3>7. Windows and Doors</h3>
            <div class="rating-item">
                <span class="rating-label">Average Score:</span>
                <span class="rating-value"><?php echo $rating['windows_score']; ?>/5</span>
            </div>

            <!-- Insulation -->
            <h3>8. Insulation and Ventilation</h3>
            <div class="rating-item">
                <span class="rating-label">Average Score:</span>
                <span class="rating-value"><?php echo $rating['insulation_score']; ?>/5</span>
            </div>

            <!-- Comments -->
            <div class="comments">
                <h3>Additional Comments:</h3>
                <p><?php echo nl2br(htmlspecialchars($rating['comments'])); ?></p>
            </div>

            <div class="rating-item">
                <span class="rating-label">Rating Date:</span>
                <span class="rating-value"><?php echo date('F j, Y, g:i a', strtotime($rating['rating_date'])); ?></span>
            </div>
        <?php else: ?>
            <p>No rating found for this plan.</p>
        <?php endif; ?>

        <button onclick="window.location.href='userviewprogress.php?plan_ID=<?php echo $plan_ID; ?>'">Go Back</button>
    </div>
</body>
</html>