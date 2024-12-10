// Customer List Interactions (assets/js/customer-list.js)
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sorting and filtering
    let sortDirection = {};
    const searchInput = document.querySelector('.search-box');
    const table = document.querySelector('.customers-table');
    
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
    
    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', filterTable);
    }
    
    // Table sorting function
    function sortTable(column) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        sortDirection[column] = sortDirection[column] === 'asc' ? 'desc' : 'asc';
        
        rows.sort((a, b) => {
            let aValue = a.querySelector(`td[data-${column}]`).textContent.trim();
            let bValue = b.querySelector(`td[data-${column}]`).textContent.trim();
            
            // Try to convert to number if possible
            const aNum = parseFloat(aValue);
            const bNum = parseFloat(bValue);
            
            if (!isNaN(aNum) && !isNaN(bNum)) {
                aValue = aNum;
                bValue = bNum;
            }
            
            if (sortDirection[column] === 'asc') {
                return aValue > bValue ? 1 : -1;
            } else {
                return aValue < bValue ? 1 : -1;
            }
        });
        
        // Clear table
        while (tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }
        
        // Add sorted rows
        rows.forEach(row => tbody.appendChild(row));
        
        // Add animation
        rows.forEach((row, index) => {
            row.style.animation = `fadeIn 0.3s ease forwards ${index * 0.05}s`;
        });
    }
    
    // Table filtering function
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const name = row.querySelector('td[data-name]').textContent.toLowerCase();
            const email = row.querySelector('td[data-email]').textContent.toLowerCase();
            const matchesSearch = 
                name.includes(searchTerm) || 
                email.includes(searchTerm);
            
            if (matchesSearch) {
                row.style.display = '';
                row.style.animation = 'fadeIn 0.3s ease forwards';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    // Delete confirmation
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const customerName = this.dataset.name;
            
            if (confirm(`Are you sure you want to delete ${customerName}?`)) {
                window.location.href = this.href;
            }
        });
    });
});