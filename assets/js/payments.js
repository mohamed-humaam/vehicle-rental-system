/* JavaScript for payments (assets/js/payments.js) */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sorting
    let sortDirection = {};
    const table = document.querySelector('.payment-table');
    
    if (table) {
        // Add event listeners for sorting
        document.querySelectorAll('th[data-sortable]').forEach(header => {
            header.addEventListener('click', () => {
                const column = header.dataset.column;
                sortTable(column);
                
                // Update sort indicators
                document.querySelectorAll('th').forEach(th => {
                    th.classList.remove('sorted-asc', 'sorted-desc');
                });
                
                header.classList.add(sortDirection[column] === 'asc' ? 'sorted-asc' : 'sorted-desc');
            });
        });
    }
    
    // Payment form handling
    const paymentForm = document.querySelector('.payment-form');
    if (paymentForm) {
        const bookingSelect = document.querySelector('select[name="booking_id"]');
        const amountInput = document.querySelector('input[name="amount"]');
        
        // Auto-fill amount when booking is selected
        bookingSelect?.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const amount = selectedOption.textContent.match(/Amount: \$([0-9.]+)/)?.[1];
            if (amount) {
                amountInput.value = amount;
            }
        });
        
        // Form validation
        paymentForm.addEventListener('submit', function(e) {
            if (!bookingSelect.value || !amountInput.value) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    }
    
    // Table sorting function
    function sortTable(column) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        sortDirection[column] = sortDirection[column] === 'asc' ? 'desc' : 'asc';
        
        rows.sort((a, b) => {
            let aValue = a.querySelector(`td[data-${column}]`).dataset[column];
            let bValue = b.querySelector(`td[data-${column}]`).dataset[column];
            
            if (column === 'amount') {
                aValue = parseFloat(aValue);
                bValue = parseFloat(bValue);
            }
            
            if (sortDirection[column] === 'asc') {
                return aValue > bValue ? 1 : -1;
            } else {
                return aValue < bValue ? 1 : -1;
            }
        });
        
        // Clear and repopulate table
        while (tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }
        
        rows.forEach(row => tbody.appendChild(row));
        
        // Add animation
        rows.forEach((row, index) => {
            row.style.animation = `fadeIn 0.3s ease forwards ${index * 0.05}s`;
        });
    }
});