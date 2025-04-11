<?php
include 'config.php';

if (isset($_GET['plan_id'])) {
    $plan_id = $_GET['plan_id'];
    
    // Get Progress
    $progress_sql = "SELECT Name, Status FROM report WHERE plan_id = ?";
    $stmt = $conn->prepare($progress_sql);
    $stmt->bind_param("i", $plan_id);
    $stmt->execute();
    $progress_result = $stmt->get_result();
    
    // Get Tasks
    $tasks_sql = "SELECT task, status, timestamp FROM task WHERE plan_id = ? AND status != 'completed'";
    $stmt = $conn->prepare($tasks_sql);
    $stmt->bind_param("i", $plan_id);
    $stmt->execute();
    $tasks_result = $stmt->get_result();
    
    // Get Completed Tasks
    $completed_sql = "SELECT name, timestamp, status FROM completed_task WHERE plan_id = ? AND status = 'completed'";
    $stmt = $conn->prepare($completed_sql);
    $stmt->bind_param("i", $plan_id);
    $stmt->execute();
    $completed_result = $stmt->get_result();
    
    // Get Attendance
    $attendance_sql = "SELECT type_of_work, Time_in, Time_out FROM attendance WHERE plan_id = ?";
    $stmt = $conn->prepare($attendance_sql);
    $stmt->bind_param("i", $plan_id);
    $stmt->execute();
    $attendance_result = $stmt->get_result();
    
    $data = [
        'progress' => $progress_result->fetch_all(MYSQLI_ASSOC),
        'tasks' => $tasks_result->fetch_all(MYSQLI_ASSOC),
        'completed' => $completed_result->fetch_all(MYSQLI_ASSOC),
        'attendance' => $attendance_result->fetch_all(MYSQLI_ASSOC)
    ];
    
    echo json_encode($data);
}

$conn->close();
?>