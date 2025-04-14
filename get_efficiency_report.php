<?php
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $sql = "SELECT 
        pt.contract_ID,
        con.Carpenter_ID,
        p.start_date,
        p.end_date,
        pt.actual_completion_date,
        (SELECT COUNT(*) FROM completed_task WHERE contract_ID = pt.contract_ID) as task_completed,
        DATEDIFF(p.end_date, p.start_date) as total_days_estimated,
        DATEDIFF(pt.actual_completion_date, p.start_date) as total_days_spent,
        CASE 
            WHEN DATEDIFF(p.end_date, p.start_date) > 0 
            THEN ROUND(((DATEDIFF(p.end_date, p.start_date) - 
                DATEDIFF(pt.actual_completion_date, p.start_date)) / 
                DATEDIFF(p.end_date, p.start_date) * 100), 2)
            ELSE 0
        END as efficiency_gain
    FROM project_turnover pt
    JOIN contracts con ON pt.contract_ID = con.contract_ID
    JOIN plan p ON con.plan_ID = p.plan_ID
    WHERE pt.confirmation_status = 'confirmed'
    ORDER BY pt.actual_completion_date DESC";

    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception($conn->error);
    }

    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode(['success' => true, 'data' => $data]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>