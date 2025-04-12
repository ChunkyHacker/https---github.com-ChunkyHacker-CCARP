<?php
include 'config.php';

if (isset($_GET['contract_id'])) {
    $contract_id = $_GET['contract_id'];
    
    try {
        $ratings_sql = "SELECT criteria1, criteria2, criteria3, criteria4, 
                              criteria5, criteria6, criteria7, criteria8, 
                              comments, rating_date 
                       FROM ratings 
                       WHERE contract_ID = ?";
        
        $stmt = $conn->prepare($ratings_sql);
        $stmt->bind_param("i", $contract_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'No ratings found']);
        }
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'An error occurred']);
    }
}

$conn->close();
?>