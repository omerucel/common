<?php

return [
    'php_di' => [
        'definitions' => [
            \OU\RequestId::class => \DI\factory(function () {
                return new \OU\RequestId('test-id');
            })
        ]
    ]
];