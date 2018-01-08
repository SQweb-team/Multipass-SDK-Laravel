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

    /* Plugin locale, supported: en_US, en_GB, fr_FR */
    'lang'              => env('SQW_LANG', 'en_US'),

    /* Buttons customization (see README.md) */
    'login'             => '',
    'connected'         => '',
    'support'           => '',
    'btn_noads'         => '',
    'login_tiny'        => '',
    'connected_s'       => '',
    'btn_unlimited'     => '',
    'connected_tiny'    => '',
    'connected_support' => '',
];
