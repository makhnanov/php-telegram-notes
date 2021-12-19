<?php

namespace App\Handlers\PrivateChat;

use App\Core\Action;
use JetBrains\PhpStorm\Pure;

class DropTemp extends PrivateChat
{
    #[Pure]
    public function needHandle(): bool
    {
        return $this->callbackData->action->isEqual(Action::drop_temp);
    }

    public function handle(): void
    {
        app()->bot->deleteMessage($this->chat_id, $this->message_id);
    }
}
