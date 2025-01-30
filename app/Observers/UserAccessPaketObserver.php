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

            foreach ($tryouts as $tryout) {
                $userAccessTryout = $tryout->userAccess()->where('user_id', $userAccessPaket->user_id)->first();
                if ($userAccessTryout) {
                    // Update status user access tryout sesuai dengan status baru
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