<?php
    session_start(); 
    include('config.php');

    if (!isset($_SESSION['Carpenter_ID'])) {
        header('Location: login.html');
        exit();
    }

    if (!isset($_GET['plan_ID'])) {
        die("Project ID is required.");
    }

    $plan_ID = $_GET['plan_ID'];

    // ✅ Get contract details from plan table including approval_ID
    $query = "SELECT p.*, pa.approval_ID, pa.Carpenter_ID FROM plan p 
            JOIN plan_approval pa ON p.plan_ID = pa.plan_ID 
            WHERE p.plan_ID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $plan_ID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$contract = mysqli_fetch_assoc($result)) {
        die("Contract not found.");
    }

    // ✅ Assign approval_ID ug Carpenter_ID
    $approval_ID = isset($contract['approval_ID']) ? $contract['approval_ID'] : null;
    $Carpenter_ID = $_SESSION['Carpenter_ID']; // Get Carpenter_ID from session



    // Get carpenter details
    $carpenter_name = "Unknown Carpenter";
    if (!empty($Carpenter_ID)) {
        $carpenterQuery = "SELECT First_Name, Last_Name FROM carpenters WHERE Carpenter_ID = ?";
        $carpenterStmt = mysqli_prepare($conn, $carpenterQuery);
        mysqli_stmt_bind_param($carpenterStmt, "i", $Carpenter_ID);
        mysqli_stmt_execute($carpenterStmt);
        $carpenterResult = mysqli_stmt_get_result($carpenterStmt);

        if ($carpenter = mysqli_fetch_assoc($carpenterResult)) {
            $carpenter_name = $carpenter['First_Name'] . " " . $carpenter['Last_Name'];
        }
    }

    // Assign variables to avoid undefined array keys
    $start_date = isset($contract['start_date']) ? $contract['start_date'] : "Not Available";
    $end_date = isset($contract['end_date']) ? $contract['end_date'] : "Not Available";

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

    // Get carpenter details
    $sql = "SELECT * FROM carpenters WHERE Carpenter_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $Carpenter_ID);
    $stmt->execute();
    $result = $stmt->get_result();
    $carpenterDetails = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Contract</title>
    <style>
        /* Global font styles */
        body {
            font-family: Arial, sans-serif;
            font-size: 18px;
            line-height: 1.6;
        }

        /* Sidebar styles */
        .sidenav {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #FF8C00;
            overflow-x: hidden;
            padding-top: 20px;
        }

        .sidenav .profile-section {
            text-align: center;
            padding: 10px;
            margin-bottom: 10px;
        }

        .sidenav .profile-section img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 5px;
            object-fit: cover;
        }

        .sidenav h3 {
            font-size: 28px;
            margin-bottom: 2px;
            line-height: 1.2;
            color: #000000;
            display: block;
        }

        .sidenav p {
            font-size: 20px;
            margin-bottom: 10px;
            color: #000000;
        }

        .sidenav a {
            padding: 8px 15px;
            text-decoration: none;
            font-size: 24px;
            color: #000000;
            display: block;
            transition: 0.3s;
            margin-bottom: 2px;
        }

        .sidenav a:hover {
            background-color: #000000;
            color: #FF8C00;
        }

        /* Contract styles */
        .contract-container {
            margin-left: 270px;
            max-width: 800px;
            padding: 40px;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .contract-title {
            text-align: center;
            font-size: 28px;
            font-weight: normal;
            margin-bottom: 30px;
            text-transform: uppercase;
        }

        .contract-text {
            text-align: justify;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .project-details {
            margin: 20px 0;
        }

        .project-details p {
            margin: 10px 0;
            line-height: 1.6;
        }

        .project-photo {
            text-align: center;
            margin: 30px 0;
        }

        .project-photo img {
            max-width: 400px;
            height: auto;
            border: 1px solid #ddd;
            padding: 5px;
        }

        .dates-section {
            margin: 20px 0;
        }

        .dates-section p {
            margin: 10px 0;
        }

        .labor-cost {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
        }

        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            padding: 0 50px;
        }

        .signature-line {
            width: 250px;
            text-align: center;
        }

        .signature-line p {
            margin: 0;
            line-height: 1.4;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Form elements */
        input[type="text"],
        input[type="number"],
        textarea {
            font-family: Arial, sans-serif;
            font-size: 18px;
            padding: 8px;
        }

        /* Buttons */
        button {
            font-family: Arial, sans-serif;
            font-size: 16px;
        }

        /* Modal styles */
        .modal-content {
            font-family: Arial, sans-serif;
        }

        .modal-content h2 {
            font-size: 28px;
            font-weight: normal;
        }

        .modal-content label {
            font-size: 18px;
        }

        .modal-content input {
            font-size: 18px;
        }

        /* Signature section */
        .signature-section p {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidenav">
        <div class="profile-section">
            <?php
            // Display profile picture
            if (isset($carpenterDetails['Photo']) && !empty($carpenterDetails['Photo'])) {
                echo '<img src="' . $carpenterDetails['Photo'] . '" alt="Profile Picture">';
            } else {
                echo '<img src="assets/img/default-avatar.png" alt="Default Profile Picture">';
            }
            
            // Split name into separate lines
            $firstName = $carpenterDetails['First_Name'];
            $lastName = $carpenterDetails['Last_Name'];
            
            echo "<h3>$firstName $lastName</h3>";
            echo "<p>Carpenter ID: " . $Carpenter_ID . "</p>";
            ?>
        </div>
        <a href="comment.php"><i class="fas fa-home"></i> Home</a>
        <a href="about.php"><i class="fas fa-info-circle"></i> About</a>
        <a href="getideas.php"><i class="fas fa-lightbulb"></i> Get Ideas</a>
        <a href="project.php"><i class="fas fa-project-diagram"></i> Project</a>
        <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

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
    <h2 class="contract-title">CONSTRUCTION AGREEMENT</h2>

    <p class="contract-text">
        This Construction Agreement (the "Agreement") is made and entered into by and between <strong><?php echo $client_name; ?></strong> (hereinafter referred to as the "Client") and <strong><?php echo $carpenter_name; ?></strong> (hereinafter referred to as the "Contractor"). This Agreement sets forth the terms and conditions governing the construction project as detailed below:
    </p>

    <h3>1. Project Details</h3>
    <div class="project-details">
        <p><strong>Lot Area:</strong> <?php echo $contract['length_lot_area']; ?>m x <?php echo $contract['width_lot_area']; ?>m (<?php echo $contract['square_meter_lot']; ?> sqm)</p>
        <p><strong>Floor Area:</strong> <?php echo $contract['length_floor_area']; ?>m x <?php echo $contract['width_floor_area']; ?>m (<?php echo $contract['square_meter_floor']; ?> sqm)</p>
        <p><strong>Project Type:</strong> <?php echo $contract['type']; ?></p>
        <p><strong>Initial Budget:</strong> PHP <?php echo number_format($contract['initial_budget'], 2); ?></p>
        <p><strong>Scope of Work:</strong> Construction of residential/commercial building, including foundation, framework, roofing, plumbing, and electrical installation.</p>
        <p><strong>Materials to be Used:</strong> High-grade concrete, steel reinforcements, plywood, cement, roofing materials, and other necessary building supplies.</p>
    </div>

    <div class="project-photo">
        <?php 
        if (!empty($photoPath)) {
            echo "<img src='$photoPath' alt='Project Photo'>";
        }
        ?>
    </div>

    <h3>2. Responsibilities</h3>
    <p class="contract-text">
        <strong>Contractor’s Responsibilities:</strong>
        <ul>
            <li>Provide all labor, tools, and materials necessary for the completion of the project.</li>
            <li>Ensure the work is completed in compliance with the approved design and specifications.</li>
            <li>Adhere to all safety and building regulations applicable to the construction site.</li>
            <li>Maintain a clean and organized construction site.</li>
        </ul>
    </p>
    <p class="contract-text">
        <strong>Client’s Responsibilities:</strong>
        <ul>
            <li>Provide necessary permits and approvals required for the construction.</li>
            <li>Ensure timely payments as per the agreed payment schedule.</li>
            <li>Facilitate access to the construction site for the contractor and workers.</li>
        </ul>
    </p>

    <h3>3. Project Timeline</h3>
    <div class="dates-section">
        <p><strong>Start Date:</strong> <?php echo date("F j, Y", strtotime($contract['start_date'])); ?></p>
        <p><strong>End Date:</strong> <?php echo date("F j, Y", strtotime($contract['end_date'])); ?></p>
        <p><strong>Duration:</strong> <?php echo $duration; ?> day(s)</p>
        <p><strong>Work Schedule:</strong> Monday to Saturday, 8:00 AM - 5:00 PM</p>
    </div>

    <h3>4. Payment Terms</h3>
    <div class="labor-cost">
        <p><strong>Labor Cost:</strong> <span id="savedLaborCostDisplay">PHP 0.00</span></p>
        <p><strong>Type of Work: <span id="savedTypeOfWorkDisplay"></span></strong></p>
        <p><strong>Payment Schedule:</strong>
            <ul>
                <li>30% Initial Down Payment upon contract signing</li>
                <li>40% Midway Payment after 50% of work completion</li>
                <li>30% Final Payment upon project completion and approval</li>
            </ul>
        </p>
        <button onclick="openContractModal()" 
                style="background-color: #FF8C00; color: white; padding: 10px 20px; 
                border: none; border-radius: 5px; cursor: pointer; margin-top: 10px;">
            Name Your Price
        </button>
    </div>

    <h3>5. Terms and Conditions</h3>
    <p class="contract-text">
        <strong>Amendments:</strong> Any modifications to this Agreement must be made in writing and signed by both parties.
    </p>
    <p class="contract-text">
        <strong>Termination Clause:</strong> Either party may terminate this Agreement with a written notice of at least 14 days, provided valid reasons are given.
    </p>
    <p class="contract-text">
        <strong>Dispute Resolution:</strong> Any disputes arising from this Agreement shall be settled through negotiation. If unresolved, legal action may be pursued as per governing laws.
    </p>

    <h3>6. Signatures</h3>
    <div class="signature-section">
        <div class="signature-line">
            <p>_______________________</p>
            <p style="margin-top: 5px;"><?php echo $client_name; ?></p>
            <p style="margin-top: 0; font-size: 14px; font-weight: bold; color: #000;">Client Signature over Printed Name</p>
        </div>
        <div class="signature-line">
            <p>_______________________</p>
            <p style="margin-top: 5px;"><?php echo $carpenter_name; ?></p>
            <p style="margin-top: 0; font-size: 14px; font-weight: bold; color: #000;">Contractor Signature over Printed Name</p>
        </div>
    </div>

    <form method="POST" action="save_agreement.php" style="display: flex; gap: 10px; justify-content: flex-start;">
            <input type="hidden" name="plan_ID" value="<?php echo $plan_ID; ?>">
            <input type="hidden" name="labor_cost" id="labor_cost_input" value="0.00">
            <input type="hidden" name="type_of_work" id="type_of_work_input" value="">
            <input type="hidden" name="duration" value="<?php echo $duration; ?>">
            <input type="hidden" name="approval_ID" value="<?php echo $approval_ID; ?>">
            <input type="hidden" name="Carpenter_ID" value="<?php echo $Carpenter_ID; ?>">
            <button type="button" onclick="history.back()" 
                style="width: 150px; height: 50px; background-color: #FF8C00; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                Go Back</button>
            <button type="submit" 
                style="width: 150px; height: 50px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                Submit to Client</button>
        </form>
    </div>

<!-- Modal for Set Labor Cost -->
<div id="contractModal" class="modal" style="display: none; 
    position: fixed; 
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%; 
    background-color: rgba(0,0,0,0.5); 
    z-index: 1000;
    /* Center modal content */
    align-items: center;
    justify-content: center;">
    
    <div class="modal-content" style="
        background-color: white; 
        padding: 30px; 
        width: 40%; 
        min-height: 300px;
        /* Remove margin since we're using flex centering */
        margin: 0 auto;
        position: relative;
        font-family: Arial, sans-serif;">
        
        <h2 style="font-size: 28px; margin-bottom: 25px; font-weight: normal;">Set Labor Cost</h2>
        
        <!-- Type of Work dropdown -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 10px; font-size: 18px;">Type of Work:</label>
            <select id="typeOfWork" style="width: 100%; 
                   padding: 8px; 
                   border: 1px solid #000;
                   border-radius: 0;
                   font-size: 18px;">
                <option value="">Select type of work</option>
                <option value="Per day">Per day</option>
                <option value="On The Job">On The Job</option>
            </select>
        </div>
        
        <!-- Duration field -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 10px; font-size: 18px;">Duration:</label>
            <input type="text" value="<?php echo $duration; ?> day(s)" readonly
                   style="width: 100%; 
                          padding: 8px; 
                          border: 1px solid #000;
                          border-radius: 0;
                          background-color: #f9f9f9;
                          font-size: 18px;">
        </div>
        
        <!-- Price per day input -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 10px; font-size: 18px;">Price per Day (PHP):</label>
            <input type="number" id="priceInput" 
                   style="width: 100%; 
                          padding: 8px; 
                          border: 1px solid #000;
                          border-radius: 0;
                          font-size: 18px;">
        </div>

        <!-- Calculated Labor Cost -->
        <div style="margin-bottom: 30px;">
            <label style="display: block; margin-bottom: 10px; font-size: 18px;">Labor Cost:</label>
            <input type="text" id="laborCost" readonly 
                   style="width: 100%; 
                          padding: 8px;
                          border: 1px solid #000;
                          border-radius: 0;
                          background-color: #fff;
                          font-size: 18px;">
        </div>

        <!-- Buttons container at the bottom -->
        <div style="position: absolute; 
                    bottom: 20px; 
                    right: 20px; 
                    text-align: right;">
            <button onclick="saveLaborCost()" 
                    style="background-color: #4CAF50; 
                           color: white; 
                           padding: 8px 20px; 
                           border: none; 
                           margin-right: 10px;
                           cursor: pointer;
                           font-size: 16px;">Save</button>
            <button onclick="closeContractModal()" 
                    style="background-color: #f44336; 
                           color: white; 
                           padding: 8px 20px; 
                           border: none;
                           cursor: pointer;
                           font-size: 16px;">Cancel</button>
        </div>
    </div>
</div>

<!-- Add this JavaScript before the closing body tag -->
<script>
    window.onload = function() {
        document.getElementById('contractModal').style.display = 'none';
    };

    function openContractModal() {
        document.getElementById('contractModal').style.display = 'flex';
        // Clear previous values
        document.getElementById('priceInput').value = '';
        document.getElementById('laborCost').value = '';
        // Set default type of work to empty (requiring selection)
        document.getElementById('typeOfWork').value = '';
    }

    function closeContractModal() {
        document.getElementById('contractModal').style.display = 'none';
    }

    function calculateLaborCost() {
        // Get the price and duration values
        var pricePerDay = parseFloat(document.getElementById('priceInput').value);
        var duration = <?php echo $duration; ?>; // PHP value for duration in days
        var typeOfWork = document.getElementById('typeOfWork').value;
        
        // Check if type of work is selected
        if (!typeOfWork) {
            document.getElementById('laborCost').value = '';
            return;
        }
        
        // Check if price per day is provided
        if (!isNaN(pricePerDay) && pricePerDay > 0) {
            // Calculate the labor cost - always multiply by duration regardless of type
            var laborCost = pricePerDay * duration;
            
            // Display the calculated labor cost with proper formatting
            document.getElementById('laborCost').value = 'PHP ' + laborCost.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        } else {
            // Clear the labor cost field if price is invalid
            document.getElementById('laborCost').value = '';
        }
    }

    function saveLaborCost() {
        // Get the labor cost from the input field
        var laborCostText = document.getElementById('laborCost').value;
        var typeOfWork = document.getElementById('typeOfWork').value;
        
        // Check if a type of work has been selected
        if (!typeOfWork) {
            alert("Please select a type of work.");
            return;
        }
        
        // Check if a labor cost has been calculated
        if (!laborCostText) {
            alert("Please enter a valid price.");
            return;
        }
        
        // Extract the numeric value from the formatted text (remove 'PHP ' and commas)
        var laborCostValue = parseFloat(laborCostText.replace('PHP ', '').replace(/,/g, ''));
        
        // Save the calculated labor cost for display
        document.getElementById('savedLaborCostDisplay').textContent = laborCostText;
        
        // Save the type of work for display
        document.getElementById('savedTypeOfWorkDisplay').textContent = typeOfWork;
        
        // Save the labor cost in the hidden input field for backend use
        document.getElementById('labor_cost_input').value = laborCostValue;
        
        // Save the type of work in the hidden input field
        document.getElementById('type_of_work_input').value = typeOfWork;
        
        // Close the modal after saving the labor cost
        closeContractModal();
    }

    // Automatically call the labor cost calculation whenever the price input changes
    document.getElementById('priceInput').addEventListener('input', calculateLaborCost);
    
    // Also recalculate when type of work changes
    document.getElementById('typeOfWork').addEventListener('change', calculateLaborCost);
</script>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</body>
</html>
