<?php

namespace App\Core;

use Makhnanov\PhpEnum81\UpgradeEnum;

enum State
{
    use UpgradeEnum;

    case wait_new_list_name;
    case unset;
}
