<?php

namespace App\Http\Controllers;

use App\Models\Pengiriman;
use App\Models\OrderSparePart;
use App\Models\Ekspedisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengirimanController extends Controller
{
    public function index()
    {
        $pengiriman = Pengiriman::with([
            'orderSparePart.pelanggan',
            'ekspedisi'
        ])->get();

        return view('pengiriman.index', compact('pengiriman'));
    }

    public function create()
    {
        $orders = OrderSparePart::with('pelanggan')->get();
        $ekspedisi = Ekspedisi::all();

        return view('pengiriman.create', compact(
            'orders',
            'ekspedisi'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_order' => 'required|exists:ordersparepart,id_order',
            'id_ekspedisi' => 'required|exists:ekspedisi,id_ekspedisi',
            'kurir' => 'required|string|max:255',
            'no_resi' => 'required|string|max:255',
            'status' => 'required|in:Pending,Dikirim,Tiba',
            'estimasi_tiba' => 'nullable|date',
            'tanggal_kirim' => 'nullable|date',
        ]);

        DB::transaction(function () use ($validated) {
            $pengiriman = Pengiriman::create($validated);

            $this->sinkronkanStatusPesanan($pengiriman);
        });

        return redirect('/pengiriman')
            ->with('success', 'Data pengiriman berhasil disimpan.');
    }

    public function edit($id)
    {
        $pengiriman = Pengiriman::findOrFail($id);

        $orders = OrderSparePart::with('pelanggan')->get();
        $ekspedisi = Ekspedisi::all();

        return view('pengiriman.edit', compact(
            'pengiriman',
            'orders',
            'ekspedisi'
        ));
    }

    

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'id_order' => 'required|exists:ordersparepart,id_order',
        'id_ekspedisi' => 'required|exists:ekspedisi,id_ekspedisi',
        'kurir' => 'required|string|max:255',
        'no_resi' => 'required|string|max:255',
        'status' => 'required|in:Pending,Dikirim,Tiba,pending,dikirim,tiba',
        'estimasi_tiba' => 'nullable|date',
        'tanggal_kirim' => 'nullable|date',
    ]);

    DB::transaction(function () use ($validated, $id) {
        $pengiriman = Pengiriman::findOrFail($id);

        $statusPengiriman = strtolower(trim($validated['status']));

        $statusPesanan = match ($statusPengiriman) {
            'pending' => 'Diproses',
            'dikirim' => 'Dikirim',
            'tiba' => 'Selesai',
        };

        $pengiriman->update($validated);

        DB::table('ordersparepart')
            ->where('id_order', $validated['id_order'])
            ->update(['status' => $statusPesanan]);
    });

    return redirect('/pengiriman')
        ->with('success', 'Status pengiriman dan pesanan berhasil diperbarui.');
}

}