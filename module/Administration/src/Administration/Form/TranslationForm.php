<?php

namespace Administration\Form;

use Zend\Form\Form;

class TranslationForm extends Form {

    public function __construct ($entityManager, $name = null) {

        parent::__construct('translation');

        $this->add(array(
            'name' => 'translation-file',
            'type' => 'File',
            'attributes' => array(
                'id' => 'translation-file',
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
            'name' => 'locale',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'locale',
                'class' => 'col-md-12 form-control',
            ),
            'options' => array(
                'value_options' => $languageArray,
            ),
        ));
    }
}
