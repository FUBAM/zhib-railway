# 📘 ZHIB Community Platform

**ZHIB Community Platform** adalah ekosistem komunitas digital berbasis web yang berfokus di wilayah **Daerah Istimewa Yogyakarta (DIY)**. Platform ini dibangun dengan tujuan utama untuk **mempertemukan orang-orang yang memiliki minat dan hobi yang sama** agar dapat berinteraksi, berkolaborasi, dan berkompetisi dalam lingkungan yang positif.

---

## 🎯 Latar Belakang & Masalah

Pembuatan website ini didasari oleh realitas sosial di mana:
* **Masalah:** Banyak orang merasa kesulitan menemukan teman atau lingkungan baru yang memiliki hobi ("sehobi") di sekitar mereka.
* **Solusi:** ZHIB hadir sebagai jembatan digital untuk menghubungkan individu dengan komunitas yang relevan, memudahkan mereka menyalurkan hobi melalui kegiatan komunitas dan lomba.
* **Target Audiens:** **Semua orang** (inklusif). Siapa saja yang ingin mencari teman baru, mengembangkan hobi, atau mencari event seru di Jogja dan sekitarnya.

---

## 🛠️ Tech Stack

Project ini dibangun menggunakan arsitektur Monolith:

* **Backend Framework:** Laravel 11
* **Database:** MySQL
* **Frontend:** Blade Templating
* **Styling:** **Native CSS** (Tanpa Framework CSS seperti Tailwind/Bootstrap)
* **Asset Management:**
    * CSS per halaman disimpan di `public/css/`
    * Gambar/Aset disimpan di `public/image/`

---

## 👥 Sistem Role & Hak Akses

Sistem ini memiliki aturan hak akses yang spesifik:

1.  **Global Roles (Tabel `users`)**:
    * **Admin:** Memiliki akses penuh ke panel kendali, verifikasi pembayaran, dan manajemen konten.
    * **Member:** Pengguna umum yang mencari komunitas dan event.

2.  **Lokal Role (Tabel Pivot `anggota_komunitas`)**:
    * **Moderator:** Status ini **BUKAN** role global. Seorang `member` bisa diangkat menjadi moderator hanya untuk **satu komunitas spesifik**.

---

## 🚀 Panduan Instalasi & Setup

Ikuti langkah ini agar tampilan Frontend dan logika Backend terhubung dengan benar:

1.  **Clone Repository & Install Dependencies**
    ```bash
    git clone [https://github.com/username/zhib-platform.git](https://github.com/username/zhib-platform.git)
    cd zhib-platform
    composer install
    ```

2.  **Konfigurasi Environment**
    * Salin `.env.example` menjadi `.env`
    * Atur koneksi database (DB_DATABASE, DB_USERNAME, dll).

3.  **Generate Key & Migrasi**
    ```bash
    php artisan key:generate
    php artisan migrate
    ```

4.  **Seeding Data (Wajib)**
    Jalankan seeder agar halaman berita dan kategori tidak kosong.
    ```bash
    php artisan db:seed --class=BeritaSeeder
    ```

5.  **Jalankan Server**
    ```bash
    php artisan serve
    ```

---

## 🛡️ Panduan Fitur Admin (Admin Panel)

Pusat kendali untuk pengelolaan ekosistem ZHIB. Akses via `/admin/dashboard`.

### 1. Dashboard & Monitoring
* **Statistik Real-time:** Memantau pertumbuhan member, event aktif, dan pembayaran masuk.
* **Notifikasi:** Alert untuk pembayaran baru atau laporan pelanggaran.

### 2. Manajemen Pembayaran (Keuangan)
Sistem verifikasi manual untuk Lomba Berbayar.
* **Lihat Bukti:** Admin memeriksa bukti transfer via popup modal.
* **Aksi:**
    * ✅ **Approve:** User resmi terdaftar sebagai peserta.
    * ❌ **Reject:** Pembayaran ditolak (wajib input alasan).

### 3. Manajemen Lomba (Global Events)
* **Fungsi:** Membuat kompetisi skala besar yang terbuka untuk umum.
* **Fitur:** CRUD Lomba, Monitoring peserta, dan Rekap Pemenang.

