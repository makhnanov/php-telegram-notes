<?php

namespace App\Handlers;

use App\Core\CallbackData;
use App\Core\User;
use Makhnanov\Telegram81\Api\Enumeration\ParseMode;
use Makhnanov\Telegram81\Api\Type\EntityCollection;
use Makhnanov\Telegram81\Api\Type\Message;
use Makhnanov\Telegram81\Api\Type\ReplyMarkup;
use Makhnanov\Telegram81\Api\Type\Update;
use Makhnanov\Telegram81\Helper\ResponsiveResultativeInterface;

abstract class Handler
{
    protected Priority $priority = Priority::low_priority;

    protected ?string $text;

    protected CallbackData $callbackData;

    protected ?int $chat_id;
    protected ?int $message_id;

    protected ?string $chat_type;

    /**
     * @ru Прервать весь цикл поиска нужного обработчика после первого подходящего
     * @en Break all handler loop after first Handler::needHandle === true
     */
    protected bool $needBreak = true;

    abstract public function needHandle(): bool;

    abstract public function handle(): void;


    public function __construct(protected User $user) {
        $update = update();
        $this->text = $update->message?->text;
        $this->callbackData = CallbackData::fill($update->callback_query?->data);
        $this->chat_id = $update->message?->chat?->id ?? $update?->callback_query?->message?->chat?->id;
        $this->message_id = $update->message?->message_id ?? $update->callback_query?->message?->message_id;
        $this->chat_type = $update->message?->chat?->type;
    }

    final public function getPriority(): Priority
    {
        return $this->priority;
    }

    final public function needBreak(): bool
    {
        return $this->needBreak;
    }

    protected function replyMessage(
        string                      $text,
        null|string|ParseMode       $parse_mode = null,
        null|array|EntityCollection $entities = null,
        ?bool                       $disable_web_page_preview = null,
        ?bool                       $disable_notification = null,
        ?int                        $reply_to_message_id = null,
        ?bool                       $allow_sending_without_reply = null,
        null|array|ReplyMarkup      $reply_markup = null,
        ?array                      $viaArray = null,
    ): Message & ResponsiveResultativeInterface {
        return app()->bot->sendMessage(
            $this->chat_id,
            ...func_get_args()
        );
    }
}
