<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_beritaRole extends Model
{
    use HasFactory;
    protected $fillable = [
        'berita_id',
        'role_id',
    ];
}
