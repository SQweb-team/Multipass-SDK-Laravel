<?php

return [
    'id_site'       => env('SQW_ID_SITE', 00000),
    'sitename'		=> env('SQW_SITENAME', 'change_this'),
    'message'       => env('SQW_MESSAGE', ''),
    'adblock_modal' => env('SQW_ADBLOCK_MODAL', 0),
    'targeting'     => env('SQW_TARGETING', 0),
    'beacon'        => env('SQW_BEACON', 0),
    'debug'         => env('SQW_DEBUG', 0),
    'dwide'         => env('SQW_DWIDE', 1),
    'lang'          => env('SQW_LANG', 'en'),
];
