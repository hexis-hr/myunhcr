<?php

namespace Administration\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\FormInterface;

class ServicePartnerForm extends Form {

    public function __construct ($name = null) {

        parent::__construct('servicePartner');

        $this->add(array(
            'name' => 'partnerName',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'partnerAcronym',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'partnerUrl',
            'type' => 'Url',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'type' => 'Textarea',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

    }

}
