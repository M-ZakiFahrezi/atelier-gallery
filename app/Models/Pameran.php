<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pameran extends Model
{
    protected $table = 'pameran';
    protected $primaryKey = 'id_pameran';
    public $timestamps = false;

    protected $fillable = [
        'nama_pameran',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi_pameran',
        'id_galeri'
    ];
}
