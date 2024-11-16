# CatatAja RESTful API

API ini menyediakan endpoint untuk mengelola data pengguna (_user_) dan catatan (_note_). API ini memungkinkan pengguna untuk membuat, membaca, memperbarui, dan menghapus catatan, serta mengelola akun pengguna.

## Kelompok 2

Nama anggota kelompok:

-   Muhammad Fahreza (10220053)
-   Bagus Hary (10220037)
-   Galeh Pamungkas (10220077)
-   Sirajuddin Ahmad Kurniawan (10220079)
-   Thomas Febry Cahyono (10220063)

## Teknologi yang Digunakan

Berikut teknologi yang kami gunakan untuk membuat RESTful API ini:

-   PHP
-   Laravel
-   MySQL
-   RESTful API

## Fitur

-   **User API**:

    -   Registrasi Pengguna
    -   Login Pengguna
    -   Mendapatkan Data Pengguna Saat Ini
    -   Memperbarui Data Pengguna
    -   Logout Pengguna

-   **Note API**:
    -   Menambahkan Catatan
    -   Mendapatkan Daftar Catatan
    -   Mencari Catatan
    -   Memperbarui Catatan
    -   Menghapus Catatan

## Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan API:

1. Clone repository ini.
2. Instalasi Dependensi, Jalankan perintah `composer install` untuk menginstal semua dependensi yang diperlukan.
3. Konfigurasi Environment, Salin file `.env.example` menjadi `.env`.
4. Generate Key Aplikasi, Jalankan perintah `php artisan key:generate` untuk menghasilkan kunci aplikasi
5. Migrasi Database, Untuk membuat tabel di database, jalankan perintah `php artisan migrate`.
6. Menjalankan Server, Jalankan server pengembangan Laravel dengan perintah `php artisan serve`. Server akan berjalan di `http://localhost:8000`.

## Penggunaan API

Setelah menginstal dan menjalankan API, Anda dapat menggunakan endpoint berikut untuk berinteraksi dengan API:

-   **User Endpoints**:

    -   `POST /api/users` Menambahkan pengguna baru. Mengirimkan data pengguna yang diperlukan untuk pendaftaran.
    -   `POST /api/users/login` Melakukan login pengguna. Mengirimkan kredensial pengguna (seperti email dan password) untuk otentikasi.
    -   `GET /api/users/current` Mendapatkan data pengguna saat ini yang sedang login. Mengembalikan informasi pengguna berdasarkan token yang diberikan.
    -   `PATCH /api/users/current` Memperbarui data pengguna saat ini yang sedang login. Mengirimkan data yang akan diperbarui (seperti nama atau password saat ini dan password baru).
    -   `DELETE /api/users/logout` Melakukan logout pengguna. Menghapus sesi token otentikasi.

-   **Note Endpoints**:
    -   `POST /api/notes` Menambahkan catatan baru. Mengirimkan data catatan yang diperlukan untuk membuat catatan.
    -   `GET /api/notes` Mendapatkan daftar semua catatan yang dimiliki oleh pengguna saat ini.
    -   `GET /api/notes/search` Mencari catatan berdasarkan kriteria tertentu. Menggunakan parameter keyword untuk memfilter catatan.
    -   `PUT /api/notes/{id}` Memperbarui catatan berdasarkan ID. Mengirimkan data yang akan diperbarui untuk catatan dengan ID yang ditentukan.
    -   `DELETE /api/notes/{id}` Menghapus catatan berdasarkan ID. Menghapus catatan yang memiliki ID yang ditentukan dari database.
