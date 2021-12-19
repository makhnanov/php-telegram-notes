<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

namespace App\Core;

use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;
use Stringable;

#[Immutable]
class CallbackData implements Stringable
{
    protected function __construct(
        public Action  $action,
        /* List for delete */
        public ?string $ld = null,
    ) {
    }

    #[Pure]
    public static function new(
        Action  $action,
        ?string $ld = null,
    ): CallbackData {
        return new self(...func_get_args());
    }

    public static function fill(?string $data): CallbackData
    {
        if ($data) {
            $data = json_decode($data) ?? null;
        }
        $action = $data?->action;
        return self::new(
            Action::tryByName($action, Action::undefined),
            $data?->ld ?? null
        );
    }

    public function __toString(): string
    {
        return json_encode(array_filter([
            'action' => $this->action->name,
            'ld' => $this->ld
        ]));
    }
}
