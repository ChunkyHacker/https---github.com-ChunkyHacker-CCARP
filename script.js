function showOptions(selectedPart) {
    // Hide all type containers
    var typeContainers = document.querySelectorAll('.type-container');
    typeContainers.forEach(function(container) {
        container.classList.add('hidden');
    });

    // Show the selected type container
    var selectedTypeContainer = document.getElementById(selectedPart);
    selectedTypeContainer.classList.remove('hidden');

    // Hide all material containers
    var materialContainers = document.querySelectorAll('.material-container');
    materialContainers.forEach(function(container) {
        container.classList.add('hidden');
    });
}

function showMaterials(selectedPart, selectedType) {
    // Show the selected material container
    var materialContainerId = selectedPart + '-' + selectedType + '-materials';
    var selectedMaterialContainer = document.getElementById(materialContainerId);
    selectedMaterialContainer.classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    // Attach click event listeners to part radio buttons
    var partRadios = document.querySelectorAll('input[name="part"]');
    partRadios.forEach(function(radio) {
        radio.addEventListener('click', function() {
            showOptions(this.value);
        });
    });

    // Attach click event listeners to type radio buttons
    var typeRadios = document.querySelectorAll('input[name="type"]');
    typeRadios.forEach(function(radio) {
        radio.addEventListener('click', function() {
            var selectedPart = document.querySelector('input[name="part"]:checked').value;
            showMaterials(selectedPart, this.value);
        });
    });
});
