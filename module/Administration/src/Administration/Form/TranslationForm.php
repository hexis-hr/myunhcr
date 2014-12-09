<?php

namespace Administration\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Stdlib\ArrayUtils;

class TranslationForm extends Form
{

    public function __construct($name = null)
    {

        parent::__construct('translation');

        $this->add(array(
            'name' => 'translation-file',
            'type' => 'File',
            'attributes' => array(
                'id' => 'translation-file',
                'class' => 'col-md-12 form-control',
            ),
        ));
    }
}
