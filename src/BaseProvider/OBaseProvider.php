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

    private static ?int $organizationId = null;

    public static function getOserviceLocator()
    {
        return ServiceInjector::$serviceLocator;
    }

    public static function getOconfig()
    {
        return ServiceInjector::$serviceLocator->get('config');
    }

    public static function getOconfigManager()
    {
        return self::getOconfig()['oconfig_manager'];
    }

    public static function appUrl()
    {
        return self::getOconfigManager()['settings']['app_url'];
    }

    public static function appVersion()
    {
        return self::getOconfigManager()['settings']['app_version'];
    }

    public static function apiUrl()
    {
        return self::getOconfigManager()['settings']['api_url'];
    }

    public static function apiKey()
    {
        return self::getOconfigManager()['api']['api_key'];
    }

    public function setOrganizationId($id)
    {
        self::$organizationId = $id;
    }

    public static function organizationId(bool $asQueryString = false)
    {
        $req = self::getOserviceLocator()->get('Request');
        $tenantIdName = self::getOconfigManager()['tenant']['tenant_id_name'];
        $organizationId = (int)$req->getQuery($tenantIdName);

        if(!$organizationId) {
            $organizationId = self::$organizationId??$organizationId;
        }
        else if (self::$organizationId && ($organizationId != self::$organizationId)) {
            $organizationId = self::$organizationId??$organizationId;
        }

        if ($asQueryString) {
            $organizationId = $tenantIdName.'='.$organizationId;
        }
        return $organizationId;
    }

}
