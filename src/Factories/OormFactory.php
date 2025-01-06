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
use Lamirest\Services\OormService;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
/**
 * Description of OormFactory
 *
 * @author OviMughal
 */
class OormFactory implements FactoryInterface
{    
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        //return new OormService($serviceLocator);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) : OormService
    {
        return new OormService($container);
    }

}
