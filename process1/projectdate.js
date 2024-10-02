function validateDates() {
    const startDate = new Date(document.getElementById('start_date').value);
    const endDateInput = document.getElementById('end_date');
    const endDate = new Date(endDateInput.value);

    if (endDate < startDate) {
      alert('End date should not be before the start date.');
      endDateInput.value = ""; 
    }
  }