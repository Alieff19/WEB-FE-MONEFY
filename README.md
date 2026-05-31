# <p align="center"><br><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="150" alt="Laravel Logo"><br>Monefy Web Dashboard</p>

<p align="center">
  <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-v12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12"></a>
  <a href="https://php.net"><img src="https://img.shields.io/badge/PHP-%5E8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2"></a>
  <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind_CSS-v4.0-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS v4"></a>
  <a href="https://vite.dev"><img src="https://img.shields.io/badge/Vite-v7.0-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite 7"></a>
  <a href="#"><img src="https://img.shields.io/badge/Frontend_Port-8001-blueviolet?style=for-the-badge" alt="Port 8001"></a>
</p>

---

**Monefy** is a sleek, modern, and highly responsive personal financial management web application. This folder (`Frontend-Web`) serves as the client-facing application, providing a premium and interactive dashboard to manage wallets, view analytics, pay bills, track wishlists, and record transactions. It connects to the **Monefy Backend API** to achieve seamless financial orchestration.

Designed with modern UI/UX paradigms—featuring dark glassmorphism, dynamic AJAX-driven filters, responsive components, and rich micro-interactions.

---

## Key Features

- **Dynamic Dashboard**: Surfacing total balance, real-time monthly income, and daily expenses with rich formatting.
- **AJAX-based Fast Filtering**: Swap recent transaction lists dynamically (by *Day, Week, Month, Year, All*) using standard JavaScript Fetch API without page reloads.
- **Wallet Management**: Complete CRUD operations for multiple wallets (Cash, Bank Accounts, E-Wallets) featuring live CSS previews of cards.
- **Bills Management**: Track premium utility bills, select dynamic payment wallets, validate balances, and log history.
- **Smart Wishlists**: View financial goal progress, saving tracking, and modern checkouts.
- **Advanced Analytics**: Clear category-wise visual breakdowns of user expenditure.
- **Secure Client Portal**: Custom register/login panels with seamless session state persistence.

---

## Technology Stack

| Component | Technology | Version |
| :--- | :--- | :--- |
| **Framework** | Laravel | `^12.0` |
| **Asset Bundler** | Vite | `^7.0.7` |
| **Styling** | TailwindCSS & Vanilla CSS | `^4.0.0` |
| **Scripting** | JavaScript (ES6+ / Fetch API) | Native |
| **Icons** | Bootstrap Icons | CDN |

---

## Directory Structure

Here are the key frontend files and directories to assist in development:

```bash
Frontend-Web/
├── app/
│   └── Http/
│       └── Controllers/      # Handles API communication & page rendering
├── config/                   # Application configs
├── public/
│   └── assets/               # Custom assets (CSS styles, JS modules, images)
├── resources/
│   ├── css/                  # Entry CSS files compiled by Vite
│   ├── js/                   # Entry JS files compiled by Vite
│   └── views/                # Blade Templates (The User Interface)
│       ├── auth/             # Login & Register views
│       ├── layouts/          # Base App Shell layout
│       └── *.blade.php       # Dashboard, Wallets, Bills, Wishlist views
├── routes/
│   └── web.php               # Frontend routing endpoints
├── vite.config.js            # Vite configuration for asset optimization
└── .env                      # Application environment settings
```

---

## System Prerequisites

Ensure you have the following installed on your machine:
1. **PHP** `>= 8.2`
2. **Composer** `>= 2.x`
3. **Node.js** `>= 18.x` & **NPM**

*Note: Ensure your **Monefy Backend API** is up and running (default: `http://127.0.0.1:8000`) before running the frontend.*

---

## Step-by-Step Installation & Setup

Follow these simple instructions to set up the frontend project locally:

### 1. Navigasi ke Folder Frontend
Buka terminal Anda dan masuk ke direktori frontend:
```bash
cd "Frontend-Web"
```

### 2. Install Dependencies
Instal paket php composer dan paket javascript npm:
```bash
# Install PHP Dependencies
composer install

# Install Javascript Packages
npm install
```

### 3. Konfigurasi Environment File
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

Buka file `.env` di editor kode Anda, lalu sesuaikan URL API Backend Anda (letakkan di baris paling bawah):
```env
BACKEND_API_URL=http://127.0.0.1:8000/api
```

### 4. Generate App Key
Generate key enkripsi unik untuk aplikasi Laravel Anda:
```bash
php artisan key:generate
```

### 5. Kompilasi Aset (Vite)
Build stylesheet TailwindCSS dan aset JavaScript lainnya:
```bash
npm run build
```

---

## Menjalankan Aplikasi Frontend (Port 8001)

Untuk menjalankan server frontend secara lokal agar **berjalan di port 8001**, jalankan perintah Laravel Artisan berikut di terminal Anda:

```bash
php artisan serve --port=8001
```

Setelah server aktif, Anda dapat langsung mengakses aplikasi melalui peramban (browser) di alamat:
### **[http://localhost:8001](http://localhost:8001)**

---

## Mode Pengembangan (Development Mode)

Jika Anda ingin melakukan perubahan pada tampilan atau CSS dan melihat hasilnya secara langsung (Hot Module Replacement), jalankan server development Vite di terminal terpisah:

```bash
npm run dev
```
