function handleSearch() {
    var searchQuery = document.getElementById('searchInput').value.toLowerCase();
    var productCards = document.querySelectorAll('.product-card');

    productCards.forEach(function (card) {
        var itemName = card.querySelector('.product-title').textContent.toLowerCase();
        var itemType = card.querySelector('.product-type').textContent.toLowerCase();

        if (itemName.includes(searchQuery) || itemType.includes(searchQuery)) {
            card.style.display = ''; // Show the card if the search query matches
        } else {
            card.style.display = 'none'; // Hide the card if the search query does not match
        }
    });

    console.log("Searching...");
}

function handleSortChange(selectElement) {
    // Implement sort functionality if needed
}