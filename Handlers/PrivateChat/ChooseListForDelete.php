<?php

namespace App\Handlers\PrivateChat;

use App\Core\Action;
use App\Core\CallbackData;
use JetBrains\PhpStorm\Pure;
use Makhnanov\Telegram81\Api\Type\keyboard\inline\InlineKeyboardMarkup;

use function Makhnanov\Telegram81\callbackButton;

class ChooseListForDelete extends PrivateChat
{
    #[Pure]
    public function needHandle(): bool
    {
        return $this->callbackData->action->isEqual(Action::choose_list_for_delete);
    }

    public function handle(): void
    {
        $collection = $this->user->getListsCollection();
        $lists = $collection->find()->toArray();

        if ($lists) {

            $text = 'Выберите список для удаления:' . EOL . EOL;

            foreach ($lists as $key => $list) {
                if (!isset($list->name) || !isset($list->position)) {
                    continue;
                }
                $text .= $key + 1 . '. ' . $list->name . ' ' . '/dl' . $list->position . EOL;
            }

            $keyboard = InlineKeyboardMarkup::new([
                callbackButton('Назад 🔙', CallbackData::new(Action::update_all_list_message)),
                callbackButton('Обновить 🔄', CallbackData::new(Action::undefined)),
                callbackButton('Метаданные')
            ]);

        } else {
            $text = 'Нечего удалять!';
            $keyboard = InlineKeyboardMarkup::new([
                callbackButton('Назад 🔙', CallbackData::new(Action::update_all_list_message)),
                callbackButton('Обновить 🔄', CallbackData::new(Action::undefined)),
            ]);
        }

        app()->bot->editMessageText(
            $text,
            $this->chat_id,
            $this->message_id,
            reply_markup: $keyboard
        );
    }
}
