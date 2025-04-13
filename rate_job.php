<?php
session_start();
include('config.php');

if (!isset($_SESSION['Carpenter_ID'])) {
    header('Location: login.html');
    exit();
}

$plan_ID = isset($_GET['plan_ID']) ? $_GET['plan_ID'] : null;
if (!$plan_ID) {
    die("Plan ID is required.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rate Job Opportunity</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }
        .question {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .options {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-top: 10px;
        }
        .option-label {
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        textarea {
            width: 100%;
            min-height: 100px;
            margin-top: 10px;
            padding: 10px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Job Opportunity Survey</h1>
    <form action="save_rating.php" method="POST">
        <input type="hidden" name="plan_ID" value="<?php echo $plan_ID; ?>">

        <!-- Question 1 -->
        <div class="question">
            <h3>1. How easy was it to navigate through job postings on the platform?</h3>
            <div style="margin-top: 20px;">
                <?php
                $q1_options = [
                    1 => 'Very difficult',
                    2 => 'Difficult',
                    3 => 'Neutral',
                    4 => 'Easy',
                    5 => 'Very Easy'
                ];
                foreach ($q1_options as $value => $label) {
                    echo "<div style='margin-bottom: 10px;'>";
                    echo "<input type='radio' name='q1' value='$value' id='q1_$value' required>";
                    echo "<label for='q1_$value'> $label</label>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <!-- For questions 2-7, use the same pattern -->
        <!-- Question 2 -->
        <div class="question">
            <h3>2. How relevant were the job postings to your skills and preferences?</h3>
            <div style="margin-top: 20px;">
                <?php
                $q2_options = [
                    1 => 'Not relevant at all',
                    2 => 'Slightly Relevant',
                    3 => 'Neutral',
                    4 => 'Relevant',
                    5 => 'Extremely Relevant'
                ];
                foreach ($q2_options as $value => $label) {
                    echo "<div style='margin-bottom: 10px;'>";
                    echo "<input type='radio' name='q2' value='$value' id='q2_$value' required>";
                    echo "<label for='q2_$value'> $label</label>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <!-- Question 3 -->
        <div class="question">
            <h3>3. How satisfied are you with the number of job opportunities available on the platform?</h3>
            <div style="margin-top: 20px;">
                <?php
                $q3_options = [
                    1 => 'Very Dissatisfied',
                    2 => 'Dissatisfied',
                    3 => 'Neutral',
                    4 => 'Satisfied',
                    5 => 'Extremely Satisfied'
                ];
                foreach ($q3_options as $value => $label) {
                    echo "<div style='margin-bottom: 10px;'>";
                    echo "<input type='radio' name='q3' value='$value' id='q3_$value' required>";
                    echo "<label for='q3_$value'> $label</label>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <!-- Question 4 -->
        <div class="question">
            <h3>4. Did you find it easy to communicate with clients through the platform?</h3>
            <div style="margin-top: 20px;">
                <?php
                $q4_options = [
                    1 => 'Not easy at all',
                    2 => 'Somewhat Difficult',
                    3 => 'Neutral',
                    4 => 'Somewhat Easy',
                    5 => 'Very Easy'
                ];
                foreach ($q4_options as $value => $label) {
                    echo "<div style='margin-bottom: 10px;'>";
                    echo "<input type='radio' name='q4' value='$value' id='q4_$value' required>";
                    echo "<label for='q4_$value'> $label</label>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <!-- Question 5 -->
        <div class="question">
            <h3>5. How frequently do you engage with the job postings (e.g., like, comment, apply)?</h3>
            <div style="margin-top: 20px;">
                <?php
                $q5_options = [
                    1 => 'Never',
                    2 => 'Rarely',
                    3 => 'Occasionally',
                    4 => 'Frequently',
                    5 => 'Very Frequently'
                ];
                foreach ($q5_options as $value => $label) {
                    echo "<div style='margin-bottom: 10px;'>";
                    echo "<input type='radio' name='q5' value='$value' id='q5_$value' required>";
                    echo "<label for='q5_$value'> $label</label>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <!-- Question 6 -->
        <div class="question">
            <h3>6. How likely are you to recommend this platform to other carpenters?</h3>
            <div style="margin-top: 20px;">
                <?php
                $q6_options = [
                    1 => 'Not likely at all',
                    2 => 'Unlikely',
                    3 => 'Neutral',
                    4 => 'Good',
                    5 => 'Excellent'
                ];
                foreach ($q6_options as $value => $label) {
                    echo "<div style='margin-bottom: 10px;'>";
                    echo "<input type='radio' name='q6' value='$value' id='q6_$value' required>";
                    echo "<label for='q6_$value'> $label</label>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <!-- Question 7 -->
        <div class="question">
            <h3>7. How would you rate the overall job accessibility on the platform?</h3>
            <div style="margin-top: 20px;">
                <?php
                $q7_options = [
                    1 => 'Very Poor',
                    2 => 'Poor',
                    3 => 'Neutral',
                    4 => 'Good',
                    5 => 'Excellent'
                ];
                foreach ($q7_options as $value => $label) {
                    echo "<div style='margin-bottom: 10px;'>";
                    echo "<input type='radio' name='q7' value='$value' id='q7_$value' required>";
                    echo "<label for='q7_$value'> $label</label>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <!-- Question 8 -->
        <div class="question">
            <h3>8. Have you ever encountered any issues while applying for jobs on the platform?</h3>
            <div style="margin-top: 20px;">
                <input type="radio" name="q8" value="yes" id="q8_yes" required onclick="toggleExplanation('show')">
                <label for="q8_yes">Yes</label><br>
                <input type="radio" name="q8" value="no" id="q8_no" required onclick="toggleExplanation('hide')">
                <label for="q8_no">No</label>
                
                <div id="issuesExplanation" style="margin-top: 10px; display: none;">
                    <label for="q8_explain">If Yes, Please explain:</label><br>
                    <textarea name="q8_explain" id="q8_explain"></textarea>
                </div>
            </div>
        </div>

        <!-- Question 9 -->
        <div class="question">
            <h3>9. What additional features would make job accessibility easier for you?</h3>
            <textarea name="q9" required></textarea>
        </div>

        <!-- Question 10 -->
        <div class="question">
            <h3>10. Please provide any additional feedback or suggestions for improving the platform's job accessibility.</h3>
            <textarea name="q10" required></textarea>
        </div>

        <!-- Hidden Fields -->
        <input type="hidden" name="Carpenter_ID" value="<?php echo $_SESSION['Carpenter_ID']; ?>">
        <input type="hidden" name="plan_ID" value="<?php echo $plan_ID; ?>">

        <!-- Buttons -->
        <div style="display: flex; justify-content: flex-start; gap: 20px; margin-top: 30px; margin-left: 20px;">
            <button type="button" onclick="history.back()" 
                style="width: 150px; height: 50px; background-color: #FF8C00; color: white; 
                border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                Go back</button>
            <button type="submit" 
                style="width: 150px; height: 50px; background-color: #4CAF50; color: white; 
                border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
                Submit Survey</button>
        </div>
    </form>

    <script>
        function toggleExplanation(action) {
            const explanation = document.getElementById('issuesExplanation');
            explanation.style.display = (action === 'show') ? 'block' : 'none';
        }
    </script>
</body>
</html>