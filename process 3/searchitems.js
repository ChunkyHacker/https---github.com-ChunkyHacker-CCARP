function searchItems() {
    var searchQuery = document.getElementById('search').value.toLowerCase();
    var tableRows = document.querySelectorAll('#materials tbody tr');

    tableRows.forEach(function (row) {
        var itemName = row.cells[1].textContent.toLowerCase(); // Assuming the item name is in the second column

        if (itemName.includes(searchQuery)) {
            row.style.display = ''; // Show the row if the search query matches
        } else {
            row.style.display = 'none'; // Hide the row if the search query does not match
        }
    });

    console.log("Searching...");
}

function submitSearch() {
    // You can add additional logic here if needed
    console.log("Search submitted...");
}
