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

        <!-- Single Carpenter Progress card -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>Carpenter Progress</h5>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ratings Modal -->
        <div class="modal fade" id="ratingsModal" tabindex="-1">
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

        <!-- Update your script section -->
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
    </div>
</body>
</html>

<?php
// Add this before the closing </body> tag
?>
<!-- Plan Ratings Modal -->
<div class="modal fade" id="planRatingsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Job Rating</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <h6>Rated by:</h6>
                    <p id="job_carpenter_name"></p>  <!-- Changed ID to be unique -->
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
                            <canvas id="ratingsChart"></canvas>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const planRatingsModal = document.getElementById('planRatingsModal');
    planRatingsModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const planId = button.getAttribute('data-plan-id');
        
        if (planId) {
            fetch('get_job_ratings.php?plan_id=' + planId)
                .then(response => response.json())
                .then(data => {
                    // Update carpenter name with unique ID
                    document.getElementById('job_carpenter_name').textContent = data.carpenter_name || 'Not available';
                    
                    for (let i = 1; i <= 7; i++) {
                        document.getElementById('q' + i + '_rating').textContent = 
                            data['q' + i + '_rating'] + ' - ' + data['q' + i + '_description'];
                    }
                    
                    // Update issues
                    document.getElementById('q8_answer').textContent = data.q8_answer;
                    if (data.q8_answer === 'yes') {
                        document.getElementById('q8_explanation').textContent = 'Explanation: ' + data.q8_explanation;
                    } else {
                        document.getElementById('q8_explanation').textContent = '';
                    }
                    
                    // Update additional features and feedback
                    document.getElementById('q9_answer').textContent = data.q9_answer;
                    document.getElementById('q10_answer').textContent = data.q10_answer;
                    
                    // Update rating date
                    document.getElementById('job_rating_date').textContent = data.rating_date;

                    // Create chart data
                    const ctx = document.getElementById('ratingsChart').getContext('2d');
                    const ratings = [];
                    const labels = [
                        'Navigation Ease',
                        'Job Relevance',
                        'Job Opportunities',
                        'Communication Ease',
                        'Engagement Level',
                        'Recommendation',
                        'Accessibility'
                    ];
                    
                    for (let i = 1; i <= 7; i++) {
                        ratings.push(parseInt(data['q' + i + '_rating']));
                    }
                    
                    // Calculate average accessibility score from all ratings
                    const totalScore = ratings.reduce((sum, rating) => sum + rating, 0);
                    const averageScore = (totalScore / (ratings.length * 5)) * 100; // Convert to percentage
                    document.getElementById('accessibilityScore').textContent = `${averageScore.toFixed(1)}%`;
                    
                    // Show status based on 30% goal
                    const statusElement = document.getElementById('accessibilityStatus');
                    if (averageScore >= 30) {
                        statusElement.textContent = 'Goal Achieved! ✅';
                        statusElement.className = 'text-success';
                    } else {
                        statusElement.textContent = 'In Progress';
                        statusElement.className = 'text-warning';
                    }
                    
                    // Create chart
                    new Chart(ctx, {
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
                })
                .catch(error => console.error('Error:', error));
        }
    });
});
</script>
<?php
$conn->close();
?>