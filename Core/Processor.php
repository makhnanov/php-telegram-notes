<?php

namespace App\Core;

use App\Handlers\Handler;
use Exception;
use Makhnanov\Telegram81\Api\Enumeration\ChatType;
use ReflectionClass;

use function App\in_array;

class Processor
{
    public static function processUpdate(): void
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $sorted = self::getHandlersSortedByPriority();
        foreach ($sorted as $handler) {

            /** @var Handler $handler */
            if (!$handler->needHandle()) {
                continue;
            }

            $handler->handle();

            if ($handler->needBreak()) {
                return;
            }
        }
    }

    /**
     * @throws Exception
     */
    public static function getHandlersSortedByPriority(): array
    {
        $exclude = [];

        if (
            !in_array(ChatType::private->name, [
                update()?->message?->chat?->type,
                update()?->callback_query?->message?->chat?->type
            ])
        ) {
            $exclude[] = 'PrivateChat';
        }

        if (!preg_match('/^\/[A-Za-z0-9]/', update()?->message?->text ?? '')) {
            $exclude[] = 'Command';
        }

        $handlerClasList = Processor::getHandlerList('/Handlers', $exclude);

        $user = new User(
            update()?->message?->chat?->id
            ?? update()?->callback_query?->message?->chat?->id
            ?? throw new Exception('chat_id not found')
        );

        $handlers = [];
        foreach ($handlerClasList as $handlerClassString) {
            /** @var Handler $handler */
            $handlers[] = new $handlerClassString($user);
        }

        usort($handlers, function (Handler $a, Handler $b) {
            return $b->getPriority()->value <=> $a->getPriority()->value;
        });

        return $handlers;
    }

    public static function getHandlerList(string $dir, array $exclude = []): array
    {
        $handlers = [];
        $content = scandir(app()->basePath . $dir);
        $content = array_diff($content, $exclude);
        foreach ($content as $dirOrFile) {

            if (in_array($dirOrFile, ['.', '..'])) {
                continue;
            }

            $fullPath = $dir . '/' . $dirOrFile;

            if (is_dir(app()->basePath . $fullPath)) {

                $recursive = self::getHandlerList($fullPath, $exclude);
                if ($recursive) {
                    $handlers = array_merge($handlers, $recursive);
                }

            } else {

                $fullClassName = self::removePhpSuffix(self::replaceSlashes('App' . $fullPath));
                if (
                    class_exists($fullClassName)
                    && !(new ReflectionClass($fullClassName))->isAbstract()
                    && is_a($fullClassName, Handler::class, true)
                ) {
                    $handlers[] = $fullClassName;
                }

            }
        }
        return $handlers;
    }

    private static function replaceSlashes(string $text): string
    {
        return str_replace('/', '\\', $text);
    }

    private static function removePhpSuffix(string $text): string
    {
        return preg_replace('/.php$/', '', $text);
    }
}
