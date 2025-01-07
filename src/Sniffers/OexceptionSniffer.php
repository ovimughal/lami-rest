<?php

/*
 * This file is part of Lamirest.
 *
 * (c) OwaisMughal <ovi.mughal@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Lamirest\Sniffers;

use Exception;
use Lamirest\BaseProvider\OhandlerBaseProvider;
use Lamirest\OpenServices\OLoggerService;

/**
 * Description of OexceptionSniffer
 *
 * @author OviMughal
 */
class OexceptionSniffer extends OhandlerBaseProvider
{
    private static $isEmptyObjectReturn = false;

    public static function exceptionScanner($result)
    {
        if (is_a($result, 'Error')) {
            list($msg, $stackTrace) = [$result->getMessage(), $result->getTrace()[0]];

            self::setMsg('An Error Occured');
            $result = self::errorParser(OErrorType::ERROR, $result, $msg, $stackTrace);
        }
        else if (is_a($result, 'Exception')) {
            list($msg, $stackTrace) = array_pad(explode('Stack trace:', $result->getMessage()), 2, null);

            if(!$stackTrace) {
                $stackTrace = $result->getTrace();
            }

            self::setMsg('An Exception Occured');
            $result = self::errorParser(OErrorType::EXCEPTION, $result, $msg, $stackTrace);
        }

        // if (is_array($result)) {
        //     if (!count($result)) {
        //         /*** Committed by @Atiq Khawaja
        //          * Empty object are not iterable i.e 
        //          * i commented the below line which explicitly casts empty array to object */
        //         // $result = (object) null;
        //     }
        // } else {
        else if (
            (is_array($result) && !count($result)) ||
            '' === $result ||
            null === $result
        ) {
            $result = self::$isEmptyObjectReturn ? (object) null : [];
        }
        // }

        return $result;
    }

    public static function errorParser(int $errType, $result, string $msg, $stackTrace)
    {
        $res = self::getOserviceLocator()->get('Response');
        $res->setStatusCode(500); //Expectation Failed           

        // list($msg, $stackTrace) = array_pad(explode('Stack trace:', $result->getMessage()), 2, null);

        self::setSuccess(false);
        self::setNotificationType('error');
        // self::setMsg('An Exception Occured');

        $logData = [
            [
                'logCall' => 'exceptionScanner',
                'stackTrace' => $stackTrace
            ],
            [
                'code' => $result->getCode(),
                'filename' => $result->getFile(),
                'line' => $result->getLine(),
            ]
        ];

        $prefix = null;
        switch ($errType) {
            case OErrorType::ERROR:
                OLoggerService::ERROR($msg,$logData[0], $logData[1]);
                $prefix = 'Err';
                break;
            case OErrorType::EXCEPTION:
                OLoggerService::CRITICAL($msg,$logData[0], $logData[1]);
                $prefix = 'Exc';
                break;
        }        

        if (DEV_ENV) {
            $stackTrace = implode(',', $logData[1]);
            // $stackTrace['file'] = $logData[1]['filename'];
            // $stackTrace['line'] = $logData[1]['line'];
            // $stackTrace['code'] = $logData[1]['code'];
            // $result = [$msg, $stackTrace];
            $result = $msg.'---STACKTRACE--- '.$stackTrace;
        } else {
            $result = $prefix . '-Please Contact Administrator';
        }

        return $result;
    }
}

class OErrorType
{
    public const ERROR = 1;
    public const EXCEPTION = 2;
}
