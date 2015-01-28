<?php

namespace Administration\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\FormInterface;

class IncidentTypeForm extends Form {

    public function __construct ($entityManager, $name = null) {

        parent::__construct('incidentType');

        $this->add(array(
            'name' => 'type',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

    }

}
