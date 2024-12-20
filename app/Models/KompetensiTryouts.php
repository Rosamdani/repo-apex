<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KompetensiTryouts extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    protected $table = 'kompetensi_tryouts';

    protected $fillable = [
        'id',
        'nama',
    ];

    public function questions()
    {
        return $this->hasMany(SoalTryout::class);
    }
}
