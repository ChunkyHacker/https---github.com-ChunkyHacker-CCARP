document.addEventListener("DOMContentLoaded", function () {
    var houseForm = document.getElementById("houseForm");
    var houseType = document.getElementById("houseType");

    // Add event listener to update display when houseType changes
    houseType.addEventListener("change", checkCustomOption);

    // Add event listener to handle form submission
    houseForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent the default form submission behavior   

        var customValue = getCustomOptionValue();

        console.log("Custom Value:", customValue);
        console.log("Selected House Type:", houseType.value);

        if (customValue === "" && houseType.value !== "custom") {
            console.log("Handling predefined option");
            // Perform the necessary actions for predefined options
        } else {
            console.log("Handling custom option");
            // Perform the necessary actions for custom options
        }
        // Continue with your database interaction or other logic here
    });
});

// Function to check if the selected option requires a custom input
function checkCustomOption() {
    var selectedOption = document.getElementById("houseType").value;
    var customOptionContainer = document.getElementById("customOptionContainer");

    // If the selected option requires a custom input, show the custom option input
    if (selectedOption === "custom") {
        customOptionContainer.style.display = "block";
    } else {
        // Otherwise, hide the custom option input
        customOptionContainer.style.display = "none";
        // Clear the input field when not needed
        document.getElementById("customOption").value = "";
    }
}

// Function to get the value from the custom input field
function getCustomOptionValue() {
    var customOptionInput = document.getElementById("customOption");
    return customOptionInput.value;
}