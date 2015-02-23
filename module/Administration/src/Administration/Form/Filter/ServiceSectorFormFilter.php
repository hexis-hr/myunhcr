<?php

namespace Administration\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ServiceSectorFormFilter implements InputFilterAwareInterface {

    public $id;
    public $sectorName;
    public $sectorAcronym;
    public $sectorUrl;
    public $description;
    protected $inputFilter;

    public function exchangeArray ($data) {

        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->sectorName = (isset($data['sectorName'])) ? $data['sectorName'] : null;
        $this->sectorAcronym = (isset($data['sectorAcronym'])) ? $data['sectorAcronym'] : null;
        $this->sectorUrl = (isset($data['sectorUrl'])) ? $data['sectorUrl'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
    }

    // Add content to these methods:
    public function setInputFilter (InputFilterInterface $inputFilter) {

        throw new \Exception("Not used");
    }

    public function getInputFilter () {

        if (!$this->inputFilter) {

            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'sectorName',
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
                'name' => 'sectorAcronym',
                'required' => false,
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
                'name' => 'description',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
