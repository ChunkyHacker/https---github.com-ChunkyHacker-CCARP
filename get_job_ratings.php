<?php
include 'config.php';

if (isset($_GET['plan_id']) && isset($_GET['carpenter_id'])) {
    $plan_id = $_GET['plan_id'];
    $carpenter_id = $_GET['carpenter_id'];
    
    $sql = "SELECT 
        jr.*,
        CONCAT(c.First_Name, ' ', c.Last_Name) as carpenter_name,
        CASE 
            WHEN q1_rating = 1 THEN 'Very difficult'
            WHEN q1_rating = 2 THEN 'Difficult'
            WHEN q1_rating = 3 THEN 'Moderate'
            WHEN q1_rating = 4 THEN 'Easy'
            WHEN q1_rating = 5 THEN 'Very Easy'
        END as q1_description,
        CASE 
            WHEN q2_rating = 1 THEN 'Not relevant at all'
            WHEN q2_rating = 2 THEN 'Slightly relevant'
            WHEN q2_rating = 3 THEN 'Moderately relevant'
            WHEN q2_rating = 4 THEN 'Very relevant'
            WHEN q2_rating = 5 THEN 'Extremely relevant'
        END as q2_description,
        CASE 
            WHEN q3_rating = 1 THEN 'Very Dissatisfied'
            WHEN q3_rating = 2 THEN 'Dissatisfied'
            WHEN q3_rating = 3 THEN 'Neutral'
            WHEN q3_rating = 4 THEN 'Satisfied'
            WHEN q3_rating = 5 THEN 'Very Satisfied'
        END as q3_description,
        CASE 
            WHEN q4_rating = 1 THEN 'Not easy at all'
            WHEN q4_rating = 2 THEN 'Slightly easy'
            WHEN q4_rating = 3 THEN 'Moderately easy'
            WHEN q4_rating = 4 THEN 'Very easy'
            WHEN q4_rating = 5 THEN 'Extremely easy'
        END as q4_description,
        CASE 
            WHEN q5_rating = 1 THEN 'Never'
            WHEN q5_rating = 2 THEN 'Rarely'
            WHEN q5_rating = 3 THEN 'Sometimes'
            WHEN q5_rating = 4 THEN 'Often'
            WHEN q5_rating = 5 THEN 'Very Frequently'
        END as q5_description,
        CASE 
            WHEN q6_rating = 1 THEN 'Not likely at all'
            WHEN q6_rating = 2 THEN 'Slightly likely'
            WHEN q6_rating = 3 THEN 'Moderately likely'
            WHEN q6_rating = 4 THEN 'Very likely'
            WHEN q6_rating = 5 THEN 'Extremely likely'
        END as q6_description,
        CASE 
            WHEN q7_rating = 1 THEN 'Very Poor'
            WHEN q7_rating = 2 THEN 'Poor'
            WHEN q7_rating = 3 THEN 'Fair'
            WHEN q7_rating = 4 THEN 'Good'
            WHEN q7_rating = 5 THEN 'Excellent'
        END as q7_description
        FROM job_ratings jr
        LEFT JOIN carpenters c ON jr.Carpenter_ID = c.Carpenter_ID
        WHERE jr.plan_ID = ? AND jr.Carpenter_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $plan_id, $carpenter_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(['error' => 'No ratings found']);
    }
} else {
    echo json_encode(['error' => 'Missing parameters']);
}

$conn->close();
?>