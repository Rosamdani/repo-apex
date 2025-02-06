<?php

namespace App\Repository\Contracts;

interface TryoutInterface
{
    public function getTryouts();

    public function getTryoutById($id);

    public function getTryoutByBatchId($batchId);
}