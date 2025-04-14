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
$sql = "SELECT c.*, p.start_date, p.end_date,
       (SELECT COUNT(*) FROM completed_task WHERE contract_ID = c.contract_ID) as completed_tasks,
       (SELECT COUNT(*) FROM task WHERE contract_ID = c.contract_ID AND status = 'Not yet started') as pending_tasks,
       car.First_Name as carpenter_first, car.Last_Name as carpenter_last,
       pt.client_signature, pt.turnover_notes, pt.supporting_documents, pt.confirmation_status, pt.created_at
       FROM contracts c 
       JOIN plan p ON c.Plan_ID = p.Plan_ID
       JOIN carpenters car ON c.Carpenter_ID = car.Carpenter_ID
       JOIN project_turnover pt ON c.contract_ID = pt.contract_ID
       WHERE c.contract_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $contract_ID);
$stmt->execute();
$project = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Turnover Details</title>
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
        .approval-form {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Project Turnover Details</h1>

        <!-- Project Details -->
        <div class="project-details">
            <h3>Project Information</h3>
            <?php
            if ($project) {
                $start_date = new DateTime($project['start_date']);
                $end_date = new DateTime($project['end_date']);
                $actual_date = new DateTime(); // Current date for actual completion
                
                $estimated_days = $start_date->diff($end_date)->days;
                $actual_days = $start_date->diff($actual_date)->days;
                
                echo "<div class='detail-item'><strong>Carpenter:</strong> {$project['carpenter_first']} {$project['carpenter_last']}</div>";
                echo "<div class='detail-item'><strong>Start Date:</strong> {$project['start_date']}</div>";
                echo "<div class='detail-item'><strong>End Date:</strong> {$project['end_date']}</div>";
                echo "<div class='detail-item'><strong>Actual Completion:</strong> " . $actual_date->format('Y-m-d') . "</div>";
                echo "<div class='detail-item'><strong>Tasks Completed:</strong> {$project['completed_tasks']}</div>";
                echo "<div class='detail-item'><strong>Pending Tasks:</strong> {$project['pending_tasks']}</div>";
                echo "<div class='detail-item'><strong>Total Days Estimated:</strong> {$estimated_days}</div>";
                echo "<div class='detail-item'><strong>Total Days Spent:</strong> {$actual_days}</div>";
                
                // Add turnover details
                echo "<h3 class='mt-4'>Turnover Information</h3>";
                echo "<div class='detail-item'><strong>Client Signature:</strong> {$project['client_signature']}</div>";
                echo "<div class='detail-item'><strong>Turnover Notes:</strong> {$project['turnover_notes']}</div>";
                
                // Display supporting documents with better formatting
                if (!empty($project['supporting_documents'])) {
                    echo "<div class='detail-item'>";
                    echo "<strong>Supporting Documents:</strong><br>";
                    echo "<div class='document-preview mt-2'>";
                    $doc_path = "uploads/turnover/" . $project['supporting_documents'];
                    $file_extension = pathinfo($project['supporting_documents'], PATHINFO_EXTENSION);
                    
                    // If it's an image, show preview
                    if (in_array(strtolower($file_extension), ['jpg', 'jpeg', 'png', 'gif'])) {
                        echo "<img src='{$doc_path}' alt='Document Preview' style='max-width: 200px; margin-bottom: 10px;'><br>";
                    }
                    
                    echo "<a href='{$doc_path}' class='btn btn-sm btn-info' target='_blank'>
                            <i class='fa fa-download'></i> Download Document
                          </a>";
                    echo "</div></div>";
                } else {
                    echo "<div class='detail-item'><strong>Supporting Documents:</strong> No documents attached</div>";
                }
                
                echo "<div class='detail-item'><strong>Confirmation Status:</strong> {$project['confirmation_status']}</div>";
                echo "<div class='detail-item'><strong>Submitted On:</strong> {$project['created_at']}</div>";
            }
            ?>
        </div>

        <!-- Approval Form -->
        <div class="approval-form">
            <h3>Project Approval</h3>
            <form action="process_user_turnover.php" method="POST">
                <input type="hidden" name="contract_ID" value="<?php echo $contract_ID; ?>">
                <input type="hidden" name="user_ID" value="<?php echo $User_ID; ?>">
                
                <div class="mb-3">
                    <label class="form-label">Project Status:</label>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="approval_status" value="approved" required>
                        <label class="form-check-label">Approve Project</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="approval_status" value="revision">
                        <label class="form-check-label">Request Revision</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="comments" class="form-label">Comments/Feedback:</label>
                    <textarea class="form-control" id="comments" name="comments" rows="4" required
                              placeholder="Please provide your feedback about the project..."></textarea>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                    <a href="userprofile.php" class="btn btn-secondary">Back to Profile</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>