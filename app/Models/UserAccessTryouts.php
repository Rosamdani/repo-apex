<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserAccessTryouts extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($tryout) {
            $tryout->id = Str::uuid();
        });
    }

    protected $table = 'user_access_tryouts';

    protected $fillable = [
        'user_id',
        'tryout_id',
        'status',
        'catatan',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tryouts()
    {
        return $this->belongsTo(Tryouts::class, 'tryout_id');
    }
}
