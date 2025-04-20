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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        <!-- Plan Ratings Modal -->
        <div class="modal fade" id="planRatingsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Job Rating</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="margin-right: 0;"></button>
                    </div>
                    <div class="modal-body px-0">
                        <div class="px-3"> <!-- Container for all content with consistent padding -->
                            <div class="mb-3">
                                <h6>Rated by:</h6>
                                <select id="carpenter_selector" class="form-select" style="width: 100%">
                                    <option value="">Select a carpenter...</option>
                                </select>
                            </div>
                            
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Criteria</th>
                                        <th>Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>1. Website Navigation and Usability</td><td id="q1_rating"></td></tr>
                                    <tr><td>2. Job Post Relevance to Skills</td><td id="q2_rating"></td></tr>
                                    <tr><td>3. Available Job Opportunities</td><td id="q3_rating"></td></tr>
                                    <tr><td>4. Client Communication Platform</td><td id="q4_rating"></td></tr>
                                    <tr><td>5. User Engagement and Activity</td><td id="q5_rating"></td></tr>
                                    <tr><td>6. Platform Recommendation Rate</td><td id="q6_rating"></td></tr>
                                    <tr><td>7. Overall Job Accessibility</td><td id="q7_rating"></td></tr>
                                </tbody>
                            </table>

                            <div class="mt-3">
                                <h6>Issues:</h6>
                                <p id="q8_answer"></p>
                                <p id="q8_explanation"></p>
                            </div>
                            <div class="mt-3">
                                <h6>Additional Features:</h6>
                                <p id="q9_answer"></p>
                            </div>
                            <div class="mt-3">
                                <h6>Feedback:</h6>
                                <p id="q10_answer"></p>
                            </div>
                            <div class="mt-3">
                                <h6>Rating Date:</h6>
                                <p id="job_rating_date"></p>
                            </div>
                            
                            <!-- Add Chart Section -->
                            <div class="mt-4">
                                <h6>Ratings Analysis:</h6>
                                <div class="row">
                                    <div class="col-md-8">
                                        <canvas id="ratingsChart" style="width: 100%; height: 300px;"></canvas>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>Over all Score</h6>
                                                <h3 id="accessibilityScore" class="text-center"></h3>
                                                <p id="accessibilityStatus" class="text-center"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Close px-3 container -->
                    </div> <!-- Close modal-body -->
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
// Initialize Select2 and Plan Ratings Modal Handler
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('#carpenter_selector').select2({
        dropdownParent: $('#planRatingsModal'),
        width: '100%'
    });

    // Plan Ratings Modal Handler
    const planRatingsModal = document.getElementById('planRatingsModal');
    planRatingsModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const planId = button.getAttribute('data-plan-id');
        planRatingsModal.setAttribute('data-plan-id', planId); // Store plan_id directly on modal
        
        // Clear previous data
        $('#carpenter_selector').empty().append('<option value="">Select a carpenter...</option>');
        
        if (planId) {
            // Fetch carpenters who rated this plan
            fetch('get_plan_raters.php?plan_id=' + planId)
                .then(response => response.json())
                .then(data => {
                    const select = $('#carpenter_selector');
                    
                    data.forEach(carpenter => {
                        select.append(new Option(
                            carpenter.First_Name + ' ' + carpenter.Last_Name,
                            carpenter.Carpenter_ID
                        ));
                    });
                })
                .catch(error => console.error('Error:', error));
        }
    });

    // Handle carpenter selection change
    $('#carpenter_selector').on('change', function() {
        const carpenterId = $(this).val();
        const planId = planRatingsModal.getAttribute('data-plan-id'); // Get plan_id directly from modal
        
        console.log('Selected carpenter:', carpenterId); // Debug log
        console.log('Plan ID:', planId); // Debug log
        
        // Inside the carpenter selection change handler, in the .then(data => {}) block
        if (carpenterId && planId) {
            fetch(`get_job_ratings.php?plan_id=${planId}&carpenter_id=${carpenterId}`)
                .then(response => response.json())
                .then(data => {
                    // Update ratings with descriptions
                    for (let i = 1; i <= 7; i++) {
                        $(`#q${i}_rating`).text(data[`q${i}_description`] + ` (${data[`q${i}_rating`]}/5)`);
                    }
            
                    // Update additional fields
                    $('#q8_answer').text(data.q8_answer || 'No issues reported');
                    $('#q8_explanation').text(data.q8_explanation || '');
                    $('#q9_answer').text(data.q9_answer || 'No additional features suggested');
                    $('#q10_answer').text(data.q10_answer || 'No feedback provided');
                    $('#job_rating_date').text(data.rating_date || 'Not specified');
                    
                    // Inside the Plan Ratings Modal section, let's modify the chart initialization code
                    // Make sure Chart.js is loaded
                    if (typeof Chart === 'undefined') {
                        console.error('Chart.js is not loaded!');
                        return;
                    }
                    
                    // Create chart data
                    const ctx = document.getElementById('ratingsChart');
                    if (!ctx) {
                        console.error('Canvas element not found!', 'ratingsChart');
                        // Add debugging to check all canvas elements
                        console.log('All canvas elements:', document.querySelectorAll('canvas'));
                        return;
                    }
                    
                    const ratings = [];
                    const labels = [
                        'Navigation Ease',
                        'Job Relevance',
                        'Job Opportunities',
                        'Communication',
                        'User Engagement',
                        'Recommendation',
                        'Accessibility'
                    ];
                    
                    // Get ratings from q1 to q7
                    for (let i = 1; i <= 7; i++) {
                        ratings.push(parseInt(data['q' + i + '_rating']));
                    }
                    
                    // Calculate average accessibility score from all ratings
                    const totalScore = ratings.reduce((sum, rating) => sum + rating, 0);
                    const averageScore = (totalScore / (ratings.length * 5)) * 100; // Convert to percentage
                    document.getElementById('accessibilityScore').textContent = `${averageScore.toFixed(1)}%`;
                    
                    // Show status based on 60% goal
                    const statusElement = document.getElementById('accessibilityStatus');
                    if (averageScore >= 60) {
                        statusElement.textContent = 'Goal Achieved! ✅';
                        statusElement.className = 'text-center text-success';
                    } else {
                        statusElement.textContent = 'In Progress';
                        statusElement.className = 'text-center text-warning';
                    }
                    
                    // Add debugging to check if chart exists before destroying
                    console.log('Existing chart:', window.ratingsChart);
                    
                    // Destroy existing chart if it exists - FIX THE ERROR HERE
                    if (window.ratingsChart && typeof window.ratingsChart.destroy === 'function') {
                        window.ratingsChart.destroy();
                    } else {
                        // If chart exists but destroy method doesn't, create a new canvas
                        if (window.ratingsChart) {
                            const parent = ctx.parentNode;
                            parent.removeChild(ctx);
                            const newCanvas = document.createElement('canvas');
                            newCanvas.id = 'ratingsChart';
                            newCanvas.style = 'width: 100%; height: 300px;';
                            parent.appendChild(newCanvas);
                            // Use let instead of const for ctx so we can reassign it
                        }
                    }
                    
                    // Get the canvas element again after potential recreation
                    let chartCanvas = document.getElementById('ratingsChart');
                    
                    // Create chart with consistent blue color and add debugging
                    console.log('Creating new chart on canvas:', chartCanvas);
                    try {
                        window.ratingsChart = new Chart(chartCanvas, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Rating Score',
                                    data: ratings,
                                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                    borderColor: 'rgba(54, 162, 235, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 5,
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                },
                                plugins: {
                                    title: {
                                        display: true,
                                        text: 'Job Accessibility Ratings'
                                    }
                                }
                            }
                        });
                        console.log('Chart created successfully:', window.ratingsChart);
                    } catch (error) {
                        console.error('Error creating chart:', error);
                    }
                    
                    // Add debugging to confirm chart was created
                    console.log('Chart created:', window.ratingsChart);
                })
                .catch(error => console.error('Error:', error));
        }
    });
});
</script>
<script>
// Add efficiency modal handler here
const efficiencyModal = document.getElementById('efficiencyModal');
efficiencyModal.addEventListener('show.bs.modal', function(event) {
    console.log('Fetching efficiency data...');
    
    fetch('get_efficiency_report.php')
        .then(response => response.json())
        .then(data => {
            console.log('Data received:', data);
            const tbody = document.getElementById('efficiencyTableBody');
            // Check if data.data exists (from the new response structure)
            const reportData = data.data || data;
            
            if (reportData && reportData.length > 0) {
                tbody.innerHTML = reportData.map(row => `
                    <tr>
                        <td>Plan_${row.contract_ID}</td>
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
            } else {
                tbody.innerHTML = '<tr><td colspan="10" class="text-center">No efficiency data available</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('efficiencyTableBody').innerHTML = 
                '<tr><td colspan="10" class="text-center">Error loading efficiency data</td></tr>';
        });
});
</script>

<?php
$conn->close();
?>
