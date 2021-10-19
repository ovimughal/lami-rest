<?php

namespace Lamirest;

return [
    'service_manager' => [
        'factories' => [
            //'Ojwtizer' => 'Lamirest\Factories\OjwtizerFactory',
            Services\OjwtizerService::class => Factories\OjwtizerFactory::class,
            //'Ohydration' => 'Lamirest\Factories\OhydrationFactory',
            Services\OhydrationService::class => Factories\OhydrationFactory::class,
            //'Oorm' => 'Lamirest\Factories\OormFactory',
            Services\OormService::class => Factories\OormFactory::class,
            //'Ovalidate' => 'Lamirest\Factories\OvalidationFactory',            
            //'Oapisecurity' => 'Lamirest\Factories\OapisecurityFactory',
            Services\OapisecurityService::class => Factories\OapisecurityFactory::class,
            //'Oimagecurler' => 'Lamirest\Factories\OimagecurlerFactory',
            Services\OimagecurlerService::class => Factories\OimagecurlerFactory::class,
            //'Oacl' => 'Lamirest\Factories\OaclFactory',
            Services\OaclService::class => Factories\OaclFactory::class,
            //'OfileManager' => 'Lamirest\Factories\OfilemanagerFactory',
            Services\OfilemanagerService::class => Factories\OfilemanagerFactory::class,
            //'Olanguage' => 'Lamirest\Factories\OlanguageFactory',
            Services\OlanguageService::class => Factories\OlanguageFactory::class,
            //'OConfigHighjacker' => 'Lamirest\Factories\OConfigHighjackerFactory',
            Services\OConfigHighjackerService::class => Factories\OConfigHighjackerFactory::class,
            //'OEncryption' => 'Lamirest\Factories\OEncryptionFactory',
            Services\OEncryptionService::class => Factories\OEncryptionFactory::class,
            //'OTenant' => 'Lamirest\Factories\OTenantFactory',
            Services\OTenantService::class => Factories\OTenantFactory::class
        ],
        'aliases' => [
            // Register an alias for Services
            'Ojwtizer' => Services\OjwtizerService::class,
            'Ohydration' => Services\OhydrationService::class,
            'Oorm' => Services\OormService::class,
            'Oapisecurity' => Services\OapisecurityService::class,
            'Oimagecurler' => Services\OimagecurlerService::class,
            'Oacl' => Services\OaclService::class,
            'Ofilemanager' => Services\OfilemanagerService::class,
            'Olanguage' => Services\OlanguageService::class,
            'OConfigHighjacker' => Services\OConfigHighjackerService::class,
            'OEncryption' => Services\OEncryptionService::class,
            'OTenant' => Services\OTenantService::class
        ],
    ]
];
