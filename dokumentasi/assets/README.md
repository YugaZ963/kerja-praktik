# Assets Documentation - RAVAZKA Project

## Overview

Dokumentasi ini menjelaskan struktur dan penggunaan assets dalam proyek RAVAZKA, termasuk CSS, JavaScript, dan gambar yang digunakan untuk antarmuka pengguna.

## Directory Structure

```
public/
├── css/
│   ├── app.css          # Main application styles
│   ├── custom.css        # Custom RAVAZKA styles
│   └── seo.css          # SEO optimization styles
├── js/
│   ├── app.js           # Empty main JS file
│   ├── checkout.js      # Checkout functionality
│   └── main.js          # Customer-facing JavaScript
├── images/
│   ├── Product Images (Seragam Sekolah):
│   │   ├── kemeja-sd-pdk.png        # Kemeja SD pendek
│   │   ├── kemeja-sd-pj.png         # Kemeja SD panjang
│   │   ├── kemeja-smp-pdk.png       # Kemeja SMP pendek
│   │   ├── kemeja-smp-pj.png        # Kemeja SMP panjang
│   │   ├── kemeja-sma-pdk.png       # Kemeja SMA pendek
│   │   ├── kemeja-sma-pj.png        # Kemeja SMA panjang
│   │   ├── celana-pj-sd.png         # Celana panjang SD
│   │   ├── celana-pj-smp.png        # Celana panjang SMP
│   │   ├── celana-pj-sma.png        # Celana panjang SMA
│   │   ├── rok-pj-sd-merah.png      # Rok panjang SD merah
│   │   ├── rok-pj-sd-coklat.png     # Rok panjang SD coklat
│   │   ├── rok-pj-sd-hijau.png      # Rok panjang SD hijau
│   │   ├── rok-pj-sd-hitam.png      # Rok panjang SD hitam
│   │   ├── rok-pj-sd-putih.png      # Rok panjang SD putih
│   │   ├── topi-sd.png              # Topi SD
│   │   ├── topi-smp.png             # Topi SMP
│   │   ├── topi-sma.png             # Topi SMA
│   │   ├── sabuk-sd.png             # Sabuk SD
│   │   ├── sabuk-smp.png            # Sabuk SMP
│   │   └── sabuk-sma.png            # Sabuk SMA
│   ├── Celana Variations:
│   │   ├── celana-sd-coklat.png     # Celana SD coklat
│   │   ├── celana-sd-hijau.png      # Celana SD hijau
│   │   ├── celana-sd-hitam.png      # Celana SD hitam
│   │   ├── celana-sd-putih.png      # Celana SD putih
│   │   ├── celana-pj-smp-biru-2.png # Celana SMP biru variant
│   │   ├── celana-pj-smp-sma-coklat.png
│   │   ├── celana-pj-smp-sma-hijau.png
│   │   ├── celana-pj-smp-sma-hitam.png
│   │   ├── celana-pj-smp-sma-putih.png
│   │   ├── celana-pdl-coklat.png    # Celana PDL coklat
│   │   └── celana-pdl-hitam.png     # Celana PDL hitam
│   ├── Pramuka Collection:
│   │   ├── Kemeja-PJ-Pramuka.png    # Kemeja panjang pramuka
│   │   ├── Kemeja-pj-sd.png         # Kemeja panjang SD pramuka
│   │   ├── kemeja-pramuka-siaga-pdk.png
│   │   ├── kemeja-pramuka-siaga-pdk-2.png
│   │   ├── kemeja-pramuka-siaga-pdk(beta).png
│   │   ├── Kerudung-pramuka.png     # Kerudung pramuka
│   │   ├── Sabuk.png                # Sabuk pramuka
│   │   └── Topi.png                 # Topi pramuka
│   └── Brand Assets:
│       ├── logo1.jpeg               # Logo variant 1
│       ├── logo2.jpeg               # Logo variant 2
│       ├── logo3.jpg                # Logo variant 3
│       └── ravazka.jpg              # Main brand image
├── favicon.ico
├── index.php
└── robots.txt

resources/
├── css/
│   ├── app.css          # Tailwind CSS configuration
│   └── custom.css       # Source custom styles
└── js/
    ├── app.js           # Main JS entry point
    └── bootstrap.js     # Axios configuration
```

## CSS Architecture

### 1. app.css (Main Styles)
- **Location**: `public/css/app.css`
- **Size**: 184 lines
- **Purpose**: Core application styling dengan Bootstrap integration

