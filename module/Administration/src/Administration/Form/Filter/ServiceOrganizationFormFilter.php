<?php

namespace Administration\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ServiceOrganizationFormFilter implements InputFilterAwareInterface {

    public $id;
    public $organizationName;
    public $organizationAcronym;
    public $organizationUrl;
    public $description;
    protected $inputFilter;

    public function exchangeArray ($data) {

        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->organizationName = (isset($data['organizationName'])) ? $data['organizationName'] : null;
        $this->organizationAcronym = (isset($data['organizationAcronym'])) ? $data['organizationAcronym'] : null;
        $this->organizationUrl = (isset($data['organizationUrl'])) ? $data['organizationUrl'] : null;
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
                'name' => 'organizationName',
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
                'name' => 'organizationAcronym',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'Alnum',
                        'options' => array(
                            'allowWhiteSpace' => true,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'organizationAddress',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'organizationEmail',
                'required' => true,
                'validators' => array(
                    array('name' => 'EmailAddress')
                ),
            ));

            $inputFilter->add(array(
                'name' => 'organizationPhone',
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '/^((\+| 00)+[0-4]+[0-9]+)?([ -\/]?[0-9]{2,15}){1,5}$/',
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'organizationOpeningHours',
                'required' => false,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'organizationUrl',
                'required' => false,
                'validators' => array(
                    array('name' => 'Uri'),
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
