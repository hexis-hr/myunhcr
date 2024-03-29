<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\FormInterface;

class PersonalDataForm extends Form {

    public function __construct ($entityManager, $name = null) {

        parent::__construct('chooseSurvey');

        $this->add(array(
            'name' => 'authentification',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'formInput',
                'id' => 'authentication',
                'placeholder' => 'ID Number',
            ),
        ));

        $this->add(array(
            'name' => 'date',
            'type' => 'Date',
            'attributes' => array(
                'class' => 'formInput',
                'id' => 'date',
                'placeholder' => 'Day/Month/Year',
            ),
        ));

        $this->add(array(
            'name' => 'updateCategory',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'formSelect customSelect_select -custom',
                'id' => 'updateCategory',
            ),
            'options' => array(
                'value_options' => array(
                    'Address' => 'Address',
                ),
            ),
        ));
    }
}
