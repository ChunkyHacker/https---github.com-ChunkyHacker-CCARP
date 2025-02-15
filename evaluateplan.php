<?php

session_start();
        include('config.php');

        $Carpeneter_ID = $_SESSION['Carpenter_ID'];

        $carpenterquery = "SELECT *FROM carpenters WHERE Carpenter_ID = $Carpeneter_ID";
        $carpenterresult = mysqli_query($conn, $carpenterquery);
        $carpenterData = mysqli_fetch_assoc($carpenterresult);


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client's Plan</title>
    <style>
        * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        }

        body {
        font-family: Verdana, sans-serif;
        margin: 0;
        background-color: #FF8C00;
        font-size: 20px; /* Set base font size to 20px */
        }

        .header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        padding: 10px;
        text-align: left;
        background: #FF8C00;
        color: #000000;
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-decoration: none;
        z-index: 100;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header h1 {
        font-size: 40px; /* Keep h1 larger */
        border-left: 20px solid transparent;
        text-decoration: none;
        }

        .right {
        margin-right: 20px;
        }

        .header a {
        font-size: 25px;
        font-weight: bold;
        text-decoration: none;
        color: #000000;
        margin-right: 15px;
        }

        /* Modal content styling */
        .modal-content {
        background-color: #fefefe;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-height: 60%;
        overflow-y: auto;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        margin-top: 10%; /* Adjust as needed to give space from the nav bar */
        margin-left: auto;
        margin-right: auto;
        }

        h2 {
        font-size: 24px; /* Adjusted to be slightly larger than the base font */
        margin-bottom: 20px;
        color: #FF8C00;
        }

        form {
        display: flex;
        flex-direction: column;
        }

        label {
        font-size: 20px; /* Set label font size to 20px */
        margin-bottom: 5px;
        color: #000000;
        }

        input,
        select,
        textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 20px; /* Set input font size to 20px */
        }

        .post-btn,
        .cancel-btn {
        margin-bottom: 10px;
        }

        .cancel-btn {
        background-color: red;
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin-right: 10px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        width: 100%;
        font-size: 20px; /* Set cancel button font size to 20px */
        }

        .cancel-btn:hover {
        background-color: #000000;
        }

        button {
        background-color: #FF8C00;
        color: #fff;
        border: none;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 20px; /* Set button font size to 20px */
        }

        button:hover {
        background-color: #000000;
        }

        /* New styling for the radio button groups */
        .radio-group {
        display: inline-flex;
        align-items: center;
        gap: 10px; /* Adjust gap as needed */
        margin-top: 5px;
        }

        .radio-group label {
        display: inline-flex;
        align-items: center;
        margin: 0; /* Remove extra margin */
        font-size: 20px;
        color: #000000;
        }

        .radio-group input[type="radio"] {
        margin: 0 5px 0 0; /* Small margin to the right of the radio circle */
        vertical-align: middle;
        }
  </style>

</head>
<body>
    <div class="header">
        <a href="comment.php">
            <h1>
                <img src="assets/img/logos/logo.png" alt=""  style="width: 75px; margin-right: 10px;">
            </h1>
        </a>
        <div class="right">
            <a href="logout.php" style="text-decoration: none; color: black; margin-right: 20px;">Log Out</a>
            <a class="active" href="comment.php">Home</a>
            <a href="about/index.html">About</a>
            <a href="#contact">Get Ideas</a>
            <a href="plan.php">Project</a>
        </div>
    </div>
