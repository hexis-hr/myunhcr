<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\FormInterface;

class ReportIncidentForm extends Form {

    public function __construct ($entityManager, $name = null) {

        parent::__construct('reportIncident');

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

        $this->add(array(
            'name' => 'incidentCategory',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'formSelect customSelect_select -custom',
                'id' => 'incidentCategory',
            ),
            'options' => array(
                'value_options' => array(
                    'Protection Incident' => 'Protection Incident',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'protectionType',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'formSelect customSelect_select -custom',
                'id' => 'protectionType',
            ),
            'options' => array(
                'value_options' => array(
                    'I don\'t know' => 'I don\'t know',
                ),
            ),
        ));

        $countryAll = $entityManager->getRepository('Administration\Entity\Country')->findAll();

        $countries = array();
        foreach ($countryAll as $country) {
            $countries[$country->getCountryId()] = $country->getName();
        }

        $this->add(array(
            'name' => 'reportCountry',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'formSelect customSelect_select -custom',
                'id' => 'reportCountry',
            ),
            'options' => array(
                'value_options' => $countries,
            ),
        ));

        $this->add(array(
            'name' => 'reportLocation',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'formInput',
                'id' => 'reportLocation',
                'placeholder' => 'Location Address',
            ),
        ));

        $this->add(array(
            'name' => 'feedbackMessage',
            'type' => 'Textarea',
            'attributes' => array(
                'class' => 'formTextarea',
                'id' => 'feedbackMessage',
            ),
        ));

        $this->add(array(
            'name' => 'incidentImage',
            'type' => 'File',
            'attributes' => array(
                'id' => 'incidentImage',
                'class' => 'fileUpload_input',
                'accept' => 'image/*;capture=camera',
                'capture' => 'camera',
            ),
        ));

        $this->add(array(
            'name' => 'incidentAudio',
            'type' => 'File',
            'attributes' => array(
                'id' => 'incidentAudio',
                'class' => 'fileUpload_input',
                'accept' => 'audio/*;capture=microphone',
                'capture' => 'microphone',
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

        $this->setObject($object);
        $this->extract();

        return $this;
    }
}
