<?php

namespace Administration\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;


class IndexController extends AbstractActionController {

    public function indexAction()
    {
        return new ViewModel(array());
    }

}
