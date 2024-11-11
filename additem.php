<?php
include('config.php');
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $itemname = $_POST["itemname"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"]; // New line to capture the price
    $type = $_POST["type"];
    $itemimage = $row["itemimage"];

    // Process the image if uploaded
    $uploadedImagePath = '';
    if ($_FILES['image'] && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = 'assets\items';
        $uploadedFileName = uniqid() . '_' . $_FILES['image']['name'];
        $uploadedFilePath = $targetDir . '\\' . $uploadedFileName;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadedFilePath);
        $uploadedImagePath = $uploadedFileName;
    }
    // Prepare the SQL query to insert the item into the database
    $sql = "INSERT INTO items (itemname, quantity, price, type, itemimage) 
    VALUES ('$itemname', '$quantity', '$price', '$type', '$uploadedImagePath')";

    if ($conn->query($sql) === TRUE) {
        // Item added successfully, redirect back to products.php
        $conn->close();
        header("Location: inventory.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    echo "Error: There was an error uploading the file.";
}
?>
