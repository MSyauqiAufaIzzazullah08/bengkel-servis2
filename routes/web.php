<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\BookingServisController;
use App\Http\Controllers\OrderSparePartController;
use App\Http\Controllers\HomeServisController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\PenugasanTeknisiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminCabangController;
use App\Http\Controllers\OperasionalCabangController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\KategoriSparePartController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\EkspedisiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\LayananServisController;
use App\Http\Controllers\DetailServisController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\MetodePembayaranController;
use App\Http\Controllers\MontirLapanganController;
use App\Http\Controllers\PelangganBookingController;
use App\Http\Controllers\PelangganKendaraanController;
use App\Http\Controllers\PelangganPembayaranController;
use App\Http\Controllers\PelangganRatingController;
use App\Http\Controllers\PelangganSparePartController;
use App\Http\Controllers\AdminPesananSparePartController;
use App\Http\Controllers\DashboardMontirLapanganController;
use App\Http\Controllers\PembayaranHomeServisController;

/*
|--------------------------------------------------------------------------
| LOGIN DAN REGISTRASI
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLogin']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

Route::get('/register', [RegistrasiController::class, 'showRegister']);
Route::post('/register', [RegistrasiController::class, 'register']);

/*
|--------------------------------------------------------------------------
| HALAMAN PUBLIK
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('public.welcome');
});

Route::get('/layanan-publik', function () {
    $layanans = \App\Models\LayananServis::all();

    return view('public.layanan', compact('layanans'));
});

Route::get('/home-service-publik', function () {
    return view('public.home-service-publik');
});

Route::get('/sparepart-publik', function () {
    return view('public.sparepart');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD UMUM DAN PELANGGAN
|--------------------------------------------------------------------------
*/



Route::middleware('role:1,2,3,4,5,6,9,10')->group(function () {
    Route::get('/dashboard', function () {
        return match ((int) session('role_id')) {
            1 => redirect('/dashboard-super-admin'),
            2 => redirect('/dashboard-admin-cabang'),
            3 => redirect('/dashboard-petugas-service'),
            4 => redirect('/dashboard-teknisi'),
            5 => redirect('/dashboard-montir-lapangan'),
            6 => redirect('/dashboard-pelanggan'),
            9 => redirect('/dashboard-petugas-sparepart'),
            10 => redirect('/dashboard-petugas-pengiriman'),
            default => view('dashboard'),
        };
    });
});


Route::middleware('role:6')->group(function () {
    Route::get('/dashboard-pelanggan', function () {
        return view('dashboard-pelanggan');
    });

    Route::get('/pembayaran-sparepart/{id_order}', [
    \App\Http\Controllers\PelangganPembayaranController::class,
    'formSparePart'
]);

Route::post('/pembayaran-sparepart/{id_order}', [
    \App\Http\Controllers\PelangganPembayaranController::class,
    'storeSparePart'
]);

    // Form booking khusus pelanggan
    Route::get(
        '/booking-pelanggan/create',
        [PelangganBookingController::class, 'create']
    );

    // Menyimpan booking pelanggan
    Route::post(
        '/booking-pelanggan',
        [PelangganBookingController::class, 'store']
    );
    
    
Route::get('/booking-pelanggan', [
    \App\Http\Controllers\PelangganBookingController::class,
    'index'
]);

Route::middleware('role:6')->group(function () {
    Route::get('/pembayaran-pelanggan', [
        PelangganPembayaranController::class,
        'index'
    ]);
});

});

Route::get('/kendaraan-pelanggan', [
    PelangganKendaraanController::class, 'index'
]);

Route::get('/kendaraan-pelanggan/create', [
    PelangganKendaraanController::class, 'create'
]);

Route::post('/kendaraan-pelanggan', [
    PelangganKendaraanController::class, 'store'
]);

Route::delete('/kendaraan-pelanggan/{id}', [
    PelangganKendaraanController::class, 'destroy'
]);

