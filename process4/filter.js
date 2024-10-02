function handleSortChange(selectElement) {
    var selectedType = selectElement.value.toLowerCase();
    var productCards = document.querySelectorAll('.product-card');

    productCards.forEach(function (card) {
        var itemTypeElement = card.querySelector('.product-type');
        var itemType = itemTypeElement ? itemTypeElement.textContent.toLowerCase() : '';

        if (selectedType === 'all' || itemType === selectedType) {
            card.style.display = ''; // Show the card if the selected type matches
        } else {
            card.style.display = 'none'; // Hide the card if the selected type does not match
        }
    });

    console.log("Filtering...");
}
