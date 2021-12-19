<?php

namespace App\Handlers\PrivateChat;

use App\Core\Action;
use App\Core\State;
use App\Core\User;
use JetBrains\PhpStorm\Pure;

class CancelCreateNewList extends PrivateChat
{
    #[Pure]
    public function needHandle(): bool
    {
        return $this->callbackData->action->isEqual(Action::cancel_create_new_list);
    }

    public function handle(): void
    {
        $this->user->store([User::PROP_STATE => State::unset]);
        app()->bot->deleteMessage(
            $this->chat_id,
            $this->message_id
        );
    }
}
