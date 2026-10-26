# Fix Report — BengkelServis Semantic Review Findings
Tanggal: 2025-07-17

---

## Ringkasan

Semua temuan KRITIS dan MEDIUM yang bisa diperbaiki tanpa mengubah behavior yang sudah ada telah diperbaiki. Temuan yang memerlukan keputusan desain dicatat di bagian terakhir.

---

## Perbaikan yang Dilakukan

### [KRITIS] Finding 1 — BookingOfflineController tidak ada

**Problem**: `BookingOfflineController.php` belum ada. Route `/booking-offline` tidak terdaftar. Fitur pendaftaran pelanggan offline (FEAT-001) belum diimplementasikan.

**Fix**:
- Dibuat `app/Http/Controllers/BookingOfflineController.php` dengan method:
  - `index()` — daftar booking offline per cabang (paginated)
  - `create()` — form booking baru
  - `store()` — simpan booking dalam DB::transaction (buat pelanggan + kendaraan + booking jika belum ada)
  - `cariPelanggan()` — AJAX lookup by no_hp
  - `cariKendaraan()` — AJAX lookup by nopol
- Dibuat `resources/views/booking-offline/index.blade.php`
- Dibuat `resources/views/booking-offline/create.blade.php` (dengan AJAX search pelanggan & kendaraan)
- Ditambahkan 5 routes di `routes/web.php` dalam middleware `role:2`
- Ditambahkan menu "Booking Offline" ke sidebar Admin Cabang di `layouts/app.blade.php`

**Aturan bisnis yang diimplementasikan**:
- `sumber_pendaftaran = 'offline'`
- `didaftarkan_oleh = session('user_id')` (id admin cabang)
- `user_id = null` pada pelanggan offline
- Scope cabang: booking hanya dibuat untuk `id_cabang` milik admin yang login
- Menggunakan `DB::transaction()` untuk atomicity

**Verifikasi**: `php artisan route:list --path=booking-offline` menampilkan 5 route terdaftar dengan middleware `role:2`.

---

### [KRITIS] Finding 2 — LaporanCabangController::exportPdf() tidak menggunakan DomPDF

**Problem**: Method `exportPdf()` memanggil `return view(...)` langsung, menghasilkan HTML bukan file PDF.

**Fix**: Ditambahkan `use Barryvdh\DomPDF\Facade\Pdf;` dan diubah method `exportPdf()` menjadi:
```php
$pdf = Pdf::loadView('admin-cabang.laporan.pdf', compact(...))
    ->setPaper('a4', 'portrait');
return $pdf->download('laporan-cabang-' . $dateStart->format('Y-m-d') . '.pdf');
```

**File diubah**: `app/Http/Controllers/LaporanCabangController.php`

---

### [KRITIS] Finding 3 — LaporanSuperAdminController::exportPdf() tidak menggunakan DomPDF

**Problem**: Sama dengan finding 2 — menghasilkan HTML bukan PDF.

**Fix**: Ditambahkan `use Barryvdh\DomPDF\Facade\Pdf;` dan diubah method `exportPdf()` menjadi:
```php
$pdf = Pdf::loadView('super-admin.laporan.pdf', compact(...))
    ->setPaper('a4', 'landscape');
return $pdf->download('laporan-eksekutif-' . $dateStart->format('Y-m-d') . '.pdf');
```

**File diubah**: `app/Http/Controllers/LaporanSuperAdminController.php`

---

### [KRITIS] Finding 6 — resolvePelanggan() menggunakan session('user_email') bukan session('user_id')

**Problem**: `LaporanServisPelangganController::resolvePelanggan()` melakukan lookup `WHERE email = session('user_email')`. Email bisa berubah dan tidak dijamin unik.

**Fix**: Diubah menjadi `WHERE user_id = session('user_id')` — konsisten dengan cara controller lain mengidentifikasi pelanggan.

```php
$pelanggan = DB::table('pelanggan')
    ->where('user_id', session('user_id'))
    ->first();
```

**File diubah**: `app/Http/Controllers/LaporanServisPelangganController.php`

---

### [KRITIS] Finding 7 — KomisiController::indexKomisi() bisa null-dereference untuk Admin Cabang tanpa row adminCabang

**Problem**: Kode melakukan `->adminCabang?->cabang_id` tanpa cek null, sehingga `$cabangId` bisa null dan query bocor data antar cabang.

