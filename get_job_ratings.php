<?php
include 'config.php';

if (isset($_GET['plan_id']) && isset($_GET['carpenter_id'])) {
    $plan_id = $_GET['plan_id'];
    $carpenter_id = $_GET['carpenter_id'];
    
    $sql = "SELECT 
        jr.*,
        DATE_FORMAT(rating_date, '%M %d, %Y %h:%i %p') as formatted_date
        FROM job_ratings jr
        WHERE jr.plan_ID = ? AND jr.Carpenter_ID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $plan_id, $carpenter_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        
        // Format the response to include all rating fields
        $response = [
            'DIS1' => $data['DIS1'] ?? 0,
            'DIS2' => $data['DIS2'] ?? 0,
            'DIS3' => $data['DIS3'] ?? 0,
            'DIS4' => $data['DIS4'] ?? 0,
            'DIS5' => $data['DIS5'] ?? 0,
            'INN1' => $data['INN1'] ?? 0,
            'INN2' => $data['INN2'] ?? 0,
            'INN3' => $data['INN3'] ?? 0,
            'INN4' => $data['INN4'] ?? 0,
            'INN5' => $data['INN5'] ?? 0,
            'INS1' => $data['INS1'] ?? 0,
            'INS2' => $data['INS2'] ?? 0,
            'INS3' => $data['INS3'] ?? 0,
            'INS4' => $data['INS4'] ?? 0,
            'INS5' => $data['INS5'] ?? 0,
            'OPT1' => $data['OPT1'] ?? 0,
            'OPT2' => $data['OPT2'] ?? 0,
            'OPT3' => $data['OPT3'] ?? 0,
            'OPT4' => $data['OPT4'] ?? 0,
            'OPT5' => $data['OPT5'] ?? 0,
            'INTB1' => $data['INTB1'] ?? 0,
            'INTB2' => $data['INTB2'] ?? 0,
            'INTB3' => $data['INTB3'] ?? 0,
            'rating_date' => $data['formatted_date']
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'No ratings found']);
    }
} else {
    echo json_encode(['error' => 'Missing parameters']);
}

$conn->close();
?>