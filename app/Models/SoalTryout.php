<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SoalTryout extends Model
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

    protected $fillable = [
        'id',
        'bidang_id',
        'kompetensi_id',
        'nomor',
        'tryout_id',
        'soal',
        'pilihan_a',
        'pilihan_b',
        'pilihan_c',
        'pilihan_d',
        'pilihan_e',
        'jawaban',
        'file_pembahasan',
    ];

    public function tryout()
    {
        return $this->belongsTo(Tryouts::class);
    }

    public function bidang()
    {
        return $this->belongsTo(BidangTryouts::class);
    }

    public function kompetensi()
    {
        return $this->belongsTo(KompetensiTryouts::class);
    }

    public function userAnswer()
    {
        return $this->hasOne(UserAnswer::class, 'soal_id', 'id');
    }

    public function getUserAnswerCountProgress($userId, $tryoutId): int
    {
        return $this::where('tryout_id', $tryoutId)
            ->whereHas('userAnswer', function ($query) use ($userId) { // Pastikan soal terkait dengan jawaban pengguna
                $query->where('user_id', $userId);
            })
            ->count();
    }


    public function getSoalCount($tryoutId)
    {
        return $this->where('tryout_id', $tryoutId)->count();
    }
    public function getUserPercentageProgress($userId, $tryoutId): float
    {
        return $this->getSoalCount($tryoutId) > 0 ? ($this->getUserAnswerCountProgress(Auth::id(), $tryoutId) / $this->getSoalCount($tryoutId)) * 100 : 0;
    }
}
