<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Badge;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BadgeUser extends Model
{
    protected $table = 'badge_user';

    protected $fillable = [
        'user_id',
        'badge_id',
        'earned_at',
    ];

    protected $casts = [
        'earned_at' => 'datetime',
    ];

    /**
     * Tabel tidak memakai created_at / updated_at
     */
    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function badge(): BelongsTo
    {
        return $this->belongsTo(Badge::class, 'badge_id');
    }
}
