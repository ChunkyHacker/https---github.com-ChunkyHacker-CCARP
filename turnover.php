<?php
session_start();
include('config.php');

// Check if contract_ID is set
if (!isset($_GET['contract_ID'])) {
    header('Location: profile.php');
    exit();
}

$contract_ID = $_GET['contract_ID'];

// Validate if contract exists
$sql = "SELECT * FROM contracts WHERE contract_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $contract_ID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: profile.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Turnover</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;  /* Increased from 800px */
            margin: 0 auto;
            background-color: white;
            padding: 30px;  /* Increased padding */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* Adjust table layout */
        .efficiency-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            table-layout: fixed;  /* Added for better column distribution */
        }

        .efficiency-table th, .efficiency-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;  /* Changed from left to center */
            word-wrap: break-word;  /* Added for text wrapping */
        }

        /* Form adjustments */
        .form-group {
            margin-bottom: 30px;
            max-width: 100%;  /* Ensure form uses full width */
        }

        textarea {
            width: 100%;
            min-height: 200px;  /* Increased height */
            padding: 15px;  /* Increased padding */
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 20px;  /* Increased gap */
            margin-top: 30px;
        }

        button {
            padding: 12px 30px;  /* Increased padding */
            min-width: 150px;  /* Added minimum width */
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            height: 150px;
            resize: vertical;
        }

        .button-group {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .submit-btn {
            background-color: #FF8C00;
            color: white;
        }

        .cancel-btn {
            background-color: #dc3545;
            color: white;
        }

        button:hover {
            opacity: 0.9;
        }

        /* Add new table styles */
        .efficiency-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .efficiency-table th, .efficiency-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .efficiency-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .status-efficient {
            color: green;
        }

        .status-delayed {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Project Turnover</h1>

        <!-- Project Details -->
        <div class="project-details">
            <?php
            // Fetch project details
            $sql = "SELECT c.*, p.start_date, p.end_date,
                       (SELECT COUNT(*) FROM completed_task WHERE contract_ID = c.contract_ID) as completed_tasks,
                       (SELECT COUNT(*) FROM task WHERE contract_ID = c.contract_ID AND status = 'Not yet started') as pending_tasks
                       FROM contracts c 
                       JOIN plan p ON c.Plan_ID = p.Plan_ID
                       WHERE c.contract_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $contract_ID);
            $stmt->execute();
            $project = $stmt->get_result()->fetch_assoc();

            if ($project) {
                $start_date = new DateTime($project['start_date']);
                $end_date = new DateTime($project['end_date']);
                $actual_date = new DateTime(); // Current date for actual completion
                
                $estimated_days = $start_date->diff($end_date)->days;
                $actual_days = $start_date->diff($actual_date)->days;
                
                echo "<div class='detail-item'><strong>Start Date:</strong> {$project['start_date']}</div>";
                echo "<div class='detail-item'><strong>End Date:</strong> {$project['end_date']}</div>";
                echo "<div class='detail-item'><strong>Actual Completion:</strong> " . $actual_date->format('Y-m-d') . "</div>";
                echo "<div class='detail-item'><strong>Tasks Completed:</strong> {$project['completed_tasks']}</div>";
                echo "<div class='detail-item'><strong>Pending Tasks:</strong> {$project['pending_tasks']}</div>";
                echo "<div class='detail-item'><strong>Total Days Estimated:</strong> {$estimated_days}</div>";
                echo "<div class='detail-item'><strong>Total Days Spent:</strong> {$actual_days}</div>";
            }
            ?>
        </div>

        <form action="process_turnover.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="contract_ID" value="<?php echo $contract_ID; ?>">
            <?php
            // Fetch carpenter ID from contracts table
            $sql_carpenter = "SELECT carpenter_ID FROM contracts WHERE contract_ID = ?";
            $stmt = $conn->prepare($sql_carpenter);
            $stmt->bind_param("i", $contract_ID);
            $stmt->execute();
            $carpenter_result = $stmt->get_result();
            $carpenter_data = $carpenter_result->fetch_assoc();
            ?>
            <input type="hidden" name="carpenter_ID" value="<?php echo $carpenter_data['carpenter_ID']; ?>">
            
            <!-- Client Confirmation Section -->
            <div class="form-group">
                <label>Client Confirmation:</label>
                <div class="confirmation-box">
                    <input type="checkbox" id="client_confirmation" name="client_confirmation" required>
                    <label for="client_confirmation">I confirm that the project is complete and delivered as agreed.</label>
                </div>
                <input type="text" id="client_signature" name="client_signature" 
                       placeholder="Type your full name as signature" required>
            </div>

            <!-- Attachment Section -->
            <div class="form-group">
                <label>Supporting Documents:</label>
                <div class="file-upload-container">
                    <input type="file" name="attachments[]" multiple 
                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    <small>Accepted files: Photos of final output, PDFs of signed documents, Inspection reports, etc.</small>
                </div>
            </div>

            <!-- Turnover Notes -->
            <div class="form-group">
                <label for="turnover_notes">Turnover Notes:</label>
                <textarea id="turnover_notes" name="turnover_notes" required 
                    placeholder="Enter any final notes or comments about the project turnover..."></textarea>
            </div>

            <div class="button-group">
                <button type="submit" class="submit-btn">Submit Turnover</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='progress.php?contract_ID=<?php echo $contract_ID; ?>'">Cancel</button>
            </div>
        </form>
    </div>

    <!-- Add this before closing body tag -->
    <style>
        .confirmation-box {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .file-upload-container {
            padding: 15px;
            border: 1px dashed #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .file-upload-container small {
            display: block;
            color: #666;
            margin-top: 5px;
        }

        #client_signature {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 10px;
        }
    </style>

    <!-- Add this before closing body tag -->
    <style>
        .project-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .detail-item {
            margin-bottom: 15px;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .detail-item strong {
            display: inline-block;
            width: 200px;
            color: #555;
        }
    </style>

    <!-- Add this before closing body tag -->
    <script>
        // Log entry will be handled in process_turnover.php
        function validateForm() {
            var confirmation = document.getElementById('client_confirmation');
            var signature = document.getElementById('client_signature');
            
            if (!confirmation.checked) {
                alert('Please confirm the project completion');
                return false;
            }
            
            if (!signature.value.trim()) {
                alert('Please provide your signature');
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>