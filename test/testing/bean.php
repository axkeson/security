<?php declare(strict_types=1);

return [
    'config' => [
        'path' => __DIR__ . '/../config',
    ],
    \Xswoft\Security\Contract\AuthManagerInterface::class=>[
        'class' => \XswoftTest\Security\Testing\TestManager::class
    ],
    'httpDispatcher' => [
        'middlewares' => [
            \Xswoft\Security\Middleware\AuthMiddleware::class,
            \Xswoft\Security\Middleware\AclMiddleware::class,
        ],
    ]
];
