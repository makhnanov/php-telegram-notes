<?php

namespace App\Handlers\PrivateChat\Command;

use Yiisoft\Strings\StringHelper;

class Show extends Command
{
    private ?int $listPosition;

    public function needHandle(): bool
    {
        if (!StringHelper::startsWith($this->text, '/show_')) {
            return false;
        }

        $listPosition = preg_replace('/^\/show_/', '', $this->text);
        if (!preg_match('/^[0-9]+$/', $listPosition)) {
            return false;
        }

        $this->listPosition = (int)$listPosition;
        return true;
    }

    public function handle(): void
    {
        $listName = $this->user->getListsCollection()->findOne(['position' => $this->listPosition]);
        if (!$listName) {
            app()->bot->sendMessage($this->chat_id, 'Список не найден.');
            return;
        }
        app()->bot->sendMessage($this->chat_id, (string)$listName->_id);
    }
}
