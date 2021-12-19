<?php

declare(strict_types=1);

use App\Core\App;
use Dotenv\Dotenv;
use Makhnanov\Telegram81\Api\Bot;
use Makhnanov\Telegram81\Api\Type\Update;

require_once __DIR__ . '/redefined.php';

const EOL = PHP_EOL;

function app(): App
{
    /** @noinspection PhpUnhandledExceptionInspection */
    return App::instance();
}

function update(): Update
{
    return app()->update;
}

$dotenv = Dotenv::createUnsafeImmutable(__DIR__, '.env');
$dotenv->load();

/** @noinspection PhpUnhandledExceptionInspection */
$app = App::create(
    __DIR__,
    $dotenv,
    new Bot(getenv('BOT_TOKEN')),
    new MongoDB\Client('mongodb://' . getenv('MONGO_HOST'))
);
