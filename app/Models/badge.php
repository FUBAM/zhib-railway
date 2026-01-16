<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    protected $table = 'badge';

    protected $fillable = [
        'nama',
        'image_url',
        'deskripsi',
        'xp_bonus',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'badge_user', 'badge_id', 'user_id')
            ->withPivot('earned_at');
    }
}
