<?php

/*
 * This file is part of Lamirest.
 *
 * (c) OwaisMughal <ovi.mughal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lamirest;

use Laminas\Http\Request;
use Laminas\Mvc\MvcEvent;

class Module
{

    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        $config = [];

        $configFiles = [
            __DIR__ . '/../config/module.config.php',
            __DIR__ . '/../config/services.config.php',
            __DIR__ . '/../config/doctrine.config.php'
        ];

        // Merge all module config options
        foreach ($configFiles as $configFile) {
            $config = \Laminas\Stdlib\ArrayUtils::merge($config, include $configFile);
        }

        return $config;
    }

    public function onBootstrap(MvcEvent $e)
    {
        /**
         * @var Request
         */
        $req = $e->getRequest();
        if($req->getMethod() == 'OPTIONS'){
           die();
        }
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach('route', array($this, 'loadConfiguration'), 1000);
        //$moduleRouteListener = new ModuleRouteListener();
        //$moduleRouteListener->attach($eventManager);
        //$eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'authorizationScanner'));
        //$eventManager->attach(MvcEvent::EVENT_FINISH, array($this, 'thePileDriver'));
    }

    public function loadConfiguration(MvcEvent $e)
    {
        $application = $e->getApplication();
        $sm = $application->getServiceManager();
        $sharedManager = $application->getEventManager()->getSharedManager();

        $router = $sm->get('router');
        $request = $sm->get('request');

        $matchedRoute = $router->match($request);
        if (null !== $matchedRoute) {
            //$route = $matchedRoute->getMatchedRouteName(); //oapi-by Ovi
            //if ('oapi' === substr($route, 0, 4)) {//oapi-by Ovi
                $sharedManager->attach('Laminas\Mvc\Controller\AbstractActionController', 'dispatch', function($e) use ($sm) {
                    $sm->get('ControllerPluginManager')->get('GateKeeper')
                            ->routeIdentifier($e); //pass to the plugin...
                    return $this->authorizationScanner($e);
                }, 1000
                );
           // }
        } else {
            //oapi-by Ovi
            //$path = ltrim($router->getRequestUri()->getPath(), '/');
            //$pathFragments = explode('/', $path);

           // if ('api' === $pathFragments[0]) {
                $res = $sm->get('response');
                $res->getHeaders()->addHeaderLine('Content-Type', 'application/json');
                $res->setContent(json_encode(['success' => false, 'msg' => 'Page Not Found', 'data' => (object) null]));
                return $res->setStatusCode(404);
          //  }
        }
    }

    public function authorizationScanner($e)
    {
        if (405 == $e->getResponse()->getStatusCode()) {
            $e->getResponse()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
            $e->getResponse()->setContent(json_encode(['success' => false, 'msg' => 'Method Not Found', 'data' => (object) null]));
            $e->stopPropagation();
            return $e->getResponse();
        } else if (404 == $e->getResponse()->getStatusCode()) {
            $e->getResponse()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
            $e->getResponse()->setContent(json_encode(['success' => false, 'msg' => 'Page Not Found', 'data' => (object) null]));
            $e->stopPropagation();
            return $e->getResponse();
        } else if (200 != $e->getResponse()->getStatusCode()) {
            $e->getResponse()->getHeaders()->addHeaderLine('Content-Type', 'application/json');
            $e->stopPropagation();
            return $e->getResponse();
        }
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'masterDoctObjMngr' => function($sm) {
                    $em = $sm->get('Doctrine\ORM\EntityManager');
                    return $em;
                },
                'doctObjMngr' => function($sm) {
                    $em = $sm->get('doctrine.entitymanager.orm_tenant');
                    return $em;
                }
            ),
        );
    }

}
