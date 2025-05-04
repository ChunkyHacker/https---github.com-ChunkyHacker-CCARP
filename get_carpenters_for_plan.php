<?php
include 'config.php';

if (isset($_GET['plan_id'])) {
    $plan_id = $_GET['plan_id'];
    
    // Fixed: Changed planID to plan_ID to match database column name
    $sql = "SELECT DISTINCT c.Carpenter_ID, c.First_Name, c.Last_Name 
            FROM job_ratings jr 
            JOIN carpenters c ON jr.Carpenter_ID = c.Carpenter_ID 
            WHERE jr.plan_ID = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $plan_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $carpenters = array();
    while ($row = $result->fetch_assoc()) {
        $carpenters[] = array(
            'Carpenter_ID' => $row['Carpenter_ID'],
            'First_Name' => $row['First_Name'],
            'Last_Name' => $row['Last_Name']
        );
    }
    
    header('Content-Type: application/json');
    echo json_encode($carpenters);
} else {
    echo json_encode(['error' => 'No plan ID provided']);
}

$conn->close();
?>