### 4. Manajemen Komunitas
* **Moderasi:** Admin memantau aktivitas komunitas.
* **Penugasan Moderator:** Admin menunjuk member untuk menjadi pengelola (moderator) komunitas.

### 5. Laporan & Trust Score
* **Sistem:** Menangani laporan penyalahgunaan platform.
* **Sanksi:** Laporan valid akan menurunkan **Trust Score** user terkait.

---

## 👤 Panduan Fitur Member (User)

Fitur untuk pengguna umum yang ingin menyalurkan hobi.

### 1. Eksplorasi & Join Komunitas
* **Cari Teman Sehobi:** User mencari komunitas berdasarkan lokasi (Sleman, Bantul, dll) atau kategori minat.
* **Reward:** Bergabung dengan komunitas memberikan **+20 XP**.

### 2. Gamifikasi Profil
* **XP & Level:** Semakin aktif user (ikut event, gabung komunitas), semakin tinggi Level dan XP mereka.
* **Hall of Fame:** Menampilkan Top 10 User paling aktif di Landing Page.

### 3. Event & Kegiatan
* **Lomba:** Kompetisi skala besar buatan Admin (Bisa berbayar).
* **Kegiatan:** Acara gathering/internal buatan Komunitas (Wajib join komunitas dulu).
* **Klaim XP:** User mendapat tambahan XP setelah menyelesaikan event.

### 4. Chat Komunitas
* **Grup Diskusi:** Wadah komunikasi antar anggota komunitas untuk membahas hobi yang sama.

---

## 🛡️ Panduan Fitur Moderator

Fitur ini hanya muncul di dashboard komunitas bagi user yang ditunjuk sebagai moderator.

### 1. Manajemen Anggota & Chat
Moderator bertanggung jawab menjaga kenyamanan interaksi di grup.
* **Kick Member:** Moderator dapat mengeluarkan anggota yang mengganggu/toxic dari komunitas.
* **Pin Chat:** Moderator dapat menyematkan pesan penting (pengumuman/aturan) agar selalu muncul di atas chat.
* **Moderasi Grup:** Mengirim info di grup *Read-Only*.

### 2. Kelola Kegiatan Internal
Moderator membuat "Kegiatan" (Kopdar, Workshop, Latihan Bareng) khusus untuk anggota komunitasnya.

---

## ⚙️ Panduan Developer (Backend 🤝 Frontend)

Aturan kolaborasi untuk menghubungkan Blade ke Controller:

1.  **Hapus Mock Data:**
    Hapus blok kode dummy `@php $data = [...] @endphp` di file Blade. Biarkan data mengalir dari Controller.

2.  **Variable Injection:**
    Gunakan sintaks Blade `{{ $variabel }}` untuk menampilkan data dinamis.
    * *Benar:* `<h4>{{ $komunitas->nama }}</h4>`

3.  **Jangan Ubah Class CSS:**
    Biarkan nama `class` dan struktur HTML tetap sama agar desain tidak rusak.

4.  **Struktur Folder View:**
    * `resources/views/admin/` -> Panel Admin
    * `resources/views/dashboard/member/` -> Home User
    * `resources/views/komunitas/` -> List & Detail Komunitas
    * `resources/views/events/` -> List & Form Pendaftaran
    * `resources/views/auth/` -> Popup Login/Register

---

## 👨‍💻 Tim Pengembang

Proyek ini dikembangkan oleh mahasiswa **Sistem Informasi** sebagai bagian dari tugas kuliah:

* **Ihsan Zufar Adyatma** – *UI/UX Designer*
    * Perancangan User Interface & User Experience.
* **Habib Farhan** – *Frontend Developer*
    * Implementasi Slicing Design, Blade Templating, dan Native CSS.
* **Muhammad Basiru F. A. Ugar** – *Backend Developer*
    * Database Architecture, API Logic, dan Keamanan Sistem.
* **Afrizal Ibnu Aziz** – *Backend Developer*
    * Integrasi Sistem, Controller Logic, dan Manajemen Server.

---

**ZHIB Community Platform © 2026**