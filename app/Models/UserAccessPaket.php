<?php

namespace App\Models;

use App\Observers\UserAccessPaketObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[ObservedBy([UserAccessPaketObserver::class])]
class UserAccessPaket extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function booted()
    {
        static::creating(function ($tryout) {
            $tryout->id = Str::uuid();
        });
    }

    protected $table = 'user_access_pakets';

    protected $fillable = [
        'user_id',
        'paket_id',
        'status',
        'catatan',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paket()
    {
        return $this->belongsTo(PaketTryout::class);
    }
}