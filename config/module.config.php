<?php

namespace Lamirest;

use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'controller_plugins' => [
        'factories' => [
            Gateway\GateKeeper::class => InvokableFactory::class
        ],
        'aliases' => [
            'GateKeeper' => Gateway\GateKeeper::class
        ]
    ],
];
