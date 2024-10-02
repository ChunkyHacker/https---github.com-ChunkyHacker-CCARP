        // Get references to the input fields
        const totalLaborCostInput = document.getElementById("total_of_laborcost");
        const additionalCostInput = document.getElementById("additional_cost");
        const overallCostInput = document.getElementById("overall_cost");

        // Function to calculate the overall cost
        function calculateOverallCost() {
            const totalLaborCost = parseFloat(totalLaborCostInput.value) || 0;
            const additionalCost = parseFloat(additionalCostInput.value) || 0;

            // Calculate the overall cost
            const overallCost = totalLaborCost + additionalCost;

            // Update the overall cost input field
            overallCostInput.value = overallCost.toFixed(2); // Displaying up to 2 decimal places
        }

        // Add event listeners to trigger calculation when input values change
        totalLaborCostInput.addEventListener("input", calculateOverallCost);
        additionalCostInput.addEventListener("input", calculateOverallCost);

        // Initial calculation on page load (if needed)
        calculateOverallCost();