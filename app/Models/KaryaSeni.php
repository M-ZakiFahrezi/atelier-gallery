<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Favorit;

class KaryaSeni extends Model
{
    protected $table = 'karya_seni';
    protected $primaryKey = 'id_karya';
    public $timestamps = false;

    protected $fillable = [
        'judul_karya',
        'tahun_pembuatan',
        'deskripsi',
        'harga',
        'status_karya',
        'id_seniman',
        'id_tipe',
        'gambar'
    ];

public function favorit()
{
    return $this->hasMany(Favorit::class, 'id_karya', 'id_karya');
}

}