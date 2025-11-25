<?php

namespace App\Models;

use App\Models\KaryaSeni;
use App\Models\Kolektor;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    protected $table = 'transaksi_penjualan';
    protected $primaryKey = 'id_transaksi';
    public $timestamps = false; // tabel tidak punya created_at & updated_at

    protected $fillable = [
    'kode_transaksi',
    'tanggal_transaksi',
    'harga_terjual',
    'metode_pembayaran',
    'id_penjualan',
    'status_bayar',
    'id_karya',
    'id_kolektor',
    'status_transaksi',
    'tanggal_pelunasan',
    'catatan_transaksi',
    'bukti_pembayaran',
    'alamat_pengiriman',
    'tanggal_pengiriman',
    'status_pengiriman',
    'snap_token',
    'qris_url',
    'order_id',
    'transaction_status',
];


    // Relasi jika perlu
public function karya()
{
    return $this->belongsTo(KaryaSeni::class, 'id_karya', 'id_karya');
}

public function kolektor()
{
    return $this->belongsTo(Kolektor::class, 'id_kolektor', 'id_kolektor');
}

}
