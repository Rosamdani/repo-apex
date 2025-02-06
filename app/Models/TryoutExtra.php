<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TryoutExtra extends Model
{
    protected $table = 'tryout_extras';

    protected $fillable = [
        'extraable_id',
        'extraable_type',
        'type',
        'data',
        'title',
        'display_on',
    ];

    protected $casts = [
        'display_on' => 'array',
    ];


    public function extraable()
    {
        return $this->morphTo();
    }
}
