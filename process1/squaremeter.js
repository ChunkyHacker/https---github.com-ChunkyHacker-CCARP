// area-calculation-script.js

// Function to calculate square meter for lot area
function calculateLotArea() {
    var length = document.getElementById("length_lot_area").value;
    var width = document.getElementById("width_lot_area").value;
    var squareMeter = length * width;
    document.getElementById("square_meter_lot").value = squareMeter;

    // Recalculate floor area when lot area changes
    calculateFloorArea();

    // Check if floor area dimensions exceed lot area dimensions
    checkFloorAreaDimensions();
}

// Function to calculate square meter for floor area
function calculateFloorArea() {
    var length = document.getElementById("length_floor_area").value;
    var width = document.getElementById("width_floor_area").value;
    var squareMeterFloor = length * width;
    document.getElementById("square_meter_floor").value = squareMeterFloor;

    // Get the lot area
    var lotArea = document.getElementById("square_meter_lot").value;

    // Set the limit for floor area based on lot area
    var floorAreaLimit = parseFloat(lotArea);

    // Check if floor area exceeds the limit
    if (squareMeterFloor > floorAreaLimit) {
        alert("Error: Floor area exceeds the limit of " + floorAreaLimit + " square meters.");
        // Optionally, you can clear the floor area inputs or handle the error in some way
        document.getElementById("length_floor_area").value = "";
        document.getElementById("width_floor_area").value = "";
        document.getElementById("square_meter_floor").value = "";
    }
}

// Function to check if floor area dimensions exceed lot area dimensions
function checkFloorAreaDimensions() {
    var lengthLot = document.getElementById("length_lot_area").value;
    var widthLot = document.getElementById("width_lot_area").value;
    var lengthFloor = document.getElementById("length_floor_area").value;
    var widthFloor = document.getElementById("width_floor_area").value;

    // Check if length or width for floor area exceeds the lot area
    if (parseFloat(lengthFloor) > parseFloat(lengthLot) || parseFloat(widthFloor) > parseFloat(widthLot)) {
        alert("Error: Floor area dimensions cannot exceed the lot area dimensions.");
        // Optionally, you can clear the floor area inputs or handle the error in some way
        document.getElementById("length_floor_area").value = "";
        document.getElementById("width_floor_area").value = "";
        document.getElementById("square_meter_floor").value = "";
    }
}
