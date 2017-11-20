<?php

return [
    /* Plugin general options */
    'id_site'           => env('SQW_ID_SITE', 00000),
    'sitename'          => env('SQW_SITENAME', 'change_this'),
    'message'           => env('SQW_MESSAGE', ''),
    'adblock_modal'     => env('SQW_ADBLOCK_MODAL', 0),
    'targeting'         => env('SQW_TARGETING', 0),
    'beacon'            => env('SQW_BEACON', 0),
    'debug'             => env('SQW_DEBUG', 0),
    'dwide'             => env('SQW_DWIDE', 1),
    'lang'              => env('SQW_LANG', 'en'),

    /* Buttons customization (see README.md) */
    'login'             => env('SQW_LOGIN', ''),
    'connected'         => env('SQW_CONNECTED', ''),
    'support'           => env('SQW_SUPPORT', ''),
    'btn_noads'         => env('SQW_BTN_NOADS', ''),
    'login_tiny'        => env('SQW_LOGIN_TINY', ''),
    'connected_s'       => env('SQW_CONNECTED_S', ''),
    'btn_unlimited'     => env('SQW_BTN_UNLIMITED', ''),
    'connected_tiny'    => env('SQW_CONNECTED_TINY', ''),
    'connected_support' => env('SQW_CONNECTED_SUPPORT', ''),
];
