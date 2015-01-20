<?php

namespace Administration\Form;

use Zend\Form\Form;

class CountrySettingsForm extends Form {

    public function __construct ($entityManager, $serviceLocator, $name = null) {

        parent::__construct('countrySetings');

        $allCountries = $entityManager->getRepository('Administration\Entity\CodeCountries')->findAll();

        $countries = array();
        foreach ($allCountries as $country) {
            if (is_null($entityManager->getRepository('Administration\Entity\Country')
                ->findOneBy(array('countryId' => $country->getId()))) && $country->getName() != '') {
                $countries[$country->getId()] = $country->getName();
            }
        }
        asort($countries);

        $this->add(array(
            'name' => 'country',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'country',
                'class' => 'col-md-12 form-control',
            ),
            'options' => array(
                'value_options' => $countries,
            ),
        ));

        $allLocales = $serviceLocator->get('LocaleService')->getLocales();
        $countryLanguages = $entityManager->getRepository('Administration\Entity\Country')->findAll();
        $languages = array_map(function ($obj) { return $obj->getLanguages(); }, $countryLanguages);

        foreach ($languages as $language)
            foreach ($language as $languageValue)
                if (in_array($languageValue, $allLocales))
                    unset($allLocales[$languageValue]);

        $this->add(array(
            'name' => 'language',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'language',
                'class' => 'col-md-12 form-control',
            ),
            'options' => array(
                'value_options' => $allLocales,
            ),
        ));
    }
}
