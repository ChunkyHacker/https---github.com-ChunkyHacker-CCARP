<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!empty($data)) {
        foreach ($data as $task) {
            $task_id = intval($task["task_id"]);
            $task_name = $task["task_name"];
            $task_details = $task["task_details"];
            $status = $task["status"]; // Gikuha ang status gikan sa frontend

            if ($status === "Done") {
                // Get requirement_ID from task table
                $queryRequirement = "SELECT requirement_ID FROM task WHERE task_id = ?";
                $stmtRequirement = $conn->prepare($queryRequirement);
                $stmtRequirement->bind_param("i", $task_id);
                $stmtRequirement->execute();
                $resultRequirement = $stmtRequirement->get_result();
                $rowRequirement = $resultRequirement->fetch_assoc();
                $requirement_ID = $rowRequirement['requirement_ID'];

                // Insert the task into completed_task table with requirement_ID
                $sql = "INSERT INTO completed_task (name, details, task_id, requirement_ID, status) VALUES (?, ?, ?, ?, 'Completed')";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssii", $task_name, $task_details, $task_id, $requirement_ID);
                
                if ($stmt->execute()) {
                    // Temporarily disable foreign key checks
                    $conn->query("SET FOREIGN_KEY_CHECKS=0");
                
                    // Delete the task from task table after successful insert
                    $delete_sql = "DELETE FROM task WHERE task_id = ?";
                    $delete_stmt = $conn->prepare($delete_sql);
                    $delete_stmt->bind_param("i", $task_id);
                    $delete_stmt->execute();
                
                    // Re-enable foreign key checks
                    $conn->query("SET FOREIGN_KEY_CHECKS=1");

                    echo "Good Job!";
                }                
            } else {
                echo "Buhata sa na bago na mabutang sa completed task.";
            }
        }
    } else {
        echo "No tasks selected!";
    }
}

$conn->close();
?>
