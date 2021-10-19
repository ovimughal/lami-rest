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
use Lamirest\Services\OjwtizerService;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * Description of OjwtizerFactory
 *
 * @author OviMughal
 */
class OjwtizerFactory implements FactoryInterface
{    
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //return new OjwtizerService($serviceLocator, $this->getConfiguration($serviceLocator));
    }
    
    public function getConfiguration(ContainerInterface $serviceLocator)
    {
        $oconfig = $serviceLocator->get('config');
        return $oconfig['oconfig_manager']['ojwt'];
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) : OjwtizerService
    {
        return new OjwtizerService($container, $this->getConfiguration($container));
    }

}
