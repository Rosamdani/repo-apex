<?php

namespace App\Services;

use App\Models\Tryouts;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class TryoutService
{

    public function getUserAccessibleTryouts()
    {
        return Tryouts::whereHas('userAccessTryouts', function ($query) {
            $query->where('user_id', FacadesAuth::id());
        })->get();
    }
}