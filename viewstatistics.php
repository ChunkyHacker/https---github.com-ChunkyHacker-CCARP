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
                                      data-bs-target='#checklistratingsmodal' 
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
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="completedTasksTableBody">
                                </tbody>
                            </table>
                        </div>

                        <!--Difference-->
                        <div class="mb-5">
                            <h6>Time Analysis</h6>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Task</th>
                                        <th colspan="3">Difference in start time</th>
                                        <th colspan="3">Difference in end time</th>
                                    </tr>
                                    <tr>
                                        <th><2h</th>
                                        <th>2-4 h</th>
                                        <th>>4 h</th>
                                        <th><2h</th>
                                        <th>2-4 h</th>
                                        <th>>4h</th>
                                    </tr>
                                </thead>
                                <tbody id="timeAnalysisTableBody">
                                </tbody>
                            </table>
                        </div>

                        <!--Analysis-->
                        <div class="mb-5">
                            <h6>Efficiency Analysis</h6>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Task</th>
                                        <th>Efficiency Status</th>
                                        <th>Time Saved</th>
                                    </tr>
                                </thead>
                                <tbody id="efficiencyAnalysisTableBody">
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

        <!-- Plan Ratings Modal -->
        <div class="modal fade" id="planRatingsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Job Rating</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" style="margin-right: 0;"></button>
                    </div>
                    <div class="modal-body px-0">
                        <div class="px-3">
                            <div class="mb-3">
                                <h6>Rated by:</h6>
                                <select id="carpenter_selector" class="form-select" style="width: 100%">
                                    <option value="">Select a carpenter...</option>
                                </select>
                            </div>
                            
                            <!-- Section Ratings Table -->
                            <table class="table table-bordered">
 
                            <div class="mt-3">
                                <h6>Rating Date:</h6>
                                <p id="rating_date"></p>
                            </div>
                            
                            
                            <!-- Add Chart Section -->
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Section</th>
                                        <th>Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td>DISCOMFORT (DIS)</td><td id="dis_rating"></td></tr>
                                    <tr><td>INNOVATIVENESS (INN)</td><td id="inn_rating"></td></tr>
                                    <tr><td>INSECURITY (INS)</td><td id="ins_rating"></td></tr>
                                    <tr><td>OPTIMISM (OPT)</td><td id="opt_rating"></td></tr>
                                    <tr><td>INTENTION TO USE (INTB)</td><td id="intb_rating"></td></tr>
                                </tbody>
                            </table>

                            <!-- Analysis Section -->
                            <!-- Inside the Plan Ratings Modal, in the Analysis Section -->
                            <div class="mt-4">
                                <h6>Ratings Analysis:</h6>
                                <div class="row">
                                    <div class="col-md-8">
                                        <!-- Make sure this canvas exists and has the correct ID -->
                                        <canvas id="ratingsChart" width="400" height="300"></canvas>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <h6>Accessibility Score</h6>
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
                                        <td>${task.Start_Time}</td>
                                        <td>${task.End_Time}</td>
                                        <td>${task.Status}</td>
                                    </tr>
                                `).join('') || '<tr><td colspan="3">No completed tasks</td></tr>';

                                // Process time analysis for completed tasks
                                if (data.completed_tasks && data.completed_tasks.length > 0) {
                                    const taskAnalysis = {};
                                    
                                    data.completed_tasks.forEach(task => {
                                        const startTime = new Date(task.Start_Time);
                                        const endTime = new Date(task.End_Time);
                                        const timeDiff = (endTime - startTime) / (1000 * 60 * 60); // Convert to hours
                                        
                                        if (!taskAnalysis[task.Task]) {
                                            taskAnalysis[task.Task] = {
                                                start: { less2: 0, two_to_4: 0, more4: 0 },
                                                end: { less2: 0, two_to_4: 0, more4: 0 }
                                            };
                                        }
                                        
                                        // Categorize start time differences
                                        if (timeDiff < 2) taskAnalysis[task.Task].start.less2++;
                                        else if (timeDiff <= 4) taskAnalysis[task.Task].start.two_to_4++;
                                        else taskAnalysis[task.Task].start.more4++;
                                        
                                        // Categorize end time differences
                                        if (timeDiff < 2) taskAnalysis[task.Task].end.less2++;
                                        else if (timeDiff <= 4) taskAnalysis[task.Task].end.two_to_4++;
                                        else taskAnalysis[task.Task].end.more4++;
                                    });
                                    
                                    // Generate table rows
                                    document.getElementById('timeAnalysisTableBody').innerHTML = 
                                        Object.entries(taskAnalysis).map(([task, analysis]) => `
                                            <tr>
                                                <td>${task}</td>
                                                <td>${analysis.start.less2}</td>
                                                <td>${analysis.start.two_to_4}</td>
                                                <td>${analysis.start.more4}</td>
                                                <td>${analysis.end.less2}</td>
                                                <td>${analysis.end.two_to_4}</td>
                                                <td>${analysis.end.more4}</td>
                                            </tr>
                                        `).join('');
                                } else {
                                    document.getElementById('timeAnalysisTableBody').innerHTML = 
                                        '<tr><td colspan="7">No completed tasks available for analysis</td></tr>';
                                }

                                // Process efficiency analysis
                                if (data.completed_tasks && data.completed_tasks.length > 0) {
                                    const efficiencyAnalysis = {};
                                    
                                    data.completed_tasks.forEach(task => {
                                        const startTime = new Date(task.Start_Time);
                                        const endTime = new Date(task.End_Time);
                                        const actualHours = (endTime - startTime) / (1000 * 60 * 60);
                                        const standardHours = 8; // Standard work hours
                                        
                                        if (!efficiencyAnalysis[task.Task]) {
                                            efficiencyAnalysis[task.Task] = {
                                                totalEfficiency: 0,
                                                count: 0
                                            };
                                        }
                                        
                                        const efficiency = ((standardHours - actualHours) / standardHours) * 100;
                                        efficiencyAnalysis[task.Task].totalEfficiency += efficiency;
                                        efficiencyAnalysis[task.Task].count++;
                                    });
                                    
                                    document.getElementById('efficiencyAnalysisTableBody').innerHTML = 
                                        Object.entries(efficiencyAnalysis).map(([task, analysis]) => {
                                            const avgEfficiency = analysis.totalEfficiency / analysis.count;
                                            const timeSaved = avgEfficiency > 0 ? 
                                                `${avgEfficiency.toFixed(1)}% faster` : 
                                                `${Math.abs(avgEfficiency).toFixed(1)}% slower`;
                                            
                                            return `
                                                <tr>
                                                    <td>${task}</td>
                                                    <td class="${avgEfficiency >= 20 ? 'text-success' : 'text-warning'}">
                                                        ${avgEfficiency >= 20 ? '✓ Target Achieved' : 'Below Target'}
                                                    </td>
                                                    <td>${timeSaved}</td>
                                                </tr>
                                            `;
                                        }).join('');
                                } else {
                                    document.getElementById('efficiencyAnalysisTableBody').innerHTML = 
                                        '<tr><td colspan="3">No completed tasks available for analysis</td></tr>';
                                }

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


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define the updateRatingsChart function at the global scope
        function updateRatingsChart(ratings) {
            const ctx = document.getElementById('ratingsChart');
            
            // Destroy existing chart if it exists
            if (window.ratingsChart && typeof window.ratingsChart.destroy === 'function') {
                window.ratingsChart.destroy();
            }
            
            window.ratingsChart = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['DISCOMFORT', 'INNOVATIVENESS', 'INSECURITY', 'OPTIMISM', 'INTENTION TO USE'],
                    datasets: [{
                        label: 'Ratings',
                        data: ratings,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)'
                    }]
                },
                options: {
                    scales: {
                        r: {
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
                            text: 'Job Rating Analysis'
                        }
                    }
                }
            });
        }

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
            planRatingsModal.setAttribute('data-plan-id', planId);
            
            if (planId) {
                console.log('Fetching carpenters for plan:', planId);  // Debug log
                fetch(`get_carpenters_for_plan.php?plan_id=${planId}`)
                    .then(response => response.json())
                    .then(carpenters => {
                        console.log('Received carpenters:', carpenters);  // Debug log
                        const selector = $('#carpenter_selector');
                        selector.empty().append('<option value="">Select a carpenter...</option>');
                        
                        carpenters.forEach(carpenter => {
                            selector.append(`<option value="${carpenter.Carpenter_ID}">${carpenter.First_Name} ${carpenter.Last_Name}</option>`);
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }
        });

        // Handle carpenter selection change
        $('#carpenter_selector').on('change', function() {
            const carpenterId = $(this).val();
            const planId = $('#planRatingsModal').attr('data-plan-id');
            
            if (carpenterId && planId) {
                fetch(`get_job_ratings.php?plan_id=${planId}&carpenter_id=${carpenterId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Calculate section averages with 0 decimal places
                        const disAvg = ((parseFloat(data.DIS1) + parseFloat(data.DIS2) + parseFloat(data.DIS3) + 
                                    parseFloat(data.DIS4) + parseFloat(data.DIS5)) / 5).toFixed(0);
                        const innAvg = ((parseFloat(data.INN1) + parseFloat(data.INN2) + parseFloat(data.INN3) + 
                                    parseFloat(data.INN4) + parseFloat(data.INN5)) / 5).toFixed(0);
                        const insAvg = ((parseFloat(data.INS1) + parseFloat(data.INS2) + parseFloat(data.INS3) + 
                                    parseFloat(data.INS4) + parseFloat(data.INS5)) / 5).toFixed(0);
                        const optAvg = ((parseFloat(data.OPT1) + parseFloat(data.OPT2) + parseFloat(data.OPT3) + 
                                    parseFloat(data.OPT4) + parseFloat(data.OPT5)) / 5).toFixed(0);
                        const intbAvg = ((parseFloat(data.INTB1) + parseFloat(data.INTB2) + 
                                        parseFloat(data.INTB3)) / 3).toFixed(0);

                        // Update section ratings
                        $('#dis_rating').text(`${disAvg}/5`);
                        $('#inn_rating').text(`${innAvg}/5`);
                        $('#ins_rating').text(`${insAvg}/5`);
                        $('#opt_rating').text(`${optAvg}/5`);
                        $('#intb_rating').text(`${intbAvg}/5`);

                        // Calculate accessibility score (OPT + INN + INTB average)
                        const accessibilityScore = (
                            (parseFloat(optAvg) + parseFloat(innAvg) + parseFloat(intbAvg)) / (3 * 5) * 100
                        ).toFixed(1);

                        // Update accessibility score display
                        $('#accessibilityScore').text(`${accessibilityScore}%`);
                        const statusElement = $('#accessibilityStatus');
                        if (accessibilityScore >= 30) {
                            statusElement.text('Target Achieved').removeClass('text-warning').addClass('text-success');
                        } else {
                            statusElement.text('Below Target').removeClass('text-success').addClass('text-warning');
                        }

                        // Update rating date
                        $('#rating_date').text(data.rating_date);

                        // Update chart
                        updateRatingsChart([disAvg, innAvg, insAvg, optAvg, intbAvg]);
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    });
</script>