// PEMBAYARAN PELANGGAN
Route::middleware('role:6')->group(function () {
    Route::get(
        '/pembayaran-pelanggan',
        [PelangganPembayaranController::class, 'index']
    );

    Route::post(
        '/pembayaran-pelanggan/{id_booking}',
        [PelangganPembayaranController::class, 'store']
    );
});


Route::middleware('role:1,2,3')->group(function () {
    Route::get(
        '/pengelolaan-booking',
        [\App\Http\Controllers\PengelolaanBookingController::class, 'index']
    )->name('pengelolaan-booking.index');

    Route::put(
        '/pengelolaan-booking/{id}',
        [\App\Http\Controllers\PengelolaanBookingController::class, 'update']
    )->name('pengelolaan-booking.update');
});


Route::middleware('role:1')->group(function () {
    Route::get('/dashboard-super-admin', function () {
        return view('dashboard-super-admin');
    });
});

// =====================================================
// HALAMAN LAYANAN SERVIS KHUSUS PELANGGAN
// =====================================================

Route::middleware('role:6')->group(function () {
    Route::get('/layanan-pelanggan', function () {
        $layanans = \App\Models\LayananServis::orderBy('nama_layanan')
            ->get();

        return view('pelanggan.layanan.index', compact('layanans'));
    });
});


Route::middleware('role:6')->group(function () {
    Route::get(
        '/order-pelanggan',
        [OrderSparePartController::class, 'pesananPelanggan']
    );

    
Route::get('/pembayaran-sparepart/{id_order}', [
    \App\Http\Controllers\PelangganPembayaranController::class,
    'formSparePart'
]);

Route::post('/pembayaran-sparepart/{id_order}', [
    \App\Http\Controllers\PelangganPembayaranController::class,
    'storeSparePart'
]);

});

// RATING DAN ULASAN KHUSUS PELANGGAN
Route::middleware('role:6')->group(function () {
    Route::get('/rating-pelanggan', [
        PelangganRatingController::class,
        'index'
    ]);

    Route::post('/rating-pelanggan', [
        PelangganRatingController::class,
        'store'
    ]);

    Route::delete('/rating-pelanggan/{id}', [
        PelangganRatingController::class,
        'destroy'
    ]);
});


 // KATALOG, KERANJANG, DAN CHECKOUT SPARE PART PELANGGAN
Route::middleware('role:6')->group(function () {
    Route::get('/sparepart-pelanggan', [
        PelangganSparePartController::class,
        'index'
    ]);

    Route::post('/keranjang-sparepart', [
        PelangganSparePartController::class,
        'tambahKeranjang'
    ]);

    Route::get('/keranjang-sparepart', [
        PelangganSparePartController::class,
        'keranjang'
    ]);

    Route::delete('/keranjang-sparepart/{id}', [
        PelangganSparePartController::class,
        'hapusKeranjang'
    ]);

    Route::get('/checkout-sparepart', [
        PelangganSparePartController::class,
        'checkout'
    ]);

    Route::post('/checkout-sparepart', [
        PelangganSparePartController::class,
        'prosesCheckout'
    ]);

    Route::get('/checkout-sparepart/berhasil/{id}', [
        PelangganSparePartController::class,
        'pesananBerhasil'
    ]);
});




// ADMIN - PENGELOLAAN PESANAN SPARE PART
Route::middleware('role:1,2,9')->group(function () {
    Route::get(
        '/admin-pesanan-sparepart',
        [AdminPesananSparePartController::class, 'index']
    );

    Route::put(
        '/admin-pesanan-sparepart/{id}/status',
        [AdminPesananSparePartController::class, 'updateStatus']
    );
});

