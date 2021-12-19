<?php

namespace App\Handlers\PrivateChat;

use App\Core\Action;
use App\Core\CallbackData;
use App\Core\State;
use App\Core\User;
use JetBrains\PhpStorm\Pure;
use Makhnanov\Telegram81\Api\Type\keyboard\inline\InlineKeyboardMarkup;

use function Makhnanov\Telegram81\callbackButton;

class CreateNewListButton extends PrivateChat
{
    #[Pure]
    public function needHandle(): bool
    {
        return $this->callbackData->action->isEqual(Action::create_new_list);
    }

    public function handle(): void
    {
        $result = app()->bot->sendMessage(
            $this->chat_id,
            'Пожалуйста, отправьте название нового списка или выберите из предложенных:',
            reply_markup: InlineKeyboardMarkup::new(
                [callbackButton('Список покупок')],
                [callbackButton('Задачи'), callbackButton('Не забыть')],
                [callbackButton('Отмена ❌', CallbackData::new(Action::cancel_create_new_list))]
            )
        );
        $this->user->store([
            User::PROP_STATE => State::wait_new_list_name,
            User::PROP_DELETE_MESSAGE_AFTER_CREATE_LIST => $result->message_id,
            User::PROP_UPDATE_FULL_LIST_MESSAGE_ID => $this->message_id
        ]);
    }
}
