<?php

return [
    'id_site'       => env('SQWEB_ID_SITE', 00000),
    'debug'         => env('SQWEB_DEBUG', 0),
    'targeting'     => env('SQWEB_TARGET', 0),
    'beacon'        => env('SQWEB_BEACON', 0),
    'dwide'         => env('SQWEB_DWIDE', 1),
    'lang'          => env('SQWEB_LANG', 'en'),
    'message'       => env('SQWEB_MESSAGE', ''),
    'limit_article' => env('SQWEB_LIMIT_ARTICLE', 5),
];
