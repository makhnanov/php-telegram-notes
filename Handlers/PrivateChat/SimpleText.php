<?php

namespace App\Handlers\PrivateChat;

use App\Core\Action;
use App\Core\CallbackData;
use Makhnanov\Telegram81\Api\Type\keyboard\inline\InlineKeyboardMarkup;
use MongoDB\BSON\ObjectId;

use function Makhnanov\Telegram81\callbackButton;

class SimpleText extends PrivateChat
{
    public function needHandle(): bool
    {
        return (bool)$this->text;
    }

    public function handle(): void
    {
        $defaultListOid = $this->user->defaultList;
        if (!$defaultListOid) {
            $this->replyMessage('ToDo' . __LINE__);
            return;
        }

        $document = $this->user->getListsCollection()->findOne(['_id' => new ObjectId($defaultListOid)]);
        if (!$document) {
            $this->replyMessage('ToDo' . __LINE__);
            return;
        }

        $this->replyMessage(<<<TEXT
Добавить запись:

$this->text

в список «{$document->name}» ?
TEXT,
            reply_markup: InlineKeyboardMarkup::new([
                callbackButton('Да ✅'),
                callbackButton('Удалить ❌', CallbackData::new(
                    Action::drop_temp
                )),
            ], [
                callbackButton('Да, всегда в этот список'),
            ], [
                callbackButton('Нет, в другой список')
            ], [
                callbackButton('В список «Несортированные»'),
            ]),
        );
    }
}
