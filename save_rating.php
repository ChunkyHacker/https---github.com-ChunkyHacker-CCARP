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
    $required_fields = [
        'DIS1', 'DIS2', 'DIS3', 'DIS4', 'DIS5',
        'INN1', 'INN2', 'INN3', 'INN4', 'INN5',
        'INS1', 'INS2', 'INS3', 'INS4', 'INS5',
        'OPT1', 'OPT2', 'OPT3', 'OPT4', 'OPT5',
        'INTB1', 'INTB2', 'INTB3'
    ];
    
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field])) {
            die("Error: Missing required field: $field");
        }
    }

    // Check if carpenter has already submitted a rating for this plan
    $check_sql = "SELECT * FROM job_ratings WHERE Carpenter_ID = ? AND plan_ID = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $Carpenter_ID, $plan_ID);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows == 0) {
        // Prepare the SQL statement
        $sql = "INSERT INTO job_ratings (Carpenter_ID, plan_ID, 
                DIS1, DIS2, DIS3, DIS4, DIS5,
                INN1, INN2, INN3, INN4, INN5,
                INS1, INS2, INS3, INS4, INS5,
                OPT1, OPT2, OPT3, OPT4, OPT5,
                INTB1, INTB2, INTB3) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
    
        $stmt->bind_param("iiiiiiiiiiiiiiiiiiiiiiiii", 
            $Carpenter_ID,
            $plan_ID,
            $_POST['DIS1'],
            $_POST['DIS2'],
            $_POST['DIS3'],
            $_POST['DIS4'],
            $_POST['DIS5'],
            $_POST['INN1'],
            $_POST['INN2'],
            $_POST['INN3'],
            $_POST['INN4'],
            $_POST['INN5'],
            $_POST['INS1'],
            $_POST['INS2'],
            $_POST['INS3'],
            $_POST['INS4'],
            $_POST['INS5'],
            $_POST['OPT1'],
            $_POST['OPT2'],
            $_POST['OPT3'],
            $_POST['OPT4'],
            $_POST['OPT5'],
            $_POST['INTB1'],
            $_POST['INTB2'],
            $_POST['INTB3']
        );
    
        if ($stmt->execute()) {
            echo "<!DOCTYPE html>
                  <html>
                  <head>
                      <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                  </head>
                  <body>
                      <script>
                          Swal.fire({
                              icon: 'success',
                              title: 'Thank you!',
                              text: 'Your feedback has been submitted successfully.',
                              timer: 2000
                          }).then(function() {
                              window.location.href = 'viewapprovedplan.php?plan_ID=" . $plan_ID . "';
                          });
                      </script>
                  </body>
                  </html>";
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
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
    $stmt->close();
    $conn->close();
} else {
    header('Location: viewapprovedplan.php?plan_ID=' . $_GET['plan_ID']);
    exit();
}
?>