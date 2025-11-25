<?php

namespace App\Models;   // ⬅ HARUS ADA

use Illuminate\Database\Eloquent\Model;

class Favorit extends Model
{
    protected $table = 'favorit';
    protected $primaryKey = 'id_favorit';
    protected $fillable = ['id_kolektor', 'id_karya'];

    // --> tambahkan ini
    public $timestamps = false;

    public function kolektor()
    {
        return $this->belongsTo(Kolektor::class, 'id_kolektor', 'id_kolektor');
    }

    public function karya()
    {
        return $this->belongsTo(KaryaSeni::class, 'id_karya', 'id_karya');
    }
}
