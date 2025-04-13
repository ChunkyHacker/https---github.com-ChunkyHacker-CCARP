<?php
session_start();
include('config.php');

if (!isset($_SESSION['Carpenter_ID'])) {
    header('Location: login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Carpenter_ID = $_POST['Carpenter_ID'];
    $plan_ID = $_POST['plan_ID'];
    
    // Validate required fields
    $required_fields = ['q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9', 'q10'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field])) {
            die("Error: Missing required field: $field");
        }
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO job_ratings (Carpenter_ID, plan_ID, q1_rating, q2_rating, q3_rating, 
            q4_rating, q5_rating, q6_rating, q7_rating, q8_answer, q8_explanation, 
            q9_answer, q10_answer) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Check if carpenter has already submitted a rating for this plan
    $check_sql = "SELECT * FROM job_ratings WHERE Carpenter_ID = ? AND plan_ID = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $Carpenter_ID, $plan_ID);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo "<!DOCTYPE html>
              <html>
              <head>
                  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              </head>
              <body>
                  <script>
                      Swal.fire({
                          icon: 'warning',
                          title: 'Already Submitted',
                          text: 'You have already submitted a survey for this plan.',
                          confirmButtonText: 'OK'
                      }).then(function() {
                          window.location.href = 'viewapprovedplan.php?plan_ID=" . $plan_ID . "';
                      });
                  </script>
              </body>
              </html>";
        exit();
    }
    
    // If no previous submission, continue with insert
    $stmt = $conn->prepare($sql);
    
    // Get q8_explanation (might be empty if "No" was selected)
    $q8_explanation = isset($_POST['q8_explain']) ? $_POST['q8_explain'] : '';

    $stmt->bind_param("iiiiiiiiissss", 
        $Carpenter_ID,
        $plan_ID,
        $_POST['q1'],
        $_POST['q2'],
        $_POST['q3'],
        $_POST['q4'],
        $_POST['q5'],
        $_POST['q6'],
        $_POST['q7'],
        $_POST['q8'],
        $q8_explanation,
        $_POST['q9'],
        $_POST['q10']
    );

    if ($stmt->execute()) {
        echo "<!DOCTYPE html>
              <html>
              <head>
                  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
              </head>
              <body>
                  <script>
                      document.addEventListener('DOMContentLoaded', function() {
                          Swal.fire({
                              icon: 'success',
                              title: 'Thank you!',
                              text: 'Your feedback has been submitted successfully.',
                              timer: 2000
                          }).then(function() {
                              window.location.href = 'viewapprovedplan.php?plan_ID=" . $_POST['plan_ID'] . "';
                          });
                      });
                  </script>
              </body>
              </html>";
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: viewapprovedplan.php?plan_ID=' . $_GET['plan_ID']);
    exit();
}
?>