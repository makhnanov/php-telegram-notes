<?php

namespace App\Handlers\PrivateChat;

class GetPhotoFileId extends PrivateChat
{
    public function needHandle(): bool
    {
        return false; // return (bool)update()?->message?->photo
    }

    public function handle(): void
    {
        app()->bot->sendMessage(
            $this->chat_id,
            print_r(update()->getResult(), true),
        );
    }
}
