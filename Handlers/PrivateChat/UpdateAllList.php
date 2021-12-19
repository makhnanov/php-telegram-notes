<?php

namespace App\Handlers\PrivateChat;

use App\Core\Action;
use App\Handlers\Handler;
use JetBrains\PhpStorm\Pure;
use Makhnanov\Telegram81\Api\Exception\UnchangedMessageException;

class UpdateAllList extends PrivateChat
{
    #[Pure]
    public function needHandle(): bool
    {
        return $this->callbackData->action->isEqual(Action::update_all_list_message);
    }

    public static function update(Handler $handler, int $message_id)
    {
        list($text, $keyboard) = ListInfoSend::getTextAndKeyboard($handler);
        try {
            app()->bot->editMessageText(
                $text,
                $handler->chat_id,
                $message_id,
                reply_markup: $keyboard
            );
        } catch (UnchangedMessageException) {
        }
    }

    public function handle(): void
    {
        self::update($this, $this->message_id);
    }
}
