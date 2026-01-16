<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $xp_total
 * @property-read int $trust_score
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'xp_terkini',
        'level_terkini',
        'skor_kepercayaan',
        'terpercaya',
        'foto_profil_url',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'terpercaya'        => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (ADAPTER UNTUK FRONTEND & CONTROLLER)
    |--------------------------------------------------------------------------
    */

    // Untuk Hall of Fame: $user->xp_total
    public function getXpTotalAttribute(): int
    {
        return (int) ($this->xp_terkini ?? 0);
    }

    // Alias aman untuk Trust Score
    public function getTrustScoreAttribute(): int
    {
        return (int) ($this->skor_kepercayaan ?? 0);
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE & HELPER
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    // Komunitas yang dibuat user
    public function komunitasBuatan(): HasMany
    {
        return $this->hasMany(Komunitas::class, 'pembuat_id');
    }

    // Relasi ke komunitas (dengan pivot role moderator)
    public function communities(): BelongsToMany
    {
        return $this->belongsToMany(
            Komunitas::class,
            'anggota_komunitas',
            'user_id',
            'komunitas_id'
        )
        ->using(AnggotaKomunitas::class)
        ->withPivot('id', 'role', 'joined_at')
        ->withTimestamps();
    }

    // Alias Bahasa Indonesia
    public function komunitas(): BelongsToMany
    {
        return $this->communities();
    }

    // Cek moderator via pivot (WAJIB tetap)
    public function isModeratorOf(int $communityId): bool
    {
        return $this->communities()
            ->where('komunitas_id', $communityId)
            ->wherePivot('role', 'moderator')
            ->exists();
    }

    // Event yang diikuti user
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(
            Events::class,
            'peserta_kegiatan',
            'user_id',
            'events_id'
        )
        ->using(PesertaKegiatan::class)
        ->withPivot('status', 'bukti_url', 'review_text')
        ->withTimestamps();
    }

    // Badge user (gamifikasi tambahan)
    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(
            Badge::class,
            'badge_user',
            'user_id',
            'badge_id'
        )
        ->using(BadgeUser::class)
        ->withPivot('earned_at');
    }

    public function tambahXP($amount)
    {
        $this->xp_terkini += $amount;

        // Hitung Level: (Total XP / 100) + 1 (agar mulai dari lvl 1)
        // Contoh: 50 XP = Lvl 1. 120 XP = Lvl 2.
        $this->level_terkini = floor($this->xp_terkini / 100) + 1;

        $this->save();
    }
}
