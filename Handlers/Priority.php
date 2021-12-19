<?php

namespace App\Handlers;

enum Priority: int
{
    case max = 999;

    case command = 100;
    case wait_text = 90;
    case low_priority = 0;
}
