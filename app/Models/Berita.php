<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'user_id',
        'judul',
        'slug',
        'konten',
        'gambar_url',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (ADAPTER UNTUK BLADE)
    |--------------------------------------------------------------------------
    */

    // Untuk Blade: $berita->thumbnail
    public function getThumbnailAttribute(): ?string
    {
        return $this->gambar_url;
    }

    // Untuk Blade: $berita->ringkasan
    public function getRingkasanAttribute(): string
    {
        return Str::limit(strip_tags($this->konten), 120);
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
