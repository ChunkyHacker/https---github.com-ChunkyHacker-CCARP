<?php
include 'config.php';

if (isset($_GET['contract_id'])) {
    $contract_id = $_GET['contract_id'];
    
    $sql = "SELECT r.*, 
            CONCAT(c.First_Name, ' ', c.Last_Name) as carpenter_name,
            r.rating_date,
            r.comments
            FROM ratings r
            JOIN carpenters c ON r.Carpenter_ID = c.Carpenter_ID
            WHERE r.contract_ID = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $contract_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Map the ratings to criteria descriptions
        $criteria_descriptions = [
            'criteria1' => ['Site Preparation', $row['site_prep_score']],
            'criteria2' => ['Materials Management', $row['materials_score']],
            'criteria3' => ['Foundation Work', $row['foundation_score']],
            'criteria4' => ['MEP Installation', $row['mep_score']],
            'criteria5' => ['Exterior Construction', $row['exterior_score']],
            'criteria6' => ['Interior Finishing', $row['interior_score']],
            'criteria7' => ['Windows Installation', $row['windows_score']],
            'criteria8' => ['Insulation Work', $row['insulation_score']],
            'criteria9' => ['Landscaping', $row['landscaping_score']],
            'criteria10' => ['Final Inspection', $row['final_score']]
        ];
        
        $response = [
            'carpenter_name' => $row['carpenter_name'],
            'rating_date' => $row['rating_date'],
            'comments' => $row['comments']
        ];
        
        // Add criteria ratings and descriptions to response
        foreach ($criteria_descriptions as $key => $value) {
            $response[$key . '_description'] = $value[0];
            $response[$key . '_rating'] = $value[1];
        }
        
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'No ratings found']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['error' => 'No contract ID provided']);
}

$conn->close();
?>