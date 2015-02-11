<?php

namespace Administration\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class IncidentTypeFormFilter implements InputFilterAwareInterface {

    public $id;
    public $type;
    protected $inputFilter;

    public function exchangeArray ($data) {

        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->type = (isset($data['type'])) ? $data['type'] : null;
    }

    // Add content to these methods:
    public function setInputFilter (InputFilterInterface $inputFilter) {

        throw new \Exception("Not used");
    }

    public function getInputFilter () {

        if (!$this->inputFilter) {

            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'type',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Alpha',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
