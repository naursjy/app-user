<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class M_artikel extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'user_id'
    ];

    protected $table = 'tb_artikel';

    public $timestamps = false;

    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }
}
