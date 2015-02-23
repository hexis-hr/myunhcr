<?php

namespace Administration\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ServiceActivityFormFilter implements InputFilterAwareInterface {

    public $id;
    public $activityName;
    public $activityCategory;
    public $activityStart;
    public $activityEnd;
    protected $inputFilter;

    public function exchangeArray ($data) {

        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->activityName = (isset($data['activityName'])) ? $data['activityName'] : null;
        $this->activityCategory = (isset($data['activityCategory'])) ? $data['activityCategory'] : null;
        $this->activityStart = (isset($data['activityStart'])) ? $data['activityStart'] : null;
        $this->activityEnd = (isset($data['activityEnd'])) ? $data['activityEnd'] : null;
    }

    // Add content to these methods:
    public function setInputFilter (InputFilterInterface $inputFilter) {

        throw new \Exception("Not used");
    }

    public function getInputFilter () {

        if (!$this->inputFilter) {

            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'activityName',
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

            $inputFilter->add(array(
                'name' => 'activityCategory',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'activityStart',
                'required' => true,
            ));

            $inputFilter->add(array(
                'name' => 'activityEnd',
                'required' => true,
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
