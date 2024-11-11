<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_detail extends Model
{
    use HasFactory;
    protected $table = 'm_details';
    protected $fillable = [
        'pengaduan_id',
        'keterangan'
    ];
}
