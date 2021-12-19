<?php

namespace App\Core;

use JetBrains\PhpStorm\Immutable;
use JetBrains\PhpStorm\Pure;
use MongoDB\Collection;

#[Immutable]
class User
{
    public const PROP_STATE = 'state';

    public const PROP_DELETE_MESSAGE_AFTER_CREATE_LIST = 'deleteMessageAfterCreateList';

    public const PROP_UPDATE_FULL_LIST_MESSAGE_ID = 'updateFullListMessageId';

    public const PROP_MESSAGE_ID_WITH_ALL_LIST = 'messageIdWithAllList';

    public const PROP_COMMAND_MESSAGE_ID_FOR_DELETE_LIST = 'commandMessageIdForDeleteList';

    public const PROP_DEFAULT_LIST = 'defaultList';

    public bool $isNew;

    public State $state;

    public ?int $deleteMessageAfterCreateList;

    public ?int $updateFullListMessageId;

    public ?int $createdAt;

    public ?int $lastAddAt;

    public ?int $lastRemoveAt;

    public ?int $messageIdWithAllList;

    public ?string $defaultList;

    public function __construct(public int $id)
    {
        $this->refresh();
    }

    /** @noinspection PhpImmutablePropertyIsWrittenInspection */
    public function refresh()
    {
        $data = Mongo::getUserState($this->id);
        $this->isNew = !(bool)$data;
        $obj = $data?->jsonSerialize();
        $this->state = State::tryByName($obj->state ?? null, State::unset);
        $this->deleteMessageAfterCreateList = $obj->deleteMessageAfterCreateList ?? null;
        update()FullListMessageId = $obj->updateFullListMessageId ?? null;
        $this->createdAt = $obj->created_at ?? null;
        $this->lastAddAt = $obj->last_add_at ?? null;
        $this->lastRemoveAt = $obj->last_remove_at ?? null;
        $this->messageIdWithAllList = $obj->messageIdWithAllList ?? null;
        $this->defaultList = $obj->defaultList ?? null;
    }

    public function store(array $data): void
    {
        if ($this->isNew) {
            Mongo::getStatesCollection()->insertOne(['_id' => $this->id, ...$data]);
        } else {
            foreach ($data as $key => $value) {
                if ($key === self::PROP_STATE && $value instanceof State) {
                    $data[$key] = $value->name;
                }
            }
            Mongo::getStatesCollection()->updateOne(['_id' => $this->id], ['$set' => $data]);
        }
        $this->refresh();
    }

    public function fresh()
    {
        $this->store([
            self::PROP_STATE => State::unset,
            self::PROP_UPDATE_FULL_LIST_MESSAGE_ID => null,
            self::PROP_DELETE_MESSAGE_AFTER_CREATE_LIST => null,
        ]);
    }

    #[Pure]
    public function assertState(State $state): bool
    {
        return $this->state->isEqual($state);
    }

    public function getListsCollection(): Collection
    {
        return Mongo::getListsCollection($this->id);
    }
}
