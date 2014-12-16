<?php

namespace Administration\Form;

use Zend\Form\Form;

class FileForm extends Form {

    public function __construct ($name = null) {

        parent::__construct('file');

        $this->add(array(
            'name' => 'file',
            'type' => 'File',
            'attributes' => array(
                'id' => 'file',
                'class' => 'col-md-12 form-control',
            ),
        ));
    }
}
