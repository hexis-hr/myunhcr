<?php

namespace Administration\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\FormInterface;

class IncidentForm extends Form {

    public function __construct ($entityManager, $serviceLocator, $name = null) {

        $this->em = $entityManager;

        parent::__construct('incident');

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

        $incidentCategories = $entityManager->getRepository('Administration\Entity\IncidentCategory')->findAll();

        $categories = array();
        foreach ($incidentCategories as $category) {
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

        if (property_exists($object, 'category') && !is_null($object->getCategory()))
            $object->setCategory($object->getCategory()->getId());

        $this->setObject($object);
        $this->extract();

        return $this;
    }
}
