<?php

namespace Administration\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\FormInterface;

class NewsForm extends Form {

    public function __construct ($entityManager, $serviceLocator, $name = null) {

        parent::__construct('news');

        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'content',
            'type' => 'Textarea',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'source',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'sourceUrl',
            'type' => 'Url',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $country = $entityManager->getRepository('Administration\Entity\Country')
            ->findOneBy(array('id' => $_SESSION['countrySettings']['countryId']));

        $languageArray = $country->getLanguages();
        foreach ($languageArray as $languageKey => $languageValue) {
            $languageArray[$languageValue] = $languageValue;
            unset($languageArray[$languageKey]);
        }

        $this->add(array(
            'name' => 'language',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'lang',
                'class' => 'col-md-12 form-control',
            ),
            'options' => array(
                'value_options' => $languageArray,
            ),
        ));
    }

}