/*
|--------------------------------------------------------------------------
| CRUD PENGGUNA - KHUSUS SUPER ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware('role:1')->group(function () {
    Route::get('/user', [UserController::class, 'index'])
        ->name('user.index');

    Route::get('/user/create', [UserController::class, 'create'])
        ->name('user.create');

    Route::post('/user', [UserController::class, 'store'])
        ->name('user.store');

    Route::get('/user/{id}/edit', [UserController::class, 'edit'])
        ->name('user.edit');

    Route::put('/user/{id}', [UserController::class, 'update'])
        ->name('user.update');

    Route::delete('/user/{id}', [UserController::class, 'destroy'])
        ->name('user.destroy');
});

// CRUD ROLE - khusus Super Admin
Route::middleware('role:1')->group(function () {
    Route::get('/role', [RoleController::class, 'index'])->name('role.index');
    Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');
    Route::post('/role', [RoleController::class, 'store'])->name('role.store');
    Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');
    Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
}); 

// CRUD ADMIN CABANG - khusus Super Admin
Route::middleware('role:1')->group(function () {
    Route::get('/admincabang', [AdminCabangController::class, 'index'])
        ->name('admincabang.index');

    Route::get('/admincabang/create', [AdminCabangController::class, 'create'])
        ->name('admincabang.create');

    Route::post('/admincabang', [AdminCabangController::class, 'store'])
        ->name('admincabang.store');

    Route::get('/admincabang/{id}/edit', [AdminCabangController::class, 'edit'])
        ->name('admincabang.edit');

    Route::put('/admincabang/{id}', [AdminCabangController::class, 'update'])
        ->name('admincabang.update');

    Route::delete('/admincabang/{id}', [AdminCabangController::class, 'destroy'])
        ->name('admincabang.destroy');
});


/*
|--------------------------------------------------------------------------
| CRUD CABANG
|--------------------------------------------------------------------------
*/

Route::middleware('role:1')->group(function () {
    Route::get('/cabang', [CabangController::class, 'index'])
        ->name('cabang.index');

    Route::get('/cabang/create', [CabangController::class, 'create'])
        ->name('cabang.create');

    Route::post('/cabang', [CabangController::class, 'store'])
        ->name('cabang.store');

    Route::get('/cabang/{id}/edit', [CabangController::class, 'edit'])
        ->name('cabang.edit');

    Route::put('/cabang/{id}', [CabangController::class, 'update'])
        ->name('cabang.update');

    Route::delete('/cabang/{id}', [CabangController::class, 'destroy'])
        ->name('cabang.destroy');
});

Route::middleware('role:1,2')->group(function () {
    Route::get('/operasional', [OperasionalCabangController::class, 'index'])
        ->name('operasional.index');

    Route::get('/operasional/create', [OperasionalCabangController::class, 'create'])
        ->name('operasional.create');

    Route::post('/operasional', [OperasionalCabangController::class, 'store'])
        ->name('operasional.store');

    Route::get('/operasional/{id}/edit', [OperasionalCabangController::class, 'edit'])
        ->name('operasional.edit');

    Route::put('/operasional/{id}', [OperasionalCabangController::class, 'update'])
        ->name('operasional.update');

    Route::delete('/operasional/{id}', [OperasionalCabangController::class, 'destroy'])
        ->name('operasional.destroy');
});


Route::middleware('role:1,2,3')->group(function () {
    Route::get('/pelanggan', [PelangganController::class, 'index'])
        ->name('pelanggan.index');

    Route::get('/pelanggan/create', [PelangganController::class, 'create'])
        ->name('pelanggan.create');

    Route::post('/pelanggan', [PelangganController::class, 'store'])
        ->name('pelanggan.store');

    Route::get('/pelanggan/{id}/edit', [PelangganController::class, 'edit'])
        ->name('pelanggan.edit');

    Route::put('/pelanggan/{id}', [PelangganController::class, 'update'])
        ->name('pelanggan.update');

    Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy'])
        ->name('pelanggan.destroy');
});


