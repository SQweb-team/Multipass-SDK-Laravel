<?php

return [
    /* Plugin general options */
    'id_site'           => env('MLTPSS_WEBSITE_ID', null),
    'sitename'          => env('MLTPSS_WEBSITE_NAME', null),
    'message'           => env('MLTPSS_MESSAGE', ''),
    'adblock_modal'     => env('MLTPSS_ADBLOCK_MODAL', 0),
    'targeting'         => env('MLTPSS_TARGETING', 0),
    'beacon'            => env('MLTPSS_BEACON', 0),
    'debug'             => env('MLTPSS_DEBUG', 0),
    'dwide'             => env('MLTPSS_DWIDE', 1),
    'autologin'         => env('MLTPSS_AUTOLOGIN', 1),
    'content_limit'     => env('MLTPSS_CONTENT_LIMIT', 5),

    /* Plugin locale, supported: en_US, en_GB, fr_FR */
    'lang'              => env('MLTPSS_LANG', 'en_US'),

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
