<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Komunitas;
use App\Models\Events;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = [
        'nama',
        'icon_url',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function komunitas(): HasMany
    {
        return $this->hasMany(Komunitas::class, 'kategori_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Events::class, 'kategori_id');
    }
}