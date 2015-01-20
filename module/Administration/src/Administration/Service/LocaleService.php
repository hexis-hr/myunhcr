<?php

namespace Administration\Service;

use Administration\Provider\ProvidesEntityManager;

use Administration\Provider\ProvidesServiceLocator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class LocaleService implements ServiceLocatorAwareInterface {

    use ProvidesEntityManager;

    public function getLocales () {

        $local = \ResourceBundle::getLocales('');

        foreach ($local as $localeKey => $localeValue) {
            $local[$localeValue] = $localeValue;
            unset($local[$localeKey]);
        }

        return $local;
    }

}
