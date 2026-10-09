# TAPGO Travel ✈️

> **Travel & Tour Booking Platform — Currently in Development**

TAPGO Travel adalah project website **travel dan tour booking** yang sedang dikembangkan sebagai platform untuk membantu pengguna menemukan dan merencanakan perjalanan dengan pengalaman browsing yang modern, informatif, dan mudah digunakan.

Project ini dibangun sebagai bagian dari pengembangan portfolio **Web Development**, dengan fokus pada full-stack development, responsive design, dan struktur aplikasi yang scalable.

---

## 🚧 Project Status

**TAPGO Travel is currently under development.**

Beberapa bagian website masih dalam proses pengerjaan, termasuk penyempurnaan UI/UX, halaman destinasi, detail perjalanan, dan fitur booking.

> ⚠️ Karena project masih dalam tahap development, beberapa fitur dan halaman mungkin belum tersedia atau masih mengalami perubahan.

---

## ✨ Planned Features

Beberapa fitur yang direncanakan untuk TAPGO Travel:

* 🏠 Modern travel homepage
* 🔎 Search destinations and travel packages
* 🗺️ Explore travel destinations
* 🏨 Tour & travel package listings
* 📄 Travel package detail pages
* 📅 Booking flow
* 👤 User account & profile
* ❤️ Wishlist / favorite destinations
* ⭐ Reviews & ratings
* 🧾 E-ticket & invoice
* 🛠️ Admin dashboard (bookings, customers, destinations, payments, schedules, trips)
* 📱 Fully responsive interface
* 🎨 Modern and intuitive UI/UX

> Fitur dapat berubah selama proses pengembangan.

---

## 🎨 Design Direction

TAPGO Travel mengambil inspirasi dari beberapa modern travel booking websites, terutama dalam hal:

* Clean travel-focused layout
* Destination discovery
* Search and filtering experience
* Large visual imagery
* Card-based travel listings
* Clear booking flow
* Responsive design
* Modern typography and spacing

Design akan dikembangkan kembali dengan identitas **TAPGO Travel** dan tidak dimaksudkan sebagai salinan langsung dari website referensi.

---

## 🛠️ Tech Stack

Project ini menggunakan beberapa teknologi berikut:

### Backend

* **Laravel 12** (PHP ^8.2)
* **Laravel Tinker**
* **MariaDB / MySQL**

### Frontend

* **Blade Templates**
* **Bootstrap 5**
* **Sass**
* **Tailwind CSS 4**
* **Axios**

### Build Tool

* **Vite** (via `laravel-vite-plugin`)

### Development Tools

* **Composer**
* **NPM**
* **Git**
* **GitHub**
* **Visual Studio Code**
* **Laravel Pint** (code style)
* **PHPUnit** (testing)

---

## 📂 Project Structure

Struktur project mengikuti standar arsitektur **Laravel**.

```text
TAPGO-Travel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Admin/
│   │   └── Middleware/
│   ├── Models/
│   ├── Providers/
│   ├── Services/
│   └── Support/
│
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── resources/
│   ├── css/
│   ├── js/
│   ├── sass/
│   └── views/
│       ├── admin/
│       ├── auth/
│       ├── bookings/
│       ├── components/
│       ├── destinations/
│       ├── layouts/
│       ├── pages/
│       ├── tickets/
│       └── trips/
│
├── routes/
│   └── web.php
│
├── public/
├── config/
├── tests/
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

Struktur dapat berubah mengikuti perkembangan project.

---

## 🚀 Getting Started

Clone repository:

```bash
git clone https://github.com/MaulanaYusufzidan/TAPGO-Travel.git
```

Masuk ke directory project:

```bash
cd TAPGO-Travel
```

Install PHP dependencies:

```bash
composer install
```

Install JS dependencies:

```bash
npm install
```

Copy environment file dan generate application key:

```bash
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi database di `.env` (default menggunakan MariaDB/MySQL), lalu jalankan migrasi:

```bash
php artisan migrate
```

Jalankan development server (server, queue, log, dan Vite sekaligus):

```bash
composer run dev
```

Atau jalankan secara terpisah:

```bash
php artisan serve
npm run dev
```

Kemudian buka:

```text
http://localhost:8000
```

---

## 🧩 Development Roadmap

### Phase 1 — Foundation

* [x] Project setup (Laravel)
* [x] Database structure (migrations, models)
* [x] Base layout & Blade components

### Phase 2 — UI/UX

* [x] Homepage
* [x] Navigation & layout
* [ ] Hero section polish
* [ ] Responsive layout refinement
* [ ] Animations & interactions

### Phase 3 — Travel Experience

* [x] Destination pages
* [x] Trip listing & detail
* [ ] Search
* [ ] Filtering refinement
* [x] Booking interface (checkout, confirmation, invoice)

### Phase 4 — Application Features

* [x] Authentication (login, register, password reset)
* [ ] User profile
* [ ] Wishlist
* [ ] Reviews
* [x] Booking management
* [x] Admin dashboard (bookings, customers, destinations, payments, schedules, trips)

### Phase 5 — Finalization

* [ ] Performance optimization
* [ ] Accessibility improvements
* [ ] Responsive testing
* [ ] Production deployment

---

## 📸 Preview

> **Preview will be added once the main interface is completed.**

The current version is still actively being developed.

---

## 🌐 Repository

**GitHub:**
https://github.com/MaulanaYusufzidan/TAPGO-Travel

---

## 👨‍💻 Developer

**Maulana Yusuf Zidan**

Information Systems Student
Universitas Bina Sarana Informatika

Focused on:

* Web Development
* Full-Stack Development
* UI/UX Design

---

## 📄 License

This project is currently developed as a personal portfolio and learning project.

The project structure, design, and implementation are subject to change during development.
