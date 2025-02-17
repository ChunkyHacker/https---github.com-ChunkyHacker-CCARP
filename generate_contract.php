<?php
session_start(); 
include('config.php');

if (!isset($_SESSION['Carpenter_ID'])) {
    header('Location: login.html');
    exit();
}
?>

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

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 400px;
            position: relative;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php
    // Assuming you already have the $contract['start_date'] and $contract['end_date']
    // Fetching the dates from contract
    $start_date = $contract['start_date'];
    $end_date = $contract['end_date'];

    // Convert the dates to DateTime objects
    $startDateTime = new DateTime($start_date);
    $endDateTime = new DateTime($end_date);

    // Calculate the difference between the two dates
    $interval = $startDateTime->diff($endDateTime);
    $duration = $interval->format('%a'); // Get the number of days
?>

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
            <?php if (!empty($photoPath) && file_exists($photoPath)): ?>
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

        <!-- Button to Open Modal -->
        <button type="button" onclick="openContractModal()" class="btn btn-primary">Name ur price</button>

        <!-- Modal Structure -->
        <div id="contractModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeContractModal()">&times;</span>
                <h3>Project Details</h3>
                <p><strong>Start Date:</strong> <?php echo date("F j, Y", strtotime($contract['start_date'])); ?></p>
                <p><strong>End Date:</strong> <?php echo date("F j, Y", strtotime($contract['end_date'])); ?></p>
                
                <!-- Show duration in days -->
                <p><strong>Duration:</strong> <?php echo $duration; ?> day(s)</p>
                
                <!-- Price Input -->
                <label for="priceInput">Enter Price per Day:</label>
                <input type="number" id="priceInput" name="priceInput" placeholder="Enter price per day" required><br><br>

                <!-- Labor Cost Display -->
                <label for="laborCost">Labor Cost:</label>
                <input type="text" id="laborCost" name="laborCost" placeholder="Labor cost will be calculated" readonly><br><br>

                <!-- Save Button (does not save to the database, just for pre-save purposes) -->
                <button type="button" onclick="saveLaborCost()">Save Labor Cost</button>
            </div>
        </div>
        <p class="contract-text">
            Both parties agree to the conditions stated above. The contractor is responsible for completing the project within the agreed timeframe and budget.
        </p>
        <!-- Labor Cost display outside modal -->
        <p><strong>Labor Cost:</strong> <span id="savedLaborCostDisplay">PHP 0.00</span></p>

        <!-- Hidden field to store labor cost for backend use -->
        <input type="hidden" id="hiddenLaborCost" name="labor_cost" value="0.00">

        <br>
        <input type="hidden" name="requirement_ID" value="<?php echo $requirement_ID; ?>">
        <br><br>
        <button type="submit">Submit to client</button>
    </form>
</div>

<!-- JavaScript for Modal -->
<script>
    window.onload = function() {
        document.getElementById('contractModal').style.display = 'none';
    };

    function openContractModal() {
        document.getElementById('contractModal').style.display = 'flex';
    }

    function closeContractModal() {
        document.getElementById('contractModal').style.display = 'none';
    }

    function calculateLaborCost() {
        // Get the price and duration values
        var pricePerDay = parseFloat(document.getElementById('priceInput').value);
        var duration = <?php echo $duration; ?>; // PHP value for duration in days
        
        // Check if price per day is provided
        if (!isNaN(pricePerDay) && pricePerDay > 0) {
            // Calculate the labor cost
            var laborCost = pricePerDay * duration;
            // Display the calculated labor cost
            document.getElementById('laborCost').value = 'PHP ' + laborCost.toFixed(2);
        } else {
            // If price is not valid, show an error
            alert("Please enter a valid price per day.");
        }
    }

    function saveLaborCost() {
        // Get the labor cost from the input field
        var laborCostValue = document.getElementById('laborCost').value;

        // Save the calculated labor cost (this is just pre-save for display purposes)
        document.getElementById('savedLaborCostDisplay').textContent = laborCostValue;

        // Save the labor cost in the hidden input field for backend use
        document.getElementById('hiddenLaborCost').value = laborCostValue;

        // Close the modal after saving the labor cost
        closeContractModal();
    }

    // Automatically call the labor cost calculation whenever the price input changes
    document.getElementById('priceInput').addEventListener('input', calculateLaborCost);
</script>
</body>
</html>
