<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BatchTryouts extends Model
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
        'nama',
        'start_date',
        'end_date',
    ];

    public function getTryoutsWithUserProgress($userId, $limit = 3)
    {
        return Tryouts::select(['tryouts.id', 'tryouts.nama', 'tryouts.tanggal', 'tryouts.image', 'batch_tryouts.nama as nama_batch'])
            ->leftJoin('user_tryouts', function ($join) use ($userId) {
                $join->on('tryouts.id', '=', 'user_tryouts.tryout_id')
                    ->where('user_tryouts.user_id', '=', $userId)
                    ->whereIn('user_tryouts.status', ['started', 'finished']);
            })
            ->join('batch_tryouts', 'tryouts.batch_id', '=', 'batch_tryouts.id')
            ->where('batch_tryouts.id', $this->id)
            ->limit($limit)
            ->get()
            ->map(function ($tryout) use ($userId) {
                return $this->mapTryoutWithUserProgress($tryout, $userId);
            })
            ->filter(function ($tryout) {
                return $tryout !== null;
            });
    }

    public function getAllTryoutsWithUserProgress($userId)
    {
        return Tryouts::select(['tryouts.id', 'tryouts.nama', 'tryouts.tanggal', 'tryouts.image', 'batch_tryouts.nama as nama_batch'])
            ->leftJoin('user_tryouts', function ($join) use ($userId) {
                $join->on('tryouts.id', '=', 'user_tryouts.tryout_id')
                    ->where('user_tryouts.user_id', '=', $userId)
                    ->whereIn('user_tryouts.status', ['started', 'finished']);
            })
            ->join('batch_tryouts', 'tryouts.batch_id', '=', 'batch_tryouts.id')
            ->get()
            ->map(function ($tryout) use ($userId) {
                return $this->mapTryoutWithUserProgress($tryout, $userId);
            })
            ->filter(function ($tryout) {
                return $tryout !== null;
            });
    }


    private function mapTryoutWithUserProgress($tryout, $userId)
    {
        $userTryout = UserTryouts::where('user_id', $userId)
            ->where('tryout_id', $tryout->id)
            ->first();

        if ($userTryout && $userTryout->status !== 'belum') {
            $soalTryout = new SoalTryout();
            $tryout->jumlah_soal = $soalTryout->getSoalCount($tryout->id);
            $tryout->progress = $soalTryout->getUserPercentageProgress($userId, $tryout->id);
            $tryout->status_pengerjaan = $userTryout->status;
            return $tryout;
        }

        return null;
    }
}
