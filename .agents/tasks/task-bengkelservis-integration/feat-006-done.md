# FEAT-006: Integrasi Home Service ke Laporan dan Riwayat Pelanggan — DONE

Tanggal: 2025

## Status: ✅ Selesai

---

## Ringkasan Temuan & Tindakan per Tugas

### TUGAS 1: Verifikasi Routes Home Service ✅ Sudah Ada

Seluruh routes yang diperlukan sudah terdaftar di `routes/web.php`:

| Route | Middleware | Status |
|---|---|---|
| `/home-service-pelanggan` | `role:6` | ✅ Ada |
| `/homeservice` (resource) | `role:1,2` | ✅ Ada |
| `/montir-lapangan/tugas` | `role:5` | ✅ Ada |
| `/pembayaran-home-service` | `role:1,2,3` | ✅ Ada |

Tidak ada perubahan dilakukan pada routes.

---

### TUGAS 2: LaporanCabangController ✅ Sudah Lengkap

Controller sudah memiliki:
- Query `$pendapatanHomeService` terpisah via join `pembayaran` ↔ `homeservice`
- Query `$homeServiceCount` dari tabel `homeservice`
- `$totalPendapatan = $pendapatanBengkel + $pendapatanHomeService` (dua query terpisah, anti double-count)
- Kedua variabel diteruskan ke view via `compact()`

Tidak ada perubahan dilakukan.

---

### TUGAS 3: LaporanSuperAdminController ✅ Sudah Lengkap

Controller sudah memiliki per cabang:
- `$hsJumlah`: count dari tabel `homeservice`
- `$pendapatanHomeService`: sum dari join `pembayaran` ↔ `homeservice`
- Array data per cabang memuat `hs_jumlah` dan `pendapatan_home_service`

Tidak ada perubahan dilakukan.

---

### TUGAS 4: LaporanServisPelangganController ✅ Diperbarui

**Perubahan:** Ditambahkan query `$homeServiceHistory` di method `index()`:
```php
$homeServiceHistory = DB::table('homeservice')
    ->leftJoin('layananservis', 'homeservice.id_layanan_servis', '=', 'layananservis.id_layanan')
    ->leftJoin('kendaraan', 'homeservice.id_kendaraan', '=', 'kendaraan.id_kendaraan')
    ->where('homeservice.id_pelanggan', $pelanggan->id_pelanggan)
    ->select(...)
    ->orderByDesc('homeservice.tanggal')
    ->get();
```
- Query `$laporanBengkel` yang sudah ada tidak diubah
- `$homeServiceHistory` diteruskan ke view via `compact()`

**View diperbarui** (`resources/views/pelanggan/laporan-servis/index.blade.php`):
- Section "Riwayat Servis Bengkel" tetap utuh dengan heading baru + badge biru "Booking Workshop"
- Ditambahkan section baru "Riwayat Home Service" di bawahnya dengan badge ungu "Home Service"
- Tabel Home Service: Tanggal, Kendaraan, Layanan, Alamat, Status (warna dinamis per status)
- Tidak ada perubahan pada tampilan riwayat booking yang sudah ada

---

### TUGAS 5: Model LaporanServis ✅ Diperbarui

Ditambahkan relasi `homeServis()` dengan docblock penjelasan dual-use `booking_id`:
```php
/**
 * Relasi ke HomeServis.
 * CATATAN: Kolom booking_id digunakan secara dual-purpose:
 * - Ketika sumber='booking': booking_id merujuk ke bookingservis.id_booking
 * - Ketika sumber='home_service': booking_id merujuk ke homeservice.id_home_service
 * Ini untuk menghindari penambahan kolom baru.
 */
public function homeServis() {
    return $this->belongsTo(HomeServis::class, 'booking_id', 'id_home_service');
}
```

---

### TUGAS 6: KomisiService — Scope Comment ✅ Diperbarui

Ditambahkan docblock di atas deklarasi class:
```php
/**
 * KomisiService mengelola komisi untuk Teknisi (role 4) melalui PenugasanTeknisi.
 * Scope: hanya penugasanteknisi (booking workshop).
 * Home Service (homeservice + montirlapangan) adalah alur terpisah dan
 * TIDAK dimasukkan ke dalam komisi_teknisi melalui class ini.
 * (hitungKomisiHomeService() adalah utility method opsional — lihat komentar method tersebut)
 */
```
Tidak ada perubahan pada logika bisnis.

---

### TUGAS 7: View LaporanCabang (Stat Card) ✅ Sudah Ada

View `admin-cabang/laporan/index.blade.php` sudah memiliki:
- Stat card "Home Service" (badge "Terpisah") menampilkan `$homeServiceCount`
- Stat card "Pend. Home Service" (badge "Terpisah") menampilkan `$pendapatanHomeService`

Tidak ada perubahan dilakukan.

---

### TUGAS 8: View LaporanSuperAdmin (Tabel per Cabang) ✅ Sudah Ada

View `super-admin/laporan/index.blade.php` sudah memiliki:
- Kolom "Pend. Home Service" menampilkan `$row['pendapatan_home_service']`
- Kolom "Home Service" menampilkan `$row['hs_jumlah']`

Tidak ada perubahan dilakukan.

---

## File yang Diubah

| File | Perubahan |
|---|---|
| `app/Http/Controllers/LaporanServisPelangganController.php` | Tambah query `$homeServiceHistory` di `index()`, teruskan ke view |
| `resources/views/pelanggan/laporan-servis/index.blade.php` | Tambah section Riwayat Home Service, restructure section bengkel |
| `app/Models/LaporanServis.php` | Tambah relasi `homeServis()` + docblock dual-use `booking_id` |
| `app/Services/KomisiService.php` | Tambah docblock scope class |

## Aturan yang Dipatuhi

- ✅ Tidak ada migrate:fresh / migrate:refresh / db:wipe
- ✅ Tidak mengubah .env
- ✅ Tidak mengubah HomeServisController atau PengajuanHomeServisController
- ✅ Auth: session('user_id'), session('role_id'), session('cabang_id')
- ✅ Nama tabel: `homeservice` (bukan home_service / home_services)
- ✅ Anti double-count: dua query terpisah, bukan JOIN gabungan
- ✅ PHP syntax check: semua file yang diubah — "No syntax errors detected"
