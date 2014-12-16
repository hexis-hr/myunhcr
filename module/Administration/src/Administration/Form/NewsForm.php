<?php

namespace Administration\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\FormInterface;

class NewsForm extends Form {

    public function __construct ($entityManager, $name = null) {

        parent::__construct('news');

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

        $this->setObject($object);
        $this->extract();

        return $this;
    }
}
