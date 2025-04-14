<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contract_ID = $_POST['contract_ID'];
    
    // Get plan_ID from contracts table
    $sql_plan = "SELECT Plan_ID FROM contracts WHERE contract_ID = ?";
    $stmt = $conn->prepare($sql_plan);
    $stmt->bind_param("i", $contract_ID);
    $stmt->execute();
    $plan_result = $stmt->get_result();
    $plan_data = $plan_result->fetch_assoc();
    $plan_ID = $plan_data['Plan_ID'];

    // Get task_id and completed_task_id
    $sql_task = "SELECT task_id FROM task WHERE contract_ID = ? LIMIT 1";
    $stmt = $conn->prepare($sql_task);
    $stmt->bind_param("i", $contract_ID);
    $stmt->execute();
    $task_result = $stmt->get_result();
    $task_data = $task_result->fetch_assoc();
    $task_id = $task_data['task_id'];

    $sql_completed = "SELECT completed_task_id FROM completed_task WHERE contract_ID = ? LIMIT 1";
    $stmt = $conn->prepare($sql_completed);
    $stmt->bind_param("i", $contract_ID);
    $stmt->execute();
    $completed_result = $stmt->get_result();
    $completed_data = $completed_result->fetch_assoc();
    $completed_task_id = $completed_data['completed_task_id'];

    // Handle file upload
    $supporting_documents = '';
    if(isset($_FILES['attachments']) && $_FILES['attachments']['error'] == 0) {
        $target_dir = "uploads/turnover/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES["attachments"]["name"], PATHINFO_EXTENSION);
        $new_filename = "turnover_" . $contract_ID . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        if (move_uploaded_file($_FILES["attachments"]["tmp_name"], $target_file)) {
            $supporting_documents = $new_filename;
        }
    }

    // Insert into project_turnover
    $sql = "INSERT INTO project_turnover (contract_ID, plan_ID, task_id, completed_task_id, 
            actual_completion_date, client_signature, turnover_notes, supporting_documents) 
            VALUES (?, ?, ?, ?, CURRENT_DATE(), ?, ?, ?)";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiissss", 
        $contract_ID,
        $plan_ID,
        $task_id,
        $completed_task_id,
        $_POST['client_signature'],
        $_POST['turnover_notes'],
        $supporting_documents
    );

    if ($stmt->execute()) {
        // Update contract status
        $update_contract = "UPDATE contracts SET status = 'completed' WHERE contract_ID = ?";
        $stmt = $conn->prepare($update_contract);
        $stmt->bind_param("i", $contract_ID);
        $stmt->execute();

        header("Location: profile.php?success=true&message=Project turnover successful!");
        exit();
    } else {
        header("Location: turnover.php?contract_ID=" . $contract_ID . "&error=true&message=Failed to process turnover");
        exit();
    }
}
?>