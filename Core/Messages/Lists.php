<?php

namespace App\Core\Messages;

use App\Core\Action;
use App\Core\CallbackData;
use App\Handlers\Handler;
use finfo;
use Makhnanov\Telegram81\Api\Type\keyboard\inline\InlineKeyboardMarkup;

use function Makhnanov\Telegram81\callbackButton;

class Lists
{
    public function __construct(protected Handler $handler)
    {
    }

    public function infoList()
    {

    }

    public function deleteList()
    {

    }

    public function orderList()
    {

    }

    public function renameList()
    {

    }

    public static function infoKeyboard()
    {
        return InlineKeyboardMarkup::new([
            callbackButton('Создать 📝', CallbackData::new(Action::create_new_list)),
            callbackButton('Обновить 🔄', CallbackData::new(Action::update_all_list_message)),
        ], [
            callbackButton('Удалить ❌', CallbackData::new(Action::choose_list_for_delete)),
            callbackButton('Порядок 🔤', CallbackData::new(Action::change_lists_order))
        ],  [
            callbackButton('Переименовать'),
            callbackButton('Метаданные ', CallbackData::new(Action::toggle_metadata))
        ], [
            callbackButton('Параметры')
        ]);
    }
}
