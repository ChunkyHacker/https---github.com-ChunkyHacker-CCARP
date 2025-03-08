<?php
session_start();
include('config.php');

if (!isset($_SESSION['Carpenter_ID'])) {
    exit('Unauthorized access');
}

if (!isset($_GET['contract_ID'])) {
    exit('Contract ID is required');
}

$contract_ID = $_GET['contract_ID'];

// Query to get contract details
$query = "SELECT c.*, p.*, u.First_Name AS client_first, u.Last_Name AS client_last,
          cr.First_Name AS carpenter_first, cr.Last_Name AS carpenter_last
          FROM contracts c
          JOIN plan p ON c.Plan_ID = p.Plan_ID
          JOIN users u ON p.User_ID = u.User_ID
          JOIN carpenters cr ON c.Carpenter_ID = cr.Carpenter_ID
          WHERE c.contract_ID = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $contract_ID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($contract = mysqli_fetch_assoc($result)) {
    // Calculate duration
    $startDateTime = new DateTime($contract['start_date']);
    $endDateTime = new DateTime($contract['end_date']);
    $interval = $startDateTime->diff($endDateTime);
    $duration = $interval->format('%a');
    ?>
    
    <div class="contract-container" style="padding: 40px;">
        <h2 class="contract-title" style="text-align: center; font-size: 28px; font-weight: normal; margin-bottom: 30px; text-transform: uppercase;">
            CONSTRUCTION AGREEMENT
        </h2>

        <p class="contract-text" style="text-align: justify; line-height: 1.6; margin-bottom: 20px;">
            This agreement is made between <strong><?php echo $contract['client_first'] . ' ' . $contract['client_last']; ?></strong> (Client) 
            and <strong><?php echo $contract['carpenter_first'] . ' ' . $contract['carpenter_last']; ?></strong> (Contractor), 
            regarding the construction project with the following details:
        </p>

        <div class="project-details" style="margin: 20px 0;">
            <p><strong>Lot Area:</strong> <?php echo $contract['length_lot_area']; ?>m x <?php echo $contract['width_lot_area']; ?>m (<?php echo $contract['square_meter_lot']; ?> sqm)</p>
            <p><strong>Floor Area:</strong> <?php echo $contract['length_floor_area']; ?>m x <?php echo $contract['width_floor_area']; ?>m (<?php echo $contract['square_meter_floor']; ?> sqm)</p>
            <p><strong>Project Type:</strong> <?php echo $contract['type']; ?></p>
            <p><strong>Initial Budget:</strong> PHP <?php echo number_format($contract['initial_budget'], 2); ?></p>
        </div>

        <div class="project-photo" style="text-align: center; margin: 30px 0;">
            <?php if (!empty($contract['Photo'])): ?>
                <img src="<?php echo $contract['Photo']; ?>" alt="Project Photo" 
                     style="max-width: 400px; height: auto; border: 1px solid #ddd; padding: 5px;">
            <?php endif; ?>
        </div>

        <div class="dates-section" style="margin: 20px 0;">
            <p><strong>Start Date:</strong> <?php echo date("F j, Y", strtotime($contract['start_date'])); ?></p>
            <p><strong>End Date:</strong> <?php echo date("F j, Y", strtotime($contract['end_date'])); ?></p>
            <p><strong>Duration:</strong> <?php echo $duration; ?> day(s)</p>
        </div>

        <div class="labor-cost" style="margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 5px;">
            <p><strong>Labor Cost:</strong> PHP <?php echo number_format($contract['labor_cost'], 2); ?></p>
        </div>

        <p class="contract-text" style="text-align: justify; line-height: 1.6; margin: 20px 0;">
            Both parties agree to the conditions stated above. The contractor is responsible for completing the 
            project within the agreed timeframe and budget.
        </p>

        <div class="signature-section" style="margin-top: 50px; display: flex; justify-content: space-between; padding: 0 50px;">
            <div class="signature-line" style="width: 250px; text-align: center;">
                <p>_______________________</p>
                <p style="margin-top: 5px;"><?php echo $contract['client_first'] . ' ' . $contract['client_last']; ?></p>
                <p style="margin-top: 0; font-size: 14px; font-weight: bold; color: #000;">Client Signature over Printed Name</p>
            </div>
            <div class="signature-line" style="width: 250px; text-align: center;">
                <p>_______________________</p>
                <p style="margin-top: 5px;"><?php echo $contract['carpenter_first'] . ' ' . $contract['carpenter_last']; ?></p>
                <p style="margin-top: 0; font-size: 14px; font-weight: bold; color: #000;">Contractor Signature over Printed Name</p>
            </div>
        </div>

        <!-- Status Badge moved below signatures and aligned left -->
        <div style="margin-top: 30px; text-align: left; padding-left: 50px;">
            <span style="background-color: #FFA500; color: white; padding: 5px 15px; border-radius: 5px; font-weight: bold; font-size: 16px;">
                Status: Pending
            </span>
        </div>
    </div>

<?php
} else {
    echo "<p style='text-align: center; margin-top: 20px;'>Contract not found.</p>";
}

mysqli_close($conn);
?> 