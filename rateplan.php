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

    $sql = "INSERT INTO ratings (contract_ID, plan_ID, criteria1, criteria2, criteria3, criteria4, criteria5, criteria6, criteria7, criteria8, comments) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiiiiiiiss", $contract_ID, $plan_ID, $criteria1, $criteria2, $criteria3, $criteria4, $criteria5, $criteria6, $criteria7, $criteria8, $comments);
    
    if ($stmt->execute()) {
        echo "<script>alert('Rating submitted successfully!'); window.location.href='userviewprogress.php?plan_ID=" . $plan_ID . "';</script>";
    } else {
        echo "<script>alert('Error submitting rating.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate Plan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center; /* Changed from left to center */
        }

        th {
            background-color: #FF8C00;
            color: white;
        }

        th:first-child {
            text-align: left; /* Keep first column (Criteria) left-aligned */
            background-color: #FF8C00;
        }

        td:first-child {
            text-align: left; /* Keep first column (Criteria) left-aligned */
        }

        .radio-group {
            display: flex;
            justify-content: space-around;
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
</head>
<body>
    <div class="rating-container">
        <h2>Rate the Carpenter's Work</h2>
        <form method="POST">
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