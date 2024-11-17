<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Materials</title>
        <style>
                /* General styles */
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
            }

            form {
                max-width: 1200px;
                margin: 0 auto;
            }

            /* Search and filter section */
            #filteredMaterials {
                display: flex;
                justify-content: space-between;
                margin-bottom: 20px;
            }

            #filteredMaterials input,
            #filteredMaterials button {
                padding: 10px;
                font-size: 16px;
            }

            #filterOptions {
                margin-bottom: 20px;
            }

            #filterOptions label {
                font-size: 16px;
                margin-right: 10px;
            }

            #filterOptions select {
                padding: 10px;
                font-size: 16px;
            }

            /* Table styles */
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th, td {
                padding: 12px;
                border: 1px solid #ddd;
                text-align: center;
            }

            th {
                background-color: #FF8C00;
            }

            td input[type="number"] {
                padding: 8px;
                width: 100px;
                text-align: right;
            }

            td input[type="checkbox"] {
                margin: 5px;
            }

            /* Image styling */
            img {
                width: 100px;
                height: auto;
                border-radius: 5px;
            }

            /* Overall cost section */
            div {
                margin-top: 20px;
            }

            #materials_overall_cost {
                width: 100%;
                padding: 12px;
                font-size: 18px;
                text-align: right;
                background-color: #f9f9f9;
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            button {
                background-color: #FF8C00;
                color: black;
                padding: 12px 24px;
                border: none;
                font-size: 16px;
                cursor: pointer;
                margin-top: 20px;
            }

            button:hover {
                background-color: #45a049;
            }
        </style>
    </head>
    <body>
        <?php
            include('config.php');
            echo "<form method='post' action='selectingmaterialsrequirement.php'>";
            $searchQuery = isset($_POST['search']) ? $_POST['search'] : '';
            $selectedType = isset($_POST['filterType']) ? $_POST['filterType'] : '';

            echo '<div id="filteredMaterials">';
            echo '<input type="text" id="search" name="search" oninput="searchItems()" placeholder="Search materials">';
            echo '<button type="button" onclick="submitSearch()">Search</button>';
            echo '</div>';

            echo '<div id="filterOptions">';
            echo '<label for="filterType">Filter by Type:</label>';
            echo '<select id="filterType" name="filterType" onchange="filterByType()">';
            echo '<option value="">All</option>';

            $sqlTypes = "SELECT DISTINCT type FROM items";
            $resultTypes = $conn->query($sqlTypes);
            while ($rowType = $resultTypes->fetch_assoc()) {
                $typeName = $rowType["type"];
                $selected = ($typeName == $selectedType) ? 'selected' : '';
                echo "<option value='$typeName' $selected>$typeName</option>";
            }

            echo '</select>';
            echo '</div>';

            $sql = "SELECT * FROM items WHERE itemname LIKE '%$searchQuery%'";

            if (!empty($selectedType)) {
                $sql .= " AND type = '$selectedType'";
            }

            $result = $conn->query($sql);

            echo '<table id="materials" style="border-collapse: collapse; width: 100%; border: 1px solid black; text-align: center;">
                    <thead>
                        <tr>
                            <th style="border: 1px solid black;">Select</th>
                            <th style="border: 1px solid black;">Materials</th>
                            <th style="border: 1px solid black;">Type</th>
                            <th style="border: 1px solid black;">Photo</th>
                            <th style="border: 1px solid black;">Quantity</th>
                            <th style="border: 1px solid black;">Quantity Available</th>
                            <th style="border: 1px solid black;">Price (₱)</th>
                            <th style="border: 1px solid black;">Total (₱)</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($row = $result->fetch_assoc()) {
                $itemname = $row["itemname"];
                $quantity = $row["quantity"];
                $price = $row["price"];
                $type = $row["type"];
                $itemimage = $row["itemimage"];

                echo '<tr>
                        <td style="border: 1px solid black;">
                            <input type="checkbox" class="item-checkbox" name="rawmaterials[]" data-price="' . $price . '" value="' . $itemname . '" onchange="updateMaterialsOverallCost()">
                            <input type="hidden" name="material[]" value="' . $itemname . '">
                            <input type="hidden" name="type[]" value="' . $type . '">
                            <input type="hidden" name="image[]" value="' . $itemimage . '">
                            <input type="hidden" name="quantity[]" value="' . $quantity . '">
                            <input type="hidden" name="price[]" value="' . $price . '">
                            <input type="hidden" name="total[]" class="totalInput" value="0.00">
                        </td>
                        <td style="border: 1px solid black;"><span name="name[]">' . $itemname . '</span></td>
                        <td style="border: 1px solid black;"><span name="type[]">' . $type . '</span></td>
                        <td style="border: 1px solid black;">
                            <span name="itemimage[]">
                                <img class="image[]" src="assets/items/' . $itemimage . '" alt="' . $itemname . '" style="width: 100px; height: auto;">
                            </span>
                        </td>
                        <td style="border: 1px solid black;">
                            <input type="number" class="quantity-input" name="itemQuantity[]" oninput="calculateTotal(this);" min="0">
                        </td>
                        <td style="border: 1px solid black;"><span name="quantity[]">' . $quantity . '</span></td>
                        <td style="border: 1px solid black;"><span name="price[]" class="price">' . $price . '</span></td>
                        <td style="border: 1px solid black;"><span name="total[]" class="total">0.00</span></td>
                    </tr>';
            }

            echo '</tbody></table>';

            // Materials Overall Cost field (readonly)
            echo '<div style="margin-top: 20px;">
                    <label for="materials_overall_cost"><b>Materials Overall Cost</b></label><br>
                    <input type="text" id="materials_overall_cost" name="materials_overall_cost" value="0.00" readonly style="width: 100%; padding: 10px; text-align: right;">
                </div>';

            echo "<button type='submit'>Submit</button>";
            echo "</form>";

            $conn->close();
        ?>

        <script>
            // Function to calculate the total for each item based on the quantity and update the overall materials cost
            function calculateTotal(quantityInput) {
                const row = quantityInput.closest('tr');
                const price = parseFloat(row.querySelector('.item-checkbox').dataset.price);
                const quantity = parseInt(quantityInput.value, 10) || 0;
                const total = price * quantity;

                // Update the total for this row
                row.querySelector('.total').innerText = total.toFixed(2);
                row.querySelector('.totalInput').value = total.toFixed(2);

                // Update the overall materials cost
                updateMaterialsOverallCost();
            }

            // Function to update the materials overall cost when an item is selected or deselected
            function updateMaterialsOverallCost() {
                let materialsOverallCost = 0;

                // Loop through each checkbox to check if it is selected
                document.querySelectorAll('.item-checkbox').forEach((checkbox) => {
                    if (checkbox.checked) {
                        const row = checkbox.closest('tr');
                        const total = parseFloat(row.querySelector('.totalInput').value);
                        materialsOverallCost += total;
                    }
                });

                // Update the materials overall cost input
                document.getElementById("materials_overall_cost").value = materialsOverallCost.toFixed(2);
            }
        </script>
    </body>
</html>
