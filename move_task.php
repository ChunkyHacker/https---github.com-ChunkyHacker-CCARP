<?php
include('config.php');
date_default_timezone_set('Asia/Manila'); // Add this line to set Philippine timezone

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    ob_clean();
    
    $data = json_decode(file_get_contents("php://input"), true);

    if (!empty($data)) {
        $response = array();
        foreach ($data as $task) {
            $task_id = intval($task["task_id"]);
            $task_name = $task["task_name"];
            
            // Get the original timestamp from task table
            $getTimeQuery = "SELECT timestamp FROM task WHERE task_id = ?";
            $timeStmt = $conn->prepare($getTimeQuery);
            $timeStmt->bind_param("i", $task_id);
            $timeStmt->execute();
            $timeResult = $timeStmt->get_result();
            $timeRow = $timeResult->fetch_assoc();
            $start_time = $timeRow['timestamp']; // Use the original timestamp from task table
            
            $end_time = date('m/d/Y, h:i:s A');
            $status = $task["status"];

            if ($status === "Done") {
                // Start transaction
                $conn->begin_transaction();
                try {
                    // Get contract_ID
                    $queryRequirement = "SELECT contract_ID FROM task WHERE task_id = ?";
                    $stmtRequirement = $conn->prepare($queryRequirement);
                    $stmtRequirement->bind_param("i", $task_id);
                    $stmtRequirement->execute();
                    $resultRequirement = $stmtRequirement->get_result();
                    $rowRequirement = $resultRequirement->fetch_assoc();
                    $contract_ID = $rowRequirement['contract_ID'];

                    // Disable foreign key checks
                    $conn->query("SET FOREIGN_KEY_CHECKS=0");

                    // Insert into completed_task with start_time and end_time
                    $sql = "INSERT INTO completed_task (name, task_id, contract_ID, status, start_time, end_time) 
                           VALUES (?, ?, ?, 'Completed', ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("siiss", $task_name, $task_id, $contract_ID, $start_time, $end_time);
                    $stmt->execute();

                    // Delete from task
                    $delete_sql = "DELETE FROM task WHERE task_id = ?";
                    $delete_stmt = $conn->prepare($delete_sql);
                    $delete_stmt->bind_param("i", $task_id);
                    $delete_stmt->execute();

                    // Enable foreign key checks
                    $conn->query("SET FOREIGN_KEY_CHECKS=1");

                    // Commit transaction
                    $conn->commit();

                    $response = array(
                        'status' => 'success',
                        'title' => 'Success!',
                        'message' => 'Task completed successfully!'
                    );
                } catch (Exception $e) {
                    // Rollback on error
                    $conn->rollback();
                    $response = array(
                        'status' => 'error',
                        'title' => 'Error!',
                        'message' => 'Failed to complete task: ' . $e->getMessage()
                    );
                }
            } else if ($status === "Working") {
                $sql = "UPDATE task SET status = ? WHERE task_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $status, $task_id);
                
                if ($stmt->execute()) {
                    $response = array(
                        'status' => 'info',
                        'title' => 'Status Updated',
                        'message' => 'Task is now in progress'
                    );
                }
            } else if ($status === "Not yet started") {
                $sql = "UPDATE task SET status = ? WHERE task_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $status, $task_id);
                
                if ($stmt->execute()) {
                    $response = array(
                        'status' => 'info',
                        'title' => 'Status Updated',
                        'message' => 'Task status set to not yet started'
                    );
                }
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
$conn->close();
