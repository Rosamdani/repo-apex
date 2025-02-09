<?php

namespace App\Observers;

use App\Models\Tryouts;
use App\Enum\TryoutStatus;
use App\Models\UserAnswer;
use App\Models\UserTryouts;
use Illuminate\Support\Facades\Log;

class UserTryoutsObserver
{

    /**
     * Handle the UserTryouts "updated" event.
     */
    public function updated(UserTryouts $userTryouts): void
    {
        // Periksa apakah status berubah menjadi 'finished'
        if ($userTryouts->isDirty('status') && $userTryouts->status === TryoutStatus::FINISHED) {
            try {
                // Ambil soal dan jawaban yang diperlukan saja
                $questions = Tryouts::where('id', $userTryouts->tryout_id)
                    ->with([
                        'questions:id,tryout_id,jawaban' // Hanya ambil kolom yang diperlukan
                    ])
                    ->firstOrFail()
                    ->questions;

                // Ambil jawaban pengguna dengan kunci soal_id
                $userAnswers = UserAnswer::where('user_id', $userTryouts->user_id)
                    ->whereIn('soal_id', $questions->pluck('id')) // Filter hanya yang terkait
                    ->pluck('jawaban', 'soal_id'); // Format hasil sebagai [soal_id => jawaban]

                // Hitung jumlah jawaban benar
                $correctAnswers = $questions->filter(function ($question) use ($userAnswers) {
                    return isset($userAnswers[$question->id]) && $userAnswers[$question->id] === $question->jawaban;
                })->count();

                // Hitung nilai
                $totalQuestions = $questions->count();
                $score = $totalQuestions > 0
                    ? round(($correctAnswers / $totalQuestions) * 100, 2) // Hitung nilai dengan 2 desimal
                    : 0;

                // Update nilai tanpa memicu observer lagi
                $userTryouts->forceFill(['nilai' => $score])->saveQuietly();
            } catch (\Exception $e) {
                Log::error('Error calculating score: ' . $e->getMessage());
            }
        }
    }
}
