<?php

namespace Lamirest\Services;

use Exception;
use Interop\Container\ContainerInterface;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\View\Model\JsonModel;
use Login\Model\LoginModel;
use Lamirest\BaseProvider\OhandlerBaseProvider;
use Lamirest\BaseProvider\OmodelBaseProvider;
use Lamirest\DI\ServiceInjector;
use Lamirest\Implement\TenantProviderInterface;
use Lamirest\Sniffers\OexceptionSniffer;

class OTenantService
{
    private TenantProviderInterface $tenantProvider;

    public function __construct(ContainerInterface $container)
    {
        if ($this->isMultitenancyEnabled()) {
            $tenantConfig = ServiceInjector::oFileManager()->getOconfigManager()['tenant']['tenant_provider'];
            $this->tenantProvider = new $tenantConfig;
        }
    }

    public function isMultitenancyEnabled(): bool
    {
        $multitenancy_enabled = (bool)ServiceInjector::oFileManager()->getOconfigManager()['settings']['enable_multitenancy'];
        return $multitenancy_enabled;
    }

    // public function tenantLogin($username, $password)
    // {
    //     self::init();
    //     try {
    //         $loginResult = self::$tenantProvider->login($username, $password);
    //     } catch (Exception $exc) {
    //         throw $exc;
    //     }

    //     return $loginResult;
    // }

    public function tenantInfo(?int $organizationId, ?array $tenantinfo = null)
    {
        try {
            $tenantInfo = $this->tenantProvider->getTenantInfoById($organizationId, $tenantinfo);
        } catch (Exception $exc) {
            throw $exc;
        }

        return $tenantInfo;
    }

    public function tenantConnection(?int $organizationId, ?array $tenantinfo = null)
    {
        try {
            $tenantInfo = $this->tenantProvider->getTenantConnectionInfoById($organizationId, $tenantinfo);
            if ($tenantInfo && count($tenantInfo)) {
                ServiceInjector::oConfigHighjacker()->overrideDbConfig($tenantInfo);
            }
        } catch (Exception $exc) {
            throw $exc;
        }

        return $tenantInfo;
    }

    public function tenantIdentifier()
    {
        try {
            /**
             * @var Response
             */
            $res = ServiceInjector::$serviceLocator->get('Response');
            /**
             * @var Request
             */
            $req = ServiceInjector::$serviceLocator->get('Request');

            $tenantIdName = ServiceInjector::oFileManager()->getOconfigManager()['tenant']['tenant_id_name'];
            $organizationId = (int)$req->getQuery($tenantIdName);
            $tenantInfo = null;

            if ($organizationId) {
                $tenantInfo = $this->tenantConnection($organizationId);
            }
            
            if (!$tenantInfo) {

                // $tenantData['host'] = getenv('MASTER_DB_HOST');
                // $tenantData['port'] = getenv('MASTER_DB_PORT');
                // $tenantData['username'] = getenv('MASTER_DB_USER');
                // $tenantData['password'] = getenv('MASTER_DB_PASS');
                // $tenantData['database'] = getenv('MASTER_DB_NAME');
                // If no connection is provided it throws exception
                // ServiceInjector::oConfigHighjacker()->overrideDbConfig($tenantData);

                throw new Exception('Invalid tenant');
            } else if($tenantInfo['islocked']){
                throw new Exception('Tenant has been locked, you are not authorized to access');
            }
        } catch (Exception $exc) {
            $res->setStatusCode(401); //Unauthorised access to tenant
            OhandlerBaseProvider::setSuccess(false);
            OhandlerBaseProvider::setMsg($exc->getMessage());
            $jsonModel = new JsonModel(OhandlerBaseProvider::getResult());
            $res->setContent($jsonModel->serialize());
        }

        return $res;
    }
}
