// Get references to the input fields
var quantityInput = document.getElementById('quantity');
var costInput = document.getElementById('cost');
var totalCostInput = document.getElementById('total_cost');

// Add event listeners to quantity and cost input fields
quantityInput.addEventListener('input', calculateTotalCost);
costInput.addEventListener('input', calculateTotalCost);

// Function to calculate total cost
function calculateTotalCost() {
    // Parse quantity and cost inputs as numbers
    var quantity = parseFloat(quantityInput.value);
    var cost = parseFloat(costInput.value);

    // Calculate total cost
    var totalCost = quantity * cost;

    // Update total cost input field with the calculated value
    totalCostInput.value = totalCost.toFixed(2); // Displaying up to 2 decimal places
}
