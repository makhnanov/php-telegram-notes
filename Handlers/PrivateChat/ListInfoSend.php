<?php

declare(strict_types=1);

namespace App\Handlers\PrivateChat;

use App\Core\Action;
use App\Core\CallbackData;
use App\Core\Messages\Lists;
use App\Core\User;
use App\Handlers\Handler;
use JetBrains\PhpStorm\Pure;
use Makhnanov\Telegram81\Api\Type\keyboard\inline\InlineKeyboardMarkup;

use function Makhnanov\Telegram81\callbackButton;

class ListInfoSend extends PrivateChat
{
    #[Pure]
    public function needHandle(): bool
    {
        return $this->callbackData->action->isEqual(Action::get_all_list);
    }

    public static function getTextAndKeyboard(Handler $handler, ?string $newText = null): array
    {
        $collection = $handler->user->getListsCollection();
        $lists = $collection->find()->toArray();

        if ($lists) {
            $text = 'Ваши списки:' . EOL . EOL;
            foreach ($lists as $key => $list) {
                if (!isset($list->name) || !isset($list->position)) {
                    continue;
                }
                //            /** @var ObjectId $id */
                //            $id = $list->_id;
                $text .= $key + 1 . '. ' . $list->name . ($list->name === $newText ? ' 🆕 ' : '') . ' ' . '/show' . $list->position . EOL;
            }
            $keyboard = Lists::infoKeyboard();

        } else {
            $text = 'Нет ни одного списка!';
            $keyboard = InlineKeyboardMarkup::new([
                callbackButton('Обновление 🔄', CallbackData::new(Action::update_all_list_message)),
                callbackButton('Создание 📝', CallbackData::new(Action::create_new_list))
            ]);
        }

        return [$text, $keyboard];
    }

    public function handle(): void
    {
        list($text, $keyboard) = self::getTextAndKeyboard($this);
        $message = app()->bot->sendMessage(
            $this->chat_id,
            $text,
            reply_markup: $keyboard
        );
        $this->user->store([
            User::PROP_MESSAGE_ID_WITH_ALL_LIST => $message->message_id
        ]);
    }
}
