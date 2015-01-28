<?php

namespace Administration\Form;

use Zend\Form\Form;

class SurveyODKForm extends Form {

    public function __construct ($name = null) {

        parent::__construct('surveyOdkForm');

        $this->add(array(
            'name' => 'url',
            'type' => 'Url',
            'attributes' => array(
                'id' => 'url',
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'name',
                'class' => 'col-md-12 form-control',
            ),
        ));
    }
}
