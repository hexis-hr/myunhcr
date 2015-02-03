<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\FormInterface;

class ReportIncidentForm extends Form {

    public function __construct ($entityManager, $name = null) {

        parent::__construct('reportIncident');

        $this->em = $entityManager;

        $this->add(array(
            'name' => 'anonymous',
            'type' => 'Radio',
            'attributes' => array(
                'class' => 'bigRadio_input',
            ),
            'options' => array(
                'value_options' => array(
                    '0' => 'No',
                    '1' => 'Yes',
                ),
            ),
        ));

        $incidentCategories = $entityManager->getRepository('Administration\Entity\IncidentCategory')->findAll();

        $categories = array(
            0 => 'Please select one',
        );
        foreach ($incidentCategories as $category) {
            $categories[$category->getId()] = $category->getCategory();
        }

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

        $incidentTypes = $entityManager->getRepository('Administration\Entity\IncidentType')->findAll();

        $types = array(
            0 => 'Please select one',
        );
        foreach ($incidentTypes as $type) {
            $types[$type->getId()] = $type->getType();
        }
        $this->add(array(
            'name' => 'type',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'formSelect customSelect_select -custom',
                'id' => 'type',
            ),
            'options' => array(
                'value_options' => $types,
            ),
        ));

        $countryAll = $entityManager->getRepository('Administration\Entity\Country')->findAll();

        $countries = array(
            0 => '(Autodetect)',
        );

        foreach ($countryAll as $country) {
            $countries[$country->getId()] = $country->getName();
        }

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

        $this->add(array(
            'name' => 'address',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'formInput',
                'id' => 'address',
                'placeholder' => 'Location Address',
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'type' => 'Textarea',
            'attributes' => array(
                'class' => 'formTextarea',
                'id' => 'description',
            ),
        ));

        $this->add(array(
            'name' => 'image',
            'type' => 'File',
            'attributes' => array(
                'id' => 'incidentImage',
                'class' => 'fileUpload_input',
                'accept' => 'image/*;capture=camera',
                'capture' => 'camera',
            ),
        ));

        $this->add(array(
            'name' => 'audio',
            'type' => 'File',
            'attributes' => array(
                'id' => 'incidentAudio',
                'class' => 'fileUpload_input',
                'accept' => 'audio/*;capture=microphone',
                'capture' => 'microphone',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'id' => 'next',
                'class' => 'inputSubmit button -primary -fullWidth',
            )
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

        $this->setObject($object);
        $this->extract();

        return $this;
    }
}
