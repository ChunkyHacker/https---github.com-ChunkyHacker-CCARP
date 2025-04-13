<?php
session_start();
include('config.php');

// Check if carpenter is logged in
if (!isset($_SESSION['Carpenter_ID'])) {
    header('Location: login.html');
    exit();
}

// Check if plan_ID is set
if (!isset($_GET['plan_ID'])) {
    header('Location: profile.php');
    exit();
}

$Carpenter_ID = $_SESSION['Carpenter_ID'];
$plan_ID = $_GET['plan_ID'];

// Define the rating descriptions
$rating_descriptions = [
    'q1' => [
        1 => 'Very difficult',
        2 => 'Difficult',
        3 => 'Neutral',
        4 => 'Easy',
        5 => 'Very Easy'
    ],
    'q2' => [
        1 => 'Not relevant at all',
        2 => 'Slightly Relevant',
        3 => 'Neutral',
        4 => 'Relevant',
        5 => 'Extremely Relevant'
    ],
    'q3' => [
        1 => 'Very Dissatisfied',
        2 => 'Dissatisfied',
        3 => 'Neutral',
        4 => 'Satisfied',
        5 => 'Extremely Satisfied'
    ],
    'q4' => [
        1 => 'Not easy at all',
        2 => 'Somewhat Difficult',
        3 => 'Neutral',
        4 => 'Somewhat Easy',
        5 => 'Very Easy'
    ],
    'q5' => [
        1 => 'Never',
        2 => 'Rarely',
        3 => 'Occasionally',
        4 => 'Frequently',
        5 => 'Very Frequently'
    ],
    'q6' => [
        1 => 'Not likely at all',
        2 => 'Unlikely',
        3 => 'Neutral',
        4 => 'Good',
        5 => 'Excellent'
    ],
    'q7' => [
        1 => 'Very Poor',
        2 => 'Poor',
        3 => 'Neutral',
        4 => 'Good',
        5 => 'Excellent'
    ]
];

// Get ratings data
// Get the logged-in carpenter's ID
$Carpenter_ID = $_SESSION['Carpenter_ID'];

// Get ratings data for the specific carpenter only
$sql = "SELECT jr.*, c.First_Name, c.Last_Name, p.type 
        FROM job_ratings jr 
        JOIN carpenters c ON jr.Carpenter_ID = c.Carpenter_ID 
        JOIN plan p ON jr.plan_ID = p.plan_ID
        WHERE jr.Carpenter_ID = ? AND jr.plan_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $Carpenter_ID, $plan_ID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Ratings Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .rating-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .rating-header {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .rating-item {
            padding: 10px;
            margin: 5px 0;
            background: #f9f9f9;
            border-radius: 3px;
        }
        .rating-value {
            color: #ff6b01;
            font-weight: bold;
            float: right;
        }
        .rating-date {
            color: #666;
            font-size: 0.9em;
            margin-top: 20px;
        }
        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #FF8C00;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="rating-container">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="rating-header">
                <h2>Rating Details</h2>
                <p>Rated by: <?php echo $row['First_Name'] . ' ' . $row['Last_Name']; ?></p>
            </div>

            <div class="rating-item">
                <span>Navigation Ease:</span>
                <span class="rating-value"><?php echo $row['q1_rating']; ?>/5</span>
            </div>

            <div class="rating-item">
                <span>Job Relevance:</span>
                <span class="rating-value"><?php echo $row['q2_rating']; ?>/5</span>
            </div>

            <div class="rating-item">
                <span>Job Opportunities:</span>
                <span class="rating-value"><?php echo $row['q3_rating']; ?>/5</span>
            </div>

            <div class="rating-item">
                <span>Communication Ease:</span>
                <span class="rating-value"><?php echo $row['q4_rating']; ?>/5</span>
            </div>

            <div class="rating-item">
                <span>Engagement Level:</span>
                <span class="rating-value"><?php echo $row['q5_rating']; ?>/5</span>
            </div>

            <div class="rating-item">
                <span>Recommendation:</span>
                <span class="rating-value"><?php echo $row['q6_rating']; ?>/5</span>
            </div>

            <div class="rating-item">
                <span>Accessibility:</span>
                <span class="rating-value"><?php echo $row['q7_rating']; ?>/5</span>
            </div>

            <div class="rating-item">
                <span>Issues:</span>
                <div>
                    <?php 
                    echo $row['q8_answer'];
                    if ($row['q8_answer'] == 'yes') {
                        echo '<br>Explanation: ' . $row['q8_explanation'];
                    }
                    ?>
                </div>
            </div>

            <div class="rating-item">
                <span>Additional Features:</span>
                <div><?php echo $row['q9_answer']; ?></div>
            </div>

            <div class="rating-item">
                <span>Feedback:</span>
                <div><?php echo $row['q10_answer']; ?></div>
            </div>

            <div class="rating-date">
                Rating Date: <?php echo date('F j, Y, g:i a', strtotime($row['rating_date'])); ?>
            </div>
        <?php endwhile; ?>

        <a href="viewapprovedplan.php?plan_ID=<?php echo $plan_ID; ?>" class="back-btn">Go Back</a>
    </div>
</body>
</html>