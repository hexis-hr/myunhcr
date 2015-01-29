<?php

namespace Application\Form;

use Zend\Form\Form;

class CompliantForm extends Form {

    public function __construct ($name = null) {

        parent::__construct('compliantForm');

        $this->add(array(
            'name' => 'feedbackMessage',
            'type' => 'Textarea',
            'attributes' => array(
                'id' => 'feedbackMessage',
                'class' => 'formTextarea',
            ),
        ));
    }
}
