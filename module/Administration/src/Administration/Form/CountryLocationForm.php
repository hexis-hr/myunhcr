<?php

namespace Administration\Form;

use Zend\Form\Form;

class CountryLocationForm extends Form {

    public function     __construct ($name = null) {

        parent::__construct('countryLocationForm');

        $this->add(array(
            'name' => 'locationName',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'locationName',
                'class' => 'col-md-12 form-control',
            ),
        ));
    }
}
