<?php

return [
    /* Plugin general options */
    'id_site'           => env('SQW_ID_SITE', 00000),
    'sitename'          => env('SQW_SITENAME', 'change_this'),
    'debug'             => env('SQW_DEBUG', 0),
    'dwide'             => env('SQW_DWIDE', 1),
    'autologin'         => env('SQW_AUTOLOGIN', 1),
    'tunnel'            => env('SQW_TUNNEL', 'support'),

    /* Plugin locale, supported: en_US, en_GB, fr_FR */
    'lang'              => env('SQW_LANG', 'en_US'),

    /* Buttons customization (see README.md) */
    'login'             => '',
    'login_tiny'        => '',
    'connected'         => '',
    'connected_s'       => '',
    'connected_tiny'    => '',
    'connected_support' => '',
    'btn_noads'         => '',
    'btn_unlimited'     => '',
    'support'           => '',
];
