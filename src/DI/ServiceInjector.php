<?php

/*
 * This file is part of Lamirest.
 *
 * (c) OwaisMughal <ovi.mughal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lamirest\DI;

use Interop\Container\ContainerInterface;

/**
 * Description of ServiceInjector
 *
 * @author OviMughal
 */
class ServiceInjector
{

    public static ContainerInterface $serviceLocator;

    public static function oJwtizer(): \Lamirest\Services\OjwtizerService
    {
        /*
         * Methods that can be used from the caller
         * # oJwtify($userData)
         */
        return self::$serviceLocator->get('Ojwtizer');
    }

    public static function oOrm(): \Lamirest\Services\OormService
    {
        /*
         * Methods that can be used from the caller
         * # getDoctObjMngr()
         * # entityHydrator()
         * # getServiceLocator()
         * # getEntityPath()
         */
        return self::$serviceLocator->get('Oorm');
    }

    public static function iCurler(): \Lamirest\Services\OimagecurlerService
    {
        /*
         * Methods that can be used from the caller
         * # getCurledImageData($imageName, $imageResource = null)
         * # getPictureData($rawData, $imageName)
         */
        return self::$serviceLocator->get('Oimagecurler');
    }

    /**
     * This gives you access to various methods to solve and handle
     * complex file operations and file structure.
     * 
     * Methods available are:
     * 
     * Get the root path of application from anywhere
     * 1. getAppRootPath() 
     * 
     * Get Folder path of given resource from anywhere. Resource name is the
     * Key given in config/autoload/lamirest.global.php
     * 2. getFolderPath($resourceName = null) 
     * 
     * Get value of Key anywhere given in config/autoload/lamirest.global.php
     * 3. getConfigValue($key)
     * 
     * Download file. Just pass filename & resource key in config/autoload/lamirest.global.php
     * 4. downloadFile($filename, $resourceName = null)
     * 
     * Get File as a data, & use it anywhere. You can also fetch file data from 
     * remote locations as well just fill `file_server` key in config/autoload/lamirest.global.php
     * 5. getFileData($fileName, $fileResource = null, $fromFileServer = false)
     * 
     * @author OviMughal
     */
    public static function oFileManager(): \Lamirest\Services\OfilemanagerService
    {
        /*
         * Methods that can be used from the caller
         * # downloadFile($imageName, $folderName = null)
         */
        return self::$serviceLocator->get('Ofilemanager');
    }

    public static function oLanguage(): \Lamirest\Services\OlanguageService
    {
        /*
         * Methods that can be used from the caller
         * # setLanguage($language)
         * # getLanguage()
         */
        return self::$serviceLocator->get('Olanguage');
    }

    public static function oConfigHighjacker(): \Lamirest\Services\OConfigHighjackerService
    {
        return self::$serviceLocator->get('OConfigHighjacker');
    }

    public static function oEncryption(): \Lamirest\Services\OEncryptionService
    {
        return self::$serviceLocator->get('OEncryption');
    }

    public static function oTenant(): \Lamirest\Services\OTenantService
    {
        return self::$serviceLocator->get('OTenant');
    }

}
