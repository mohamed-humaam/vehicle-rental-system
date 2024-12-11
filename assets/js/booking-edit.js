document.addEventListener("DOMContentLoaded", function () {
  const startDate = document.getElementById("start_date");
  const endDate = document.getElementById("end_date");

  if (startDate && endDate) {
    startDate.addEventListener("change", function () {
      endDate.min = this.value;
    });

    endDate.addEventListener("change", function () {
      startDate.max = this.value;
    });
  }

  const form = document.querySelector(".booking-edit-form");

  form.addEventListener("submit", function (e) {
    const customer = document.getElementById("customer_id");
    const vehicle = document.getElementById("vehicle_id");

    if (!customer.value || !vehicle.value) {
      e.preventDefault();
      alert("Please select both a customer and a vehicle");
    }
  });
});
