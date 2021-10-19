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

use Interop\Container\ContainerInterface;
use Laminas\Http\Request;

/**
 * Description of OlanguageService
 *
 * @author ovimughal
 */
class OlanguageService
{

    private ?string $language = null;
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    private function extractLanguage() : void
    {
        /**
         * @var Request
         */
        $request = $this->container->get('Request');
        $languageHeader = $request->getHeader('X-Language');

        if ($languageHeader) {
            list($language) = sscanf($languageHeader->toString(), 'X-Language: %s');            
        }
        else {
            $language = 'en';
        }

        $this->setLanguage($language);
    }

    private function setLanguage(string $language) : void
    {
        $this->language = $language;
    }

    public function getLanguage() : ?string 
    {
        $this->extractLanguage();
        return $this->language;
    }

}
