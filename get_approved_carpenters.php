<?php
include('config.php');

if (isset($_GET['plan_id'])) {
    $plan_id = $_GET['plan_id'];

    $query = "SELECT GROUP_CONCAT(CONCAT(c.First_Name, ' ', c.Last_Name) SEPARATOR ', ') AS carpenters 
              FROM plan_approval p
              JOIN carpenters c ON p.carpenter_ID = c.carpenter_ID
              WHERE p.plan_ID = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $plan_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    echo "<ul>";
    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['carpenters']) {
            echo "<li><strong>Carpenters:</strong> {$row['carpenters']}</li>";
        } else {
            echo "<li>No approved carpenters yet.</li>";
        }
    } else {
        echo "<li>No approved carpenters yet.</li>";
    }
    echo "</ul>";

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