#### Key Features:
- **Bootstrap Icons Import**: CDN integration untuk icon set
- **CSS Custom Properties**: Consistent color scheme
- **Component Styling**: Cards, buttons, tables, forms
- **Focus States**: Enhanced form control focus styling
- **Inventory Specific**: Specialized styles untuk inventory management

#### Color Variables:
```css
:root {
    --primary-color: #0d6efd;
    --secondary-color: #6c757d;
    --success-color: #198754;
    --danger-color: #dc3545;
    --warning-color: #ffc107;
    --info-color: #0dcaf0;
    --light-color: #f8f9fa;
    --dark-color: #212529;
}
```

#### Component Styles:
- **Cards**: Hover effects, shadow transitions
- **Buttons**: Custom primary/success/danger styling
- **Tables**: Enhanced table headers, hover states
- **Forms**: Focus states, validation styling
- **Alerts**: Borderless design dengan rounded corners
- **Pagination**: Custom active states

### 2. custom.css (RAVAZKA Theme)
- **Location**: `public/css/custom.css`
- **Size**: 361 lines
- **Purpose**: Brand-specific styling dan advanced components

#### RAVAZKA Color Palette:
```css
:root {
    /* Primary Colors */
    --primary-green: #0d9d17;
    --primary-blue: #0d6efd;
    --primary-pink: #ca2068;
    --primary-yellow: #fbdd15;
    --primary-white: #fff;
    
    /* Derived Colors */
    --primary-green-light: rgba(13, 157, 23, 0.1);
    --primary-blue-light: rgba(13, 110, 253, 0.1);
    --primary-pink-light: rgba(202, 32, 104, 0.1);
    --primary-yellow-light: rgba(251, 221, 21, 0.1);
}
```

#### Advanced Components:
- **Hero Section**: Gradient backgrounds, responsive typography
- **Filter Section**: Card-based filtering interface
- **Form Sections**: Enhanced form grouping dengan headers
- **Status Badges**: Color-coded status indicators
- **Card Icons**: Circular icon containers dengan color variants
- **Price Formatting**: Consistent price display styling

#### Responsive Design:
- **Mobile Phones** (≤576px): Compact layouts, stacked elements
- **Large Mobile** (577px-768px): Improved spacing
- **Tablets** (769px-992px): Balanced layouts
- **Small Laptops** (993px-1200px): Optimized containers
- **Large Screens** (≥1201px): Full desktop experience
- **Print Styles**: Print-optimized layouts

#### Animation Utilities:
- **Fade In**: Smooth opacity transitions
- **Slide Up**: Upward slide animations
- **Hover Effects**: Interactive element feedback

### 3. seo.css (SEO Optimization)
- **Location**: `public/css/seo.css`
- **Size**: 243 lines
- **Purpose**: SEO-focused styling dan performance optimization

#### Key Features:
- **Breadcrumb Navigation**: SEO-friendly navigation styling
- **Image Optimization**: Lazy loading, responsive images, hover effects
- **Content Structure**: Semantic heading hierarchy (H1-H3)
- **Product Cards**: Optimized product display dengan structured data support
- **Loading States**: Skeleton loading untuk better UX
- **Accessibility**: Skip-to-content links, focus management

#### SEO Components:
```css
/* Breadcrumb SEO styling */
.breadcrumb {
    background-color: #f8f9fa;
    border-radius: 0.375rem;
    padding: 0.75rem 1rem;
}

/* Product image optimization */
.product-image {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 0.5rem;
}

/* SEO content hierarchy */
.seo-content h1 {
    font-size: 2.5rem;
    font-weight: 700;
    line-height: 1.2;
}
```

#### Performance Features:
- **Lazy Loading**: Image placeholder dengan shimmer effect
- **Responsive Design**: Mobile-first approach
- **Loading Skeletons**: Better perceived performance
- **Hover Animations**: Smooth micro-interactions

### 4. Tailwind CSS Integration
- **Location**: `resources/css/app.css`
- **Purpose**: Tailwind CSS configuration dengan custom font
- **Font**: Instrument Sans sebagai primary font family

## JavaScript Architecture

### 1. main.js (Customer Interface)
- **Location**: `public/js/main.js`
- **Size**: 108 lines
- **Purpose**: Customer-facing functionality

