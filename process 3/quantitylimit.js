function calculateTotal(input) {
    var quantity = parseInt(input.value);
    var price = parseFloat(input.parentNode.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild.innerText);
    var totalElement = input.parentNode.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild;
    var availableQuantityElement = input.parentNode.nextElementSibling.nextElementSibling.nextElementSibling.firstElementChild;
    var availableQuantity = parseInt(availableQuantityElement.innerText);

    if (quantity < 0) {
        input.value = 0;
        quantity = 0;
    }

    if (quantity > availableQuantity) {
        alert("Requested quantity exceeds available quantity!");
        input.value = availableQuantity;
        quantity = availableQuantity;
    }

    var total = quantity * price;
    totalElement.innerText = total.toFixed(2);
}