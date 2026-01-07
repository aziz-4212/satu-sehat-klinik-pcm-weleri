# Satu Sehat - Modern Admin Template

Template admin yang modern dan responsif untuk aplikasi **Satu Sehat**, sistem input data rekam medis ke platform Satu Sehat Kementerian Kesehatan RI.

![Satu Sehat Logo](https://via.placeholder.com/200x60/1B73E8/FFFFFF?text=SATU+SEHAT)

## 📋 Fitur Utama

### 🎨 Design Modern

-   **Clean & Minimalis**: Interface yang bersih dan mudah digunakan
-   **Responsive Design**: Optimal di desktop, tablet, dan mobile
-   **Modern UI Components**: Card, button, dan form dengan desain terkini
-   **Gradient Colors**: Kombinasi warna biru dan hijau sesuai identitas Satu Sehat

### 🔧 Fungsionalitas

-   **Dashboard Interaktif**: Statistik real-time dengan chart dan grafik
-   **Notifikasi Real-time**: Sistem notifikasi terintegrasi
-   **Data Tables**: Tabel data dengan fitur pencarian, filter, dan export
-   **Form Validation**: Validasi form yang komprehensif
-   **Auto-save**: Penyimpanan otomatis untuk mencegah kehilangan data

### 🚀 Performa

-   **Fast Loading**: Optimasi loading dengan lazy loading dan caching
-   **Smooth Animations**: Animasi yang halus dan tidak mengganggu UX
-   **PWA Ready**: Siap untuk Progressive Web App

## 🛠️ Teknologi Yang Digunakan

-   **Laravel 9+** - PHP Framework
-   **AdminLTE 3** - Admin Template Base
-   **Bootstrap 4** - CSS Framework
-   **jQuery** - JavaScript Library
-   **Chart.js** - Data Visualization
-   **DataTables** - Advanced Tables
-   **SweetAlert2** - Beautiful Alerts
-   **Toastr** - Notifications
-   **Select2** - Enhanced Select Boxes
-   **Summernote** - WYSIWYG Editor

## 📁 Struktur File

```
satu-sehat/
├── resources/views/layouts/
│   ├── app.blade.php                 # Layout utama
│   └── partials/
│       ├── _head.blade.php           # Meta tags & CSS
│       ├── _navbar.blade.php         # Navigation bar
│       ├── _sidebar.blade.php        # Sidebar menu
│       ├── _footer.blade.php         # Footer
│       └── _script.blade.php         # JavaScript files
├── public/assets/
│   ├── css/
│   │   └── satu-sehat-theme.css     # Custom CSS theme
│   └── js/
│       └── satu-sehat-theme.js      # Custom JavaScript
└── resources/views/
    ├── home.blade.php               # Dashboard utama
    └── sample-patient-list.blade.php # Contoh halaman daftar pasien
```

## 🎨 Color Scheme

```css
:root {
    --primary-color: #1b73e8; /* Satu Sehat Blue */
    --secondary-color: #52c997; /* Satu Sehat Green */
    --success-color: #198754; /* Success Green */
    --warning-color: #ffc107; /* Warning Yellow */
    --danger-color: #dc3545; /* Danger Red */
    --info-color: #0dcaf0; /* Info Cyan */
    --light-color: #f8f9fa; /* Light Gray */
    --dark-color: #212529; /* Dark Gray */
}
```

## 📱 Responsive Breakpoints

-   **Mobile**: < 768px
-   **Tablet**: 768px - 1024px
-   **Desktop**: > 1024px

## 🔧 Instalasi & Setup

### 1. Clone atau Download Template

```bash
# Jika menggunakan Git
git clone [repository-url]

# Atau download dan extract file
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies (jika ada)
npm install
```

### 3. Setup Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Setup database di .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=satu_sehat
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Database Migration

```bash
php artisan migrate
php artisan db:seed # Jika ada seeder
```

### 5. Jalankan Aplikasi

```bash
php artisan serve
```

Buka browser dan akses: `http://localhost:8000`

## 📋 Menu & Fitur

### 🏠 Dashboard Utama

-   **Overview Statistik**: Total pasien, data tersinkron, dokter aktif
-   **Quick Actions**: Tombol aksi cepat untuk fungsi utama
-   **Recent Activities**: Aktivitas terbaru sistem
-   **Charts & Graphs**: Visualisasi data dalam bentuk grafik

### 🏥 Dashboard Layanan

-   **Rawat Jalan**: Dashboard khusus rawat jalan
-   **Rawat Inap**: Dashboard khusus rawat inap
-   **IGD/Emergency**: Dashboard untuk instalasi gawat darurat

### 📋 Rekam Medis

-   **Resume Medis**: Input dan kelola resume medis pasien
-   **Keluhan Utama**: Manajemen condition/keluhan utama
-   **Riwayat Alergi**: Allergy intolerance management
-   **Observasi**: Observation data management

### 👥 Master Data & Resource

-   **Data Pasien**: CRUD pasien dengan validasi NIK
-   **Pasien NIK Tidak Terdaftar**: Manajemen pasien tanpa NIK valid
-   **Data Dokter**: Practitioner management
-   **Organisasi**: Organization data
-   **Lokasi**: Location management

### 💊 Master Obat & Medical

-   **Master LOINC**: Mapping LOINC codes
-   **Bentuk Obat**: Drug forms management
-   **KFA Obat**: KFA medication codes
-   **Medication**: Medication master data

### ✅ Verifikasi

-   **KYC Verifikasi**: Know Your Customer untuk dokter

### ⚙️ Sistem

-   **Konfigurasi**: System configuration
-   **Sinkronisasi Data**: Sync dengan Satu Sehat platform

## 🎯 Customization

### Mengubah Warna Tema

Edit file `public/assets/css/satu-sehat-theme.css`:

```css
:root {
    --primary-color: #YourColor;
    --secondary-color: #YourColor;
}
```

### Menambah Menu Sidebar

Edit file `resources/views/layouts/partials/_sidebar.blade.php`:

```blade
<li class="nav-item">
    <a href="{{ route('your.route') }}" class="nav-link">
        <i class="nav-icon fas fa-your-icon"></i>
        <p>Menu Baru</p>
    </a>
</li>
```

### Custom JavaScript

Tambahkan kode di `public/assets/js/satu-sehat-theme.js` atau buat file baru.

## 📊 Dashboard Components

### Statistics Cards

```blade
<div class="widget-stat">
    <div class="d-flex align-items-center">
        <div class="widget-stat-icon">
            <i class="fas fa-your-icon"></i>
        </div>
        <div class="ml-3">
            <div class="widget-stat-number">123</div>
            <div class="widget-stat-label">Your Label</div>
        </div>
    </div>
</div>
```

### Modern Cards

```blade
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-icon mr-2"></i>Card Title
        </h3>
    </div>
    <div class="card-body">
        <!-- Content -->
    </div>
</div>
```

### Data Tables

```blade
<table class="table table-bordered table-striped table-modern">
    <thead>
        <tr>
            <th>Column 1</th>
            <th>Column 2</th>
        </tr>
    </thead>
    <tbody>
        <!-- Data rows -->
    </tbody>
</table>
```

## 🔐 Security Features

-   **CSRF Protection**: Laravel CSRF tokens
-   **Input Validation**: Client & server-side validation
-   **XSS Protection**: Output escaping
-   **SQL Injection Prevention**: Eloquent ORM & prepared statements

## 📱 Mobile Experience

-   **Touch-friendly**: Optimized for touch interfaces
-   **Swipe Gestures**: Natural mobile interactions
-   **Responsive Tables**: Horizontal scroll on small screens
-   **Mobile Menu**: Collapsible navigation

## 🚀 Performance Optimization

-   **CSS Minification**: Compressed stylesheets
-   **JavaScript Optimization**: Minified scripts
-   **Image Optimization**: WebP format support
-   **Lazy Loading**: Images loaded on demand
-   **Caching**: Browser & server-side caching

## 📈 Analytics & Monitoring

-   **User Activity**: Track user interactions
-   **Performance Metrics**: Page load times
-   **Error Monitoring**: JavaScript error tracking
-   **Usage Statistics**: Feature usage analytics

## 🔧 Troubleshooting

### Common Issues

**1. CSS tidak loading**

```bash
# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

**2. JavaScript errors**

-   Pastikan semua dependencies sudah ter-load
-   Check browser console untuk error details
-   Pastikan jQuery load sebelum custom scripts

**3. Responsive issues**

-   Test di browser developer tools
-   Check viewport meta tag di head section

## 📞 Support & Dokumentasi

### Resources

-   **Laravel Documentation**: https://laravel.com/docs
-   **AdminLTE Documentation**: https://adminlte.io/docs
-   **Bootstrap Documentation**: https://getbootstrap.com/docs

### Bantuan Teknis

-   📧 Email: support@satusehat.kemkes.go.id
-   📱 WhatsApp: +62-XXX-XXXX-XXXX
-   🌐 Website: https://satusehat.kemkes.go.id

## 📄 License

Template ini dikembangkan untuk keperluan sistem Satu Sehat Kementerian Kesehatan RI.

---

**Satu Sehat** - _Satu Data, Satu Standar, Satu Kesehatan untuk Indonesia_

🏥 _Kementerian Kesehatan Republik Indonesia_ | 🇮🇩 _Indonesia_
