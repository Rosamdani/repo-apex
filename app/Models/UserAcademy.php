<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAcademy extends Model
{
    protected $table = 'user_academies';

    protected $fillable = [
        'user_id',
        'universitas',
        'tahun_masuk',
        'status_pendidikan',
        'semester',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
