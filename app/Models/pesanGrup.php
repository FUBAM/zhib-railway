<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Grup;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PesanGrup extends Model
{
    use HasFactory;
    protected $table = 'pesan_grup';

    protected $fillable = [
        'grup_id',
        'user_id',
        'pesan',
        'lampiran_url',
        'is_pinned',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    public function grup(): BelongsTo
    {
        return $this->belongsTo(Grup::class, 'grup_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
