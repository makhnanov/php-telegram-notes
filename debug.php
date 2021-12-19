<?php

declare(strict_types=1);

use App\Core\State;
use App\Core\User;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';

//do {
//    foreach (app()->bot->getUpdates() as $update) {
//        Executor::execute($update);
//    }
//} while (true);

$user = new User(390941013);
//var_dump($user->isNew);
//var_dump($user->state->name);
$user->store([
    User::PROP_UPDATE_FULL_LIST_MESSAGE_ID => 555
]);
//var_dump($user->isNew);
//var_dump($user->state->name);

//$user = new User(390941013);
var_dump($user);

$user->store([
    User::PROP_UPDATE_FULL_LIST_MESSAGE_ID => null
]);

var_dump($user);
