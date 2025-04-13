<?php
include 'config.php';

if (isset($_GET['plan_id'])) {
    $plan_id = $_GET['plan_id'];
    
    // Get the rating descriptions
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

    // Add var_dump to check the data
    $sql = "SELECT jr.*, c.First_Name, c.Last_Name 
            FROM job_ratings jr 
            LEFT JOIN carpenters c ON jr.Carpenter_ID = c.Carpenter_ID 
            WHERE jr.plan_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $plan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Debug: Check what data we're getting
        error_log(print_r($row, true));
        
        // Add carpenter name to the result
        $row['carpenter_name'] = $row['First_Name'] . ' ' . $row['Last_Name'];
        
        // Debug: Check if name is added
        error_log("Carpenter Name: " . $row['carpenter_name']);
        
        // Add descriptions to the ratings
        for ($i = 1; $i <= 7; $i++) {  // Fixed the 'i' to '$i'
            $rating = $row['q' . $i . '_rating'];
            $row['q' . $i . '_description'] = $rating_descriptions['q' . $i][$rating];
        }
        
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'No ratings found']);
    }
} else {
    echo json_encode(['error' => 'No plan ID provided']);
}

$conn->close();
?>