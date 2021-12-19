<?php /** @noinspection PhpUndefinedFieldInspection */

namespace App\Core;

use MongoDB\Collection;
use MongoDB\Model\BSONDocument;

class Mongo
{
    public static function getListsCollection(int $telegramId): Collection
    {
        return app()->mongo->lists->{'user' . $telegramId};
    }

    public static function getContentsCollection(int $telegramId): Collection
    {
        return app()->mongo->lists->{'contents' . $telegramId};
    }

    public static function getStatesCollection(): Collection
    {
        return app()->mongo->users->states;
    }

    public static function getUserState(int $telegramId): ?BSONDocument
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return self::getStatesCollection()->findOne(['_id' => $telegramId]);
    }
}
