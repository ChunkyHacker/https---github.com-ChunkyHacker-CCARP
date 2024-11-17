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
            $image = isset($_POST["image"][$key]) ? $_POST["image"][$key] : "";
            $quantity = isset($_POST["itemQuantity"][$key]) ? $_POST["itemQuantity"][$key] : 0;
            $price = isset($_POST["price"][$key]) ? $_POST["price"][$key] : 0;
            $total = isset($_POST["total"][$key]) ? $_POST["total"][$key] : 0;

            // Ensure valid total, fallback to calculated value if not provided
            if (empty($total)) {
                $total = $price * $quantity; // Calculate total if missing
            }

            // Corrected: Updated type definition to match the number of parameters (7 variables)
            $stmt->bind_param("ssssidd", $material, $type, $image, $quantity, $price, $total, $materialsOverallCost);
            $stmt->execute();
        }

        // Close statement after execution
        $stmt->close();

        // Redirect to profile after successful insertion
        header("Location: profile.php");
        exit(); // Terminate script after redirection
    } else {
        // If no materials are selected, display a message or perform appropriate action
        echo "No materials selected.";
    }
}

// Close database connection
$conn->close();
?>
