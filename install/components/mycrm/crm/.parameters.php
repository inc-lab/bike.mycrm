<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

Loader::includeModule('bike.mycrm');
$arComponentParameters = [
    'GROUPS' => [
    ],
    'PARAMETERS' => [
        
    ]
];