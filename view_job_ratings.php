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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .question-text {
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Job Ratings Results</h1>
    <table>
        <tr>
            <th>Carpenter Name</th>
            <th>Plan Type</th>
            <th>Q1. Navigation Ease</th>
            <th>Q2. Job Relevance</th>
            <th>Q3. Job Opportunities</th>
            <th>Q4. Communication Ease</th>
            <th>Q5. Engagement Level</th>
            <th>Q6. Recommendation</th>
            <th>Q7. Accessibility</th>
            <th>Q8. Issues</th>
            <th>Q9. Additional Features</th>
            <th>Q10. Feedback</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['First_Name'] . ' ' . $row['Last_Name']; ?></td>
            <td><?php echo $row['type']; ?></td>
            <td>Rating: <?php echo $row['q1_rating']; ?> - <?php echo $rating_descriptions['q1'][$row['q1_rating']]; ?></td>
            <td>Rating: <?php echo $row['q2_rating']; ?> - <?php echo $rating_descriptions['q2'][$row['q2_rating']]; ?></td>
            <td>Rating: <?php echo $row['q3_rating']; ?> - <?php echo $rating_descriptions['q3'][$row['q3_rating']]; ?></td>
            <td>Rating: <?php echo $row['q4_rating']; ?> - <?php echo $rating_descriptions['q4'][$row['q4_rating']]; ?></td>
            <td>Rating: <?php echo $row['q5_rating']; ?> - <?php echo $rating_descriptions['q5'][$row['q5_rating']]; ?></td>
            <td>Rating: <?php echo $row['q6_rating']; ?> - <?php echo $rating_descriptions['q6'][$row['q6_rating']]; ?></td>
            <td>Rating: <?php echo $row['q7_rating']; ?> - <?php echo $rating_descriptions['q7'][$row['q7_rating']]; ?></td>
            <td>
                Answer: <?php 
                echo $row['q8_answer'];
                if ($row['q8_answer'] == 'yes') {
                    echo '<br>Explanation: ' . $row['q8_explanation'];
                }
                ?>
            </td>
            <td>Answer: <?php echo $row['q9_answer']; ?></td>
            <td>Answer: <?php echo $row['q10_answer']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Add the button here, after the table -->
    <div style="margin: 20px;">
        <button onclick="window.location.href='viewapprovedplan.php?plan_ID=<?php echo $plan_ID; ?>'" 
            style='padding: 10px 20px; background-color: #FF8C00; color: white; 
            border: none; border-radius: 5px; cursor: pointer;'>
            Go Back</button>
    </div>
</body>
</html>