<?php

/*
 * This file is part of Lamirest.
 *
 * (c) OwaisMughal <ovi.mughal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lamirest\Services;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

/**
 * Description of OormService
 *
 * @author OviMughal
 */
class OormService
{
    private ContainerInterface $serviceLocator;   
    
    public function __construct(ContainerInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
    
    public function getServiceLocator() : ContainerInterface
    {
        return $this->serviceLocator;
    }
    
    public function entityHydrator(array $dataArr, object $entity, string $doctrineServiceName = 'doctObjMngr') : OhydrationService
    {
        /**
         * @var OhydrationService
         */
        $hydrator = $this->getServiceLocator()->get('Ohydration');
        $hydrator($dataArr, $entity, $doctrineServiceName);

        return $hydrator;
    }
    
    public function getDoctObjMngr(string $doctrineServiceName = 'doctObjMngr') : EntityManager
    {
        /**
         * @var EntityManager
         */
        $doctObjMngr = $this->getServiceLocator()->get($doctrineServiceName);
        return $doctObjMngr;
    }
    
    public function getEntityPath(string $ormConfig = 'orm_default_path') : string
    {
        $oconfig = $this->getServiceLocator()->get('config');
        $entities = $oconfig['oconfig_manager']['entities'];
        $path = $entities[$ormConfig];
        return $path;
    }
}
