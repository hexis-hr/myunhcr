<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\FormInterface;

class ListOfOrganizationsForm extends Form {

    public function __construct ($entityManager, $name = null) {

        parent::__construct('listOfOrganizations');

        $serviceOrganizations = $entityManager->getRepository('Administration\Entity\ServiceOrganization')->findAll();

        $organizations = array();
        foreach ($serviceOrganizations as $serviceOrganization) {
            $organizations[$serviceOrganization->getId()] = $serviceOrganization->getOrganizationName();
        }
        asort($organizations);

        $this->add(array(
            'name' => 'organization',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'formSelect customSelect_select -custom',
                'id' => 'language',
            ),
            'options' => array(
                'value_options' => $organizations,
                'empty_option' => 'All organizations'
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

        $this->setObject($object);
        $this->extract();

        return $this;
    }
}
