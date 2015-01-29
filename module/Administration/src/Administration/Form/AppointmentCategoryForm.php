<?php

namespace Administration\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\FormInterface;

class AppointmentCategoryForm extends Form {

    public function __construct ($name = null) {

        parent::__construct('appointmentCategoryForm');

        $this->add(array(
            'name' => 'category',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

    }

}
