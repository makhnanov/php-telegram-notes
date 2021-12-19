<?php

namespace App\Handlers\PrivateChat\Command;

class DropAllLists extends Command
{
    public function needHandle(): bool
    {
        return $this->text === '/drop_all_lists';
    }

    public function handle(): void
    {
        $this->user->getListsCollection()->drop();
        app()->bot->sendMessage(
            $this->chat_id,
            'Все списки удалены.'
        );
    }
}
