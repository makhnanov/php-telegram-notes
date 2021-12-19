<?php

namespace App\Handlers\PrivateChat\Command;

use App\Handlers\PrivateChat\ListInfoSend;

class GetAllLists extends Command
{
    public function needHandle(): bool
    {
        return $this->text === '/get_all_lists';
    }

    public function handle(): void
    {
        (new ListInfoSend(update(), $this->user))->handle();
    }
}
