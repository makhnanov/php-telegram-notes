<?php

namespace App\Handlers\PrivateChat;

use App\Core\Messages\Lists;

class ListInfoEdit extends PrivateChat
{
    public function needHandle(): bool
    {
        return false;
    }

    public function handle(): void
    {
    }
}
