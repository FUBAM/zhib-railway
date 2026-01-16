<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Events;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'user_id',
        'events_id',
        'jumlah_bayar',
        'bukti_url',
        'status', // pending, lunas, ditolak
        'diverifikasi_oleh',
        'alasan_penolakan',
    ];

    protected $casts = [
        'jumlah_bayar' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * User yang melakukan pembayaran
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Event yang dibayar
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Events::class, 'events_id');
    }

    /**
     * Admin yang memverifikasi pembayaran
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }
}