Route::middleware('role:1,2,3')->group(function () {
    Route::get('/kendaraan', [KendaraanController::class, 'index'])
        ->name('kendaraan.index');

    Route::get('/kendaraan/create', [KendaraanController::class, 'create'])
        ->name('kendaraan.create');

    Route::post('/kendaraan', [KendaraanController::class, 'store'])
        ->name('kendaraan.store');

    Route::get('/kendaraan/{id}/edit', [KendaraanController::class, 'edit'])
        ->name('kendaraan.edit');

    Route::put('/kendaraan/{id}', [KendaraanController::class, 'update'])
        ->name('kendaraan.update');

    Route::delete('/kendaraan/{id}', [KendaraanController::class, 'destroy'])
        ->name('kendaraan.destroy');
});

Route::redirect('/booking', '/pengelolaan-booking')->name('booking.redirect');

// LAYANAN SERVIS
Route::middleware('role:1,2,3')->group(function () {
    Route::resource('/layanan', \App\Http\Controllers\LayananServisController::class)
        ->except(['show']);
});

// DETAIL SERVIS
Route::middleware('role:1,2,3')->group(function () {
    Route::resource(
        '/detailservis',
        \App\Http\Controllers\DetailServisController::class
    )->except(['show']);
});

// PENUGASAN TEKNISI
Route::middleware('role:1,2,3')->group(function () {
    Route::resource(
        '/penugasan',
        \App\Http\Controllers\PenugasanTeknisiController::class
    )->except(['show']);
});

// DATA TEKNISI
Route::middleware('role:1,2')->group(function () {
    Route::resource(
        '/teknisi',
        \App\Http\Controllers\TeknisiController::class
    )->except(['show']);
});

// MONTIR LAPANGAN
Route::middleware('role:1,2')->group(function () {
    Route::resource(
        '/montirlapangan',
        \App\Http\Controllers\MontirLapanganController::class
    )->except(['show']);
});

// KATEGORI SPAREPART
Route::middleware('role:1,2,9')->group(function () {
    Route::resource(
        '/kategori-sparepart',
        KategoriSparePartController::class
    )->except(['show']);
});

// DATA SPAREPART
Route::middleware('role:1,2,9')->group(function () {
    Route::resource(
        '/sparepart',
        SparePartController::class
    )->except(['show']);
});

// HOME SERVICE
Route::middleware('role:1,2')->group(function () {
    Route::resource(
        '/homeservice',
        \App\Http\Controllers\HomeServisController::class
    )->except(['show']);
});


 // ORDER SPAREPART
Route::middleware('role:1,2,9')->group(function () {
    Route::resource(
        '/order',
        OrderSparePartController::class
    )->except(['show']);
});

// ORDER DETAIL
Route::middleware('role:1,2,9')->group(function () {
    Route::resource(
        '/orderdetail',
        OrderDetailController::class
    )->except(['show']);
});

// PENGIRIMAN
Route::middleware('role:1,2,9')->group(function () {
    Route::resource(
        '/pengiriman',
        PengirimanController::class
    )->except(['show']);
});

// EKSPEDISI
Route::middleware('role:1,2,9')->group(function () {
    Route::resource(
        '/ekspedisi',
        EkspedisiController::class
    )->except(['show']);
});


/*
|--------------------------------------------------------------------------
| PEMBAYARAN ADMIN DAN PETUGAS SERVICE
|--------------------------------------------------------------------------
*/

Route::middleware('role:1,3')->group(function () {
    Route::resource(
        '/pembayaran',
        \App\Http\Controllers\PembayaranController::class
    )->except(['show']);
});

// METODE PEMBAYARAN
Route::resource(
    '/metodepembayaran',
    \App\Http\Controllers\MetodePembayaranController::class
)->except(['show']);

// PROMO
Route::resource(
    '/promo',
    \App\Http\Controllers\PromoController::class
)->except(['show']);

// RATING ADMIN
Route::resource(
    '/rating',
    \App\Http\Controllers\RatingController::class
)->except(['show']);