#### Core Features:
- **Bootstrap Integration**: Tooltips, popovers initialization
- **Auto-hide Alerts**: 5-second timeout untuk notifications
- **Form Validation**: Enhanced validation dengan visual feedback
- **Auto-submit Filters**: Product filtering dengan auto-submit
- **Price Formatting**: Indonesian Rupiah formatting
- **Loading States**: Button loading indicators
- **AJAX Cart**: Add to cart functionality
- **Error Handling**: Graceful error management

#### Key Functions:
```javascript
// Bootstrap components initialization
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

// Price formatting
new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
}).format(price);

// AJAX cart functionality
fetch('/cart/add', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(data)
});
```

### 2. checkout.js (Checkout Process)
- **Location**: `public/js/checkout.js`
- **Size**: 167 lines
- **Purpose**: Checkout flow management

#### Core Features:
- **Payment Method Selection**: BRI dan DANA integration
- **Shipping Method Selection**: Multiple shipping options
- **Dynamic Cost Calculation**: Real-time shipping cost updates
- **Form Validation**: Checkout form validation
- **Error Suppression**: Auth error handling untuk guest users
- **Visual Feedback**: Selected payment/shipping highlighting

#### Key Functions:
```javascript
// Payment method handling
paymentMethods.forEach(method => {
    method.addEventListener('change', function() {
        // Show/hide payment details
        // Update visual selection
        // Calculate costs
    });
});

// Shipping cost calculation
function updateShippingCost(subtotal) {
    const selectedShipping = document.querySelector('input[name="shipping_method"]:checked');
    // Dynamic cost calculation based on method
}
```

### 3. bootstrap.js (Core Setup)
- **Location**: `resources/js/bootstrap.js`
- **Size**: 4 lines
- **Purpose**: Axios HTTP client configuration

#### Configuration:
```javascript
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
```

### 4. app.js (Entry Point)
- **Location**: `resources/js/app.js`
- **Purpose**: Main JavaScript entry point
- **Content**: Bootstrap import untuk Axios setup

## Image Assets

### Product Categories

#### 1. Seragam Sekolah Reguler
**Kemeja Collection**:
- `kemeja-sd-pdk.png` / `kemeja-sd-pj.png`: Kemeja SD (pendek/panjang)
- `kemeja-smp-pdk.png` / `kemeja-smp-pj.png`: Kemeja SMP (pendek/panjang)
- `kemeja-sma-pdk.png` / `kemeja-sma-pj.png`: Kemeja SMA (pendek/panjang)

**Celana Collection**:
- `celana-pj-sd.png`: Celana panjang SD
- `celana-pj-smp.png`: Celana panjang SMP
- `celana-pj-sma.png`: Celana panjang SMA
- Multi-color variants: coklat, hijau, hitam, putih, biru
- PDL variants: `celana-pdl-coklat.png`, `celana-pdl-hitam.png`

**Rok Collection**:
- `rok-pj-sd-[color].png`: Rok panjang SD dalam berbagai warna
- Available colors: merah, coklat, hijau, hitam, putih

**Aksesoris Collection**:
- `topi-[level].png`: Topi untuk SD, SMP, SMA
- `sabuk-[level].png`: Sabuk untuk SD, SMP, SMA

#### 2. Koleksi Pramuka
- `Kemeja-PJ-Pramuka.png`: Kemeja panjang pramuka
- `kemeja-pramuka-siaga-pdk.png`: Kemeja pramuka siaga
- `Kerudung-pramuka.png`: Kerudung pramuka
- `Sabuk.png` / `Topi.png`: Aksesoris pramuka

### Brand Assets
- **logo1.jpeg**: Logo variant 1 (primary)
- **logo2.jpeg**: Logo variant 2 (alternative)
- **logo3.jpg**: Logo variant 3 (compact)
- **ravazka.jpg**: Main brand hero image

### Image Optimization Strategy
- **Format**: PNG untuk produk (transparency & quality)
- **Format**: JPEG untuk brand assets (compression efficiency)
- **Naming Convention**: `category-level-variant-color.extension`
- **Size Optimization**: Web-optimized untuk fast loading
- **Responsive Support**: Multiple sizes untuk different devices
- **SEO Optimization**: Descriptive alt text dan structured filenames

## Asset Loading Strategy

### CSS Loading
1. **Bootstrap Icons**: CDN loading untuk icon set
2. **app.css**: Core application styles loaded first
3. **custom.css**: Brand-specific RAVAZKA overrides
4. **seo.css**: SEO optimization dan performance styles
5. **Inline Styles**: Component-specific styles dalam Blade templates

