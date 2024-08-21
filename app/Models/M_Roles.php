<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class M_Roles extends Model
{
    use HasFactory;
    protected $fillable = [
        'jenis'
    ];

    protected $table = 'roles__tabel';

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role');
    }
}