// DASHBOARD KHUSUS PETUGAS SERVICE
Route::middleware('role:3')->group(function () {
    Route::get('/dashboard-petugas-service', function () {
        return view('dashboard-petugas-service');
    });
});


    
/*
|--------------------------------------------------------------------------
| PEMBAYARAN HOME SERVICE
|--------------------------------------------------------------------------
| Akses untuk Super Admin, Admin Cabang, dan Petugas Service.
*/

Route::middleware('role:1,2,3')->group(function () {
    Route::get(
        '/pembayaran-home-service',
        [PembayaranHomeServisController::class, 'index']
    )->name('pembayaran-home-service.index');

    Route::get(
        '/pembayaran-home-service/create',
        [PembayaranHomeServisController::class, 'create']
    )->name('pembayaran-home-service.create');

    Route::post(
        '/pembayaran-home-service',
        [PembayaranHomeServisController::class, 'store']
    )->name('pembayaran-home-service.store');
});

// DASHBOARD KHUSUS ADMIN CABANG
Route::middleware('role:2')->group(function () {
    Route::get('/dashboard-admin-cabang', function () {
        return view('dashboard-admin-cabang');
    });
});


 // DASHBOARD KHUSUS TEKNISI
Route::middleware('role:4')->group(function () {
    Route::get(
        '/dashboard-teknisi',
        [\App\Http\Controllers\DashboardTeknisiController::class, 'index']
    )->name('dashboard.teknisi');

    Route::put(
        '/teknisi/penugasan/{id}/status',
        [\App\Http\Controllers\DashboardTeknisiController::class, 'updateStatus']
    )->name('teknisi.penugasan.status');
});

Route::get(
    '/teknisi/rating',
    [\App\Http\Controllers\TeknisiRatingController::class, 'index']
)->name('teknisi.rating.index');

// DASHBOARD DAN TUGAS MONTIR LAPANGAN

Route::middleware('role:5')->group(function () {
    Route::get(
        '/dashboard-montir-lapangan',
        [DashboardMontirLapanganController::class, 'index']
    )->name('montir-lapangan.dashboard');

    Route::get(
        '/montir-lapangan/tugas',
        [DashboardMontirLapanganController::class, 'tugas']
    )->name('montir-lapangan.tugas.index');

    Route::put(
        '/montir-lapangan/tugas/{id}/status',
        [DashboardMontirLapanganController::class, 'updateStatus']
    )->name('montir-lapangan.tugas.status');
});

// DASHBOARD PETUGAS SPARE PART
Route::middleware('role:9')->group(function () {
    Route::get('/dashboard-petugas-sparepart', function () {
        return view('dashboard-petugas-sparepart');
    })->name('dashboard.petugas-sparepart');
});

// DASHBOARD PETUGAS PENGIRIMAN
Route::middleware('role:10')->group(function () {
    Route::get('/dashboard-petugas-pengiriman', function () {
        return view('dashboard-petugas-pengiriman');
    })->name('dashboard.petugas-pengiriman');
});


/*
|--------------------------------------------------------------------------
| Laporan Pembayaran
|--------------------------------------------------------------------------
| Akses hanya untuk Super Admin dan Petugas Service.
*/
Route::middleware('role:1,3')->group(function () {
    Route::get('/laporan-pembayaran', [
        \App\Http\Controllers\LaporanPembayaranController::class,
        'index'
    ])->name('laporan-pembayaran.index');

    Route::get('/laporan-pembayaran/pdf', [
        \App\Http\Controllers\LaporanPembayaranController::class,
        'download'
    ])->name('laporan-pembayaran.download');
});


/*
|--------------------------------------------------------------------------
| PENGAJUAN HOME SERVICE PELANGGAN
|--------------------------------------------------------------------------
*/

Route::middleware('role:6')->group(function () {
    Route::get(
        '/home-service-pelanggan',
        [\App\Http\Controllers\PengajuanHomeServisController::class, 'create']
    )->name('pelanggan.home-service.create');

    Route::post(
        '/home-service-pelanggan',
        [\App\Http\Controllers\PengajuanHomeServisController::class, 'store']
    )->name('pelanggan.home-service.store');
});
