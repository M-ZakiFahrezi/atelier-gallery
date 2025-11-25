<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransaksiPenjualan;
use App\Mail\StatusPengirimanUpdated;
use Illuminate\Support\Facades\Mail;


class OrderController extends Controller
{
public function index()
{
    $query = TransaksiPenjualan::orderBy('tanggal_transaksi', 'desc');

    // Search berdasarkan kode_transaksi
    if (request('kode')) {
        $query->where('kode_transaksi', 'LIKE', '%' . request('kode') . '%');
    }

    $orders = $query->get();

    return view('admin.orders.index', compact('orders'));
}

public function updateStatus($id)
{
    $order = TransaksiPenjualan::with('kolektor')->findOrFail($id);

    $order->status_pengiriman = request('status_pengiriman');
    $order->tanggal_pengiriman = now();
    $order->save();

    // Kirim email ke kolektor
    Mail::to($order->kolektor->email)->send(new StatusPengirimanUpdated($order));

    return redirect()->back()->with('success', 'Status pengiriman diperbarui dan email terkirim!');
}

public function show($id)
{
    $order = TransaksiPenjualan::findOrFail($id);
    return view('admin.orders.partials.detail', compact('order'));
}

public function detail($id)
{
    $order = TransaksiPenjualan::with(['karya', 'kolektor'])->findOrFail($id);

    // kembalikan view partial untuk modal
    return view('admin.orders.partials.detail', compact('order'));
}






}
