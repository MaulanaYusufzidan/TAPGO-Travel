# TAPGO Travel ✈️

> **Travel & Tour Booking Platform — Currently in Development**

TAPGO Travel adalah platform **travel & tour booking** berbasis web yang dikembangkan untuk membantu pengguna menemukan destinasi, melihat paket perjalanan, mendapatkan informasi perjalanan, dan melakukan proses pemesanan melalui satu platform.

Project ini dikembangkan sebagai bagian dari portfolio **Web Development**, dengan fokus pada pengembangan aplikasi web, database management, authentication, booking workflow, dan responsive user interface.

---

## 🚧 Project Status

**TAPGO Travel is currently under active development.**

Pengembangan dilakukan secara bertahap mulai dari struktur aplikasi, authentication, destination management, travel packages, booking system, hingga payment integration.

> ⚠️ Beberapa fitur dan halaman masih dalam proses pengembangan dan dapat mengalami perubahan.

---

## ✨ Features

Fitur yang dikembangkan dalam TAPGO Travel meliputi:

* 🏠 Travel-focused homepage
* 🔎 Destination & travel package search
* 🗺️ Destination exploration
* 🏨 Travel & tour package listings
* 📄 Travel package detail pages
* 📅 Booking system
* 👤 User authentication & profile
* ❤️ Wishlist / favorite destinations
* ⭐ Reviews & ratings
* 💳 Payment integration
* 🔔 Application notifications
* 🛡️ Role & permission management
* 📱 Responsive interface
* 🎨 Modern travel-focused UI/UX

Fitur dapat terus bertambah dan berubah selama proses development.

---

## 🎨 Design Direction

TAPGO Travel menggunakan pendekatan desain **modern travel booking platform** dengan fokus pada pengalaman pengguna saat mencari dan mengeksplorasi perjalanan.

Design direction meliputi:

* Clean and spacious layout
* Large destination imagery
* Destination discovery
* Search & filtering experience
* Travel package cards
* Clear information hierarchy
* Intuitive navigation
* Responsive design
* Consistent typography and spacing
* Smooth user interactions

TAPGO Travel menggunakan beberapa website travel modern sebagai **referensi visual dan user experience**, kemudian dikembangkan kembali dengan identitas dan struktur aplikasi TAPGO Travel sendiri.

---

## 🛠️ Tech Stack

### Backend

* **Laravel 12**
* **PHP 8.2+**
* **Eloquent ORM**
* **Laravel Breeze**
* **Spatie Laravel Permission**

### Frontend

* **Blade**
* **Bootstrap 5**
* **SCSS / Sass**
* **JavaScript**
* **Alpine.js**
* **Vite**

### Database & Services

* **MySQL**
* **Midtrans Sandbox**
* **Laravel Storage**
* **Laravel Notifications**

### Development Tools

* **Git**
* **GitHub**
* **Visual Studio Code**
* **XAMPP**

---

## 📂 Project Structure

TAPGO Travel menggunakan struktur aplikasi **Laravel** dengan pendekatan modular untuk memisahkan logic, presentation layer, database, dan asset frontend.

```text
TAPGO-Travel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Requests/
│   │
│   ├── Models/
│   ├── Services/
│   ├── Jobs/
│   ├── Events/
│   └── Notifications/
│
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   ├── components/
│   │   └── pages/
│   │
│   ├── css/
│   └── js/
│
├── routes/
│   ├── web.php
│   └── api.php
│
├── public/
│   ├── images/
│   └── assets/
│
├── storage/
├── tests/
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

> Struktur folder dapat berubah mengikuti perkembangan aplikasi.

---

## 🚀 Getting Started

### Prerequisites

Pastikan environment berikut sudah tersedia:

* PHP 8.2+
* Composer
* Node.js & npm
* MySQL
* XAMPP atau local PHP development environment

### 1. Clone Repository

```bash
git clone https://github.com/MaulanaYusufzidan/TAPGO-Travel.git
```

### 2. Navigate to Project

```bash
cd TAPGO-Travel
```

### 3. Install PHP Dependencies

```bash
composer install
```

### 4. Install Frontend Dependencies

```bash
npm install
```

### 5. Setup Environment

Copy file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 6. Configure Database

Buat database MySQL, kemudian sesuaikan konfigurasi pada file `.env`:

```env
DB_DATABASE=tapgo_travel
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Run Database Migration

```bash
php artisan migrate
```

Jika project menyediakan seed data:

```bash
php artisan db:seed
```

atau:

```bash
php artisan migrate --seed
```

### 8. Build Frontend Assets

Untuk development:

```bash
npm run dev
```

### 9. Run Laravel Server

```bash
php artisan serve
```

Kemudian buka:

```text
http://127.0.0.1:8000
```

---

## 🧩 Development Roadmap

### Phase 1 — Foundation

* [x] Laravel project setup
* [x] Database structure
* [x] Authentication foundation
* [x] Frontend asset configuration
* [x] Initial application structure

### Phase 2 — Travel Interface

* [x] Homepage
* [x] Navigation
* [x] Hero section
* [x] Destination section
* [ ] Travel package cards
* [ ] Responsive interface
* [ ] Interactive components

### Phase 3 — Travel Management

* [ ] Destination management
* [ ] Travel package management
* [ ] Search functionality
* [ ] Filtering
* [ ] Package detail
* [ ] Booking workflow

### Phase 4 — User & Booking

* [ ] User profile
* [ ] Wishlist
* [ ] Reviews & ratings
* [ ] Booking management
* [ ] Payment integration
* [ ] Booking notifications

### Phase 5 — Administration

* [ ] Admin dashboard
* [ ] User management
* [ ] Destination management
* [ ] Package management
* [ ] Booking management
* [ ] Role & permission management

### Phase 6 — Finalization

* [ ] Validation & error handling
* [ ] Performance optimization
* [ ] Security improvements
* [ ] Responsive testing
* [ ] Production deployment

---

## 📸 Preview

> **Preview will be added as the main interface reaches completion.**

TAPGO Travel is currently under active development, and screenshots will be added once the primary interface and booking experience are ready.

---

## 🌐 Repository

**GitHub Repository**

[MaulanaYusufzidan/TAPGO-Travel](https://github.com/MaulanaYusufzidan/TAPGO-Travel?utm_source=chatgpt.com)

---

## 👨‍💻 Developer

### Maulana Yusuf Zidan

**Information Systems Student**
Universitas Bina Sarana Informatika

Interested in:

* Web Development
* Full-Stack Development
* UI/UX Design
* Database Management
* Modern Web Applications

---

## 📄 License

TAPGO Travel is developed as a **personal portfolio and learning project**.

The design, structure, features, and implementation may change throughout the development process.
