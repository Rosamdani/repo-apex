<?php

namespace App\Repository;

use App\Models\Tryouts;
use App\Repository\Contracts\TryoutInterface;

class TryoutRespository implements TryoutInterface
{


    public function getTryouts()
    {
        return Tryouts::where('status', 1)->get();
    }

    public function getTryoutById($id)
    {
        return Tryouts::find($id);
    }

    public function getTryoutByBatchId($batchId)
    {
        return Tryouts::where('batch_id', $batchId)->get();
    }
}