<div class="container">
    <div class="modal-content">
        <?php
        

        if (isset($_GET['plan_id'])) {
            $plan_id = $_GET['plan_id'];

            $query = "SELECT * FROM plan WHERE plan_ID = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "i", $plan_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($row = mysqli_fetch_assoc($result)) {
                $userId = $row['User_ID'];
                $userQuery = "SELECT First_Name, Last_Name FROM users WHERE User_ID = ?";
                $userStmt = mysqli_prepare($conn, $userQuery);
                mysqli_stmt_bind_param($userStmt, "i", $userId);
                mysqli_stmt_execute($userStmt);
                $userResult = mysqli_stmt_get_result($userStmt);

                $userName = "";
                if ($userRow = mysqli_fetch_assoc($userResult)) {
                    $userName = "{$userRow['First_Name']} {$userRow['Last_Name']}";
                }

                $photoPath = $row['Photo'];
                ?>
                <div class='main'>
                    <h1>Client's Plan Details</h1>
                    <label for='name'>Client Name: </label>
                    <input type='text' id='name' name='User_ID' value='<?php echo $userName; ?>' readonly><br>

                    <div class="row-container" style='display: grid; grid-template-columns: repeat(3, 1fr); grid-gap: 10px; text-align: left;'>
                        <h3>Lot Area</h3>
                        <div style='grid-column: 1; display: flex; flex-direction: column;'>
                            <label for='length_lot_area'>Length for lot area</label>
                            <input type='text' id='length_lot_area' name='length_lot_area' value='<?php echo $row['length_lot_area']; ?>' readonly>
                        </div>
                        <div style='grid-column: 2; display: flex; flex-direction: column;'>
                            <label for='width_lot_area'>Width for lot area</label>
                            <input type='text' id='width_lot_area' name='width_lot_area' value='<?php echo $row['width_lot_area']; ?>' readonly>
                        </div>
                        <div style='grid-column: 3; display: flex; flex-direction: column;'>
                            <label for='square_meter_lot'>Square Meter(Sq):</label>
                            <input type='text' id='square_meter_lot' name='square_meter_lot' value='<?php echo $row['square_meter_lot']; ?>' readonly>
                        </div>
                    </div>

                    <div class="row-container" style='display: grid; grid-template-columns: repeat(3, 1fr); grid-gap: 10px; text-align: left;'>
                        <h3>Floor Area</h3>
                        <div style='grid-column: 1; display: flex; flex-direction: column;'>
                            <label for='length_floor_area'>Length for floor area</label>
                            <input type='text' id='length_floor_area' name='length_floor_area' value='<?php echo $row['length_floor_area']; ?>' readonly>
                        </div>
                        <div style='grid-column: 2; display: flex; flex-direction: column;'>
                            <label for='width_floor_area'>Width for floor area</label>
                            <input type='text' id='width_floor_area' name='width_floor_area' value='<?php echo $row['width_floor_area']; ?>' readonly>
                        </div>
                        <div style='grid-column: 3; display: flex; flex-direction: column;'>
                            <label for='square_meter_floor'>Square Meter(Sq):</label>
                            <input type='text' id='square_meter_floor' name='square_meter_floor' value='<?php echo $row['square_meter_floor']; ?>' readonly>
                        </div>
                    </div>

                    <div class="row-container" style='display: grid; grid-template-columns: repeat(2, 1fr); grid-gap: 10px; text-align: left;'>
                        <div style='display: flex; flex-direction: column;'>
                            <h3>Initial Budget</h3>
                            <label for='initial_budget'>Initial Budget</label>
                            <input type='text' id='initial_budget' name='initial_budget' value='<?php echo $row['initial_budget']; ?>' readonly>
                        </div>
                        <div style='display: flex; flex-direction: column;'>
                        </div>
                    </div>

                    <div class="row-container">
                        <h3>Project Dates</h3>
                        <div style='display: grid; grid-template-columns: repeat(2, 1fr); grid-gap: 10px;'>
                            <div style='display: flex; flex-direction: column;'>
                                <label>Start Date:</label>
                                <input type='text' value='<?php echo $row['start_date']; ?>' readonly>
                            </div>
                            <div style='display: flex; flex-direction: column;'>
                                <label>End Date:</label>
                                <input type='text' value='<?php echo $row['end_date']; ?>' readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row-container">
                        <h3>More Details:</h3>
                        <div style='display: grid; grid-template-columns: repeat(2, 1fr); grid-gap: 10px;'>
                            <div style='display: flex; flex-direction: column;'>
                                <label>More Details:</label>
                                <input type='text' value='<?php echo $row['more_details']; ?>' readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row-container">
                        <h3>Further Details</h3>

                        <table style="border-collapse: collapse; width: 100%;">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Part</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Materials</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Name</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Quantity</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Price</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Total</th>
                                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Estimated Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query_materials = "SELECT * FROM prematerials";
                                $stmt_materials = mysqli_prepare($conn, $query_materials);
                                mysqli_stmt_execute($stmt_materials);
                                $result_materials = mysqli_stmt_get_result($stmt_materials);

                                while ($material_row = mysqli_fetch_assoc($result_materials)) {
                                    ?>
                                    <tr>
                                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo htmlspecialchars($material_row['part']); ?></td>
                                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo htmlspecialchars($material_row['materials']); ?></td>
                                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo htmlspecialchars($material_row['name']); ?></td>
                                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo htmlspecialchars($material_row['quantity']); ?></td>
                                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo htmlspecialchars($material_row['price']); ?></td>
                                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo htmlspecialchars($material_row['total']); ?></td>
                                        <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;"><?php echo htmlspecialchars($material_row['estimated_cost']); ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if (!empty($photoPath) && file_exists($photoPath)): ?>
                        <p>Photo:</p>
                        <div style='text-align: center;'>
                            <a href='#' onclick='openModal("<?php echo $photoPath; ?>")'>
                                <img src='<?php echo $photoPath; ?>' alt='Plan Photo' style='width: 700px; height: 400px;'>
                            </a>
                        </div>
                    <?php endif; ?>

                    <form id='planForm' method='post' action='approveplan.php'>
                    <!-- Example Question 1 -->
                    <div class="form-section">
                        <label for="q1">Were the details of the project suitable for your scope of work?</label>
                        <br>
                        <div class="radio-group">
                            <label for="q1_yes">
                            <input type="radio" id="q1_yes" name="q1" value="Yes" required />Yes
                            </label>
                            <label for="q1_no">
                            <input type="radio" id="q1_no" name="q1" value="No" required />No
                            </label>
                        </div>
                    </div>

                    <!-- Example Question 2 -->
                    <div class="form-section">
                        <label for="q2">Are you able to finish the project even if there is an overlapping budget?</label>
                        <br>
                        <div class="radio-group">
                            <label for="q2_yes">
                            <input type="radio" id="q2_yes" name="q2" value="Yes" required />Yes
                            </label>
                            <label for="q2_no">
                            <input type="radio" id="q2_no" name="q2" value="No" required />No
                            </label>
                        </div>
                    </div>

                    <!-- Example Question 3 -->
                    <div class="form-section">
                    <label for="q3">Are you willing to accept an additional task with additional payment?</label>
                    <br>
                    <div class="radio-group">
                        <label for="q3_yes">
                        <input type="radio" id="q3_yes" name="q3" value="Yes" required />Yes
                        </label>
                        <label for="q3_no">
                        <input type="radio" id="q3_no" name="q3" value="No" required />No
                        </label>
                    </div>
                    <br>
                    </div>

                    <!-- Example Question 4 -->
                    <div class="form-section">
                    <label for="q4">Can you finish the project on time?</label>
                    <br>
                    <div class="radio-group">
                        <label for="q4_yes">
                        <input type="radio" id="q4_yes" name="q4" value="Yes" required />Yes
                        </label>
                        <label for="q4_no">
                        <input type="radio" id="q4_no" name="q4" value="No" required />No
                        </label>
                    </div>
                    </div>

                    <!-- Example Question 5 -->
                    <div class="form-section">
                    <label for="q5">Will you accept the project?</label>
                    <br>
                    <div class="radio-group">
                        <label for="q5_yes">
                        <input type="radio" id="q5_yes" name="q5" value="Yes" required />Yes
                        </label>
                        <label for="q5_no">
                        <input type="radio" id="q5_no" name="q5" value="No" required />No
                        </label>
                    </div>
                    </div>
                        <label for='comment'>If Yes/No, why?</label><br>
                        <textarea name='comment' rows='4' cols='50'></textarea><br>

                        <label for='name'>Approved By: </label>
                        <input type='text' id='name' name='approved_by' value='<?php echo $carpenterData['First_Name'];?> <?php echo $carpenterData['Last_Name'];?>' readonly><br>

                        <label for='status'>Status:</label>
                        <select id="status" name="status">
                            <option value="" selected disabled>Select an option</option>
                            <option value="approve">Approve</option>
                            <option value="decline">Decline</option>
                        </select><br>

                        <input type='hidden' name='plan_id' value='<?php echo $plan_id; ?>'>
                        <button type='submit'>Submit</button>
                    </form>

                    <button onclick="history.back()">Go back</button>
                </div>
                <?php
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
            } else {
                ?>
                <div class='main'>
                    <p>No client's plan found with Plan ID: <?php echo $plan_id; ?></p>
                </div>
                <?php
            }
        } else {
            ?>
            <div class='main'>
                <p>Plan ID is missing.</p>
            </div>
            <?php
        }
        ?>
    </div>
</div>

</body>
<script>
    function openModal(photoPath) {
        var modal = document.createElement('div');
        modal.style.display = 'block';
        modal.style.position = 'fixed';
        modal.style.zIndex = '1';
        modal.style.paddingTop = '100px';
        modal.style.left = '0';
        modal.style.top = '0';
        modal.style.width = '100%';
        modal.style.height = '100%';
        modal.style.overflow = 'auto';
        modal.style.backgroundColor = 'rgb(0,0,0)';
        modal.style.backgroundColor = 'rgba(0,0,0,0.9)';
        
        var img = document.createElement('img');
        img.src = photoPath;
        img.style.margin = 'auto';
        img.style.display = 'block';
        img.style.width = '80%';
        img.style.maxWidth = '700px';
        
        modal.appendChild(img);
        
        modal.onclick = function() {
            modal.style.display = 'none';
        }
        
        document.body.appendChild(modal);
    }
</script>
<script>
function submitForm(action) {
    document.getElementById('planForm').action = action;
    document.getElementById('planForm').submit();
}
</script>
</html>
