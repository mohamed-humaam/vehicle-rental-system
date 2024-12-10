document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.create-form');
    const photoInput = document.querySelector('#photo');
    const photoPreview = document.querySelector('.file-input-preview');
    const nameInput = document.querySelector('#name');
    const priceInput = document.querySelector('#daily_price');
    const typeSelect = document.querySelector('#type');
    const customTypeInput = document.querySelector('#custom_type');
    
    // Photo preview
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.style.display = 'block';
                photoPreview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Type selection handling
    typeSelect.addEventListener('change', function() {
        if (this.value === 'custom') {
            customTypeInput.style.display = 'block';
            customTypeInput.required = true;
            typeSelect.required = false;
        } else {
            customTypeInput.style.display = 'none';
            customTypeInput.required = false;
            typeSelect.required = true;
            customTypeInput.value = '';
        }
    });
    
    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const errorMessages = document.querySelectorAll('.error-message');
        errorMessages.forEach(msg => msg.remove());
        
        // Validate name
        if (nameInput.value.trim().length < 2) {
            isValid = false;
            showError(nameInput, 'Name must be at least 2 characters long');
        }
        
        // Validate price
        if (priceInput.value <= 0) {
            isValid = false;
            showError(priceInput, 'Price must be greater than 0');
        }

        // Validate type
        if (typeSelect.value === 'custom' && customTypeInput.value.trim() === '') {
            isValid = false;
            showError(customTypeInput, 'Please enter a custom type');
        } else if (typeSelect.value === '' && customTypeInput.style.display === 'none') {
            isValid = false;
            showError(typeSelect, 'Please select a vehicle type');
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    function showError(element, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        element.parentNode.appendChild(errorDiv);
    }
    
    // Price input formatting
    priceInput.addEventListener('input', function(e) {
        let value = e.target.value;
        if (value !== '') {
            value = parseFloat(value);
            if (!isNaN(value)) {
                e.target.value = value.toFixed(2);
            }
        }
    });
});