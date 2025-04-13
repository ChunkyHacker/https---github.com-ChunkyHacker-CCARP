<?php
include 'config.php';

if (isset($_GET['plan_id'])) {
    $plan_id = $_GET['plan_id'];
    
    $sql = "SELECT DISTINCT c.Carpenter_ID, c.First_Name, c.Last_Name 
            FROM job_ratings jr 
            JOIN carpenters c ON jr.Carpenter_ID = c.Carpenter_ID 
            WHERE jr.plan_ID = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $plan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $carpenters = [];
    while ($row = $result->fetch_assoc()) {
        $carpenters[] = $row;
    }
    
    echo json_encode($carpenters);
} else {
    echo json_encode(['error' => 'No plan ID provided']);
}

$conn->close();
?>