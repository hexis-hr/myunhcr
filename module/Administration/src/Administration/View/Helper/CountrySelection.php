<?php

namespace Administration\View\Helper;

use Zend\View\Helper\AbstractHelper;

class CountrySelection extends AbstractHelper {

    protected $sm;

    public function __construct ($serviceManager) {
        $this->sm = $serviceManager;
    }

    public function __invoke () {

        $cs = $this->sm->getServiceLocator()->get('CountryService');

        return $cs->getAllCountries();
    }

}
