<?php

namespace App\Enum;

enum TryoutStatus: string
{
    case NOT_STARTED = 'not_started';
    case STARTED = 'started';
    case FINISHED = 'finished';
    case PAUSED = 'paused';
}
