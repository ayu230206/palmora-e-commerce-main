<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class produsen extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'alamat', 'email', 'telp', 'user'];
}
