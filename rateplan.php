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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $criteria1 = $_POST['criteria1'];
    $criteria2 = $_POST['criteria2'];
    $criteria3 = $_POST['criteria3'];
    $criteria4 = $_POST['criteria4'];
    $criteria5 = $_POST['criteria5'];
    $criteria6 = $_POST['criteria6'];
    $criteria7 = $_POST['criteria7'];
    $criteria8 = $_POST['criteria8'];
    $comments = $_POST['comments'];
    $rating_date = date('Y-m-d H:i:s'); // Add current date and time

    // Check if user has already rated this contract
    $check_sql = "SELECT * FROM ratings WHERE contract_ID = ? AND plan_ID = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $contract_ID, $plan_ID);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    // Check if user has already rated this contract
    if ($result->num_rows > 0) {
        echo "<script>
            alert('You have already submitted a rating for this project.');
            window.location.href = 'userviewprogress.php?plan_ID=" . $plan_ID . "';
        </script>";
        exit();
    }

    $sql = "INSERT INTO ratings (contract_ID, plan_ID, user_ID, criteria1, criteria2, criteria3, criteria4, criteria5, criteria6, criteria7, criteria8, comments, rating_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiiiiiiiiiss", $contract_ID, $plan_ID, $user_ID, $criteria1, $criteria2, $criteria3, $criteria4, $criteria5, $criteria6, $criteria7, $criteria8, $comments, $rating_date);
    
    if ($stmt->execute()) {
        // Update contract status to indicate it's been rated
        $update_sql = "UPDATE contracts SET status = 'Rated' WHERE contract_ID = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $contract_ID);
        $update_stmt->execute();

        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Success!',
                    text: 'Thank you for rating this project!',
                    icon: 'success',
                    confirmButtonColor: '#FF8C00'
                }).then((result) => {
                    window.location.href = 'userviewprogress.php?plan_ID=" . $plan_ID . "';
                });
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Failed to submit rating. Please try again.',
                icon: 'error',
                confirmButtonColor: '#FF8C00'
            });
        </script>";
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
        <h2>Rate the Carpenter's Work</h2>
        <form method="POST">
            <!-- Add hidden fields -->
            <input type="hidden" name="plan_ID" value="<?php echo $plan_ID; ?>">
            <input type="hidden" name="contract_ID" value="<?php echo $contract_ID; ?>">
            <input type="hidden" name="user_ID" value="<?php echo $user_ID; ?>">
            
            <table>
                <tr>
                    <th>Criteria</th>
                    <th>1 - Poor</th>
                    <th>2 - Fair</th>
                    <th>3 - Good</th>
                    <th>4 - Very Good</th>
                    <th>5 - Excellent</th>
                </tr>
                <tr>
                    <td>The finished project matched the agreed design and measurements.</td>
                    <td><input type="radio" name="criteria1" value="1" required></td>
                    <td><input type="radio" name="criteria1" value="2"></td>
                    <td><input type="radio" name="criteria1" value="3"></td>
                    <td><input type="radio" name="criteria1" value="4"></td>
                    <td><input type="radio" name="criteria1" value="5"></td>
                </tr>
                <tr>
                    <td>There were no alignment issues (e.g., no uneven surfaces, gaps, or misalignment).</td>
                    <td><input type="radio" name="criteria2" value="1" required></td>
                    <td><input type="radio" name="criteria2" value="2"></td>
                    <td><input type="radio" name="criteria2" value="3"></td>
                    <td><input type="radio" name="criteria2" value="4"></td>
                    <td><input type="radio" name="criteria2" value="5"></td>
                </tr>
                <tr>
                    <td>The finishing quality (sanding, painting, varnishing) is smooth and professionally done.</td>
                    <td><input type="radio" name="criteria3" value="1" required></td>
                    <td><input type="radio" name="criteria3" value="2"></td>
                    <td><input type="radio" name="criteria3" value="3"></td>
                    <td><input type="radio" name="criteria3" value="4"></td>
                    <td><input type="radio" name="criteria3" value="5"></td>
                </tr>
                <tr>
                    <td>There were no visible defects (e.g., cracks, loose fittings, rough edges).</td>
                    <td><input type="radio" name="criteria4" value="1" required></td>
                    <td><input type="radio" name="criteria4" value="2"></td>
                    <td><input type="radio" name="criteria4" value="3"></td>
                    <td><input type="radio" name="criteria4" value="4"></td>
                    <td><input type="radio" name="criteria4" value="5"></td>
                </tr>
                <tr>
                    <td>The carpenter followed proper carpentry techniques (e.g., joints, reinforcements, fastening).</td>
                    <td><input type="radio" name="criteria5" value="1" required></td>
                    <td><input type="radio" name="criteria5" value="2"></td>
                    <td><input type="radio" name="criteria5" value="3"></td>
                    <td><input type="radio" name="criteria5" value="4"></td>
                    <td><input type="radio" name="criteria5" value="5"></td>
                </tr>
                <tr>
                    <td>The project was completed within the agreed timeframe.</td>
                    <td><input type="radio" name="criteria6" value="1" required></td>
                    <td><input type="radio" name="criteria6" value="2"></td>
                    <td><input type="radio" name="criteria6" value="3"></td>
                    <td><input type="radio" name="criteria6" value="4"></td>
                    <td><input type="radio" name="criteria6" value="5"></td>
                </tr>
                <tr>
                    <td>I am satisfied with the overall quality of the carpentry work.</td>
                    <td><input type="radio" name="criteria7" value="1" required></td>
                    <td><input type="radio" name="criteria7" value="2"></td>
                    <td><input type="radio" name="criteria7" value="3"></td>
                    <td><input type="radio" name="criteria7" value="4"></td>
                    <td><input type="radio" name="criteria7" value="5"></td>
                </tr>
                <tr>
                    <td>I would recommend this carpenter for future projects.</td>
                    <td><input type="radio" name="criteria8" value="1" required></td>
                    <td><input type="radio" name="criteria8" value="2"></td>
                    <td><input type="radio" name="criteria8" value="3"></td>
                    <td><input type="radio" name="criteria8" value="4"></td>
                    <td><input type="radio" name="criteria8" value="5"></td>
                </tr>
            </table>

            <h3>Comments / Suggestions for Improvement:</h3>
            <textarea name="comments" rows="4"></textarea>

            <div style="text-align: center; margin-top: 20px;">
                <button type="submit">Submit Rating</button>
                <button type="button" onclick="window.history.back()" style="margin-left: 10px;">Cancel</button>
            </div>
        </form>
    </div>
</body>
</html>