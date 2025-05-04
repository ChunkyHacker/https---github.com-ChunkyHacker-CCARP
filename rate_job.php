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

        <!-- SECTION 1: DISCOMFORT (DIS) -->
        <div class="question-section">
            <h2>SECTION 1: DISCOMFORT (DIS)</h2>
            <p>Scale: 1 – Strongly Disagree, 2 – Disagree, 3 – Neutral, 4 – Agree, 5 – Strongly Agree</p>
            
            <?php
            $discomfort_questions = [
                'DIS1' => 'I feel overwhelmed when using this recruitment platform.',
                'DIS2' => 'Sometimes, I think this platform is too complicated to use.',
                'DIS3' => 'I hesitate to use the platform when I cannot understand the instructions.',
                'DIS4' => 'I worry that I might make mistakes while using the system.',
                'DIS5' => 'I often feel confused when navigating this platform.'
            ];

            foreach ($discomfort_questions as $code => $question) {
                echo "<div class='question'>";
                echo "<h3>$code: $question</h3>";
                echo "<div style='margin-top: 20px;'>";
                for ($i = 1; $i <= 5; $i++) {
                    echo "<div style='margin-bottom: 10px;'>";
                    echo "<input type='radio' name='$code' value='$i' id='{$code}_$i' required>";
                    echo "<label for='{$code}_$i'> $i</label>";
                    echo "</div>";
                }
                echo "</div></div>";
            }
            ?>
        </div>

        <!-- SECTION 2: INNOVATIVENESS (INN) -->
        <div class="question-section">
            <h2>SECTION 2: INNOVATIVENESS (INN)</h2>
            <p>Scale: 1 – Strongly Disagree, 2 – Disagree, 3 – Neutral, 4 – Agree, 5 – Strongly Agree</p>
            
            <?php
            $innovativeness_questions = [
                'INN1' => 'I consider myself among the first to try new recruitment platforms.',
                'INN2' => 'I like discovering new features on online recruitment systems.',
                'INN3' => 'I actively seek out new technologies to improve my job search.',
                'INN4' => 'I enjoy using advanced features even before others do.',
                'INN5' => 'I prefer platforms that introduce new job-seeking tools.'
            ];

            foreach ($innovativeness_questions as $code => $question) {
                echo "<div class='question'>";
                echo "<h3>$code: $question</h3>";
                echo "<div style='margin-top: 20px;'>";
                for ($i = 1; $i <= 5; $i++) {
                    echo "<div style='margin-bottom: 10px;'>";
                    echo "<input type='radio' name='$code' value='$i' id='{$code}_$i' required>";
                    echo "<label for='{$code}_$i'> $i</label>";
                    echo "</div>";
                }
                echo "</div></div>";
            }
            ?>
        </div>

        <!-- SECTION 3: INSECURITY (INS) -->
        <div class="question-section">
            <h2>SECTION 3: INSECURITY (INS)</h2>
            <p>Scale: 1 – Strongly Disagree, 2 – Disagree, 3 – Neutral, 4 – Agree, 5 – Strongly Agree</p>
            
            <?php
            $insecurity_questions = [
                'INS1' => 'I am concerned about sharing personal data through this platform.',
                'INS2' => 'I feel unsure whether my information is securely protected.',
                'INS3' => 'I doubt the trustworthiness of the job postings.',
                'INS4' => 'I fear my account or information might be hacked or leaked.',
                'INS5' => 'I feel that my privacy might be compromised when using this platform.'
            ];

            foreach ($insecurity_questions as $code => $question) {
                echo "<div class='question'>";
                echo "<h3>$code: $question</h3>";
                echo "<div style='margin-top: 20px;'>";
                for ($i = 1; $i <= 5; $i++) {
                    echo "<div style='margin-bottom: 10px;'>";
                    echo "<input type='radio' name='$code' value='$i' id='{$code}_$i' required>";
                    echo "<label for='{$code}_$i'> $i</label>";
                    echo "</div>";
                }
                echo "</div></div>";
            }
            ?>
        </div>

        <!-- SECTION 4: OPTIMISM (OPT) -->
        <div class="question-section">
            <h2>SECTION 4: OPTIMISM (OPT)</h2>
            <p>Scale: 1 – Strongly Disagree, 2 – Disagree, 3 – Neutral, 4 – Agree, 5 – Strongly Agree</p>
            
            <?php
            $optimism_questions = [
                'OPT1' => 'This platform makes job searching faster and easier for me.',
                'OPT2' => 'I feel more confident applying for jobs because of this system.',
                'OPT3' => 'I believe this web app empowers me to connect better with employers.',
                'OPT4' => 'I believe technology makes recruitment more efficient.',
                'OPT5' => 'Using this system gives me better control over my job applications.'
            ];

            foreach ($optimism_questions as $code => $question) {
                echo "<div class='question'>";
                echo "<h3>$code: $question</h3>";
                echo "<div style='margin-top: 20px;'>";
                for ($i = 1; $i <= 5; $i++) {
                    echo "<div style='margin-bottom: 10px;'>";
                    echo "<input type='radio' name='$code' value='$i' id='{$code}_$i' required>";
                    echo "<label for='{$code}_$i'> $i</label>";
                    echo "</div>";
                }
                echo "</div></div>";
            }
            ?>
        </div>

        <!-- SECTION 5: INTENTION TO USE (INT B) -->
        <div class="question-section">
            <h2>SECTION 5: INTENTION TO USE (INT B)</h2>
            <p>Scale: 1 – Strongly Disagree, 2 – Disagree, 3 – Neutral, 4 – Agree, 5 – Strongly Agree</p>
            
            <?php
            $intention_questions = [
                'INTB1' => 'I intend to continue using this recruitment platform.',
                'INTB2' => 'I would recommend this platform to other job seekers.',
                'INTB3' => 'I will likely use this platform again for future job applications.'
            ];

            foreach ($intention_questions as $code => $question) {
                echo "<div class='question'>";
                echo "<h3>$code: $question</h3>";
                echo "<div style='margin-top: 20px;'>";
                for ($i = 1; $i <= 5; $i++) {
                    echo "<div style='margin-bottom: 10px;'>";
                    echo "<input type='radio' name='$code' value='$i' id='{$code}_$i' required>";
                    echo "<label for='{$code}_$i'> $i</label>";
                    echo "</div>";
                }
                echo "</div></div>";
            }
            ?>
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