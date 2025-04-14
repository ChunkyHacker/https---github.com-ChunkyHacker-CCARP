<?php
include 'config.php';

$sql = "SELECT 
            c.contract_ID,
            c.Carpenter_ID,
            p.start_date,
            p.end_date,
            pt.created_at as actual_completion_date,
            (SELECT COUNT(*) FROM completed_task WHERE contract_ID = c.contract_ID) as task_completed,
            DATEDIFF(p.end_date, p.start_date) as total_days_estimated,
            DATEDIFF(pt.created_at, p.start_date) as total_days_spent,
            ROUND(((DATEDIFF(p.end_date, p.start_date) - DATEDIFF(pt.created_at, p.start_date)) / 
                   DATEDIFF(p.end_date, p.start_date)) * 100, 2) as efficiency_gain
        FROM contracts c
        JOIN plan p ON c.Plan_ID = p.Plan_ID
        JOIN project_turnover pt ON c.contract_ID = pt.contract_ID
        WHERE c.status = 'completed'";

$result = $conn->query($sql);
$data = array();

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>