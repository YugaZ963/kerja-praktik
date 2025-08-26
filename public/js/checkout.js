// Checkout JavaScript - Simple and Robust
document.addEventListener('DOMContentLoaded', function() {
    console.log('Checkout script loaded');
    
    // Suppress auth required errors
    window.addEventListener('error', function(e) {
        if (e.message && e.message.includes('auth required')) {
            e.preventDefault();
            console.log('Auth error suppressed');
        }
    });
    
    window.addEventListener('unhandledrejection', function(e) {
        if (e.reason && e.reason.message && e.reason.message.includes('auth required')) {
            e.preventDefault();
            console.log('Auth promise rejection suppressed');
        }
    });
    
    // Payment method handling
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const paymentDetails = document.getElementById('payment-details');
    const briDetails = document.getElementById('bri-details');
    const danaDetails = document.getElementById('dana-details');
    
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            if (paymentDetails) {
                paymentDetails.style.display = 'block';
            }
            
            if (briDetails) briDetails.style.display = 'none';
            if (danaDetails) danaDetails.style.display = 'none';
            
            if (this.value === 'bri' && briDetails) {
                briDetails.style.display = 'block';
            } else if (this.value === 'dana' && danaDetails) {
                danaDetails.style.display = 'block';
            }
            
            // Update card styling
            document.querySelectorAll('.payment-option').forEach(card => {
                card.classList.remove('selected');
            });
            
            const selectedCard = document.querySelector(`[data-payment="${this.value}"]`);
            if (selectedCard) {
                selectedCard.classList.add('selected');
            }
        });
    });
    
    // Phone number formatting - allow user to input without auto prefix
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            // Only remove non-numeric characters, don't add prefix automatically
            let value = this.value.replace(/\D/g, '');
            this.value = value;
        });
    }
    
    // Form validation and submission
    const checkoutForm = document.querySelector('form[action*="process-order"]');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            console.log('Form submission started');
            
            // Get form elements
            const name = document.getElementById('name');
            const phone = document.getElementById('phone');
            const address = document.getElementById('address');
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            const submitBtn = this.querySelector('button[type="submit"]');
            
            // Validation
            let isValid = true;
            let errorMessage = '';
            
            if (!name || !name.value.trim()) {
                isValid = false;
                errorMessage = 'Nama lengkap harus diisi!';
            } else if (!phone || !phone.value.trim()) {
                isValid = false;
                errorMessage = 'Nomor WhatsApp harus diisi!';
            } else if (!address || !address.value.trim()) {
                isValid = false;
                errorMessage = 'Alamat lengkap harus diisi!';
            } else if (!paymentMethod) {
                isValid = false;
                errorMessage = 'Pilih metode pembayaran!';
            } else if (phone.value.length < 10) {
                isValid = false;
                errorMessage = 'Nomor WhatsApp tidak valid!';
            }
            
            if (!isValid) {
                e.preventDefault();
                alert(errorMessage);
                return false;
            }
            
            // Show loading state
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';
                
                // Reset button after timeout
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-whatsapp"></i> Kirim Pesanan via WhatsApp';
                }, 15000);
            }
            
            console.log('Form validation passed, submitting...');
            return true;
        });
    }
    
    // Initialize payment method if already selected
    const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
    if (selectedPayment) {
        selectedPayment.dispatchEvent(new Event('change'));
    }
    
    console.log('Checkout script initialization complete');
});

// Function to handle payment method selection when clicking anywhere on the card
function selectPayment(method) {
    const radioButton = document.getElementById('payment_' + method);
    if (radioButton) {
        radioButton.checked = true;
        radioButton.dispatchEvent(new Event('change'));
    }
}

// Function to handle shipping method selection when clicking anywhere on the card
function selectShipping(method) {
    const radioButton = document.getElementById('shipping_' + method);
    if (radioButton) {
        radioButton.checked = true;
        radioButton.dispatchEvent(new Event('change'));
    }
}

// Function to update shipping estimate
function updateShippingEstimate(subtotal) {
    const shippingMethod = document.querySelector('input[name="shipping_method"]:checked');
    const shippingEstimateDisplay = document.getElementById('shipping-estimate-display');
    const totalAmountDisplay = document.getElementById('total-amount-display');
    
    if (shippingMethod && shippingEstimateDisplay && totalAmountDisplay) {
        let estimateText = '3-5 hari kerja';
        
        if (shippingMethod.value === 'express') {
            estimateText = '1-2 hari kerja';
        }
        
        shippingEstimateDisplay.textContent = estimateText;
        totalAmountDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);
    }
}