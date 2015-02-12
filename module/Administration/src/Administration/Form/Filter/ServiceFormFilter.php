<?php

namespace Administration\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class ServiceFormFilter implements InputFilterAwareInterface {

    public $id;
    public $serviceName;
    public $serviceAcronym;
    public $address;
    public $email;
    public $phone;
    public $latitude;
    public $longitude;
    public $description;
    public $serviceUrl;
    public $partner;
    protected $inputFilter;

    public function exchangeArray ($data) {

        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->serviceName = (isset($data['serviceName'])) ? $data['serviceName'] : null;
        $this->serviceAcronym = (isset($data['serviceAcronym'])) ? $data['serviceAcronym'] : null;
        $this->address = (isset($data['address'])) ? $data['address'] : null;
        $this->email = (isset($data['email'])) ? $data['email'] : null;
        $this->phone = (isset($data['phone'])) ? $data['phone'] : null;
        $this->latitude = (isset($data['latitude'])) ? $data['latitude'] : null;
        $this->longitude = (isset($data['longitude'])) ? $data['longitude'] : null;
        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->serviceUrl = (isset($data['serviceUrl'])) ? $data['serviceUrl'] : null;
        $this->partner = (isset($data['partner'])) ? $data['partner'] : null;
    }

    // Add content to these methods:
    public function setInputFilter (InputFilterInterface $inputFilter) {

        throw new \Exception("Not used");
    }

    public function getInputFilter () {

        if (!$this->inputFilter) {

            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'serviceName',
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
                'name' => 'serviceAcronym',
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
                'name' => 'address',
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
                'name' => 'email',
                'required' => true,
                'validators' => array(
                    array('name' => 'EmailAddress')
                ),
            ));

            $inputFilter->add(array(
                'name' => 'phone',
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
                'name' => 'latitude',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '(\-?\d+(?:\.\d+)?)',
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'longitude',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Regex',
                        'options' => array(
                            'pattern' => '(\s*(\-?\d+(?:\.\d+)?)$)',
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
                'name' => 'serviceUrl',
                'required' => false,
                'validators' => array(
                    array('name' => 'Uri'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'partner',
                'required' => true,
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
