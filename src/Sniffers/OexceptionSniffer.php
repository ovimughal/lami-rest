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

use Lamirest\BaseProvider\OhandlerBaseProvider;

/**
 * Description of OexceptionSniffer
 *
 * @author OviMughal
 */
class OexceptionSniffer extends OhandlerBaseProvider
{

    public static function exceptionScanner($result)
    {
        if (is_a($result, 'Exception')) {
            $res = parent::getOserviceLocator()->get('Response');
            $res->setStatusCode(417); //Expectation Failed

            if (ENV) {
                $result = $result->getMessage();
            } else {
                $result = 'Exc-Please Contact Administrator';
            }
            parent::setSuccess(false);
            parent::setMsg('An Exception Occured');
        }

        if (is_array($result)) {
            if (!count($result)) {
                $result = (object) null;
            }
        } else {
            if ('' === $result || null === $result) {
                $result = (object) null;
            }
        }

        return $result;
    }

}
