<?php

namespace Administration\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\FormInterface;

class FaqForm extends Form {

    public function __construct ($entityManager, $serviceLocator, $name = null) {

        $this->em = $entityManager;
        $globalConfig = $serviceLocator->get('config');

        parent::__construct('faq');

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
            'name' => 'language',
            'type' => 'Select',
            'id' => 'lang',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
            'options' => array(
                'value_options' =>
                //todo: check if locales are okay for use here
                $globalConfig['translationLocales'],
            ),
        ));

        $faqCategories = $entityManager->getRepository('Administration\Entity\FaqCategory')->findAll();

        $categories = array();
        foreach ($faqCategories as $category) {
            $categories[$category->getId()] = $category->getCategory();
        }

        $this->add(array(
            'name' => 'category',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
            'options' => array(
                'value_options' => $categories,
            ),
        ));

        $codeCountries = $entityManager->getRepository('Administration\Entity\CodeCountries')->findAll();

        $countries = array();
        foreach ($codeCountries as $country) {
            $countries[$country->getId()] = $country->getName();
        }

        $this->add(array(
            'name' => 'country',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
            'options' => array(
                'value_options' => $countries,
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

        if (property_exists($object, 'country') && !is_null($object->getCountry()))
            $object->setCountry($object->getCountry()->getId());

        if (property_exists($object, 'category') && !is_null($object->getCategory()))
            $object->setCategory($object->getCategory()->getId());

        $this->setObject($object);
        $this->extract();

        return $this;
    }
}
