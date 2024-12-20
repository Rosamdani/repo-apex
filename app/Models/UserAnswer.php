<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserAnswer extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    protected $fillable = [
        'id',
        'user_id',
        'soal_id',
        'status',
        'jawaban',
    ];

    public function soal()
    {
        return $this->belongsTo(SoalTryout::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
