<?php
session_start();
include('config.php');

if (!isset($_SESSION['User_ID'])) {
    header('Location: login.html');
    exit();
}

$User_ID = $_SESSION['User_ID'];
$contract_ID = $_GET['contract_ID'];

// Fetch project details with turnover information
$sql = "SELECT c.*, p.start_date, p.end_date, p.Photo,
       (SELECT COUNT(*) FROM completed_task WHERE contract_ID = c.contract_ID) as completed_tasks,
       car.First_Name as carpenter_first, car.Last_Name as carpenter_last,
       pt.client_signature, pt.turnover_notes, pt.supporting_documents, 
       pt.confirmation_status, pt.client_feedback, pt.approved_by, pt.approved_at, pt.created_at,
       u.First_Name as client_first, u.Last_Name as client_last
       FROM contracts c 
       JOIN plan p ON c.Plan_ID = p.Plan_ID
       JOIN carpenters car ON c.Carpenter_ID = car.Carpenter_ID
       JOIN project_turnover pt ON c.contract_ID = pt.contract_ID
       JOIN users u ON pt.approved_by = u.User_ID
       WHERE c.contract_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $contract_ID);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Completed Project Details</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f5f5; padding: 20px; }
        .container { max-width: 800px; margin: auto; }
        .detail-item { margin-bottom: 10px; }
        .project-details { 
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .document-preview img {
            max-width: 200px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Completed Project Details</h1>

        <div class="project-details">
            <?php if ($project): ?>
                <!-- Project Photo -->
                <?php if (!empty($project['Photo']) && file_exists($project['Photo'])): ?>
                    <div class="text-center mb-4">
                        <img src="<?php echo $project['Photo']; ?>" alt="Project Photo" style="max-width: 100%; height: auto; border-radius: 10px;">
                    </div>
                <?php endif; ?>

                <h3>Project Information</h3>
                <div class='detail-item'><strong>Carpenter:</strong> <?php echo $project['carpenter_first'] . ' ' . $project['carpenter_last']; ?></div>
                <div class='detail-item'><strong>Start Date:</strong> <?php echo $project['start_date']; ?></div>
                <div class='detail-item'><strong>End Date:</strong> <?php echo $project['end_date']; ?></div>
                <?php
                $start_date = new DateTime($project['start_date']);
                $end_date = new DateTime($project['end_date']);
                $actual_date = new DateTime($project['created_at']); // Using turnover creation date as actual completion
                
                $estimated_days = $start_date->diff($end_date)->days;
                $actual_days = $start_date->diff($actual_date)->days;
                ?>
                <div class='detail-item'><strong>Actual Completion:</strong> <?php echo $actual_date->format('Y-m-d'); ?></div>
                <div class='detail-item'><strong>Total Days Estimated:</strong> <?php echo $estimated_days; ?></div>
                <div class='detail-item'><strong>Total Days Spent:</strong> <?php echo $actual_days; ?></div>
                <div class='detail-item'><strong>Tasks Completed:</strong> <?php echo $project['completed_tasks']; ?></div>

                <h3 class="mt-4">Turnover Information</h3>
                <div class='detail-item'><strong>Client Feedback:</strong> <?php echo $project['client_feedback']; ?></div>
                <div class='detail-item'><strong>Turnover Notes:</strong> <?php echo $project['turnover_notes']; ?></div>
                <div class='detail-item'><strong>Approved By:</strong> <?php echo $project['client_first'] . ' ' . $project['client_last']; ?></div>
                <div class='detail-item'><strong>Approved On:</strong> <?php echo $project['approved_at']; ?></div>

                <?php if (!empty($project['supporting_documents'])): ?>
                    <div class='detail-item'>
                        <strong>Supporting Documents:</strong><br>
                        <div class='document-preview mt-2'>
                            <?php
                            $doc_path = "uploads/turnover/" . $project['supporting_documents'];
                            $file_extension = pathinfo($project['supporting_documents'], PATHINFO_EXTENSION);
                            
                            if (in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                                echo "<img src='{$doc_path}' alt='Document Preview'><br>";
                            }
                            echo "<a href='{$doc_path}' class='btn btn-sm btn-info' target='_blank'>
                                    <i class='fa fa-download'></i> Download Document
                                  </a>";
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="d-grid gap-2 mt-4">
                    <a href="userprofile.php" class="btn btn-secondary">Back to Profile</a>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">Project not found or access denied.</div>
                <div class="d-grid gap-2">
                    <a href="userprofile.php" class="btn btn-secondary">Back to Profile</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>