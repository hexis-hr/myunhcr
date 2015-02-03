<?php

namespace Application\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ReportIncidentFormFilter implements InputFilterAwareInterface {

    protected $inputFilter;

    // Add content to these methods:
    public function setInputFilter (InputFilterInterface $inputFilter) {

        throw new \Exception("Not used");
    }

    public function getInputFilter () {

        if (!$this->inputFilter) {

            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'anonymous',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'category',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'category',
                'validators' => array(
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'message' => 'Category must be chosen from the list',
                            'callback' => function ($value) {
                                    if ($value !== '0') {
                                        return true;
                                    }
                                    return false;
                                },
                        )
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'type',
                'validators' => array(
                    array(
                        'name' => 'Callback',
                        'options' => array(
                            'message' => 'Type must be chosen from the list',
                            'callback' => function ($value) {
                                    if ($value !== '0') {
                                        return true;
                                    }
                                    return false;
                                },
                        )
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
