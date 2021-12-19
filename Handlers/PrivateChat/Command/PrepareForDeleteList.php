<?php

namespace App\Handlers\PrivateChat\Command;

use App\Core\Action;
use App\Core\CallbackData;
use App\Core\User;
use Exception;
use Makhnanov\Telegram81\Api\Type\keyboard\inline\InlineKeyboardMarkup;
use Yiisoft\Strings\StringHelper;

use function Makhnanov\Telegram81\callbackButton;

class PrepareForDeleteList extends Command
{
    private ?int $positionForDelete;

    public function needHandle(): bool
    {
        if (!StringHelper::startsWith($this->text, '/dl')) {
            return false;
        }
        $positionForDelete = preg_replace('/^\/dl/', '', $this->text);
        if (!preg_match('/^[0-9]+$/', $positionForDelete)) {
            return false;
        }
        $this->positionForDelete = (int)$positionForDelete;
        return true;
    }

    /**
     * @throws Exception
     */
    public function handle(): void
    {
        $this->user->store([User::PROP_COMMAND_MESSAGE_ID_FOR_DELETE_LIST => $this->message_id]);
        $collection = $this->user->getListsCollection();
        $found = $collection->findOne(['position' => $this->positionForDelete]);
        if (!$found) {
            app()->bot->sendMessage($this->chat_id, 'Список не найден. Попробуйте обновить.');
            return;
        }
        $name = $found->name ?? throw new Exception('Name not found.');
        app()->bot->sendMessage(
            $this->chat_id,
            sprintf('Вы уверены что хотите удалить список "%s"?', $name),
            reply_markup: InlineKeyboardMarkup::new([
                callbackButton(
                    'Удалить',
                    CallbackData::new(
                        Action::yes_delete,
                        (string)$found->_id,
                    )
                ),
                callbackButton('Отмена', CallbackData::new(Action::not_delete)),
            ])
        );
        app()->bot->deleteMessage($this->chat_id, $this->message_id);
    }
}
