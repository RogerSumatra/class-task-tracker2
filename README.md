# Class Task Tracker

Aplikasi Manajemen Tugas Kelas berbasis **Laravel 13** dan **PHP 8.4** dengan frontend **Blade + Tailwind CSS 4**.

---

## Fitur Utama

1. **Autentikasi & Autorisasi Dua Peran**:
   - **Guru**: membuat, mengedit, menghapus tugas kelas, dan memantau siswa yang telah menyelesaikan tugas.
   - **Siswa**: melihat daftar tugas dan menandai/membatalkan status selesai tugasnya sendiri. Siswa tidak memiliki izin membuat, mengubah, atau menghapus tugas.
   - Password di-hash menggunakan algoritma **Bcrypt** (12 rounds).
2. **Manajemen Tugas Lengkap**:
   - Atribut: Judul, Deskripsi, Tenggat Waktu (Due Date), dan Nama Kelas.
3. **Pencarian, Filter, dan Paginasi**:
   - Pencarian berdasarkan judul tugas (`search`).
   - Filter status penyelesaian (`status=selesai` atau `status=belum`).
   - Paginasi otomatis (10 tugas per halaman).
4. **Database Relasional**:
   - Migrasi dan skema relasional di MySQL dengan foreign keys dan cascade on delete.
   - Database seeder lengkap dengan akun percontohan dan data tugas.

---

## Akun Contoh untuk Login

Aplikasi sudah dilengkapi dengan seeder akun contoh:

| Peran | Nama | Email | Password |
|---|---|---|---|
| **Guru** | Budi Santoso | `guru@example.com` | `password` |
| **Guru 2** | Siti Rahayu | `guru2@example.com` | `password` |
| **Siswa** | Andi Pratama | `siswa@example.com` | `password` |
| **Siswa 2** | Dewi Lestari | `siswa2@example.com` | `password` |

---

## Cara Menjalankan Aplikasi dari Nol

### Prasyarat
- PHP >= 8.3 (direkomendasikan PHP 8.4) dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`
- Composer 2.x
- Node.js >= 18 & npm
- MySQL Server

### Langkah-langkah Instalasi

1. **Clone repositori**:
   ```bash
   git clone <url-repository>
   cd class-task-tracker2
   ```

2. **Install dependensi PHP & Node.js**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment**:
   Salin file konfigurasi environment:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan sesuaikan kredensial database MySQL Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=class_task_tracker
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Buat database MySQL**:
   Pastikan database `class_task_tracker` sudah dibuat di MySQL:
   ```sql
   CREATE DATABASE class_task_tracker;
   ```

5. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

6. **Jalankan Migrasi & Seeder**:
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Build Aset Frontend**:
   ```bash
   npm run build
   ```

8. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser di: **http://127.0.0.1:8000**

---

## Daftar Endpoint REST API

### 1. Autentikasi (`/api/auth`)

| Method | Endpoint | Auth | Role | Deskripsi |
|---|---|---|---|---|
| `POST` | `/api/auth/register` | Public | - | Registrasi user baru (`name`, `email`, `password`, `password_confirmation`, `role`) |
| `POST` | `/api/auth/login` | Public | - | Login dan mendapatkan token Sanctum (`email`, `password`) |
| `POST` | `/api/auth/logout` | Bearer Token | All | Logout dan mencabut token saat ini |
| `GET` | `/api/user` | Bearer Token | All | Mendapatkan profil user yang sedang login |

### 2. Tugas Kelas (`/api/tasks`)

| Method | Endpoint | Auth | Role | Deskripsi |
|---|---|---|---|---|
| `GET` | `/api/tasks` | Bearer Token | All | Daftar tugas (mendukung query `search`, `status`, dan `page`) |
| `POST` | `/api/tasks` | Bearer Token | **Guru** | Membuat tugas baru (`title`, `description`, `due_date`, `class_name`) |
| `GET` | `/api/tasks/{id}` | Bearer Token | All | Melihat detail tugas tertentu |
| `PUT` | `/api/tasks/{id}` | Bearer Token | **Guru** | Mengubah tugas |
| `DELETE` | `/api/tasks/{id}` | Bearer Token | **Guru** | Menghapus tugas |

#### Query Parameters pada `GET /api/tasks`:
- `search`: Mencari judul tugas (misal: `?search=Matematika`)
- `status`: Filter status penyelesaian siswa (`selesai` atau `belum`)
- `page`: Nomor halaman paginasi (misal: `?page=2`)

### 3. Penyelesaian Tugas (`/api/tasks/{id}/complete`)

| Method | Endpoint | Auth | Role | Deskripsi |
|---|---|---|---|---|
| `POST` | `/api/tasks/{id}/complete` | Bearer Token | **Siswa** | Menandai tugas sebagai selesai |
| `DELETE` | `/api/tasks/{id}/complete` | Bearer Token | **Siswa** | Membatalkan status selesai pada tugas |

---

## Keputusan Desain

1. **Relasi Many-to-Many untuk Status Penyelesaian Tugas (`task_completions`)**:
   - Status tugas dibuat sebagai tabel relasional terpisah (`task_completions`) dengan `task_id`, `user_id`, dan `completed_at`.
   - Menggunakan unique constraint `['task_id', 'user_id']` sehingga satu siswa tidak dapat menandai tugas ganda dan integritas data terjamin.
2. **Autorisasi Berlapis (Defense in Depth)**:
   - Validasi peran diterapkan pada tingkat **Route Middleware** (`GuruMiddleware`, `SiswaMiddleware`).
   - Validasi kedua diterapkan pada tingkat **Form Request** (`authorize()` method memeriksa apakah user berperan guru).
   - Tampilan UI secara kondisional menyembunyikan tombol Buat/Edit/Hapus bagi siswa dan menyembunyikan tombol Selesai bagi guru.
3. **Penyatuan Respons API**:
   - Menggunakan `JsonResource` (`TaskResource`, `UserResource`) untuk menjamin keseragaman bentuk payload API, format tanggal, dan data relasi.
   - Penanganan exception otomatis merender respons JSON seragam saat request menuju endpoint API.
4. **Validasi & Konsistensi Error**:
   - Validasi input menggunakan Form Request khusus (`RegisterRequest`, `LoginRequest`, `StoreTaskRequest`, `UpdateTaskRequest`) dengan pesan error berbahasa Indonesia yang jelas.
---

## Rencana Pengembangan Selanjutnya

Jika diberikan waktu tambahan, beberapa peningkatan yang direncanakan:
1. Menjadikan `class` entitas terpisah sehingga siswa hanya melihat tugas kelas tempat mereka terdaftar, bukan seluruh kelas
2. Penambahan fitur lain seperti notifikasi tenggat tugas.
3. Pengaturan direktori yang lebih rapi.
4. Pembuatan data guru tidak melalui fungsi daftar. Seharusnya melalui proses terpisah seperti hanya admin yang bisa menambahkan