<?php
include('config.php');

if (!isset($_GET['id'])) {
    die("Project ID is required.");
}

$requirement_ID = $_GET['id'];

// Get contract details from projectrequirements table
$query = "SELECT * FROM projectrequirements WHERE requirement_ID = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $requirement_ID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$contract = mysqli_fetch_assoc($result)) {
    die("Contract not found.");
}

// Get client name from users table
$client_name = "Unknown Client"; 
if (!empty($contract['User_ID'])) {  
    $userQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
    $userStmt = mysqli_prepare($conn, $userQuery);
    mysqli_stmt_bind_param($userStmt, "i", $contract['User_ID']);
    mysqli_stmt_execute($userStmt);
    $userResult = mysqli_stmt_get_result($userStmt);

    if ($user = mysqli_fetch_assoc($userResult)) {
        $client_name = $user['First_Name'] . " " . $user['Last_Name'];
    }
}

// Get project photo
$photoPath = $contract['Photo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Contract</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 20px; }
        .contract-container { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ddd; }
        .contract-title { text-align: center; font-size: 22px; font-weight: bold; }
        .contract-text { text-align: justify; }
        .highlight { font-weight: bold; text-transform: uppercase; }
        .project-photo { text-align: center; margin-top: 20px; }
        .project-photo img { max-width: 100%; height: auto; border: 1px solid #ccc; padding: 5px; }
    </style>
</head>
<body>

<div class="contract-container">
    <form method="POST" action="save_agreement.php">
        <h2 class="contract-title">CONSTRUCTION AGREEMENT</h2>

        <p class="contract-text">
            This agreement is made between <span class="highlight"><?php echo $client_name; ?></span> (Client) and <span class="highlight"><?php echo $contract['approved_by']; ?></span> (Contractor), regarding the construction project with the following details:
        </p>

        <p><strong>Lot Area:</strong> <?php echo $contract['length_lot_area']; ?>m x <?php echo $contract['width_lot_area']; ?>m (<?php echo $contract['square_meter_lot']; ?> sqm)</p>
        <p><strong>Floor Area:</strong> <?php echo $contract['length_floor_area']; ?>m x <?php echo $contract['width_floor_area']; ?>m (<?php echo $contract['square_meter_floor']; ?> sqm)</p>
        <p><strong>Project Type:</strong> <?php echo $contract['type']; ?></p>
        <p><strong>Initial Budget:</strong> PHP <?php echo number_format($contract['initial_budget'], 2); ?></p>

        <div class="project-photo">
            <h3>Project Photo</h3>
            <?php if (!empty($photoPath) && file_exists($photoPath)): ?>
                <p>Photo:</p>
                <div style='text-align: center;'>
                    <a href='#' onclick='openModal("<?php echo $photoPath; ?>")'>
                        <img src="<?php echo $photoPath; ?>" alt="Project Photo" style="width: 700px; height: 400px;">
                    </a>
                </div>
            <?php else: ?>
                <p>No project photo available.</p>
            <?php endif; ?>
        </div>

        <p class="contract-text">
            The project is approved by <span class="highlight"><?php echo $contract['approved_by']; ?></span> and will proceed according to the agreed terms.
        </p>

        <p><strong>Start Date:</strong> <?php echo date("F j, Y", strtotime($contract['start_date'])); ?></p>
        <p><strong>End Date:</strong> <?php echo date("F j, Y", strtotime($contract['end_date'])); ?></p>

        <p class="contract-text">
            Both parties agree to the conditions stated above. The contractor is responsible for completing the project within the agreed timeframe and budget.
        </p>
        
        <br>
            <input type="hidden" name="requirement_ID" value="<?php echo $requirement_ID; ?>">
            <br><br>
            <button type="submit">Submit to client</button>
    </form>
</div>

</body>
</html>
