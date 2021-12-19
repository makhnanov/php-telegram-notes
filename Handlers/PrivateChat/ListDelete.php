<?php

namespace App\Handlers\PrivateChat;

use App\Core\Action;
use MongoDB\BSON\ObjectId;
use JetBrains\PhpStorm\Pure;

class ListDelete extends PrivateChat
{
    #[Pure]
    public function needHandle(): bool
    {
        return $this->callbackData->action->isEqual(Action::yes_delete) && $this->callbackData->ld;
    }

    public function handle(): void
    {
        $bot = app()->bot;
        $collection = $this->user->getListsCollection();
        dump($this->chat_id);
        dump($this->callbackData->ld);
        $collection->deleteOne(['_id' => new ObjectId($this->callbackData->ld)]);
        $bot->deleteMessage($this->chat_id, $this->message_id);
        $messageIdWithAllList = $this->user->messageIdWithAllList;
        UpdateAllList::update($this, $messageIdWithAllList);
    }
}
