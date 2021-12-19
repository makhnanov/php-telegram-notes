<?php

namespace App\Core;

use Makhnanov\PhpEnum81\UpgradedEnumInterface;
use Makhnanov\PhpEnum81\UpgradeEnum;

enum Action implements UpgradedEnumInterface
{
    use UpgradeEnum;

    case undefined;

    case get_all_list;
    case create_new_list;
    case update_all_list_message;
    case change_lists_order;
    case choose_list_for_delete;
    case cancel_create_new_list;
    case not_delete;
    case yes_delete;
    case drop_temp;
    case toggle_metadata;
}
