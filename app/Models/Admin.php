<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    public $timestamps = false; // PENTING, karena tabel admin tidak punya created_at / updated_at

    protected $fillable = [
        'nama_admin',
        'username',
        'email',
        'password'
    ];
}
