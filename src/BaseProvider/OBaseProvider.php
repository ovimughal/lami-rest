<?php

/*
 * This file is part of Lamirest.
 *
 * (c) OwaisMughal <ovi.mughal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lamirest\BaseProvider;

use Lamirest\DI\ServiceInjector;

/**
 * Description of OBaseProvider
 *
 * @author OviMughal
 */
class OBaseProvider
{

    private static $organizationId = null;

    public static function getOserviceLocator()
    {
        return ServiceInjector::$serviceLocator;
    }

    public function getOconfig()
    {
        return ServiceInjector::$serviceLocator->get('config');
    }

    public function getOconfigManager()
    {
        return $this->getOconfig()['oconfig_manager'];
    }

    public function appUrl()
    {
        return $this->getOconfigManager()['settings']['app_url'];
    }

    public function apiUrl()
    {
        return $this->getOconfigManager()['settings']['api_url'];
    }

    public function apiKey()
    {
        return $this->getOconfigManager()['api']['api_key'];
    }

    public function setOrganizationId($id)
    {
        self::$organizationId = $id;
    }

    public function organizationId(bool $asQueryString = false)
    {
        $req = self::getOserviceLocator()->get('Request');
        $tenantIdName = $this->getOconfigManager()['tenant']['tenant_id_name'];
        $organizationId = (int)$req->getQuery($tenantIdName);

        if (!$organizationId) {
            $organizationId = self::$organizationId??$organizationId;
        }

        if ($asQueryString) {
            $organizationId = $tenantIdName.'='.$organizationId;
        }
        return $organizationId;
    }

}
