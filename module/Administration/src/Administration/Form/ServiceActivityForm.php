<?php

namespace Administration\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\FormInterface;

class ServiceActivityForm extends Form {

    public function __construct ($name = null) {

        parent::__construct('serviceActivity');

        $this->add(array(
            'name' => 'activityName',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'activityCategory',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'activityStart',
            'type' => 'Date',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'activityEnd',
            'type' => 'Date',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

    }

}
