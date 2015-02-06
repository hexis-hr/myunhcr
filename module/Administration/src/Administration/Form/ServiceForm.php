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
            'name' => 'address',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'phone',
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

        $servicePartners = $entityManager->getRepository('Administration\Entity\ServicePartner')->findAll();

        $partners = array();
        foreach ($servicePartners as $servicePartner) {
            $partners[$servicePartner->getId()] = $servicePartner->getPartnerName();
        }

        $this->add(array(
            'name' => 'partner',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
            'options' => array(
                'value_options' => $partners,
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

        if (property_exists($object, 'partner') && !is_null($object->getPartner()))
            $object->setPartner($object->getPartner()->getId());

        $this->setObject($object);
        $this->extract();

        return $this;
    }


}
