<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TryoutHasPaket extends Model
{
    protected $table = 'tryout_has_pakets';

    protected $fillable = [
        'tryout_id',
        'paket_id',
    ];

    public function tryout()
    {
        return $this->belongsTo(Tryouts::class);
    }

    public function paket()
    {
        return $this->belongsTo(PaketTryout::class);
    }
}
