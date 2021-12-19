<?php

namespace App\Core;

use JetBrains\PhpStorm\Immutable;

#[Immutable]
class CurrentAction
{
    public ?string $action;

    public function __construct(?object $data)
    {
        $this->action = $data->action ?? null;
    }

    public function assert(Action $action): bool
    {
        return $this->action === $action;
    }
}
