<?php
include('config.php');
session_start();

if (!isset($_SESSION['User_ID'])) {
    echo "<script>alert('You need to log in first.'); window.location.href='login.php';</script>";
    exit();
}

$user_ID = $_SESSION['User_ID'];
$plan_ID = isset($_GET['plan_ID']) ? $_GET['plan_ID'] : null;
$contract_ID = isset($_GET['contract_ID']) ? $_GET['contract_ID'] : null;

// Check if plan and contract exist
$check_plan_sql = "SELECT * FROM plan WHERE plan_ID = ?";
$check_plan_stmt = $conn->prepare($check_plan_sql);
$check_plan_stmt->bind_param("i", $plan_ID);
$check_plan_stmt->execute();
$plan_result = $check_plan_stmt->get_result();

// Get Carpenter_ID from contracts table
$get_carpenter_sql = "SELECT Carpenter_ID FROM contracts WHERE contract_ID = ?";
$get_carpenter_stmt = $conn->prepare($get_carpenter_sql);
$get_carpenter_stmt->bind_param("i", $contract_ID);
$get_carpenter_stmt->execute();
$carpenter_result = $get_carpenter_stmt->get_result();
$carpenter_row = $carpenter_result->fetch_assoc();
$Carpenter_ID = $carpenter_row['Carpenter_ID'];

if ($plan_result->num_rows === 0) {
    echo "<script>alert('Invalid plan ID.'); window.history.back();</script>";
    exit();
}

$check_contract_sql = "SELECT * FROM contracts WHERE contract_ID = ?";
$check_contract_stmt = $conn->prepare($check_contract_sql);
$check_contract_stmt->bind_param("i", $contract_ID);
$check_contract_stmt->execute();
$contract_result = $check_contract_stmt->get_result();

if ($contract_result->num_rows === 0) {
    echo "<script>alert('Invalid contract ID.'); window.history.back();</script>";
    exit();
}

if (!$plan_ID || !$contract_ID) {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
    exit();
}

// After the initial checks and before displaying the form
// Check if user has already submitted a rating for this contract
$check_rating_sql = "SELECT * FROM ratings WHERE contract_ID = ? AND user_ID = ?";
$check_rating_stmt = $conn->prepare($check_rating_sql);
$check_rating_stmt->bind_param("ii", $contract_ID, $user_ID);
$check_rating_stmt->execute();
$rating_result = $check_rating_stmt->get_result();

