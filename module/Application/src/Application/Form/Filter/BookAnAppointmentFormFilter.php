<?php

namespace Application\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class BookAnAppointmentFormFilter implements InputFilterAwareInterface {

    protected $inputFilter;

    // Add content to these methods:
    public function setInputFilter (InputFilterInterface $inputFilter) {

        throw new \Exception("Not used");
    }

    public function getInputFilter () {

        if (!$this->inputFilter) {

            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'appointmentType',
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

            $inputFilter->add(array(
                'name' => 'appointmentDate',
            ));

            $inputFilter->add(array(
                'name' => 'appointmentTime',
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
