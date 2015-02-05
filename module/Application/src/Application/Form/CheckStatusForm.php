<?php

namespace Application\Form;

use Zend\Form\Form;

class CheckStatusForm extends Form {

    public function __construct ($entityManager, $name = null) {

        parent::__construct('checkStatus');

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
                'data-placeholder' => 'Day/Month/Year',
                // disabled for now because of inability to choose year
                // 'data-inputdate' => '',
            ),
        ));

        $this->add(array(
            'name' => 'checkWhat',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'formSelect customSelect_select -custom',
                'id' => 'checkWhat',
            ),
            'options' => array(
                'value_options' => array(
                    'Asylum Application' => 'Asylum Application',
                ),
            ),
        ));
    }
}
