<?php

namespace Administration\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\FormInterface;

class IncidentCategoryForm extends Form {

    public function __construct ($entityManager, $name = null) {

        parent::__construct('incidentCategory');

        $this->add(array(
            'name' => 'category',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

    }

}
