<?php

namespace App\Models;

use App\Enum\TryoutStatus;
use App\Observers\UserTryoutsObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[ObservedBy([UserTryoutsObserver::class])]
class UserTryouts extends Model
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
        'user_id',
        'tryout_id',
        'nilai',
        'rank',
        'status',
        'waktu',
        'catatan',
        'question_order',
    ];


    protected $casts = [
        'status' => TryoutStatus::class,
        'question_order' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tryout()
    {
        return $this->belongsTo(Tryouts::class, 'tryout_id');
    }
}
