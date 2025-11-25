<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Kolektor extends Model
{
    protected $table = 'kolektor';
    protected $primaryKey = 'id_kolektor';
    public $timestamps = false;

    protected $fillable = [
        'nama_kolektor',
        'alamat',
        'kontak',
        'jenis_kolektor',
        'username',
        'email',
        'password'
    ];

    protected $hidden = [
        'password'
    ];

public function favorit()
{
    return $this->hasMany(Favorit::class, 'id_kolektor', 'id_kolektor');
}

}
