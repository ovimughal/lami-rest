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
use Laminas\ServiceManager\Factory\FactoryInterface;
use Lamirest\Services\OTenantService;

/**
 * Description of OTenantFactory
 *
 * @author OviMughal
 */
class OTenantFactory implements FactoryInterface
{    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null) : OTenantService
    {
        return new OTenantService($container);
    }

}
