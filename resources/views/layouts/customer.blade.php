{{-- resources/views/layouts/customer.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO Meta Tags -->
    <title>{{ $seoData['title'] ?? $title ?? 'UKM Seragam Sekolah' }}@yield('title')</title>
    <meta name="description" content="{{ $seoData['description'] ?? 'UKM Seragam Sekolah - Toko Seragam Sekolah Terpercaya' }}">
    <meta name="keywords" content="{{ $seoData['keywords'] ?? 'seragam sekolah, baju sekolah, UKM Seragam' }}">
    <meta name="author" content="{{ $seoData['author'] ?? 'UKM Seragam Sekolah Team' }}">
    <meta name="robots" content="{{ $seoData['robots'] ?? 'index, follow' }}">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ $seoData['canonical'] ?? request()->url() }}">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $seoData['title'] ?? $title ?? 'UKM Seragam Sekolah' }}">
    <meta property="og:description" content="{{ $seoData['description'] ?? 'UKM Seragam Sekolah - Toko Seragam Sekolah Terpercaya' }}">
    <meta property="og:image" content="{{ $seoData['image'] ?? asset('images/ukm-seragam.jpg') }}">
    <meta property="og:url" content="{{ $seoData['url'] ?? request()->url() }}">
    <meta property="og:type" content="{{ $seoData['type'] ?? 'website' }}">
    <meta property="og:site_name" content="{{ $seoData['site_name'] ?? 'UKM Seragam Sekolah' }}">
    <meta property="og:locale" content="{{ $seoData['locale'] ?? 'id_ID' }}">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seoData['title'] ?? $title ?? 'UKM Seragam Sekolah' }}">
    <meta name="twitter:description" content="{{ $seoData['description'] ?? 'UKM Seragam Sekolah - Toko Seragam Sekolah Terpercaya' }}">
    <meta name="twitter:image" content="{{ $seoData['image'] ?? asset('images/ukm-seragam.jpg') }}">
    
    <!-- Structured Data -->
    @if(isset($seoData['structured_data']))
    <script type="application/ld+json">
    {!! json_encode($seoData['structured_data'], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
     <link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
     <link rel="stylesheet" href="{{ asset('css/seo.css') }}">
    @stack('styles')
</head>

<body>
    <!-- Skip to content for accessibility -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>
    
    <div class="container" id="main-content">
        {{-- @include('partials.alerts') --}}
        @yield('content')
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    
    <script>
        // Update cart count on page load
        function updateCartCount() {
            fetch('{{ route("cart.count") }}')
                .then(response => response.json())
                .then(data => {
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.count;
                        cartCountElement.style.display = data.count > 0 ? 'inline' : 'none';
                    }
                })
                .catch(error => console.error('Error updating cart count:', error));
        }

        // Handle authentication errors for form submissions
        function handleAuthError(response) {
            if (response.status === 401) {
                response.json().then(data => {
                    alert(data.message || 'Silakan login terlebih dahulu untuk melanjutkan.');
                    window.location.href = data.redirect_url || '{{ route("login") }}';
                });
                return true;
            }
            return false;
        }

        // Update cart count when page loads
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
            
            // Handle add to cart forms
            const addToCartForms = document.querySelectorAll('form[action*="cart/add"]');
            addToCartForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(form);
                    
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (handleAuthError(response)) {
                            return;
                        }
                        
                        if (response.ok) {
                            // Reload page to show success message
                            window.location.reload();
                        } else {
                            // Let the form submit normally for other errors
                            form.submit();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Fallback to normal form submission
                        form.submit();
                    });
                });
            });
        });
    </script>
    
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Initialize Bootstrap Dropdowns -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all dropdowns on the page
        const dropdownElements = document.querySelectorAll('[data-bs-toggle="dropdown"]');
        console.log('Initializing dropdowns:', dropdownElements.length);
        
        dropdownElements.forEach(function(element) {
            try {
                if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
                    new bootstrap.Dropdown(element);
                    console.log('Dropdown initialized:', element);
                } else {
                    console.error('Bootstrap not loaded properly');
                }
            } catch (error) {
                console.error('Error initializing dropdown:', error, element);
            }
        });
        
        // Re-initialize dropdowns when content is dynamically added
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) {
                        const newDropdowns = node.querySelectorAll('[data-bs-toggle="dropdown"]');
                        newDropdowns.forEach(function(element) {
                            try {
                                if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
                                    new bootstrap.Dropdown(element);
                                }
                            } catch (error) {
                                console.error('Error initializing dynamic dropdown:', error);
                            }
                        });
                    }
                });
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
    </script>
    
    @stack('scripts')
</body>

</html>
