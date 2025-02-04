<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PaketTryout extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($tryout) {
            $tryout->id = Str::uuid();
        });
    }

    protected $fillable = [
        'id',
        'url',
        'paket',
        'harga',
        'deskripsi',
        'status',
        'image',
        'detail',
        'is_need_confirm',
    ];

    public function tryouts()
    {
        return $this->belongsToMany(Tryouts::class, 'tryout_has_pakets', 'paket_id', 'tryout_id');
    }
}
