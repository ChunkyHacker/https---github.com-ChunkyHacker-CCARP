function openModal(imageSrc) {
    var modal = document.getElementById('myModal');
    var modalImg = document.getElementById('modalImage');

    modal.style.display = 'block';
    modalImg.src = imageSrc;
}

function closeModal() {
    var modal = document.getElementById('myModal');
    modal.style.display = 'none';
}

// Close the modal if the user clicks anywhere outside the modal
window.onclick = function (event) {
    var modal = document.getElementById('myModal');
    if (event.target === modal) {
        closeModal();
    }
};
