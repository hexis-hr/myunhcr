<?php

namespace Administration\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\FormInterface;

class ServiceForm extends Form {

    public function __construct ($entityManager, $name = null) {

        parent::__construct('service');

        $this->add(array(
            'name' => 'serviceName',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'serviceAcronym',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'latitude',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'longitude',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'description',
            'type' => 'Textarea',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'serviceUrl',
            'type' => 'Url',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $serviceOrganizations = $entityManager->getRepository('Administration\Entity\ServiceOrganization')->findAll();

        $organizations = array();
        foreach ($serviceOrganizations as $serviceOrganization) {
            $organizations[$serviceOrganization->getId()] = $serviceOrganization->getOrganizationName();
        }

        $this->add(array(
            'name' => 'organization',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
            'options' => array(
                'value_options' => $organizations,
            ),
        ));

        $serviceSectors = $entityManager->getRepository('Administration\Entity\ServiceSector')->findAll();

        $sectors = array();
        foreach ($serviceSectors as $serviceSector) {
            $sectors[$serviceSector->getId()] = $serviceSector->getSectorName();
        }

        $this->add(array(
            'name' => 'sector',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
            'options' => array(
                'value_options' => $sectors,
            ),
        ));

        $serviceActivities = $entityManager->getRepository('Administration\Entity\ServiceActivity')->findAll();

        $activities = array();
        foreach ($serviceActivities as $serviceActivity) {
            $activities[$serviceActivity->getId()] = $serviceActivity->getActivityName();
        }

        $this->add(array(
            'name' => 'activity',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
            'options' => array(
                'value_options' => $activities,
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

        if (property_exists($object, 'organization') && !is_null($object->getOrganization()))
            $object->setOrganization($object->getOrganization()->getId());

        if (property_exists($object, 'sector') && !is_null($object->getSector()))
            $object->setSector($object->getSector()->getId());

        if (property_exists($object, 'activity') && !is_null($object->getActivity()))
            $object->setActivity($object->getActivity()->getId());

        $this->setObject($object);
        $this->extract();

        return $this;
    }


}
