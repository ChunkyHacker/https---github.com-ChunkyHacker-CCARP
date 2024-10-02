// JavaScript functions for Edit Modal
function openEditModal(itemId) {
    // Fetch item details and populate the modal fields
    // ...
  
    // Set the item ID in a hidden field
    document.getElementById('editItemId').value = itemId;
  
    // Open the modal and center it on the screen
    var modal = document.getElementById('editModal');
    modal.style.display = 'block';
    centerModal(modal);
  }
  
  function centerModal(modal) {
    // Calculate the top and left positions to center the modal
    var topPosition = (window.innerHeight - modal.offsetHeight) / 2;
    var leftPosition = (window.innerWidth - modal.offsetWidth) / 2;
  
    // Set the calculated positions
    modal.style.top = topPosition + 'px';
    modal.style.left = leftPosition + 'px';
  }
  
  function closeEditModal() {
    // Close the modal
    document.getElementById('editModal').style.display = 'none';
  }
  
  function saveChanges() {
    // Implement logic to save changes
    // ...
  
    // Close the modal after saving changes
    closeEditModal();
  }
  