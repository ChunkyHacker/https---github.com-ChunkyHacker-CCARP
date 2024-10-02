document.addEventListener("DOMContentLoaded", function () {
    function calculateTotal() {
        var totalCost = 0;

        // Iterate through each checkbox and calculate the total cost
        var materialCheckboxes = document.querySelectorAll('.item-checkbox:checked');
        materialCheckboxes.forEach(function (checkbox) {
            var row = checkbox.parentNode.parentNode;
            var quantity = parseInt(row.querySelector('.quantity-input').value);
            var price = parseFloat(row.querySelector('.price').textContent);
            var total = price * quantity;
            totalCost += total;
        });

        // Display the total cost directly
        document.getElementById('remaining_cost').value = totalCost.toFixed(2);
        console.log("Remaining Cost:", totalCost);
    }

    // Add event listeners to recalculate total cost when checkboxes and quantities are changed
    var materialCheckboxes = document.querySelectorAll('.item-checkbox');
    materialCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', calculateTotal);
    });

    var quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(function (input) {
        input.addEventListener('input', function () {
            calculateTotal();
        });
    });
});
