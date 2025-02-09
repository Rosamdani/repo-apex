<?php

namespace App\Console\Commands;

use App\Enum\TryoutStatus;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\UserTryouts;
use App\Models\Tryouts;
use Illuminate\Support\Facades\Log;

class UserTryoutEndTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user-tryout-end-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update tryout status when the end time is passed';

    public function handle()
    {
        $now = Carbon::now();

        $tryouts = Tryouts::where('end_time', '<', $now)->get();

        foreach ($tryouts as $tryout) {
            $userAccessTryouts = $tryout->userAccess()->where('status', 'accepted')->get();

            foreach ($userAccessTryouts as $userAccess) {
                $userTryout = UserTryouts::where('tryout_id', $tryout->id)
                    ->where('user_id', $userAccess->user_id)
                    ->first();

                if (!$userTryout) {
                    $questions = \App\Models\SoalTryout::where('tryout_id', $tryout->id)
                        ->pluck('id')
                        ->toArray();
                    shuffle($questions);

                    UserTryouts::create([
                        'tryout_id' => $tryout->id,
                        'user_id' => $userAccess->user_id,
                        'question_order' => $questions,
                        'nilai' => 0,
                        'status' => TryoutStatus::FINISHED,
                    ]);
                } elseif ($userTryout->status !== TryoutStatus::FINISHED) {
                    $userTryout->status = TryoutStatus::FINISHED;
                    $userTryout->save();
                }
            }
        }

        $this->info('Tryout statuses updated successfully.');
    }
}
