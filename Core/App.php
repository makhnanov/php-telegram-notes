<?php

declare(strict_types=1);

namespace App\Core;

use Dotenv\Dotenv;
use Exception;
use JetBrains\PhpStorm\Immutable;
use Makhnanov\Telegram81\Api\Bot;
use Makhnanov\Telegram81\Api\Type\Update;
use MongoDB\Client;

#[Immutable]
class App
{
    public static ?App $self;

    public ?Update $update;

    /**
     * @throws Exception
     */
    protected function __construct(
        public string $basePath,
        public Dotenv $dotenv,
        public Bot    $bot,
        public Client $mongo,
    ) {
        if (!isset(static::$self)) {
            static::$self = $this;
            return;
        }
        throw new Exception('One instance already created.');
    }

    public function setUpdate(Update $update)
    {
        /** @noinspection PhpImmutablePropertyIsWrittenInspection */
        $this->update = $update;
    }

    /**
     * @throws Exception
     */
    public static function instance(): static
    {
        if (!isset(static::$self)) {
            throw new Exception('Please, create before get instance.');
        }
        return static::$self;
    }

    /**
     * @throws Exception
     */
    public static function create(
        string $basePath,
        Dotenv $dotenv,
        Bot    $bot,
        Client $client,
    ): static {
        return new static(...func_get_args());
    }

    public function processUpdate(Update $update): void
    {
        $this->setUpdate($update);
        Processor::processUpdate();

    }
}
