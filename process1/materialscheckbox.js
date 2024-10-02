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
