<?php

namespace Lamirest;

$catalogEntityNamespace = getenv('CATALOG_ENTITY_NAMESPACE') === false ? 'Application' : getenv('CATALOG_ENTITY_NAMESPACE');
$tenantEntityNamespace = getenv('TENANT_ENTITY_NAMESPACE') === false ? 'Login' : getenv('TENANT_ENTITY_NAMESPACE');

$dir = __DIR__;

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $start = strpos($dir, '\vendor\\');

    $catalogEntityPath = substr_replace($dir, "\module\\$catalogEntityNamespace\src\Entity", $start);
    $tenantEntityPath = substr_replace($dir, "\module\\$tenantEntityNamespace\src\Entity", $start);
} else {
    $start = strpos($dir, '/vendor/');

    $catalogEntityPath = substr_replace($dir, "/module/$catalogEntityNamespace/src/Entity", $start);
    $tenantEntityPath = substr_replace($dir, "/module/$tenantEntityNamespace/src/Entity", $start);
}

return [
    // comment out when generating entities for tenant db
    'doctrine' => [
        'driver' => [
            //For Catalog database
            $catalogEntityNamespace . '_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [$catalogEntityPath]
            ],
            'orm_default' => [
                'drivers' => [
                    $catalogEntityNamespace . '\Entity' => $catalogEntityNamespace . '_driver'
                ],
            ],

            // For Tenant database
            $tenantEntityNamespace . '_driver' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [$tenantEntityPath]
            ],
            'orm_tenant_chain' => [
                'class'   => \Doctrine\Persistence\Mapping\Driver\MappingDriverChain::class,
                'drivers' => [
                    $tenantEntityNamespace . '\Entity' => $tenantEntityNamespace . '_driver'
                ],
            ],
            // uncomment when generating entities for tenant db
            // 'orm_default' => [
            //     'drivers' => [
            //         $tenantEntityNamespace . '\Entity' => $tenantEntityNamespace . '_driver'
            //     ],
            // ],
        ],
    ]
];
