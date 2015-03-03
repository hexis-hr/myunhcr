<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\FormInterface;
use Zend\Session\Container;

class SettingsForm extends Form {

    public function __construct ($entityManager, $name = null) {

        parent::__construct('settings');

        $this->add(array(
            'name' => 'notifications',
            'type' => 'Checkbox',
            'attributes' => array(
                'class' => 'formSwitch_checkbox',
                'id' => 'notifications',
                'checked' => 'checked',
            ),
        ));

        $allLanguages = $entityManager->getRepository('Administration\Entity\Translation')->findAll();

        $languages = array();
        foreach ($allLanguages as $language) {
            $languages[$language->getId()] = $language->getName();
        }
        asort($languages);

        $this->add(array(
            'name' => 'language',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'formSelect customSelect_select -custom',
                'id' => 'language',
            ),
            'options' => array(
                'value_options' => $languages,
            ),
        ));

        $allCountries = $entityManager->getRepository('Administration\Entity\Country')->findAll();

        $countries = array();
        foreach ($allCountries as $country) {
            $countries[$country->getId()] = $country->getName();
        }
        asort($countries);

        $this->add(array(
            'name' => 'country',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'formSelect customSelect_select -custom',
                'id' => 'country',
            ),
            'options' => array(
                'value_options' => $countries,
            ),
        ));

        $locations = array();
        if ($countries) {
            $container = new Container('userSettings');
            if ($container->id) {
                $settings = $entityManager->getRepository('Administration\Entity\UserSettings')
                    ->findOneBy(array('guid' => $container->id));
                $locationCountry = $settings->getCountry()->getId();
            } else
                $locationCountry = array_keys($countries)[0];

            $allLocations = $entityManager->getRepository('Administration\Entity\CountryLocation')
                ->findBy(array('country' => $locationCountry));

            if ($allLocations) {
                foreach ($allLocations as $location) {
                    $locations[$location->getId()] = $location->getName();
                }
                asort($locations);
            }
        }

        $this->add(array(
            'name' => 'location',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'formSelect customSelect_select -custom',
                'id' => 'location',
                'placeholder' => 'Choose country first'
            ),
            'options' => array(
                'value_options' => $locations,
            ),
        ));

        $allCategories = $entityManager->getRepository('Administration\Entity\Settings')->findAll();

        $categories = array();
        foreach ($allCategories as $category) {
            $categories[$category->getId()] = $category->getSettingsValue();
        }
        asort($categories);

        $this->add(array(
            'name' => 'category',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'formSelect customSelect_select -custom',
                'id' => 'category',
            ),
            'options' => array(
                'value_options' => $categories,
            ),
        ));
    }

    //bind method overridden because of foreign object binding
    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {

        if (!in_array($flags, array(FormInterface::VALUES_NORMALIZED, FormInterface::VALUES_RAW))) {
            throw new InvalidArgumentException(sprintf(
                '%s expects the $flags argument to be one of "%s" or "%s"; received "%s"',
                __METHOD__,
                'Zend\Form\FormInterface::VALUES_NORMALIZED',
                'Zend\Form\FormInterface::VALUES_RAW',
                $flags
            ));
        }

        if ($this->baseFieldset !== null) {
            $this->baseFieldset->setObject($object);
        }

        $this->bindAs = $flags;

        if (property_exists($object, 'language') && !is_null($object->getLanguage()))
            $object->setLanguage($object->getLanguage()->getId());

        if (property_exists($object, 'country') && !is_null($object->getCountry()))
            $object->setCountry($object->getCountry()->getId());

        if (property_exists($object, 'category') && !is_null($object->getCategory()))
            $object->setCategory($object->getCategoy()->getId());

        $this->setObject($object);
        $this->extract();

        return $this;
    }
}
