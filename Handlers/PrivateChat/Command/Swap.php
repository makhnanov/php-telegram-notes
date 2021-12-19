<?php

namespace App\Handlers\PrivateChat\Command;

use App\Handlers\PrivateChat\ListInfoSend;

class Swap extends Command
{
    public function needHandle(): bool
    {
        return $this->text === '/swap';
    }

    public function handle(): void
    {
        $f = $this->user->getListsCollection()->findOne(['position' => ['$gt' => 2]]);
//        (new AllList(update(), $this->user))->handle();
    }
}
