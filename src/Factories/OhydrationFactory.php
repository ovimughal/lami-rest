<?php

/*
 * This file is part of Lamirest.
 *
 * (c) OwaisMughal <ovi.mughal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lamirest\Factories;

use Interop\Container\ContainerInterface;
use Lamirest\Services\OhydrationService;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Description of OhydrationFactory
 *
 * @author OviMughal
 */
class OhydrationFactory implements FactoryInterface
{    
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //return new OhydrationService($serviceLocator);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
        return new OhydrationService($container);
    }

}
