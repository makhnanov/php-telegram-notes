<?php

namespace App\Handlers\PrivateChat;

use App\Core\State;
use App\Core\User;
use App\Handlers\Priority;
use Exception;
use JetBrains\PhpStorm\Pure;
use Makhnanov\Telegram81\Api\Exception\NoResultException;
use MongoDB\Model\BSONDocument;
use Throwable;

class SaveNewListName extends PrivateChat
{
    protected Priority $priority = Priority::wait_text;

    #[Pure]
    public function needHandle(): bool
    {
        return $this->user->assertState(State::wait_new_list_name) && $this->text;
    }

    /**
     * @throws NoResultException|Exception
     */
    public function handle(): void
    {
        $collection = $this->user->getListsCollection();
        $already = $collection->find(['name' => $this->text]);
        if (!$already->toArray()) {

            /** @var $newDocumentPosition BSONDocument */
            $newDocumentPosition = $collection->findOne([], ['sort' => ['position' => -1]]);

            if (!$newDocumentPosition) {
                $newDocumentPosition = 1;
            } else {
                $newDocumentPosition = $newDocumentPosition->position ?? throw new Exception('Property "position" forgotten.');
                $newDocumentPosition += 1;
            }

            $inserted = $collection->insertOne([
                'name' => $this->text,
                'position' => $newDocumentPosition,
                'created_at' => time(),
                'last_add_at' => null,
                'last_remove_at' => null,
            ]);

            $this->user->store([
                User::PROP_DEFAULT_LIST => (string)$inserted->getInsertedId()
            ]);

            try {
                app()->bot->deleteMessage($this->chat_id, $this->user->deleteMessageAfterCreateList);
            } catch (Throwable) {
            }

            app()->bot->deleteMessage($this->chat_id, $this->message_id);

            list($text, $keyboard) = ListInfoSend::getTextAndKeyboard($this, $this->text);

            try {
                app()->bot->editMessageText(
                    $text,
                    $this->chat_id,
                    $this->user->updateFullListMessageId,
                    reply_markup: $keyboard
                );
            } catch (Throwable) {
            }

            $this->user->fresh();

            return;
        }
        app()->bot->sendMessage($this->chat_id, <<<TEXT
Список "$this->text" уже существует. Пожалуйста, выберите другое название.
TEXT
);
    }
}