if ($rating_result->num_rows > 0) {
    echo "<!DOCTYPE html>
          <html>
          <head>
              <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          </head>
          <body>
              <script>
                  Swal.fire({
                      icon: 'warning',
                      title: 'Already Submitted',
                      text: 'You have already submitted a rating for this contract.',
                      confirmButtonColor: '#FF8C00'
                  }).then(function() {
                      window.location.href = 'userviewprogress.php?plan_ID=" . $plan_ID . "';
                  });
              </script>
          </body>
          </html>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get all POST data first
    $site_prep_1 = $_POST['site_prep_1'];
    $site_prep_2 = $_POST['site_prep_2'];
    $site_prep_3 = $_POST['site_prep_3'];
    $site_prep_4 = $_POST['site_prep_4'];
    
    $materials_1 = $_POST['materials_1'];
    $materials_2 = $_POST['materials_2'];
    $materials_3 = $_POST['materials_3'];
    
    $foundation_1 = $_POST['foundation_1'];
    $foundation_2 = $_POST['foundation_2'];
    $foundation_3 = $_POST['foundation_3'];
    $foundation_4 = $_POST['foundation_4'];
    
    $mep_1 = $_POST['mep_1'];
    $mep_2 = $_POST['mep_2'];
    $mep_3 = $_POST['mep_3'];
    $mep_4 = $_POST['mep_4'];
    
    $exterior_1 = $_POST['exterior_1'];
    $exterior_2 = $_POST['exterior_2'];
    $exterior_3 = $_POST['exterior_3'];
    $exterior_4 = $_POST['exterior_4'];

    $interior_1 = $_POST['interior_1'];
    $interior_2 = $_POST['interior_2'];
    $interior_3 = $_POST['interior_3'];
    $interior_4 = $_POST['interior_4'];

    $windows_1 = $_POST['windows_1'];
    $windows_2 = $_POST['windows_2'];
    $windows_3 = $_POST['windows_3'];
    $windows_4 = $_POST['windows_4'];

    $insulation_1 = $_POST['insulation_1'];
    $insulation_2 = $_POST['insulation_2'];
    $insulation_3 = $_POST['insulation_3'];

    $landscaping_1 = $_POST['landscaping_1'];
    $landscaping_2 = $_POST['landscaping_2'];
    $landscaping_3 = $_POST['landscaping_3'];

    $final_1 = $_POST['final_1'];
    $final_2 = $_POST['final_2'];
    $final_3 = $_POST['final_3'];
    $final_4 = $_POST['final_4'];

    // Calculate average scores for each category (rounded to 1 decimal place)
    $site_prep_score = round(($site_prep_1 + $site_prep_2 + $site_prep_3 + $site_prep_4) / 4, 1);
    $materials_score = round(($materials_1 + $materials_2 + $materials_3) / 3, 1);
    $foundation_score = round(($foundation_1 + $foundation_2 + $foundation_3 + $foundation_4) / 4, 1);
    $mep_score = round(($mep_1 + $mep_2 + $mep_3 + $mep_4) / 4, 1);
    $exterior_score = round(($exterior_1 + $exterior_2 + $exterior_3 + $exterior_4) / 4, 1);
    $interior_score = round(($interior_1 + $interior_2 + $interior_3 + $interior_4) / 4, 1);
    $windows_score = round(($windows_1 + $windows_2 + $windows_3 + $windows_4) / 4, 1);
    $insulation_score = round(($insulation_1 + $insulation_2 + $insulation_3) / 3, 1);
    $landscaping_score = round(($landscaping_1 + $landscaping_2 + $landscaping_3) / 3, 1);
    $final_score = round(($final_1 + $final_2 + $final_3 + $final_4) / 4, 1);

    $comments = $_POST['comments'];
    $rating_date = date('Y-m-d H:i:s');

    // Insert into database
    $sql = "INSERT INTO ratings (
        contract_ID, plan_ID, user_ID, Carpenter_ID,
        site_prep_score, materials_score, foundation_score,
        mep_score, exterior_score, interior_score,
        windows_score, insulation_score, landscaping_score,
        final_score, comments, rating_date
    ) VALUES (
        ?, ?, ?, ?,
        ?, ?, ?, 
        ?, ?, ?,
        ?, ?, ?,
        ?, ?, ?
    )";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "iiiidddddddddsss",  // Changed from "iiiidddddddddss" to "iiiidddddddddsss"
        $contract_ID, $plan_ID, $user_ID, $Carpenter_ID,
        $site_prep_score, $materials_score, $foundation_score,
        $mep_score, $exterior_score, $interior_score,
        $windows_score, $insulation_score, $landscaping_score,
        $final_score, $comments, $rating_date
    );

    if ($stmt->execute()) {
        // Update contract status if needed
        $check_status_sql = "SELECT status FROM contracts WHERE contract_ID = ?";
        $check_status_stmt = $conn->prepare($check_status_sql);
        $check_status_stmt->bind_param("i", $contract_ID);
        $check_status_stmt->execute();
        $status_result = $check_status_stmt->get_result();
        $status_row = $status_result->fetch_assoc();

        if ($status_row['status'] == 'Completed') {
            $update_sql = "UPDATE contracts SET status = 'Rated' WHERE contract_ID = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $contract_ID);
            $update_stmt->execute();
        }

        echo "<!DOCTYPE html>
              <html>
              <head>
                  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              </head>
              <body>
                  <script>
                      Swal.fire({
                          icon: 'success',
                          title: 'Success!',
                          text: 'Rating submitted successfully!',
                          confirmButtonColor: '#FF8C00'
                      }).then(function() {
                          window.location.href = 'userviewprogress.php?plan_ID=" . $plan_ID . "';
                      });
                  </script>
              </body>
              </html>";
        exit();
    } else {
        echo "<!DOCTYPE html>
              <html>
              <head>
                  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              </head>
              <body>
                  <script>
                      Swal.fire({
                          icon: 'error',
                          title: 'Error!',
                          text: 'Failed to submit rating. Please try again.',
                          confirmButtonColor: '#FF8C00'
                      });
                  </script>
              </body>
              </html>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Plan</title>
    <!-- Add Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', Arial, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .rating-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h2 {
            font-weight: 600;
            color: #333;
        }
        h3 {
            font-weight: 500;
            color: #444;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
            font-size: 20px;  /* Added font size */
            font-weight: bold;  /* Added bold text */
        }

        th {
            background-color: #FF8C00;
            color: white;
        }

        th:first-child {
            text-align: left;
            background-color: #FF8C00;
        }

        td:first-child {
            text-align: left;
        }

        .radio-group {
            display: flex;
            justify-content: space-around;
        }

        /* Custom radio button styling */
        input[type="radio"] {
            appearance: none;
            width: 25px;
            height: 25px;
            border: 3px solid #FF8C00;
            border-radius: 50%;
            outline: none;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }

        input[type="radio"]:checked {
            background-color: #FF8C00;
            border-color: #FF8C00;
        }

        input[type="radio"]:checked::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 12px;
            height: 12px;
            background-color: white;
            border-radius: 50%;
        }

        input[type="radio"]:hover {
            border-color: #FFA500;
            box-shadow: 0 0 5px rgba(255, 140, 0, 0.3);
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            resize: vertical;
        }
        button {
            background-color: #FF8C00;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #FFA500;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="rating-container">
        <h2>Quality Assurance Checklist</h2>
        <form method="POST">
            <!-- Add hidden fields -->
            <input type="hidden" name="plan_ID" value="<?php echo $plan_ID; ?>">
            <input type="hidden" name="contract_ID" value="<?php echo $contract_ID; ?>">
            <input type="hidden" name="user_ID" value="<?php echo $user_ID; ?>">
            <input type="hidden" name="Carpenter_ID" value="<?php echo $Carpenter_ID; ?>">
            
            <!-- 1. Site Preparation and Safety -->
            <h3>1. Site Preparation and Safety</h3>
            <table class="table table-bordered">
                <tr>
                    <th>Criteria</th>
                    <th>1-Not Compliant</th>
                    <th>2-Poor</th>
                    <th>3-Fair</th>
                    <th>4-Good</th>
                    <th>5- Excellent</th>
                </tr>
                <tr>
                    <td>Site cleared and properly graded</td>
                    <td><input type="radio" name="site_prep_1" value="1" required></td>
                    <td><input type="radio" name="site_prep_1" value="2"></td>
                    <td><input type="radio" name="site_prep_1" value="3"></td>
                    <td><input type="radio" name="site_prep_1" value="4"></td>
                    <td><input type="radio" name="site_prep_1" value="5"></td>
                </tr>
                <tr>
                    <td>Safety signs and equipment are visible and in place</td>
                    <td><input type="radio" name="site_prep_2" value="1" required></td>
                    <td><input type="radio" name="site_prep_2" value="2"></td>
                    <td><input type="radio" name="site_prep_2" value="3"></td>
                    <td><input type="radio" name="site_prep_2" value="4"></td>
                    <td><input type="radio" name="site_prep_2" value="5"></td>
                </tr>
                <tr>
                    <td>Proper storage of materials to avoid obstruction and hazards</td>
                    <td><input type="radio" name="site_prep_3" value="1" required></td>
                    <td><input type="radio" name="site_prep_3" value="2"></td>
                    <td><input type="radio" name="site_prep_3" value="3"></td>
                    <td><input type="radio" name="site_prep_3" value="4"></td>
                    <td><input type="radio" name="site_prep_3" value="5"></td>
                </tr>
                <tr>
                    <td>Access roads and walkways are clear and safe</td>
                    <td><input type="radio" name="site_prep_4" value="1" required></td>
                    <td><input type="radio" name="site_prep_4" value="2"></td>
                    <td><input type="radio" name="site_prep_4" value="3"></td>
                    <td><input type="radio" name="site_prep_4" value="4"></td>
                    <td><input type="radio" name="site_prep_4" value="5"></td>
                </tr>
            </table>
            
            <!-- 2. Materials -->
            <h3>2. Materials</h3>
            <table class="table table-bordered">
                <tr>
                    <th>Criteria</th>
                    <th>1-Not Compliant</th>
                    <th>2-Poor</th>
                    <th>3-Fair</th>
                    <th>4-Good</th>
                    <th>5- Excellent</th>
                </tr>
                <tr>
                    <td>Materials are stored properly to prevent damage</td>
                    <td><input type="radio" name="materials_1" value="1" required></td>
                    <td><input type="radio" name="materials_1" value="2"></td>
                    <td><input type="radio" name="materials_1" value="3"></td>
                    <td><input type="radio" name="materials_1" value="4"></td>
                    <td><input type="radio" name="materials_1" value="5"></td>
                </tr>
                <tr>
                    <td>Materials match specifications and are approved</td>
                    <td><input type="radio" name="materials_2" value="1" required></td>
                    <td><input type="radio" name="materials_2" value="2"></td>
                    <td><input type="radio" name="materials_2" value="3"></td>
                    <td><input type="radio" name="materials_2" value="4"></td>
                    <td><input type="radio" name="materials_2" value="5"></td>
                </tr>
                <tr>
                    <td>Certificates of compliance are available and verified</td>
                    <td><input type="radio" name="materials_3" value="1" required></td>
                    <td><input type="radio" name="materials_3" value="2"></td>
                    <td><input type="radio" name="materials_3" value="3"></td>
                    <td><input type="radio" name="materials_3" value="4"></td>
                    <td><input type="radio" name="materials_3" value="5"></td>
                </tr>
            </table>
            
            <!-- 3. Foundations and Structural Framing -->
            <h3>3. Foundations and Structural Framing</h3>
            <table class="table table-bordered">
                <tr>
                    <th>Criteria</th>
                    <th>1-Not Compliant</th>
                    <th>2-Poor</th>
                    <th>3-Fair</th>
                    <th>4-Good</th>
                    <th>5- Excellent</th>
                </tr>
                <tr>
                    <td>Footings placed at the correct depth and position</td>
                    <td><input type="radio" name="foundation_1" value="1" required></td>
                    <td><input type="radio" name="foundation_1" value="2"></td>
                    <td><input type="radio" name="foundation_1" value="3"></td>
                    <td><input type="radio" name="foundation_1" value="4"></td>
                    <td><input type="radio" name="foundation_1" value="5"></td>
                </tr>
                <tr>
                    <td>Concrete quality and curing meet specifications</td>
                    <td><input type="radio" name="foundation_2" value="1" required></td>
                    <td><input type="radio" name="foundation_2" value="2"></td>
                    <td><input type="radio" name="foundation_2" value="3"></td>
                    <td><input type="radio" name="foundation_2" value="4"></td>
                    <td><input type="radio" name="foundation_2" value="5"></td>
                </tr>
                <tr>
                    <td>Structural framing aligns with architectural plans</td>
                    <td><input type="radio" name="foundation_3" value="1" required></td>
                    <td><input type="radio" name="foundation_3" value="2"></td>
                    <td><input type="radio" name="foundation_3" value="3"></td>
                    <td><input type="radio" name="foundation_3" value="4"></td>
                    <td><input type="radio" name="foundation_3" value="5"></td>
                </tr>
                <tr>
                    <td>Anchor bolts and connectors are correctly installed</td>
                    <td><input type="radio" name="foundation_4" value="1" required></td>
                    <td><input type="radio" name="foundation_4" value="2"></td>
                    <td><input type="radio" name="foundation_4" value="3"></td>
                    <td><input type="radio" name="foundation_4" value="4"></td>
                    <td><input type="radio" name="foundation_4" value="5"></td>
                </tr>
            </table>
            
            <!-- 4. Mechanical, Electrical, and Plumbing (MEP) -->
            <h3>4. Mechanical, Electrical, and Plumbing (MEP)</h3>
            <table class="table table-bordered">
                <tr>
                    <th>Criteria</th>
                    <th>1-Not Compliant</th>
                    <th>2-Poor</th>
                    <th>3-Fair</th>
                    <th>4-Good</th>
                    <th>5- Excellent</th>
                </tr>
                <tr>
                    <td>MEP installations follow approved drawings and code</td>
                    <td><input type="radio" name="mep_1" value="1" required></td>
                    <td><input type="radio" name="mep_1" value="2"></td>
                    <td><input type="radio" name="mep_1" value="3"></td>
                    <td><input type="radio" name="mep_1" value="4"></td>
                    <td><input type="radio" name="mep_1" value="5"></td>
                </tr>
                <tr>
                    <td>Pipes, wires, and ducts are insulated and secured</td>
                    <td><input type="radio" name="mep_2" value="1" required></td>
                    <td><input type="radio" name="mep_2" value="2"></td>
                    <td><input type="radio" name="mep_2" value="3"></td>
                    <td><input type="radio" name="mep_2" value="4"></td>
                    <td><input type="radio" name="mep_2" value="5"></td>
                </tr>
                <tr>
                    <td>System tests (e.g., pressure test, circuit test) have been conducted and passed</td>
                    <td><input type="radio" name="mep_3" value="1" required></td>
                    <td><input type="radio" name="mep_3" value="2"></td>
                    <td><input type="radio" name="mep_3" value="3"></td>
                    <td><input type="radio" name="mep_3" value="4"></td>
                    <td><input type="radio" name="mep_3" value="5"></td>
                </tr>
                <tr>
                    <td>Equipment clearance is maintained</td>
                    <td><input type="radio" name="mep_4" value="1" required></td>
                    <td><input type="radio" name="mep_4" value="2"></td>
                    <td><input type="radio" name="mep_4" value="3"></td>
                    <td><input type="radio" name="mep_4" value="4"></td>
                    <td><input type="radio" name="mep_4" value="5"></td>
                </tr>
            </table>
            
            <!-- 5. Exterior Cladding and Roofing -->
            <h3>5. Exterior Cladding and Roofing</h3>
            <table class="table table-bordered">
                <tr>
                    <th>Criteria</th>
                    <th>1-Not Compliant</th>
                    <th>2-Poor</th>
                    <th>3-Fair</th>
                    <th>4-Good</th>
                    <th>5- Excellent</th>
                </tr>
                <tr>
                    <td>Installation complies with specifications</td>
                    <td><input type="radio" name="exterior_1" value="1" required></td>
                    <td><input type="radio" name="exterior_1" value="2"></td>
                    <td><input type="radio" name="exterior_1" value="3"></td>
                    <td><input type="radio" name="exterior_1" value="4"></td>
                    <td><input type="radio" name="exterior_1" value="5"></td>
                </tr>
                <tr>
                    <td>Waterproofing measures are properly applied</td>
                    <td><input type="radio" name="exterior_2" value="1" required></td>
                    <td><input type="radio" name="exterior_2" value="2"></td>
                    <td><input type="radio" name="exterior_2" value="3"></td>
                    <td><input type="radio" name="exterior_2" value="4"></td>
                    <td><input type="radio" name="exterior_2" value="5"></td>
                </tr>
                <tr>
                    <td>Roofing materials are correctly installed and secured</td>
                    <td><input type="radio" name="exterior_3" value="1" required></td>
                    <td><input type="radio" name="exterior_3" value="2"></td>
                    <td><input type="radio" name="exterior_3" value="3"></td>
                    <td><input type="radio" name="exterior_3" value="4"></td>
                    <td><input type="radio" name="exterior_3" value="5"></td>
                </tr>
                <tr>
                    <td>Exterior finishes are free from defects and damage</td>
                    <td><input type="radio" name="exterior_4" value="1" required></td>
                    <td><input type="radio" name="exterior_4" value="2"></td>
                    <td><input type="radio" name="exterior_4" value="3"></td>
                    <td><input type="radio" name="exterior_4" value="4"></td>
                    <td><input type="radio" name="exterior_4" value="5"></td>
                </tr>
            </table>
            
            <!-- 6. Interior Finishes -->
                        <h3>6. Interior Finishes</h3>
                        <table class="table table-bordered">
                            <tr>
                                <th>Criteria</th>
                                <th>1-Not Compliant</th>
                                <th>2-Poor</th>
                                <th>3-Fair</th>
                                <th>4-Good</th>
                                <th>5- Excellent</th>
                            </tr>
                            <tr>
                                <td>Walls, ceilings, and floors meet quality standards</td>
                                <td><input type="radio" name="interior_1" value="1" required></td>
                                <td><input type="radio" name="interior_1" value="2"></td>
                                <td><input type="radio" name="interior_1" value="3"></td>
                                <td><input type="radio" name="interior_1" value="4"></td>
                                <td><input type="radio" name="interior_1" value="5"></td>
                            </tr>
                            <tr>
                                <td>Paint and coating applications are even and free from defects</td>
                                <td><input type="radio" name="interior_2" value="1" required></td>
                                <td><input type="radio" name="interior_2" value="2"></td>
                                <td><input type="radio" name="interior_2" value="3"></td>
                                <td><input type="radio" name="interior_2" value="4"></td>
                                <td><input type="radio" name="interior_2" value="5"></td>
                            </tr>
                            <tr>
                                <td>Tile work is aligned and properly grouted</td>
                                <td><input type="radio" name="interior_3" value="1" required></td>
                                <td><input type="radio" name="interior_3" value="2"></td>
                                <td><input type="radio" name="interior_3" value="3"></td>
                                <td><input type="radio" name="interior_3" value="4"></td>
                                <td><input type="radio" name="interior_3" value="5"></td>
                            </tr>
                            <tr>
                                <td>Carpentry and joinery work are precise and free from defects</td>
                                <td><input type="radio" name="interior_4" value="1" required></td>
                                <td><input type="radio" name="interior_4" value="2"></td>
                                <td><input type="radio" name="interior_4" value="3"></td>
                                <td><input type="radio" name="interior_4" value="4"></td>
                                <td><input type="radio" name="interior_4" value="5"></td>
                            </tr>
                        </table>
                        
                        <!-- 7. Windows, Doors, and Hardware -->
                        <h3>7. Windows, Doors, and Hardware</h3>
                        <table class="table table-bordered">
                            <tr>
                                <th>Criteria</th>
                                <th>1-Not Compliant</th>
                                <th>2-Poor</th>
                                <th>3-Fair</th>
                                <th>4-Good</th>
                                <th>5- Excellent</th>
                            </tr>
                            <tr>
                                <td>Frames are properly aligned and secured</td>
                                <td><input type="radio" name="windows_1" value="1" required></td>
                                <td><input type="radio" name="windows_1" value="2"></td>
                                <td><input type="radio" name="windows_1" value="3"></td>
                                <td><input type="radio" name="windows_1" value="4"></td>
                                <td><input type="radio" name="windows_1" value="5"></td>
                            </tr>
                            <tr>
                                <td>Glazing is correctly installed and free from cracks</td>
                                <td><input type="radio" name="windows_2" value="1" required></td>
                                <td><input type="radio" name="windows_2" value="2"></td>
                                <td><input type="radio" name="windows_2" value="3"></td>
                                <td><input type="radio" name="windows_2" value="4"></td>
                                <td><input type="radio" name="windows_2" value="5"></td>
                            </tr>
                            <tr>
                                <td>Doors and windows operate smoothly</td>
                                <td><input type="radio" name="windows_3" value="1" required></td>
                                <td><input type="radio" name="windows_3" value="2"></td>
                                <td><input type="radio" name="windows_3" value="3"></td>
                                <td><input type="radio" name="windows_3" value="4"></td>
                                <td><input type="radio" name="windows_3" value="5"></td>
                            </tr>
                            <tr>
                                <td>Locks and other hardware function correctly</td>
                                <td><input type="radio" name="windows_4" value="1" required></td>
                                <td><input type="radio" name="windows_4" value="2"></td>
                                <td><input type="radio" name="windows_4" value="3"></td>
                                <td><input type="radio" name="windows_4" value="4"></td>
                                <td><input type="radio" name="windows_4" value="5"></td>
                            </tr>
                        </table>
                        
                        <!-- 8. Insulation and Ventilation -->
                        <h3>8. Insulation and Ventilation</h3>
                        <table class="table table-bordered">
                            <tr>
                                <th>Criteria</th>
                                <th>1-Not Compliant</th>
                                <th>2-Poor</th>
                                <th>3-Fair</th>
                                <th>4-Good</th>
                                <th>5- Excellent</th>
                            </tr>
                            <tr>
                                <td>Insulation materials meet the specified R-values</td>
                                <td><input type="radio" name="insulation_1" value="1" required></td>
                                <td><input type="radio" name="insulation_1" value="2"></td>
                                <td><input type="radio" name="insulation_1" value="3"></td>
                                <td><input type="radio" name="insulation_1" value="4"></td>
                                <td><input type="radio" name="insulation_1" value="5"></td>
                            </tr>
                            <tr>
                                <td>Insulation is properly installed without gaps</td>
                                <td><input type="radio" name="insulation_2" value="1" required></td>
                                <td><input type="radio" name="insulation_2" value="2"></td>
                                <td><input type="radio" name="insulation_2" value="3"></td>
                                <td><input type="radio" name="insulation_2" value="4"></td>
                                <td><input type="radio" name="insulation_2" value="5"></td>
                            </tr>
                            <tr>
                                <td>Ventilation systems are installed and fully functional</td>
                                <td><input type="radio" name="insulation_3" value="1" required></td>
                                <td><input type="radio" name="insulation_3" value="2"></td>
                                <td><input type="radio" name="insulation_3" value="3"></td>
                                <td><input type="radio" name="insulation_3" value="4"></td>
                                <td><input type="radio" name="insulation_3" value="5"></td>
                            </tr>
                        </table>
                        
                        <!-- 9. Landscaping and External Works -->
                        <h3>9. Landscaping and External Works</h3>
                        <table class="table table-bordered">
                            <tr>
                                <th>Criteria</th>
                                <th>1-Not Compliant</th>
                                <th>2-Poor</th>
                                <th>3-Fair</th>
                                <th>4-Good</th>
                                <th>5- Excellent</th>
                            </tr>
                            <tr>
                                <td>Drainage and soil systems comply with the specifications</td>
                                <td><input type="radio" name="landscaping_1" value="1" required></td>
                                <td><input type="radio" name="landscaping_1" value="2"></td>
                                <td><input type="radio" name="landscaping_1" value="3"></td>
                                <td><input type="radio" name="landscaping_1" value="4"></td>
                                <td><input type="radio" name="landscaping_1" value="5"></td>
                            </tr>
                            <tr>
                                <td>Landscaping elements are correctly located and installed</td>
                                <td><input type="radio" name="landscaping_2" value="1" required></td>
                                <td><input type="radio" name="landscaping_2" value="2"></td>
                                <td><input type="radio" name="landscaping_2" value="3"></td>
                                <td><input type="radio" name="landscaping_2" value="4"></td>
                                <td><input type="radio" name="landscaping_2" value="5"></td>
                            </tr>
                            <tr>
                                <td>Exterior lighting and street furniture are correctly installed</td>
                                <td><input type="radio" name="landscaping_3" value="1" required></td>
                                <td><input type="radio" name="landscaping_3" value="2"></td>
                                <td><input type="radio" name="landscaping_3" value="3"></td>
                                <td><input type="radio" name="landscaping_3" value="4"></td>
                                <td><input type="radio" name="landscaping_3" value="5"></td>
                            </tr>
                        </table>
                        
                        <!-- 10. Final Inspection -->
                        <h3>10. Final Inspection</h3>
                        <table class="table table-bordered">
                            <tr>
                                <th>Criteria</th>
                                <th>1-Not Compliant</th>
                                <th>2-Poor</th>
                                <th>3-Fair</th>
                                <th>4-Good</th>
                                <th>5- Excellent</th>
                            </tr>
                            <tr>
                                <td>All utilities (water, electricity, HVAC) are operational</td>
                                <td><input type="radio" name="final_1" value="1" required></td>
                                <td><input type="radio" name="final_1" value="2"></td>
                                <td><input type="radio" name="final_1" value="3"></td>
                                <td><input type="radio" name="final_1" value="4"></td>
                                <td><input type="radio" name="final_1" value="5"></td>
                            </tr>
                            <tr>
                                <td>Final cleaning has been completed; the site is free from debris</td>
                                <td><input type="radio" name="final_2" value="1" required></td>
                                <td><input type="radio" name="final_2" value="2"></td>
                                <td><input type="radio" name="final_2" value="3"></td>
                                <td><input type="radio" name="final_2" value="4"></td>
                                <td><input type="radio" name="final_2" value="5"></td>
                            </tr>
                            <tr>
                                <td>All project documentation is complete and submitted</td>
                                <td><input type="radio" name="final_3" value="1" required></td>
                                <td><input type="radio" name="final_3" value="2"></td>
                                <td><input type="radio" name="final_3" value="3"></td>
                                <td><input type="radio" name="final_3" value="4"></td>
                                <td><input type="radio" name="final_3" value="5"></td>
                            </tr>
                            <tr>
                                <td>All previously identified punch list items have been resolved</td>
                                <td><input type="radio" name="final_4" value="1" required></td>
                                <td><input type="radio" name="final_4" value="2"></td>
                                <td><input type="radio" name="final_4" value="3"></td>
                                <td><input type="radio" name="final_4" value="4"></td>
                                <td><input type="radio" name="final_4" value="5"></td>
                            </tr>
                        </table>
                        
                        <h3>Additional Comments and Observations:</h3>
                        <textarea name="comments" rows="4"></textarea>

                        <div style="text-align: center; margin-top: 20px;">
                            <button type="submit">Submit Rating</button>
                            <button type="button" onclick="window.history.back()" style="margin-left: 10px;">Cancel</button>
                        </div>
                    </form>
                </div>
            </body>
        </html>