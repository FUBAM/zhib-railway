<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Komunitas;
use App\Models\PesanGrup;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grup extends Model
{
    protected $table = 'grup';

    protected $fillable = [
        'komunitas_id',
        'nama',
        'type',          // chat | events
        'is_read_only',
    ];

    protected $casts = [
        'is_read_only' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function komunitas(): BelongsTo
    {
        return $this->belongsTo(Komunitas::class, 'komunitas_id');
    }

    public function pesanGrup(): HasMany
    {
        return $this->hasMany(PesanGrup::class, 'grup_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ALIAS (OPSIONAL, UNTUK KOMPATIBILITAS)
    |--------------------------------------------------------------------------
    */

    public function pesan()
    {
        return $this->pesanGrup();
    }
}
