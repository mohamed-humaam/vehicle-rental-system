document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".booking-form");
  const vehicleSelect = document.querySelector('[name="vehicle_id"]');
  const startDateInput = document.querySelector('[name="start_date"]');
  const endDateInput = document.querySelector('[name="end_date"]');
  const totalAmountInput = document.querySelector('[name="total_amount"]');
  const errorMessage = document.querySelector(".error-message");

  const today = new Date().toISOString().split("T")[0];
  startDateInput.min = today;
  endDateInput.min = today;

  function calculateTotalAmount() {
    const vehicleOption = vehicleSelect.options[vehicleSelect.selectedIndex];
    const pricePerDay = parseFloat(vehicleOption.getAttribute("data-price"));
    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);

    if (pricePerDay && startDate && endDate) {
      if (startDate > endDate) {
        showError("End date must be after start date");
        totalAmountInput.value = "";
        return;
      }

      hideError();
      const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
      const total = (pricePerDay * days).toFixed(2);
      totalAmountInput.value = total;

      totalAmountInput.classList.add("highlight");
      setTimeout(() => totalAmountInput.classList.remove("highlight"), 500);
    } else {
      totalAmountInput.value = "";
    }
  }

  function showError(message) {
    errorMessage.textContent = message;
    errorMessage.style.display = "block";
  }

  function hideError() {
    errorMessage.style.display = "none";
  }

  vehicleSelect.addEventListener("change", calculateTotalAmount);
  startDateInput.addEventListener("change", function () {
    endDateInput.min = this.value;
    calculateTotalAmount();
  });
  endDateInput.addEventListener("change", calculateTotalAmount);

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    if (!validateForm()) {
      return;
    }

    const submitButton = form.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    form.classList.add("form-loading");

    setTimeout(() => {
      this.submit();
    }, 500);
  });

  function validateForm() {
    const startDate = new Date(startDateInput.value);
    const endDate = new Date(endDateInput.value);

    if (!vehicleSelect.value) {
      showError("Please select a vehicle");
      return false;
    }

    if (!startDate || !endDate) {
      showError("Please select both start and end dates");
      return false;
    }

    if (startDate > endDate) {
      showError("End date must be after start date");
      return false;
    }

    if (!totalAmountInput.value) {
      showError("Total amount calculation error");
      return false;
    }

    return true;
  }

  calculateTotalAmount();
});
