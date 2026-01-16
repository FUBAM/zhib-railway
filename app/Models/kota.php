<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Komunitas;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kota extends Model
{
    protected $table = 'kota';

    protected $fillable = [
        'nama',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function komunitas(): HasMany
    {
        return $this->hasMany(Komunitas::class, 'kota_id');
    }
}
