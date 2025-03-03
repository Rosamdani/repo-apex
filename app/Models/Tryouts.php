<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Tryouts extends Model
{
    use HasFactory;
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
        'batch_id',
        'nama',
        'waktu',
        'tanggal',
        'status',
        'image',
        'deskripsi',
        'harga',
        'url',
        'file_pembahasan',
        'is_need_confirm',
        'is_configurable',
        'end_time',
    ];


    public function questions(): HasMany
    {
        return $this->hasMany(SoalTryout::class, 'tryout_id');
    }


    public function batch()
    {
        return $this->belongsTo(BatchTryouts::class, 'batch_id');
    }

    public function userTryouts()
    {
        return $this->hasMany(UserTryouts::class, 'tryout_id');
    }

    public function testimonis()
    {
        return $this->hasMany(Testimoni::class, 'tryout_id');
    }

    public function userAccess()
    {
        return $this->hasMany(UserAccessTryouts::class, 'tryout_id');
    }

    public function tryoutHasPakets()
    {
        return $this->belongsToMany(PaketTryout::class, 'tryout_has_pakets', 'tryout_id', 'paket_id');
    }

    public function extras()
    {
        return $this->morphMany(TryoutExtra::class, 'extraable');
    }
}