#### Loading Priority:
```html
<!-- Critical CSS -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
<link href="{{ asset('css/seo.css') }}" rel="stylesheet">

<!-- External CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
```

### JavaScript Loading
1. **Bootstrap JS**: CDN loading untuk components
2. **main.js**: Customer-facing functionality
3. **checkout.js**: Conditional loading pada checkout pages
4. **Inline Scripts**: Page-specific functionality

### Image Loading
- **Lazy Loading**: Implemented untuk product images
- **Responsive Images**: Multiple sizes untuk different devices
- **Fallback Images**: Default images untuk missing products

## Performance Optimization

### CSS Optimization
- **Minification**: Production CSS minification
- **Critical CSS**: Above-the-fold CSS inlining
- **Unused CSS**: Purging unused styles
- **CSS Variables**: Efficient color management

### JavaScript Optimization
- **Code Splitting**: Separate bundles untuk different pages
- **Lazy Loading**: Conditional script loading
- **Error Handling**: Graceful degradation
- **Caching**: Browser caching strategies

### Image Optimization
- **Compression**: Optimized file sizes
- **WebP Support**: Modern format fallbacks
- **CDN Integration**: Fast image delivery
- **Responsive Images**: Device-appropriate sizes

## Browser Compatibility

### CSS Support
- **CSS Custom Properties**: Modern browser support
- **Flexbox**: Full browser support
- **Grid**: Modern layout support
- **Animations**: CSS3 animation support

### JavaScript Support
- **ES6+**: Modern JavaScript features
- **Fetch API**: Modern HTTP requests
- **Promises**: Asynchronous operations
- **Arrow Functions**: Modern syntax

### Fallbacks
- **Progressive Enhancement**: Core functionality tanpa JavaScript
- **Graceful Degradation**: Fallback untuk older browsers
- **Polyfills**: Support untuk missing features

## Development Workflow

### Asset Compilation
```bash
# Development
npm run dev

# Production build
npm run build

# Watch mode
npm run watch
```

### File Organization
- **Source Files**: `resources/css/` dan `resources/js/`
- **Compiled Files**: `public/css/` dan `public/js/`
- **Version Control**: Source files only dalam Git
- **Build Process**: Automated compilation

## Security Considerations

### CSRF Protection
- **Meta Tags**: CSRF tokens dalam HTML head
- **AJAX Requests**: Token inclusion dalam headers
- **Form Submissions**: Hidden token fields

### Content Security Policy
- **Script Sources**: Trusted domains only
- **Style Sources**: Inline styles dengan nonces
- **Image Sources**: Controlled image domains

### XSS Prevention
- **Output Escaping**: Blade template escaping
- **Input Sanitization**: Client-side validation
- **Content Filtering**: Dangerous content removal

## Testing

### CSS Testing
- **Cross-browser Testing**: Multiple browser validation
- **Responsive Testing**: Device compatibility
- **Accessibility Testing**: WCAG compliance
- **Performance Testing**: Load time optimization

### JavaScript Testing
- **Unit Testing**: Function-level testing
- **Integration Testing**: Component interaction
- **E2E Testing**: Full user flow testing
- **Error Handling**: Exception management

## Maintenance

### Regular Tasks
- **Dependency Updates**: Package version updates
- **Performance Audits**: Speed optimization
- **Accessibility Audits**: Compliance checking
- **Security Audits**: Vulnerability scanning

### Monitoring
- **Performance Metrics**: Load time tracking
- **Error Tracking**: JavaScript error monitoring
- **User Analytics**: Usage pattern analysis
- **Asset Usage**: Unused asset identification

## Future Enhancements

### Planned Features
- **Dark Mode**: Theme switching capability
- **PWA Support**: Progressive Web App features
- **Advanced Animations**: Micro-interactions
- **Component Library**: Reusable UI components

### Performance Improvements
- **HTTP/2 Push**: Critical resource preloading
- **Service Workers**: Offline functionality
- **Image Optimization**: Next-gen formats
- **Bundle Optimization**: Tree shaking implementation

### Accessibility Enhancements
- **Screen Reader Support**: Enhanced ARIA labels
- **Keyboard Navigation**: Full keyboard accessibility
- **High Contrast Mode**: Accessibility theme
- **Focus Management**: Improved focus indicators

Dokumentasi ini akan terus diperbarui seiring dengan perkembangan assets dalam proyek RAVAZKA.