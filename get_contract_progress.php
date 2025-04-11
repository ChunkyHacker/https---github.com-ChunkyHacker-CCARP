<?php
include 'config.php';

if (isset($_GET['contract_id'])) {
    $contract_id = $_GET['contract_id'];
    
    try {
        // Get Progress from report table
        $progress_sql = "SELECT Name, Status, contract_ID 
                        FROM report 
                        WHERE contract_ID = ?";
        $stmt = $conn->prepare($progress_sql);
        $stmt->bind_param("i", $contract_id);
        $stmt->execute();
        $progress_result = $stmt->get_result();
        
        // Get Pending Tasks from task table
        $tasks_sql = "SELECT status, task, timestamp as Time 
                     FROM task 
                     WHERE contract_ID = ? AND status != 'completed'";
        $stmt = $conn->prepare($tasks_sql);
        $stmt->bind_param("i", $contract_id);
        $stmt->execute();
        $tasks_result = $stmt->get_result();
        
        // Get Completed Tasks from completed_task table
        $completed_sql = "SELECT name as Task, timestamp as Time, status as Status 
                         FROM completed_task 
                         WHERE contract_ID = ?";
        $stmt = $conn->prepare($completed_sql);
        $stmt->bind_param("i", $contract_id);
        $stmt->execute();
        $completed_result = $stmt->get_result();
        
        // Get Attendance
        $attendance_sql = "SELECT type_of_work as 'Type of Work', 
                                Time_in as 'Time In', 
                                Time_out as 'Time Out' 
                         FROM attendance 
                         WHERE contract_ID = ?";
        $stmt = $conn->prepare($attendance_sql);
        $stmt->bind_param("i", $contract_id);
        $stmt->execute();
        $attendance_result = $stmt->get_result();
        
        $data = [
            'progress' => $progress_result->fetch_all(MYSQLI_ASSOC),
            'pending_tasks' => $tasks_result->fetch_all(MYSQLI_ASSOC),
            'completed_tasks' => $completed_result->fetch_all(MYSQLI_ASSOC),
            'attendance' => $attendance_result->fetch_all(MYSQLI_ASSOC)
        ];
        
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'An error occurred']);
    }
}

$conn->close();
?>