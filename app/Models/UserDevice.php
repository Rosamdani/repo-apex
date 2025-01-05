<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    protected $fillable = ['user_id', 'device_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getUserDevices($userId)
    {
        return static::where('user_id', $userId)->get();
    }
}
