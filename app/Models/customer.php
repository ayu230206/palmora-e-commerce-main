<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    use HasFactory;

    protected $fillable = ['user', 'nama', 'jenis_kelamin', 'alamat', 'email', 'no_telp'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user');
    }
}
