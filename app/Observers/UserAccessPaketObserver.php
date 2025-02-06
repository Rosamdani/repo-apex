<?php

namespace App\Observers;

use App\Models\UserAccessPaket;

class UserAccessPaketObserver
{
    public function updated(UserAccessPaket $userAccessPaket)
    {
        if ($userAccessPaket->isDirty('status')) {
            $newStatus = $userAccessPaket->status;

            $tryouts = $userAccessPaket->paket->tryouts;
            dd($tryouts);
            foreach ($tryouts as $tryout) {
                $userAccessTryout = $tryout->userAccess()->firstOrCreate(
                    ['user_id' => $userAccessPaket->user_id],
                    ['status' => $newStatus]
                );
                if ($userAccessTryout) {
                    $userAccessTryout->update(['status' => $newStatus]);
                }
            }
        }
    }


    public function deleted(UserAccessPaket $userAccessPaket)
    {
        $tryouts = $userAccessPaket->paket->tryouts;
        foreach ($tryouts as $tryout) {
            $tryout->userAccess()->where('user_id', $userAccessPaket->user_id)->delete();
        }
    }
}
