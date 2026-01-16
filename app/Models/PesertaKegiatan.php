<?php

namespace App\Models;

// HAPUS ATAU GANTI BARIS INI:
// use Illuminate\Database\Eloquent\Model;

// PAKAI INI:
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// GANTI extends Model MENJADI extends Pivot
class PesertaKegiatan extends Pivot
{
    protected $table = 'peserta_kegiatan';

    protected $fillable = [
        'user_id',
        'events_id',
        'status',
        'bukti_url',
        'review_text',
    ];

    protected $guarded = ['id'];

    // Wajib true karena di migrasi Anda pakai $table->id();
    public $incrementing = true; 

    public $timestamps = true;

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Events::class, 'events_id');
    }
}