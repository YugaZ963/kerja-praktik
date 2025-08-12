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
    
    // Phone number formatting
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            
            // Convert 08xx to 628xx
            if (value.startsWith('08')) {
                value = '62' + value.substring(1);
            }
            // Add 62 prefix if not present
            else if (!value.startsWith('62') && value.length > 0) {
                value = '62' + value;
            }
            
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