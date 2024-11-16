<?php
    include('config.php');
?>

<?php

// Ensure the existence of the Plan_ID field in the session
$PlanID = isset($_SESSION['plan_ID']) ? $_SESSION['plan_ID'] : '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Verdana, sans-serif;
            margin: 0;
        }

            table {
            display: none;
            border-collapse: collapse;
            width: 100%;
            }

            th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            }



        /* Header*/
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px;
            text-align: left;
            background: #FF8C00;
            color: #000000;
            display: flex;
            align-items: center;
            text-decoration: none;
            z-index: 100;
        }

        /* Increase the font size of the heading */
        .header h1 {
            font-size: 40px;
            border-left: 20px solid transparent; 
            text-decoration: none;
        }

        .right {
            margin-right: 20px;
        }

        .header a{
            font-size: 25px;
            font-weight: bold;
            text-decoration: none;
            color: #000000;
            margin-right: 15px;
        }

        .row-container {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 20px;
                }

                .input-container {
                    flex: 1;
                }



        /* When the screen is less than 600px wide, stack the links and the search field vertically instead of horizontally */
        @media screen and (max-width: 600px) {
            .topnav a, .topnav input[type=text] {
            float: none;
            display: block;
            text-align: left;
            width: 100%;
            margin: 0;
            padding: 14px;
            }
            .topnav input[type=text] {
            border: 1px solid #ccc;
            }
            body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding-top: 300px;
        }
        }


        /* CSS styles for the modal */
        /* CSS styles for the modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(255, 140, 0, 0.4); /* Updated background color */
        }

        .modal-content {
            background-color: #FF8C00; /* Updated background color */
            margin: 15% auto;
            padding: 20px;
            width: 80%;
            overflow-y: auto;
            border-radius: 8px;
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px;
            text-align: left;
            background: #FF8C00; /* Updated background color */
            color: #000000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-decoration: none;
            z-index: 100;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }



        .modal-content {
            background-color: #fefefe;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

            h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #FF8C00;
            }

            form {
            display: flex;
            flex-direction: column;
            }

            label {
            font-size: 16px;
            margin-bottom: 5px;
            color: #000000;
            }

            input,
            select,
            textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            }

        .post-btn, .cancel-btn {
            margin-bottom: 10px; /* Adjust the margin as needed */
        }

        .cancel-btn {
            background-color: red;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            text-decoration: none;
            display: inline-block;
            text-align: center; /* Center the text */
            width: 100%; /* Make the button full-width */
        }

        .cancel-btn:hover {
            background-color: #000000;
        }

        button {
            background-color: #FF8C00;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

            button:hover {
            background-color: #000000;
            }

            @media screen and (max-width: 600px) {
            .modal-content {
                width: 100%;
            }
            }
    </style>
</head>
<body>
    <div class="housepart">
        <form id="postForm" enctype="multipart/form-data" method="POST" action="selectingmaterials.php">

            <input type="hidden" id="Plan_ID" name="plan_ID" value="<?php echo $PlanID; ?>">

            <h2>Parts of the House</h2>
            <p>What part of the house do you want to build or to renovate?</p>

            <label for="checkboxBedroom">Bedroom</label>
            <input type="checkbox" id="checkboxBedroom" name="part[]" value="Bedroom" onclick="toggleTable('Bedroom')">

            <label for="checkboxDining">Dining Room</label>
            <input type="checkbox" id="checkboxDining" name="part[]" value="Dining Room" onclick="toggleTable('Dining')">

            <label for="checkboxLiving">Living Room</label>
            <input type="checkbox" id="checkboxLiving" name="part[]"  value="Living Room" onclick="toggleTable('Living')">

            <label for="checkboxKitchen">Kitchen</label>
            <input type="checkbox" id="checkboxKitchen" name="part[]" value="Kitchen" onclick="toggleTable('Kitchen')">

            <label for="checkboxBathroom">Bathroom</label>
            <input type="checkbox" id="checkboxBathroom" name="part[]" value="Bathroom" onclick="toggleTable('Bathroom')">

            <div>
                <!-- Bedroom Table -->
                <table id="tableBedroom">
                    <thead>
                        <tr>
                        <th>Select</th>
                        <th>Materials</th>
                        <th>Quantity</th>
                        <th>Price (₱)</th>
                        <th>Total (₱)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="800.00" value="Bed" onclick="updateEstimatedCost()"></td>
                            <td>Bed</td>
                            <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                            <td>800.00</td>
                            <td class="total">800.00</td>
                            <input type="hidden" class="totalInput" name="total[]" value="800.00">
                            <input type="hidden" name="name[]" value="Bed">
                            <input type="hidden" name="price[]" value="800.00">
                        </tr>

                        </tr>
                        <tr>
                            <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="400.00" value="Curtains" onclick="updateEstimatedCost()"></td>
                            <td>Curtains</td>
                            <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                            <td><span class="price">400.00</span></td>
                            <td><span class="total">400.00</span></td>
                            <input type="hidden" class="totalInput" name="total[]" value="400.00">
                            <input type="hidden" name="name[]" value="Curtains">
                            <input type="hidden" name="price[]" value="400.00">
                        </tr>
                        <tr>
                            <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="1200.00" value="Bedding" onclick="updateEstimatedCost()"></td>
                            <td>Bedding</td>
                            <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                            <td><span class="price">400.00</span></td>
                            <td><span class="total">400.00</span></td>
                            <input type="hidden" class="totalInput" name="total[]" value="400.00">
                            <input type="hidden" name="name[]" value="Bedding">
                            <input type="hidden" name="price[]" value="400.00">
                        </tr>
                        <tr>
                            <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="3500.00" value="Matress" onclick="updateEstimatedCost()"></td>
                            <td>Matress</td>
                            <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                            <td><span class="price">400.00</span></td>
                            <td><span class="total">400.00</span></td>
                            <input type="hidden" class="totalInput" name="total[]" value="400.00">
                            <input type="hidden" name="name[]" value="Matress">
                            <input type="hidden" name="price[]" value="400.00">
                        </tr>
                        <tr>
                            <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="600.00" value="Bedside Lamps" onclick="updateEstimatedCost()"></td>
                            <td>Bedside Lamps</td>
                            <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                            <td><span class="price">400.00</span></td>
                            <td><span class="total">400.00</span></td>
                            <input type="hidden" class="totalInput" name="total[]" value="400.00">
                            <input type="hidden" name="name[]" value="Bedside Lamps">
                            <input type="hidden" name="price[]" value="400.00">

                        </tr>
                        <!-- Add more rows as needed for bedroom materials -->
                    </tbody>
                </table>

                <!-- Dining Room Table -->
                <table id="tableDining">
                    <thead>
                    <tr>
                        <th>Select</th>
                        <th>Materials</th>
                        <th>Quantity</th>
                        <th>Price (₱)</th>
                        <th>Total (₱)</th>
                    </tr>
                    </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="2500.00" value="Dining Table" onclick="updateEstimatedCost()"></td>
                        <td>Dining Table</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">400.00</span></td>
                        <td><span class="total">400.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="400.00">
                        <input type="hidden" name="name[]" value="Dining Table">
                        <input type="hidden" name="price[]" value="400.00">
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="1200.00" value="Dining Chairs" onclick="updateEstimatedCost()"></td>
                        <td>Dining Chairs</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">400.00</span></td>
                        <td><span class="total">400.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="400.00">
                        <input type="hidden" name="name[]" value="Dining Chairs">
                        <input type="hidden" name="price[]" value="400.00">
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="1800.00" value="Cabinet" onclick="updateEstimatedCost()"></td>
                        <td>Cabinet</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">400.00</span></td>
                        <td><span class="total">400.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="400.00">
                        <input type="hidden" name="name[]" value="Cabinet">
                        <input type="hidden" name="price[]" value="400.00">
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="800.00" value="Rug" onclick="updateEstimatedCost()"></td>
                        <td>Rug</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">400.00</span></td>
                        <td><span class="total">400.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="400.00">
                        <input type="hidden" name="name[]" value="Rug">
                        <input type="hidden" name="price[]" value="400.00">
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="400.00" value="Table Cloth" onclick="updateEstimatedCost()"></td>
                        <td>Table Cloth</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">400.00</span></td>
                        <td><span class="total">400.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="400.00">
                        <input type="hidden" name="name[]" value="Table Cloth">
                        <input type="hidden" name="price[]" value="400.00">

                    </tr>
                    <!-- Add more rows as needed for dining room materials -->
                </tbody>
                </table>

                <!-- Living Room Table -->
                <table id="tableLiving">
                <thead>
                    <tr>
                    <th>Select</th>
                    <th>Materials</th>
                    <th>Quantity</th>
                    <th>Price (₱)</th>
                    <th>Total (₱)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="3000.00" value="Sofa" onclick="updateEstimatedCost()"></td>
                        <td>Sofa</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">400.00</span></td>
                        <td><span class="total">400.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="400.00">
                        <input type="hidden" name="name[]" value="Sofa">
                        <input type="hidden" name="price[]" value="400.00">
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="1200.00" value="Rug" onclick="updateEstimatedCost()"></td>
                        <td>Rug</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">400.00</span></td>
                        <td><span class="total">400.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="400.00">
                        <input type="hidden" name="name[]" value="Rug">
                        <input type="hidden" name="price[]" value="400.00">
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="800.00" value="Cushion" onclick="updateEstimatedCost()"></td>
                        <td>Cushion</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">400.00</span></td>
                        <td><span class="total">400.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="400.00">
                        <input type="hidden" name="name[]" value="Cushion">
                        <input type="hidden" name="price[]" value="400.00">
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="1500.00" value="Lights" onclick="updateEstimatedCost()"></td>
                        <td>Lights</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">400.00</span></td>
                        <td><span class="total">400.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="800.00">
                        <input type="hidden" name="name[]" value="Lights">
                        <input type="hidden" name="price[]" value="400.00">
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="2000.00" value="Table" onclick="updateEstimatedCost()"></td>
                        <td>Table</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">400.00</span></td>
                        <td><span class="total">400.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="400.00">
                        <input type="hidden" name="name[]" value="Table">
                        <input type="hidden" name="price[]" value="400.00">
                    </tr>
                    <!-- Add more rows as needed for living room materials -->
                </tbody>

                </table>

                <!-- Kitchen Table -->
                <table id="tableKitchen">
                    <thead>
                    <tr>
                        <th>Select</th>
                        <th>Materials</th>
                        <th>Quantity</th>
                        <th>Price (₱)</th>
                        <th>Total (₱)</th>
                    </tr>
                    </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="1000.00" value="Sink" onclick="updateEstimatedCost()"></td>
                        <td>Sink</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">400.00</span></td>
                        <td><span class="total">400.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="400.00">
                        <input type="hidden" name="name[]" value="Sink">
                        <input type="hidden" name="price[]" value="400.00">
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="500.00" value="Dish holder" onclick="updateEstimatedCost()"></td>
                        <td>Dish holder</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">400.00</span></td>
                        <td><span class="total">400.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="400.00">
                        <input type="hidden" name="name[]" value="Dish holder">
                        <input type="hidden" name="price[]" value="400.00">
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="800.00" value="Cabinet" onclick="updateEstimatedCost()"></td>
                        <td>Cabinet</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">400.00</span></td>
                        <td><span class="total">400.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="400.00">
                        <input type="hidden" name="name[]" value="Cabinet">
                        <input type="hidden" name="price[]" value="400.00">
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="1200.00" value="Lights" onclick="updateEstimatedCost()"></td>
                        <td>Lights</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">400.00</span></td>
                        <td><span class="total">400.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="400.00">
                        <input type="hidden" name="name[]" value="Lights">
                        <input type="hidden" name="price[]" value="400.00">
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="1500.00" value="Table" onclick="updateEstimatedCost()"></td>
                        <td>Table</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">400.00</span></td>
                        <td><span class="total">400.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="400.00">
                        <input type="hidden" name="name[]" value="Table">
                        <input type="hidden" name="price[]" value="400.00">
                    </tr>
                    <!-- Add more rows as needed for kitchen materials -->
                </tbody>
                </table>

                <!-- Bathroom Table -->
                <table id="tableBathroom">
                <thead>
                    <tr>
                    <th>Select</th>
                    <th>Materials</th>
                    <th>Quantity</th>
                    <th>Price (₱)</th>
                    <th>Total (₱)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="400.00" value = "Vanity" onclick="updateEstimatedCost()"></td>
                        <td>Vanity</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">400.00</span></td>
                        <td><span class="total">400.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="400.00">
                        <input type="hidden" name="name[]" value="Vanity">
                        <input type="hidden" name="price[]" value="400.00">
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="1800.00" value = "Shower" onclick="updateEstimatedCost()"></td>
                        <td>Shower</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">1800.00</span></td>
                        <td><span class="total">1800.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="1800.00">
                        <input type="hidden" name="name[]" value="Shower">
                        <input type="hidden" name="price[]" value="1800.00">
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="600.00" value = "Faucet Set" onclick="updateEstimatedCost()"></td>
                        <td>Faucet Set</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">600.00</span></td>
                        <td><span class="total">600.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="600.00">
                        <input type="hidden" name="name[]" value="Faucet Set">
                        <input type="hidden" name="price[]" value="600.00">
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="1500.00" value = "Bathtub" onclick="updateEstimatedCost()"></td>
                        <td>Bathtub</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">1500.00</span></td>
                        <td><span class="total">1500.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="1500.00">
                        <input type="hidden" name="name[]" value="Baththub">
                        <input type="hidden" name="price[]" value="1500.00">
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="item-checkbox" name="materials[]" data-price="800.00" value = "Toilets" ></td>
                        <td>Toilets</td>
                        <td><input type="number" class="quantity" name="quantity[]" value="1" min="0" oninput="calculateTotal(this)"></td>
                        <td><span class="price">800.00</span></td>
                        <td><span class="total">800.00</span></td>
                        <input type="hidden" class="totalInput" name="total[]" value="800.00">
                        <input type="hidden" name="name[]" value="Toilets">
                        <input type="hidden" name="price[]" value="800.00">
                    </tr>
                    <!-- Add more rows as needed for bathroom materials -->
                </tbody>
                </table>
            </div>
            <div class="input-container">
              <div id="selected-materials">
                  <h3>Estimated Cost</h3>
                  <label for="estimated_cost">Estimated Cost:</label>
                  <input type="text" name="estimated_cost" id="estimated_cost" readonly>
              </div>
            </div>
            <button>Submit</button>
        </form>
    </div>
</body>
<script>
        // your-script.js

    function toggleTable(room) {
        // Show the selected table
        document.getElementById("table" + room).style.display = document.getElementById("checkbox" + room).checked ? "table" : "none";
    }

    function calculateTotal(input) {
        var row = input.closest("tr");
        var quantity = parseFloat(row.querySelector(".quantity-input").value);
        var price = parseFloat(row.querySelector(".price").innerText);
        var total = quantity * price;

        // Update the total column in the same row
        row.querySelector(".total").innerText = total.toFixed(2);
    }
</script>
<script>
    function calculateTotal(input) {
        var quantity = input.value;
        var price = input.parentElement.nextElementSibling.textContent;
        var total = parseFloat(quantity) * parseFloat(price);
        input.parentElement.nextElementSibling.nextElementSibling.textContent = total.toFixed(2);
    }
</script>
<script>
    function calculateTotal(input) {
        var quantity = input.value;
        var price = input.parentElement.nextElementSibling.textContent;
        var total = parseFloat(quantity) * parseFloat(price);
        input.parentElement.nextElementSibling.nextElementSibling.textContent = total.toFixed(2);
        updateEstimatedCost();
    }

    function updateEstimatedCost() {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
        var totalCost = 0;

        checkboxes.forEach(function(checkbox) {
            var rowTotal = parseFloat(checkbox.parentElement.nextElementSibling.nextElementSibling.nextElementSibling.textContent);
            totalCost += rowTotal;
        });

        var estimatedCostInput = document.getElementById('estimated_cost');
        estimatedCostInput.value = "₱" + totalCost.toFixed(2);
    }
</script>
<script>
        // Function to calculate the total for each item based on the quantity and update the estimated cost
    function calculateTotal(quantityInput) {
        const row = quantityInput.closest('tr');
        const price = parseFloat(row.querySelector('.item-checkbox').dataset.price);
        const quantity = parseInt(quantityInput.value, 10) || 0;
        const total = price * quantity;
        
        // Update the total for this row
        row.querySelector('.total').innerText = total.toFixed(2);
        row.querySelector('.totalInput').value = total.toFixed(2);
        
        // Update the estimated cost
        updateEstimatedCost();
    }

    // Function to update the estimated cost when an item is selected or deselected
    function updateEstimatedCost() {
        let estimatedCost = 0;

        // Loop through each checkbox
        document.querySelectorAll('.item-checkbox').forEach((checkbox) => {
            if (checkbox.checked) {
                const row = checkbox.closest('tr');
                const total = parseFloat(row.querySelector('.totalInput').value);
                estimatedCost += total;
            }
        });

        // Update the estimated cost input
        document.getElementById("estimated_cost").value = estimatedCost;
    }
</script>

</html>

