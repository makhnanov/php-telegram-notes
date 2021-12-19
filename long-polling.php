<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';

do {
    foreach (app()->bot->getUpdates() as $update) {
        app()->processUpdate($update);
    }
} while (true);
