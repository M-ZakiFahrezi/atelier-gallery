<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityTimeline extends Model
{
    protected $table = 'transaksi_penjualan';
    protected $primaryKey = 'id_transaksi';
    public $timestamps = false;

    protected $fillable = [
        'id_transaksi',
        'kode_transaksi',
        'tanggal_transaksi',
        'harga_terjual',
        'status_transaksi',
        'id_karya',
        'id_kolektor'
    ];

    // RELASI KE KARYA
    public function karya()
    {
        return $this->belongsTo(KaryaSeni::class, 'id_karya');
    }
}
