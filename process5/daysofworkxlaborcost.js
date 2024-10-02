    // Get references to the input fields
    var daysOfWorkInput = document.getElementById('days_of_work');
    var rateInput = document.getElementById('rate');
    var totalLaborCostInput = document.getElementById('total_of_laborcost');

    // Add event listeners to days of work and rate input fields
    daysOfWorkInput.addEventListener('input', calculateTotalLaborCost);
    rateInput.addEventListener('input', calculateTotalLaborCost);

    // Function to calculate total labor cost
    function calculateTotalLaborCost() {
        // Parse days of work and rate inputs as numbers
        var daysOfWork = parseFloat(daysOfWorkInput.value);
        var rate = parseFloat(rateInput.value);

        // Calculate total labor cost
        var totalLaborCost = isNaN(daysOfWork) ? rate : daysOfWork * rate;

        // Update total labor cost input field with the calculated value
        totalLaborCostInput.value = isNaN(totalLaborCost) ? '' : totalLaborCost.toFixed(2); // Displaying up to 2 decimal places
    }
