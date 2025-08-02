// Main JavaScript for customer-facing pages
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize Bootstrap popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Form validation enhancement
    const forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Product filter form auto-submit
    const filterSelects = document.querySelectorAll('select[data-auto-submit]');
    filterSelects.forEach(function(select) {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

    // Price formatting for display
    const priceElements = document.querySelectorAll('.price-format');
    priceElements.forEach(function(element) {
        const price = parseInt(element.getAttribute('data-price') || element.textContent);
        if (!isNaN(price)) {
            element.textContent = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(price);
        }
    });

    // Image gallery functionality
    const mainImage = document.getElementById('main-product-image');
    const thumbnails = document.querySelectorAll('.product-thumbnail');
    
    if (mainImage && thumbnails.length > 0) {
        thumbnails.forEach(function(thumb) {
            thumb.addEventListener('click', function() {
                // Update main image
                mainImage.src = this.getAttribute('data-image');
                
                // Update active state
                thumbnails.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }

    // Quantity selector
    const quantityInput = document.getElementById('quantity');
    const incrementBtn = document.querySelector('.quantity-up');
    const decrementBtn = document.querySelector('.quantity-down');
    
    if (quantityInput && incrementBtn && decrementBtn) {
        incrementBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value) || 0;
            const maxValue = parseInt(quantityInput.getAttribute('max')) || 100;
            if (currentValue < maxValue) {
                quantityInput.value = currentValue + 1;
            }
        });
        
        decrementBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value) || 0;
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
    }

    // Search functionality
    const searchForm = document.querySelector('.search-form');
    const searchInput = document.querySelector('.search-input');
    
    if (searchForm && searchInput) {
        searchForm.addEventListener('submit', function(e) {
            if (searchInput.value.trim() === '') {
                e.preventDefault();
                searchInput.focus();
            }
        });
    }
});