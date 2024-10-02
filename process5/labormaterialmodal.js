var materialsModal = document.getElementById('addMaterialsModal');
var laborModal = document.getElementById('addLaborModal');
var materialsBtn = document.getElementById('addMaterialsBtn');
var laborBtn = document.getElementById('addLaborBtn');
var closeBtns = document.getElementsByClassName('close');

materialsBtn.onclick = function() {
    materialsModal.style.display = 'block';
}

laborBtn.onclick = function() {
    laborModal.style.display = 'block';
}

for (var i = 0; i < closeBtns.length; i++) {
    closeBtns[i].onclick = function() {
        materialsModal.style.display = 'none';
        laborModal.style.display = 'none';
    }
}

window.onclick = function(event) {
    if (event.target == materialsModal || event.target == laborModal) {
        materialsModal.style.display = 'none';
        laborModal.style.display = 'none';
    }
}
