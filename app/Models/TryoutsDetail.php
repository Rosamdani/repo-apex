<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TryoutsDetail extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($tryout) {
            $tryout->id = Str::uuid();
        });
    }

    protected $table = 'tryouts_details';

    protected $fillable = [
        'tryout_id',
        'deskripsi',
        'harga',
        'url',
    ];

    public function tryout()
    {
        return $this->belongsTo(Tryouts::class, 'tryout_id');
    }
}
