<?php

namespace App\Handlers\PrivateChat\Command;

class GetMyId extends Command
{
    public function needHandle(): bool
    {
        return $this->text === '/get_my_id';
    }

    public function handle(): void
    {
        app()->bot->sendMessage(
            $this->chat_id,
            $this->chat_id,
        );
    }
}
