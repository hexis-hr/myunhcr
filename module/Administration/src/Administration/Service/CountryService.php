<?php

namespace Administration\Service;

use Administration\Provider\ProvidesEntityManager;

use Administration\Provider\ProvidesServiceLocator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

class CountryService implements ServiceLocatorAwareInterface {

    use ProvidesEntityManager;

    /**
     * @return object CodeCountries
    */
    public function getAllCountries () {

        return $this->getEntityManager()->getRepository('Administration\Entity\Country')->findAll();

    }

}
