<?php

namespace App\Handlers\PrivateChat\Command;

use App\Handlers\Priority;
use App\Handlers\PrivateChat\PrivateChat;

abstract class Command extends PrivateChat
{
    protected Priority $priority = Priority::command;
}
