<?php

namespace Administration\Controller;

use Administration\Provider\ProvidesEntityManager;

use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController {

    use ProvidesEntityManager;

    public function indexAction () {

        return new ViewModel(array());
    }

    public function countrySelectionAction () {

        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {

            //used in administration modal and top nav
            if ($this->params()->fromRoute('id'))
                $id = (int) $this->params()->fromRoute('id');
            else
                $id = (int) $this->params()->fromPost('id');

            $country = $this->getEntityManager()->getRepository('Administration\Entity\Country')
                ->findOneBy(array('id' => $id));

            //save country in session
            $container = new Container('countrySettings');
            $container->countryId = $country->getId();
            $container->countryName = $country->getName();

            $result = new JsonModel(array(
                'success' => true,
                'countryId' => $country->getId(),
                'country' => $country->getName(),
            ));

        } else {
            $result = new JsonModel(array(
                'error' => true,
            ));
        }

        return $result;
    }

}
