<?php

namespace App\Http\Controllers;

use App\Models\SparePart;
use App\Models\Pelanggan;
use App\Models\OrderSparePart;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PelangganSparePartController extends Controller
{
    /**
     * Menampilkan katalog spare part.
     */
    public function index()
    {
        $spareparts = SparePart::with('kategori')
            ->where('stok', '>', 0)
            ->orderBy('nama')
            ->get();

        return view(
            'pelanggan.sparepart.index',
            compact('spareparts')
        );
    }

    /**
     * Menambahkan barang ke keranjang session.
     */
    public function tambahKeranjang(Request $request)
    {
        $request->validate([
            'id_sparepart' => 'required|integer',
            'jumlah' => 'required|integer|min:1',
        ]);

        $sparepart = SparePart::findOrFail(
            $request->id_sparepart
        );

        if ($sparepart->stok < $request->jumlah) {
            return back()->with(
                'error',
                'Stok tidak mencukupi. Stok tersedia: '
                . $sparepart->stok
            );
        }

        $keranjang = session('keranjang_sparepart', []);
        $id = (string) $sparepart->id_sparepart;

        $jumlahLama = isset($keranjang[$id])
            ? (int) $keranjang[$id]['jumlah']
            : 0;

        $jumlahBaru = $jumlahLama + (int) $request->jumlah;

        if ($jumlahBaru > $sparepart->stok) {
            return back()->with(
                'error',
                'Jumlah di keranjang melebihi stok yang tersedia.'
            );
        }

        $keranjang[$id] = [
            'id_sparepart' => $sparepart->id_sparepart,
            'nama' => $sparepart->nama,
            'harga' => (float) $sparepart->harga,
            'jumlah' => $jumlahBaru,
        ];

        session(['keranjang_sparepart' => $keranjang]);

        return redirect('/keranjang-sparepart')->with(
            'success',
            'Spare part berhasil ditambahkan ke keranjang.'
        );
    }

    /**
     * Menampilkan isi keranjang.
     */
    public function keranjang()
    {
        $keranjang = session('keranjang_sparepart', []);

        foreach ($keranjang as $id => $item) {
            $sparepart = SparePart::find($id);

            if (!$sparepart) {
                unset($keranjang[$id]);
                continue;
            }

            $keranjang[$id]['nama'] = $sparepart->nama;
            $keranjang[$id]['harga'] = (float) $sparepart->harga;
            $keranjang[$id]['stok'] = (int) $sparepart->stok;
        }

        session(['keranjang_sparepart' => $keranjang]);

        $total = collect($keranjang)->sum(function ($item) {
            return $item['harga'] * $item['jumlah'];
        });

        return view(
            'pelanggan.sparepart.keranjang',
            compact('keranjang', 'total')
        );
    }

    /**
     * Menghapus barang dari keranjang.
     */
    public function hapusKeranjang($id)
    {
        $keranjang = session('keranjang_sparepart', []);

        unset($keranjang[(string) $id]);

        session(['keranjang_sparepart' => $keranjang]);

        return redirect('/keranjang-sparepart')->with(
            'success',
            'Barang dihapus dari keranjang.'
        );
    }

    /**
     * Menampilkan form checkout.
     */
    public function checkout()
    {
        $keranjang = session('keranjang_sparepart', []);

        if (empty($keranjang)) {
            return redirect('/keranjang-sparepart')
                ->with('error', 'Keranjang kamu masih kosong.');
        }

        $pelanggan = Pelanggan::where(
            'email',
            session('user_email')
        )->first();

        if (!$pelanggan) {
            return redirect('/keranjang-sparepart')->with(
                'error',
                'Data pelanggan tidak ditemukan. Pastikan email akun '
                . 'kamu terhubung dengan data pelanggan.'
            );
        }

        $items = [];
        $total = 0;

        foreach ($keranjang as $id => $item) {
            $sparepart = SparePart::find($id);

            if (!$sparepart) {
                return redirect('/keranjang-sparepart')->with(
                    'error',
                    'Ada barang yang sudah tidak tersedia. '
                    . 'Periksa kembali keranjang kamu.'
                );
            }

            $jumlah = (int) $item['jumlah'];

            if ($jumlah < 1 || $jumlah > $sparepart->stok) {
                return redirect('/keranjang-sparepart')->with(
                    'error',
                    'Stok ' . $sparepart->nama
                    . ' tidak mencukupi. Stok tersedia: '
                    . $sparepart->stok
                );
            }

            $subtotal = (float) $sparepart->harga * $jumlah;
            $total += $subtotal;

            $items[] = [
                'id_sparepart' => $sparepart->id_sparepart,
                'nama' => $sparepart->nama,
                'harga' => (float) $sparepart->harga,
                'stok' => (int) $sparepart->stok,
                'jumlah' => $jumlah,
                'subtotal' => $subtotal,
            ];
        }

        return view(
            'pelanggan.sparepart.checkout',
            compact('pelanggan', 'items', 'total')
        );
    }

    /**
     * Memproses checkout dan menyimpan pesanan.
     */
    public function prosesCheckout(Request $request)
    {
        $request->validate([
            'alamat_pengiriman' => 'required|string|max:255',
        ]);

        $keranjang = session('keranjang_sparepart', []);

        if (empty($keranjang)) {
            return redirect('/keranjang-sparepart')->with(
                'error',
                'Keranjang kamu masih kosong.'
            );
        }

        $pelanggan = Pelanggan::where(
            'email',
            session('user_email')
        )->first();

        if (!$pelanggan) {
            return redirect('/keranjang-sparepart')->with(
                'error',
                'Data pelanggan tidak ditemukan.'
            );
        }

        try {
            $order = DB::transaction(function () use (
                $keranjang,
                $pelanggan,
                $request
            ) {
                $itemsValid = [];
                $total = 0;

                // Validasi ulang harga dan stok dari database.
                foreach ($keranjang as $item) {
                    $idSparepart = (int) $item['id_sparepart'];
                    $jumlah = (int) $item['jumlah'];

                    if ($jumlah < 1) {
                        throw ValidationException::withMessages([
                            'keranjang' => 'Jumlah barang tidak valid.',
                        ]);
                    }

                    $sparepart = SparePart::where(
                        'id_sparepart',
                        $idSparepart
                    )->lockForUpdate()->first();

                    if (!$sparepart) {
                        throw ValidationException::withMessages([
                            'keranjang' =>
                                'Salah satu barang sudah tidak tersedia.',
                        ]);
                    }

                    if ($sparepart->stok < $jumlah) {
                        throw ValidationException::withMessages([
                            'keranjang' =>
                                'Stok ' . $sparepart->nama
                                . ' tidak mencukupi. Stok tersedia: '
                                . $sparepart->stok,
                        ]);
                    }

                    $harga = (float) $sparepart->harga;
                    $subtotal = $harga * $jumlah;
                    $total += $subtotal;

                    $itemsValid[] = [
                        'sparepart' => $sparepart,
                        'jumlah' => $jumlah,
                        'harga' => $harga,
                    ];
                }

                // Buat pesanan utama.
                $order = OrderSparePart::create([
                    'id_pelanggan' => $pelanggan->id_pelanggan,
                    'tanggal_order' => now()->toDateString(),
                    'status' => 'Pending',
                    'total_harga' => $total,
                    'alamat_pengiriman' =>
                        $request->alamat_pengiriman,
                ]);

                // Simpan detail barang dan kurangi stok.
                foreach ($itemsValid as $item) {
                    OrderDetail::create([
                        'id_order' => $order->id_order,
                        'id_sparepart' =>
                            $item['sparepart']->id_sparepart,
                        'jumlah' => $item['jumlah'],
                        'harga' => $item['harga'],
                    ]);

                    $item['sparepart']->decrement(
                        'stok',
                        $item['jumlah']
                    );
                }

                return $order;
            }, 3);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return redirect('/keranjang-sparepart')->with(
                'error',
                'Pesanan belum berhasil disimpan. '
                . 'Silakan coba lagi.'
            );
        }

        // Kosongkan keranjang hanya setelah transaksi berhasil.
        session()->forget('keranjang_sparepart');

        return redirect('/checkout-sparepart/berhasil/' . $order->id_order)
            ->with('success', 'Pesanan spare part berhasil dibuat.');
    }

    /**
     * Menampilkan konfirmasi pesanan milik pelanggan yang login.
     */
    public function pesananBerhasil($id)
    {
        $pelanggan = Pelanggan::where(
            'email',
            session('user_email')
        )->first();

        if (!$pelanggan) {
            abort(403, 'Data pelanggan tidak ditemukan.');
        }

        $order = OrderSparePart::with([
            'orderDetail.sparePart',
        ])
            ->where('id_order', $id)
            ->where('id_pelanggan', $pelanggan->id_pelanggan)
            ->firstOrFail();

        return view(
            'pelanggan.sparepart.berhasil',
            compact('order')
        );
    }
}
