<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["csv_file"])) {
    $file = $_FILES["csv_file"]["tmp_name"];

    if (($handle = fopen($file, "r")) !== FALSE) {
        fgetcsv($handle); // Skip CSV header row

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $structure = mysqli_real_escape_string($conn, $data[0]);
            $name = mysqli_real_escape_string($conn, $data[1]);
            $price = intval($data[2]);

            $sql = "INSERT INTO prematerialsinventory (structure, name, price) VALUES ('$structure', '$name', '$price')";
            mysqli_query($conn, $sql);
        }

        fclose($handle);
        echo "<script>alert('CSV file imported successfully!'); window.location.href='admin_view_inventory.php';</script>";
    } else {
        echo "<script>alert('Error opening file.'); window.location.href='admin_view_inventory.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='admin_view_inventory.php';</script>";
}
?>
