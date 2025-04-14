<?php
// Include the database configuration file
include 'config.php';

// Start session to check if the admin is logged in
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.html");
    exit();
}

// Query to get plan data with views and count likes
// Update the SQL query to include carpenter info
$sql = "SELECT p.plan_id, p.views, COUNT(l.like_id) as likes_count, 
        CONCAT(c.First_Name, ' ', c.Last_Name) as carpenter_name
        FROM plan p 
        LEFT JOIN likes l ON p.plan_id = l.plan_id 
        LEFT JOIN job_ratings jr ON p.plan_id = jr.plan_ID
        LEFT JOIN carpenters c ON jr.Carpenter_ID = c.Carpenter_ID
        GROUP BY p.plan_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Statistics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Statistics</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="admindashboard.php">Back to Dashboard</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h5>Plan Statistics</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Plan ID</th>
                            <th>Views</th>
                            <th>Likes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['plan_id'] . "</td>";
                                echo "<td>" . $row['views'] . "</td>";
                                echo "<td>" . $row['likes_count'] . "</td>";
                                // Update the button to include modal attributes and plan_id
                                echo "<td><button class='btn btn-primary btn-sm' data-bs-toggle='modal' 
                                      data-bs-target='#planRatingsModal' 
                                      data-plan-id='" . $row['plan_id'] . "'>View Ratings</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No data available</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Single Project Progress card -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>Project Progress</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Contract ID</th>
                            <th>Carpenter</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT c.contract_ID, CONCAT(cp.First_Name, ' ', cp.Last_Name) as carpenter_name 
                               FROM contracts c 
                               JOIN carpenters cp ON c.Carpenter_ID = cp.Carpenter_ID";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['contract_ID'] . "</td>";
                                echo "<td>" . $row['carpenter_name'] . "</td>";
                                echo "<td><button class='btn btn-primary btn-sm' data-bs-toggle='modal' 
                                      data-bs-target='#progressModal' 
                                      data-contract-id='" . $row['contract_ID'] . "'>View Progress</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='text-center'>No carpenters available</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Single Ratings Section -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>Evaluation Ratings</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Contract ID</th>
                            <th>Evaluator Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ratings_sql = "SELECT DISTINCT r.contract_ID, u.First_Name, u.Last_Name 
                                      FROM ratings r 
                                      JOIN users u ON r.user_ID = u.User_ID";
                        $ratings_result = $conn->query($ratings_sql);
                        if ($ratings_result->num_rows > 0) {
                            while($row = $ratings_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['contract_ID'] . "</td>";
                                echo "<td>" . $row['First_Name'] . " " . $row['Last_Name'] . "</td>";
                                echo "<td><button class='btn btn-primary btn-sm' data-bs-toggle='modal' 
                                      data-bs-target='#ratingsModal' 
                                      data-contract-id='" . $row['contract_ID'] . "'>View Ratings</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='text-center'>No ratings available</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Single Go Back Button -->
        <div class="mt-4 mb-4">
            <a href="javascript:history.back()" class="btn btn-secondary">Go Back</a>
        </div>

        <!-- Single Bootstrap JS -->
        <!-- Add this right after your tables but before the scripts -->
        <div class="modal fade" id="progressModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Progress and Attendance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Progress -->
                        <div class="mb-5">
                            <h6>Progress</h6>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="progressTableBody">
                                </tbody>
                            </table>
                        </div>

                        <!-- Tasks -->
                        <div class="mb-5">
                            <h6>Tasks</h6>
                            <h6 class="mt-3">Pending Task</h6>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th>Task</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody id="pendingTasksTableBody">
                                </tbody>
                            </table>

                            <h6 class="mt-4">Completed Task</h6>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Task</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="completedTasksTableBody">
                                </tbody>
                            </table>
                        </div>

                        <!-- Attendance -->
                        <div>
                            <h6>Attendance</h6>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Type of Work</th>
                                        <th>Time In</th>
                                        <th>Time Out</th>
                                    </tr>
                                </thead>
                                <tbody id="attendanceTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#efficiencyModal">
                            View Efficiency Report
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ratings Modal -->
        <div class="modal fade" id="ratingsModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Evaluation Ratings</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                    <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Criteria</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td>The finished project matched the agreed design and measurements.</td><td id="criteria1"></td></tr>
                                <tr><td>There were no alignment issues (e.g., no uneven surfaces, gaps, or misalignment).</td><td id="criteria2"></td></tr>
                                <tr><td>The finishing quality (sanding, painting, varnishing) is smooth and professionally done.</td><td id="criteria3"></td></tr>
                                <tr><td>There were no visible defects (e.g., cracks, loose fittings, rough edges).</td><td id="criteria4"></td></tr>
                                <tr><td>The carpenter followed proper carpentry techniques (e.g., joints, reinforcements, fastening).</td><td id="criteria5"></td></tr>
                                <tr><td>The project was completed within the agreed timeframe.</td><td id="criteria6"></td></tr>
                                <tr><td>I am satisfied with the overall quality of the carpentry work.</td><td id="criteria7"></td></tr>
                                <tr><td>I would recommend this carpenter for future projects.</td><td id="criteria8"></td></tr>
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <h6>Comments:</h6>
                            <p id="ratingComments"></p>
                        </div>
                        <div class="mt-3">
                            <h6>Rating Date:</h6>
                            <p id="ratingDate"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Progress Modal
                const progressModal = document.getElementById('progressModal');
                progressModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const contractId = button.getAttribute('data-contract-id');
                    
                    if (contractId) {
                        fetch('get_contract_progress.php?contract_id=' + contractId)
                            .then(response => response.json())
                            .then(data => {
                                // Update Progress
                                document.getElementById('progressTableBody').innerHTML = data.progress.map(item => `
                                    <tr>
                                        <td>${item.Name}</td>
                                        <td>${item.Status}</td>
                                    </tr>
                                `).join('') || '<tr><td colspan="2">No Progress yet</td></tr>';

                                // Update Pending Tasks
                                document.getElementById('pendingTasksTableBody').innerHTML = data.pending_tasks.map(task => `
                                    <tr>
                                        <td>${task.status}</td>
                                        <td>${task.task}</td>
                                        <td>${task.Time}</td>
                                    </tr>
                                `).join('') || '<tr><td colspan="3">No pending tasks</td></tr>';

                                // Update Completed Tasks
                                document.getElementById('completedTasksTableBody').innerHTML = data.completed_tasks.map(task => `
                                    <tr>
                                        <td>${task.Task}</td>
                                        <td>${task.Time}</td>
                                        <td>${task.Status}</td>
                                    </tr>
                                `).join('') || '<tr><td colspan="3">No completed tasks</td></tr>';

                                // Update Attendance
                                document.getElementById('attendanceTableBody').innerHTML = data.attendance.map(att => `
                                    <tr>
                                        <td>${att['Type of Work']}</td>
                                        <td>${att['Time In']}</td>
                                        <td>${att['Time Out']}</td>
                                    </tr>
                                `).join('') || '<tr><td colspan="3">No attendance yet</td></tr>';
                            })
                            .catch(error => console.error('Error:', error));
                    }
                });
            });
        // Add Ratings Modal Handler
        const ratingsModal = document.getElementById('ratingsModal');
        ratingsModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const contractId = button.getAttribute('data-contract-id');
            
            if (contractId) {
                fetch('get_ratings.php?contract_id=' + contractId)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.error) {
                            // Update criteria ratings
                            for (let i = 1; i <= 8; i++) {
                                document.getElementById('criteria' + i).textContent = data['criteria' + i];
                            }
                            
                            // Update comments and date
                            document.getElementById('ratingComments').textContent = data.comments || 'No comments';
                            document.getElementById('ratingDate').textContent = data.rating_date || 'Not specified';
                        } else {
                            console.error('Error:', data.error);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
        </script>
    </div><!-- End of container -->
</body>
</html>

<!-- Efficiency Report Modal -->
<div class="modal fade" id="efficiencyModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl"> <!-- Changed from modal-lg to modal-xl -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Project Efficiency Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive"> <!-- Added table-responsive wrapper -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Contract ID</th>
                                <th>Carpenter ID</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Actual Completion Date</th>
                                <th>Task Completed</th>
                                <th>Total Days Estimated</th>
                                <th>Total Days Spent</th>
                                <th>Efficiency Gain (%)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="efficiencyTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// Add efficiency modal handler here
const efficiencyModal = document.getElementById('efficiencyModal');
efficiencyModal.addEventListener('show.bs.modal', function(event) {
    // Add console.log for debugging
    console.log('Fetching efficiency data...');
    
    fetch('get_efficiency_report.php')
        .then(response => {
            console.log('Response:', response);
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            const tbody = document.getElementById('efficiencyTableBody');
            tbody.innerHTML = data.map(row => `
                <tr>
                    <td>${row.contract_ID}</td>
                    <td>${row.Carpenter_ID}</td>
                    <td>${row.start_date}</td>
                    <td>${row.end_date}</td>
                    <td>${row.actual_completion_date}</td>
                    <td>${row.task_completed}</td>
                    <td>${row.total_days_estimated}</td>
                    <td>${row.total_days_spent}</td>
                    <td>${row.efficiency_gain}%</td>
                    <td>
                        ${row.efficiency_gain >= 20 ? 
                            '<span class="text-success">✓ Efficient</span>' : 
                            '<span class="text-warning">Delayed</span>'}
                    </td>
                </tr>
            `).join('');
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('efficiencyTableBody').innerHTML = 
                '<tr><td colspan="10" class="text-center">Error loading data</td></tr>';
        });
});
</script>
<?php
$conn->close();
?>