<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Komunitas;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaKomunitas extends Model
{
    protected $table = 'anggota_komunitas';

    protected $fillable = [
        'user_id',
        'komunitas_id',
        'role',
        'joined_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    /**
     * Tabel ini TIDAK menggunakan created_at / updated_at
     */
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function komunitas(): BelongsTo
    {
        return $this->belongsTo(Komunitas::class, 'komunitas_id');
    }
}
