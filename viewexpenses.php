<?php
// Include database connection
include 'config.php';

// Query to fetch data from the expenses table without filtering by requirement_ID
$query = "SELECT 
            expenses_id, 
            materials, 
            type, 
            quantity, 
            price, 
            total, 
            carpenter_name, 
            Netpay, 
            Days_Of_Work, 
            Rate_per_day, 
            overall_cost 
          FROM expenses";

// Prepare and execute the query
if ($stmt = $conn->prepare($query)) {
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    die("Error preparing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Expenses</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Verdana, sans-serif;
            margin: 0;
            background-color: #f4f4f4; /* Light background for a cleaner look */
            font-size: 18px;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 30px;
            flex-direction: column;
            height: 100vh;
            margin-top: 20px; /* Adds space at the top */
            margin-bottom: 20px; /* Adds space at the bottom */
        }

        .header {
            background: #FF8C00; /* Orange background */
            padding: 15px;
            text-align: center;
            color: #fff;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px; /* Adds space below header */
        }

        .header a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
            margin-left: 15px;
        }

        button {
            background-color: #FF8C00; /* Orange button */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }

        button:hover {
            background-color: #cc7000; /* Darker orange on hover */
        }

        .table-container {
            margin-top: 20px; /* Adds space above the table */
            margin-bottom: 20px; /* Adds space below the table */
            width: 100%;
            padding: 15px;
            background-color: #fff; /* White background for the table container */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff; /* White background for the table */
            border-radius: 5px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #FF8C00;
            color: #fff;
        }

        .button-container {
            margin-bottom: 20px;
        }

        .cancel-btn {
            background-color: red;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            font-size: 18px;
        }

        .cancel-btn:hover {
            background-color: #cc0000;
        }

        @media screen and (max-width: 600px) {
            body {
                padding: 10px;
                font-size: 16px;
            }

            table {
                font-size: 14px;
            }

            button {
                font-size: 16px;
            }
        }

    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        Project Expenses
    </div>

    <div class="container">
        <!-- Go Back Button -->

        <?php if ($result->num_rows > 0): ?>
            <div class="table-container">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Expenses ID</th>
                            <th>Materials</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Carpenter Name</th>
                            <th>Net Pay</th>
                            <th>Days of Work</th>
                            <th>Rate per Day</th>
                            <th>Overall Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['expenses_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['materials']); ?></td>
                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                <td><?php echo htmlspecialchars($row['price']); ?></td>
                                <td><?php echo htmlspecialchars($row['total']); ?></td>
                                <td><?php echo htmlspecialchars($row['carpenter_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['Netpay']); ?></td>
                                <td><?php echo htmlspecialchars($row['Days_Of_Work']); ?></td>
                                <td><?php echo htmlspecialchars($row['Rate_per_day']); ?></td>
                                <td><?php echo htmlspecialchars($row['overall_cost']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No expenses found for this project.</p>
        <?php endif; ?>

        <?php $stmt->close(); ?>
        <div class="button-container">
            <button onclick="history.back()">Go Back</button>
        </div>
    </div>

</body>
</html>
