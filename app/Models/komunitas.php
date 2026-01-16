<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Kota;
use App\Models\Kategori;
use App\Models\Events;
use App\Models\Grup;
use App\Models\AnggotaKomunitas;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $members
 */
class Komunitas extends Model
{
    use HasFactory;
    protected $table = 'komunitas';

    protected $fillable = [
        'kota_id',
        'kategori_id',
        'pembuat_id',
        'nama',
        'deskripsi',
        'icon_url',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS (PRIMARY – GUNAKAN INI)
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembuat_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'anggota_komunitas',
            'komunitas_id',
            'user_id'
        )
        ->using(AnggotaKomunitas::class)
        ->withPivot('id', 'role', 'joined_at');
    }

    public function moderators(): BelongsToMany
    {
        return $this->members()->wherePivot('role', 'moderator');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Grup::class, 'komunitas_id');
    }

    public function internalActivities(): HasMany
    {
        return $this->hasMany(Events::class, 'komunitas_id')
            ->where('type', 'kegiatan');
    }

    /*
    |--------------------------------------------------------------------------
    | ALIAS BAHASA INDONESIA (OPSIONAL, AMAN)
    |--------------------------------------------------------------------------
    | Jangan buat logic baru di sini
    */

    public function pembuat()
    {
        return $this->creator();
    }

    public function kota()
    {
        return $this->city();
    }

    public function kategori()
    {
        return $this->category();
    }

    public function grup()
    {
        return $this->groups();
    }

    public function anggota()
    {
        return $this->members();
    }
}