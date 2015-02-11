<?php

namespace Administration\Form\Filter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class TranslationFormFilter implements InputFilterAwareInterface {

    public $id;
    public $translationFile;
    public $translationName;
    public $locale;
    protected $inputFilter;

    public function exchangeArray ($data) {

        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->translationFile = (isset($data['translationFile'])) ? $data['translationFile'] : null;
        $this->translationName = (isset($data['translationName'])) ? $data['translationName'] : null;
        $this->locale = (isset($data['locale'])) ? $data['locale'] : null;
    }

    // Add content to these methods:
    public function setInputFilter (InputFilterInterface $inputFilter) {

        throw new \Exception("Not used");
    }

    public function getInputFilter () {

        if (!$this->inputFilter) {

            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name' => 'translationFile',
                'required' => true,
                'validators' => array(
                    array('name' => 'File\UploadFile'),
                ),
            ));

            $inputFilter->add(array(
                'name' => 'translationName',
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
                'name' => 'locale',
                'required' => true,
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

}