**Fix**: Ditambahkan `abort_if(!$cabangId, 403, ...)` dan menggunakan `$user->cabang_id` sebagai fallback primer (konsisten dengan controller lain):

```php
$user     = \App\Models\User::findOrFail($userId);
$cabangId = $user->cabang_id ?? $user->adminCabang?->cabang_id;
abort_if(!$cabangId, 403, 'Akun Admin Cabang belum terhubung dengan cabang.');
```

**File diubah**: `app/Http/Controllers/KomisiController.php`

---

### [KRITIS] Finding 10 — minta_revisi() selalu abort(403) untuk laporan home service (booking_id null)

**Problem**: Method `minta_revisi()` hanya mencari `id_cabang` via `bookingservis.id_booking`. Jika `booking_id` null (laporan home service / sumber='home_service'), `$idCabangLaporan` selalu null dan `abort_if((int) null !== $cabangId, 403)` selalu menolak.

**Fix**: Ditambahkan fallback — jika `booking_id` null tapi `penugasan_id` ada, cari cabang via `PenugasanTeknisi → bookingservis`:

```php
} elseif ($laporan->penugasan_id) {
    $idCabangLaporan = DB::table('PenugasanTeknisi as pt')
        ->join('bookingservis as bs', 'pt.id_booking', '=', 'bs.id_booking')
        ->where('pt.id_penugasan', $laporan->penugasan_id)
        ->value('bs.id_cabang');
}
```

**File diubah**: `app/Http/Controllers/VerifikasiLaporanController.php`

---

### [MEDIUM] Finding 8 — session('cabang_id') tidak pernah diset saat login

**Problem**: `LoginController::login()` tidak menyimpan `cabang_id` ke session. `LaporanCabangController::resolveCabangId()` selalu jatuh ke fallback query.

**Fix**: Ditambahkan `cabang_id` ke session saat login:

```php
session([
    'user_id'    => $user->id_user,
    'user_nama'  => $user->nama,
    'user_email' => $user->email,
    'role_id'    => (int) $user->role_id,
    'cabang_id'  => $user->cabang_id ? (int) $user->cabang_id : null,
]);
```

**File diubah**: `app/Http/Controllers/LoginController.php`

---

### [MEDIUM] Finding 15 — Route GET /teknisi/rating tidak dilindungi middleware role:4

**Problem**: Route terdaftar tanpa middleware, siapapun yang tahu URL bisa mengakses.

**Fix**: Route dipindahkan ke dalam `Route::middleware('role:4')->group(...)`.

**Verifikasi**: `php artisan route:list --path=teknisi/rating -v` menampilkan `⇂ role:4`.

**File diubah**: `routes/web.php`

---

### [PENDING] Migration 000011 — Unique index pada penghargaan_teknisi

Migration `2024_01_01_000011_add_unique_to_penghargaan_teknisi` yang sebelumnya **Pending** telah dijalankan.  
Migration ini additive (hanya menambah unique index jika belum ada) dan aman dijalankan.  
Status setelah fix: **[3] Ran**

---

## Temuan yang Tidak Diperbaiki (Memerlukan Keputusan User)

### Finding 4 — Schema kolom komisi_teknisi tidak konsisten (kolom `tipe` orphan)

Migration `000005` membuat kolom `tipe` ENUM('per_servis','bonus_target') yang tidak pernah digunakan oleh kode manapun. Migration `000010` membuat kolom `tipe_servis` dan `is_bonus_target` yang digunakan oleh `KomisiService` dan model.

**Mengapa tidak diperbaiki**: Menghapus kolom `tipe` memerlukan `ALTER TABLE DROP COLUMN` yang dilarang oleh aturan keamanan. Kolom ini adalah orphan tapi tidak menyebabkan error runtime — kode menggunakan `tipe_servis` dan `is_bonus_target` dengan benar.

**Tindakan yang diperlukan (manual)**: Jika diinginkan, Admin bisa menjalankan: `ALTER TABLE komisi_teknisi DROP COLUMN tipe;`

---

### Finding 5 — LaporanServis.homeServis() menggunakan booking_id secara dual-purpose

Relasi Eloquent `homeServis()` di model `LaporanServis` menggunakan `booking_id` yang juga digunakan sebagai FK ke `bookingservis`. Ini benar secara konseptual (dual-purpose FK), tapi relasi bisa menghasilkan data salah jika ID integer tumpang tindih.

