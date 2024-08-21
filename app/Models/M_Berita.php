<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class M_Berita extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'content',
        'subtitle',
        'jenis',
        'user_id',
    ];
    // Nofiana 081225455526
    protected $table = 'tbberita_tabel';

    // public $timestamps = false;
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => url('/storage/posts/' . $image),
        );
    }
}
