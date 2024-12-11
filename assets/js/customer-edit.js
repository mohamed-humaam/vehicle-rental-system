document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".customer-edit-form");
  const inputs = form.querySelectorAll("input[required]");

  function validateInput(input) {
    input.classList.remove("input-error");
    const existingError = input.nextElementSibling;
    if (existingError && existingError.classList.contains("error-message")) {
      existingError.remove();
    }

    let isValid = input.checkValidity();

    if (!isValid) {
      input.classList.add("input-error");
      const errorMessage = document.createElement("div");
      errorMessage.classList.add("error-message");

      switch (input.type) {
        case "email":
          errorMessage.textContent = "Please enter a valid email address";
          break;
        case "text":
          errorMessage.textContent =
            input.name === "phone"
              ? "Please enter a valid phone number"
              : "This field is required";
          break;
        default:
          errorMessage.textContent = "This field is required";
      }

      input.parentNode.insertBefore(errorMessage, input.nextSibling);
    }

    return isValid;
  }

  inputs.forEach((input) => {
    input.addEventListener("input", function () {
      validateInput(this);
    });
  });

  form.addEventListener("submit", function (e) {
    let isFormValid = true;

    inputs.forEach((input) => {
      if (!validateInput(input)) {
        isFormValid = false;
      }
    });

    if (!isFormValid) {
      e.preventDefault();
    }
  });

  const phoneInput = form.querySelector('input[name="phone"]');
  if (phoneInput) {
    phoneInput.addEventListener("input", function (e) {
      let phoneNumber = e.target.value.replace(/\D/g, "");

      if (phoneNumber.length > 0) {
        if (phoneNumber.length <= 3) {
          e.target.value = phoneNumber;
        } else if (phoneNumber.length <= 6) {
          e.target.value = `(${phoneNumber.slice(0, 3)}) ${phoneNumber.slice(
            3
          )}`;
        } else {
          e.target.value = `(${phoneNumber.slice(0, 3)}) ${phoneNumber.slice(
            3,
            6
          )}-${phoneNumber.slice(6, 10)}`;
        }
      }
    });
  }
});
