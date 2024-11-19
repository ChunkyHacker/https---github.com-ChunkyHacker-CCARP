<?php
session_start();
include('config.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the selected materials array is not empty
    if (!empty($_POST["rawmaterials"])) {
        // Prepare SQL statement to insert selected items into the database
        $sql = "INSERT INTO requiredmaterials (material, type, image, quantity, price, total, materials_overall_cost) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Fetch the overall cost from the POST data (assuming it's part of the form)
        $materialsOverallCost = isset($_POST["materials_overall_cost"]) ? $_POST["materials_overall_cost"] : 0;

        // Loop through each selected material and insert its details into the database
        foreach ($_POST["rawmaterials"] as $key => $material) {
            // Fetching associated data for each selected material
            $type = isset($_POST["type"][$key]) ? $_POST["type"][$key] : "";
            $quantity = isset($_POST["itemQuantity"][$key]) ? $_POST["itemQuantity"][$key] : 0;
            $price = isset($_POST["price"][$key]) ? $_POST["price"][$key] : 0;
            $total = isset($_POST["total"][$key]) ? $_POST["total"][$key] : 0;

            // Ensure valid total, fallback to calculated value if not provided
            if (empty($total)) {
                $total = $price * $quantity; // Calculate total if missing
            }

            // Retrieve the image (BLOB) from the 'items' table
            $imageQuery = "SELECT itemimage FROM items WHERE itemname = ?";
            $imageStmt = $conn->prepare($imageQuery);
            $imageStmt->bind_param("s", $material); // Use the material name to find the associated image
            $imageStmt->execute();
            $imageResult = $imageStmt->get_result();
            $imageRow = $imageResult->fetch_assoc();

            // Check if the image exists for the material
            if ($imageRow && isset($imageRow['itemimage'])) {
                // Convert BLOB image data to base64 for embedding in HTML
                $imageData = base64_encode($imageRow['itemimage']);
                $imagePath = "data:image/jpeg;base64," . $imageData;  // Assuming it's a JPEG image
            } else {
                // Fallback image if no image is found in the database
                $imagePath = 'assets/listedrequirements/default.jpg';
            }

            // Bind parameters to the SQL query
            $stmt->bind_param("ssssidd", $material, $type, $imagePath, $quantity, $price, $total, $materialsOverallCost);
            $stmt->execute();
        }

        // Close statement after execution
        $stmt->close();

        // Redirect to profile.php with success message
        header("Location: profile.php?success=true&message=" . urlencode("Materials have been successfully added."));
        exit(); // Terminate script after redirection
    } else {
        // If no materials are selected, display a message or perform appropriate action
        echo "No materials selected.";
    }
}

// Close database connection
$conn->close();
?>