**Mengapa tidak diperbaiki**: Memperbaiki ini memerlukan penambahan kolom `id_home_service` ke tabel `laporan_servis` dan migrasi data yang ada — perubahan struktural yang mempengaruhi behavior.

**Status**: Controller yang ada (`LaporanServisPelangganController`) sudah menghindari masalah ini dengan menggunakan query terpisah, bukan relasi Eloquent `homeServis()`.

---

### Finding 9 — Tidak ada UNIQUE constraint di DB untuk guard duplikasi komisi

Guard duplikat komisi hanya di application-level. Tidak ada DB UNIQUE constraint untuk mencegah race condition.

**Mengapa tidak diperbaiki**: Menambah UNIQUE constraint pada `(id_penugasan, tipe_servis, is_bonus_target)` memerlukan `ALTER TABLE ADD UNIQUE INDEX` yang bisa gagal jika ada data duplikat yang sudah ada. Perubahan ini perlu diperiksa dan dieksekusi secara manual.

---

### Finding 11 — PenghargaanService menggabungkan servis bengkel dan Home Service dalam skor yang sama

Asumsi bahwa satu orang bisa punya dua role (Teknisi dan MontirLapangan) tidak sesuai dengan arsitektur normal. Namun ini adalah keputusan desain produk, bukan bug.

---

### Finding 12 — Folder view laporan-superadmin/ duplikasi

Ada folder `resources/views/laporan-superadmin/` yang tidak digunakan — controller menggunakan `super-admin.laporan.*` dengan benar. Folder duplikasi ini tidak menyebabkan error, bisa dihapus manual jika diinginkan.

---

## Output Verifikasi

### `php artisan route:list --path=booking-offline`
```
GET|HEAD   booking-offline ............................ booking-offline.index › BookingOfflineController@index
POST       booking-offline ............................ booking-offline.store › BookingOfflineController@store
GET|HEAD   booking-offline/cari-kendaraan booking-offline.cari-kendaraan › BookingOfflineController@cariKendaraan
GET|HEAD   booking-offline/cari-pelanggan booking-offline.cari-pelanggan › BookingOfflineController@cariPelanggan
GET|HEAD   booking-offline/create ................... booking-offline.create › BookingOfflineController@create
Showing [5] routes
```

### `php artisan route:list --path=laporan`
Semua 21 routes laporan terdaftar tanpa error.

### `php artisan route:list --path=komisi`
Semua 5 routes komisi terdaftar tanpa error.

### `php artisan route:list --path=penghargaan`
Semua 2 routes penghargaan terdaftar tanpa error.

### `php artisan route:list --path=teknisi/rating -v`
```
GET|HEAD  teknisi/rating ... teknisi.rating.index › TeknisiRatingController@index
          ⇂ web
          ⇂ role:4
```

### `php artisan migrate:status`
Semua migration Ran (batch 1, 2, atau 3). Tidak ada Pending.

### `php artisan config:clear` — ✅ Configuration cache cleared successfully.
### `php artisan view:clear` — ✅ Compiled views cleared successfully.
### `php artisan cache:clear` — ✅ Application cache cleared successfully.

---

## File yang Diubah / Dibuat

| File | Aksi | Alasan |
|------|------|--------|
| `app/Http/Controllers/BookingOfflineController.php` | **DIBUAT** | KRITIS: controller belum ada |
| `resources/views/booking-offline/index.blade.php` | **DIBUAT** | View list booking offline |
| `resources/views/booking-offline/create.blade.php` | **DIBUAT** | Form booking offline + AJAX search |
| `app/Http/Controllers/LaporanCabangController.php` | Diubah | KRITIS: exportPdf → DomPDF |
| `app/Http/Controllers/LaporanSuperAdminController.php` | Diubah | KRITIS: exportPdf → DomPDF |
| `app/Http/Controllers/LaporanServisPelangganController.php` | Diubah | KRITIS: user_email → user_id |
| `app/Http/Controllers/KomisiController.php` | Diubah | KRITIS: abort_if null cabang |
| `app/Http/Controllers/VerifikasiLaporanController.php` | Diubah | KRITIS: fallback cabang untuk home service laporan |
| `app/Http/Controllers/LoginController.php` | Diubah | MEDIUM: tambah cabang_id ke session |
| `routes/web.php` | Diubah | Tambah booking-offline routes + role:4 pada teknisi/rating |
| `resources/views/layouts/app.blade.php` | Diubah | Tambah menu Booking Offline di sidebar role 2 |
