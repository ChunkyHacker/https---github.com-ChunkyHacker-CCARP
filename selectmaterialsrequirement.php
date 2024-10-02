<?php 
    echo "<form method='post' action='selectingmaterialsrequirement.php'>";

        echo "<label for='materials'>Materials </label>";

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "ccarpcurrentsystem";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

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
                            <input type="checkbox" class="item-checkbox" name="rawmaterials[]" data-price="' . $price . '" value="' . $itemname . '">
                            <input type="hidden" name="material[]" value="' . $itemname . '">
                            <input type="hidden" name="type[]" value="' . $type . '">
                            <input type="hidden" name="image[]" value="' . $itemimage . '">
                            <input type="hidden" name="quantity[]" value="' . $quantity . '">
                            <input type="hidden" name="price[]" value="' . $price . '">
                            <input type="hidden" name="total[]" value="' . $price . '">
                        </td>
                        <td style="border: 1px solid black;"><span name="name[]">' . $itemname . '</span></td>
                        <td style="border: 1px solid black;"><span name="type[]">' . $type . '</span></td>
                        <td style="border: 1px solid black;">
                            <span name="itemimage[]">
                                <img class="image[]" src="assets/items/' . $itemimage . '" alt="' . $itemname . '" style="width: 100px; height: auto;">
                            </span>
                        </td>
                        <td style="border: 1px solid black;">
                            <input type="number" class="quantity-input" name="itemQuantity[]" oninput="calculateTotal(this); if(value<0)value=0;">
                        </td>
                        <td style="border: 1px solid black;"><span name="quantity[]">' . $quantity . '</span></td>
                        <td style="border: 1px solid black;"><span name="price[]" class="price">' . $price . '</span></td>
                        <td style="border: 1px solid black;"><span name="total[]" class="total">' . $price . '</span></td>
                    </tr>';
        
            }

            echo '</tbody></table>';

        echo "<button type='submit'>Submit</button>";
    echo "</form>";

    $conn->close();    
?>
