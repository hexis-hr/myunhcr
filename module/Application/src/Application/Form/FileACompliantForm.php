<?php

namespace Application\Form;

use Zend\Form\Form;

class FileACompliantForm extends Form {

    public function __construct ($entityManager, $name = null) {

        parent::__construct('fileACompliant');

        $this->add(array(
            'name' => 'feedbackMessage',
            'type' => 'Textarea',
            'attributes' => array(
                'class' => 'formTextarea',
                'id' => 'feedbackMessage',
            ),
        ));
    }
}
