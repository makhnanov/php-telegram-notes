<?php

namespace App\Handlers\PrivateChat\Command\Start;

use App\Core\Action;
use App\Core\CallbackData;
use App\Handlers\PrivateChat\Command\Command;
use App\Helper\PhotoFileId;
use Makhnanov\Telegram81\Api\Type\keyboard\inline\InlineKeyboardMarkup;

use function Makhnanov\Telegram81\callbackButton;

class StrictStart extends Command
{
    public function needHandle(): bool
    {
        return $this->text === '/start';
    }

    function handle(): void
    {
        app()->bot->sendPhoto(
            $this->chat_id,
            PhotoFileId::START_PHOTO,
            <<<TEXT
Добро пожаловать в заметки!

/start
/get_my_id
/get_all_lists
/drop_all_lists
TEXT,
            reply_markup: InlineKeyboardMarkup::new([
                callbackButton('Все списки', CallbackData::new(Action::get_all_list)),
            ])
        );
    }
}
