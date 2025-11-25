<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seniman extends Model
{
    protected $table = 'seniman';
    protected $primaryKey = 'id_seniman';
    public $timestamps = false;

    protected $fillable = [
        'nama_seniman',
        'asal',
        'tanggal_lahir',
        'bio',
        'gambar',
    ];
}
