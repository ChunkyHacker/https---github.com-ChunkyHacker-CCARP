document.addEventListener("DOMContentLoaded", function() {
    updateTotalCost();
});

function updateTotalCost() {
    var estimatedCost = parseFloat(document.getElementById("estimated_cost").value) || 0;
    var laborCost = parseFloat(document.getElementById("labor_cost").value) || 0;
    var remainingCost = parseFloat(document.getElementById("remaining_cost").value) || 0;

    console.log("Estimated Cost: " + estimatedCost);
    console.log("Labor Cost: " + laborCost);
    console.log("Remaining Cost: " + remainingCost);

    var totalCost = estimatedCost + remainingCost + laborCost;

    console.log("Total Cost: " + totalCost);

    document.getElementById("total_cost").value = totalCost.toFixed(2);
}

document.getElementById("labor_cost").addEventListener("input", updateTotalCost);
document.getElementById("remaining_cost").addEventListener("input", updateTotalCost);
document.getElementById("estimated_cost").addEventListener("input", updateTotalCost);
