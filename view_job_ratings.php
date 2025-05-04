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

            <!-- SECTION 1: DISCOMFORT -->
            <h3>DISCOMFORT</h3>
            <div class="rating-item">
                <span>I feel overwhelmed when using this recruitment platform:</span>
                <span class="rating-value"><?php echo $row['DIS1']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>Sometimes, I think this platform is too complicated to use:</span>
                <span class="rating-value"><?php echo $row['DIS2']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>I hesitate to use the platform when I cannot understand the instructions:</span>
                <span class="rating-value"><?php echo $row['DIS3']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>I worry that I might make mistakes while using the system:</span>
                <span class="rating-value"><?php echo $row['DIS4']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>I often feel confused when navigating this platform:</span>
                <span class="rating-value"><?php echo $row['DIS5']; ?>/5</span>
            </div>

            <!-- SECTION 2: INNOVATIVENESS -->
            <h3>INNOVATIVENESS</h3>
            <div class="rating-item">
                <span>I consider myself among the first to try new recruitment platforms:</span>
                <span class="rating-value"><?php echo $row['INN1']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>I like discovering new features on online recruitment systems:</span>
                <span class="rating-value"><?php echo $row['INN2']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>I actively seek out new technologies to improve my job search:</span>
                <span class="rating-value"><?php echo $row['INN3']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>I enjoy using advanced features even before others do:</span>
                <span class="rating-value"><?php echo $row['INN4']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>I prefer platforms that introduce new job-seeking tools:</span>
                <span class="rating-value"><?php echo $row['INN5']; ?>/5</span>
            </div>

            <!-- SECTION 3: INSECURITY -->
            <h3>INSECURITY</h3>
            <div class="rating-item">
                <span>I am concerned about sharing personal data through this platform:</span>
                <span class="rating-value"><?php echo $row['INS1']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>I feel unsure whether my information is securely protected:</span>
                <span class="rating-value"><?php echo $row['INS2']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>I doubt the trustworthiness of the job postings:</span>
                <span class="rating-value"><?php echo $row['INS3']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>I fear my account or information might be hacked or leaked:</span>
                <span class="rating-value"><?php echo $row['INS4']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>I feel that my privacy might be compromised when using this platform:</span>
                <span class="rating-value"><?php echo $row['INS5']; ?>/5</span>
            </div>

            <!-- SECTION 4: OPTIMISM -->
            <h3>OPTIMISM</h3>
            <div class="rating-item">
                <span>This platform makes job searching faster and easier for me:</span>
                <span class="rating-value"><?php echo $row['OPT1']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>I feel more confident applying for jobs because of this system:</span>
                <span class="rating-value"><?php echo $row['OPT2']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>I believe this web app empowers me to connect better with employers:</span>
                <span class="rating-value"><?php echo $row['OPT3']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>I believe technology makes recruitment more efficient:</span>
                <span class="rating-value"><?php echo $row['OPT4']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>Using this system gives me better control over my job applications:</span>
                <span class="rating-value"><?php echo $row['OPT5']; ?>/5</span>
            </div>

            <!-- SECTION 5: INTENTION TO USE -->
            <h3>INTENTION TO USE</h3>
            <div class="rating-item">
                <span>I intend to continue using this recruitment platform:</span>
                <span class="rating-value"><?php echo $row['INTB1']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>I would recommend this platform to other job seekers:</span>
                <span class="rating-value"><?php echo $row['INTB2']; ?>/5</span>
            </div>
            <div class="rating-item">
                <span>I will likely use this platform again for future job applications:</span>
                <span class="rating-value"><?php echo $row['INTB3']; ?>/5</span>
            </div>

            <div class="rating-date">
                Rating Date: <?php echo date('F j, Y, g:i a', strtotime($row['rating_date'])); ?>
            </div>
        <?php endwhile; ?>

        <a href="viewapprovedplan.php?plan_ID=<?php echo $plan_ID; ?>" class="back-btn">Go Back</a>
    </div>
</body>
</html>