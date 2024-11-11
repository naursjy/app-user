<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class M_pengaduan extends Model
{
    use HasFactory;

    protected $table = 'm_pengaduans';
    protected $fillable = [
        'judul',
        'isi',
        'kategori',
    ];

    public function detail(): HasMany
    {
        return $this->hasMany(M_detail::class, 'pengaduan_id', 'id');
    }
}
