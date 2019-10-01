<?php

declare(strict_types=1);

return [
    'supported_languages' => [
        'en'
    ],
    'address_format' => env('XMPP_ADDRESS_VALIDATION', '@xmpp-server.opendialog.ai'),
    'allowed_message_content' => [
        'text'
    ],
    'communications' => [
        'camel' => [
            'url' => env('XMPP_CAMEL_URL', 'localhost'),
            'port' => env('XMPP_CAMEL_PORT', 3000),
            'protocol' => env('XMPP_CAMEL_PROTOCOL', 'http'),
            'endpoint' => env('XMPP_CAMEL_ENDPOINT', 'incoming')
        ]
    ]
];
