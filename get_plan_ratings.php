<?php
include 'config.php';

if (isset($_GET['plan_id'])) {
    $plan_id = $_GET['plan_id'];
    
    $sql = "SELECT 
        CONCAT(c.First_Name, ' ', c.Last_Name) as carpenter_name,
        jr.rating,
        jr.comments,
        jr.rating_date
    FROM job_ratings jr
    JOIN carpenters c ON jr.Carpenter_ID = c.Carpenter_ID
    WHERE jr.plan_ID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $plan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $ratings = array();
    while ($row = $result->fetch_assoc()) {
        $ratings[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($ratings);
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Plan ID not provided']);
}

$conn->close();
?>