<?php
$conn->close();
?>

<!-- Checklist Ratings Modal -->
<div class="modal fade" id="checklistratingsmodal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Evaluation Rating Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-0">
                <div class="px-3">
                    <div class="mb-3">
                        <h6>Carpenter:</h6>
                        <p id="carpenter_name" class="form-control-static"></p>
                    </div>
                    
                    <div class="mb-3">
                        <h6>Criteria:</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Criteria</th>
                                    <th>Rating</th>
                                    <th>Score</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1. Site Preparation and Safety</td>
                                    <td id="criteria1_rating"></td>
                                    <td id="criteria1_score"></td>
                                    <td id="criteria1_remarks"></td>
                                </tr>
                                <tr>
                                    <td>2. Materials</td>
                                    <td id="criteria2_rating"></td>
                                    <td id="criteria2_score"></td>
                                    <td id="criteria2_remarks"></td>
                                </tr>
                                <tr>
                                    <td>3. Foundations and Structural Framing</td>
                                    <td id="criteria3_rating"></td>
                                    <td id="criteria3_score"></td>
                                    <td id="criteria3_remarks"></td>
                                </tr>
                                <tr>
                                    <td>4. Mechanical, Electrical, and Plumbing (MEP)</td>
                                    <td id="criteria4_rating"></td>
                                    <td id="criteria4_score"></td>
                                    <td id="criteria4_remarks"></td>
                                </tr>
                                <tr>
                                    <td>5. Exterior Cladding and Roofing</td>
                                    <td id="criteria5_rating"></td>
                                    <td id="criteria5_score"></td>
                                    <td id="criteria5_remarks"></td>
                                </tr>
                                <tr>
                                    <td>6. Interior Finishes</td>
                                    <td id="criteria6_rating"></td>
                                    <td id="criteria6_score"></td>
                                    <td id="criteria6_remarks"></td>
                                </tr>
                                <tr>
                                    <td>7. Windows, Doors, and Hardware</td>
                                    <td id="criteria7_rating"></td>
                                    <td id="criteria7_score"></td>
                                    <td id="criteria7_remarks"></td>
                                </tr>
                                <tr>
                                    <td>8. Insulation and Ventilation</td>
                                    <td id="criteria8_rating"></td>
                                    <td id="criteria8_score"></td>
                                    <td id="criteria8_remarks"></td>
                                </tr>
                                <tr>
                                    <td>9. Landscaping and External Works</td>
                                    <td id="criteria9_rating"></td>
                                    <td id="criteria9_score"></td>
                                    <td id="criteria9_remarks"></td>
                                </tr>
                                <tr>
                                    <td>10. Final Inspection</td>
                                    <td id="criteria10_rating"></td>
                                    <td id="criteria10_score"></td>
                                    <td id="criteria10_remarks"></td>
                                </tr>
                                <tr class="table-active">
                                    <td><strong>Total Score</strong></td>
                                    <td id="total_rating"></td>
                                    <td id="cronbach_score"></td>
                                    <td id="final_remarks"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <h6>Comments:</h6>
                        <p id="rating_comments"></p>
                    </div>

                    <div class="mt-3">
                        <h6>Rating Date:</h6>
                        <p id="rating_date"></p>
                    </div>
                    
                    <!-- Analysis Section -->
                    <div class="mt-4">
                        <h6>Ratings Analysis:</h6>
                        <div class="row">
                            <div class="col-md-8">
                                <canvas id="evaluationChart" style="width: 100%; height: 300px;"></canvas>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6>Overall Score</h6>
                                        <h3 id="overallScore" class="text-center"></h3>
                                        <p id="qualityStatus" class="text-center"></p>
                                    </div>
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
        const checklistModal = document.getElementById('checklistratingsmodal');
        checklistModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const contractId = button.getAttribute('data-contract-id');
            
            if (contractId) {
                fetch('get_checklist_ratings.php?contract_id=' + contractId)
                    .then(response => response.json())
                    .then(data => {
                        // Update carpenter name
                        document.getElementById('carpenter_name').textContent = data.carpenter_name;
                        
                        // Update criteria ratings
                        const ratings = [];
                        for (let i = 1; i <= 10; i++) {
                            let ratingText = '';
                            const rating = parseInt(data['criteria' + i + '_rating']) || 0;
                            ratings.push(rating);
                            
                            switch(rating) {
                                case 1: ratingText = 'Not Compliant'; break;
                                case 2: ratingText = 'Poor'; break;
                                case 3: ratingText = 'Fair'; break;
                                case 4: ratingText = 'Good'; break;
                                case 5: ratingText = 'Excellent'; break;
                                default: ratingText = 'Not Rated';
                            }
                            
                            document.getElementById('criteria' + i + '_rating').textContent = 
                                ratingText + ` (${rating}/5)`;
                        }
                        
                        // Update additional fields
                        document.getElementById('rating_comments').textContent = data.comments || 'No comments provided';
                        document.getElementById('rating_date').textContent = data.rating_date || 'Not specified';
                        
                        // Calculate scores
                        const totalScore = ratings.reduce((sum, rating) => sum + rating, 0);
                        const averageScore = (totalScore / (10 * 5)) * 100;
                        
                        // Calculate Cronbach's Alpha
                        const k = ratings.length;
                        const itemMeans = ratings.map(x => x);
                        const itemVariances = ratings.map(x => {
                            const mean = x;
                            return Math.pow(x - mean, 2);
                        });
                        const sumVariances = itemVariances.reduce((a, b) => a + b, 0);
                        const totalVariance = Math.pow(ratings.reduce((a, b) => a + (b - totalScore/k), 0), 2);
                        
                        const alpha = (k / (k - 1)) * (1 - (sumVariances / totalVariance));
                        
                        // Determine remarks based on Cronbach's Alpha
                        let remarks = '';
                        if (alpha >= 0.9) remarks = 'Excellent';
                        else if (alpha >= 0.8) remarks = 'Good';
                        else if (alpha >= 0.7) remarks = 'Acceptable';
                        else if (alpha >= 0.6) remarks = 'Questionable';
                        else if (alpha >= 0.5) remarks = 'Poor';
                        else remarks = 'Unacceptable';

                        // Update displays
                        document.getElementById('overallScore').textContent = `${averageScore.toFixed(1)}%`;
                        document.getElementById('total_rating').textContent = `${totalScore}/50`;
                        document.getElementById('cronbach_score').textContent = alpha.toFixed(2);
                        document.getElementById('final_remarks').textContent = remarks;
                        
                        // Update quality status
                        const qualityStatus = document.getElementById('qualityStatus');
                        qualityStatus.textContent = averageScore >= 60 ? 'Target Achieved' : 'Below Target';
                        qualityStatus.className = `text-center ${averageScore >= 60 ? 'text-success' : 'text-warning'}`;

                        // Update individual criteria scores and remarks
                        for (let i = 1; i <= 10; i++) {
                            document.getElementById(`criteria${i}_score`).textContent = alpha.toFixed(2);
                            document.getElementById(`criteria${i}_remarks`).textContent = remarks;
                        }

                        // Create/Update chart
                        if (window.evaluationChart && typeof window.evaluationChart.destroy === 'function') {
                            window.evaluationChart.destroy();
                        }

                        const ctx = document.getElementById('evaluationChart');
                        window.evaluationChart = new Chart(ctx, {
                            type: 'radar',
                            data: {
                                labels: [
                                    'Site Prep & Safety',
                                    'Materials',
                                    'Foundations',
                                    'MEP',
                                    'Exterior',
                                    'Interior',
                                    'Windows & Doors',
                                    'Insulation',
                                    'Landscaping',
                                    'Final Inspection'
                                ],
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
                                    r: {
                                        beginAtZero: true,
                                        max: 5,
                                        min: 0,
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                },
                                plugins: {
                                    title: {
                                        display: true,
                                        text: 'Construction Quality Ratings'
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
