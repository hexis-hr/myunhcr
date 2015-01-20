<?php

namespace Administration\Provider;

use Zend\ServiceManager\ServiceLocatorInterface;

trait ProvidesServiceLocator {

    protected $serviceLocator;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator (ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator () {
        return $this->serviceLocator;
    }
}
