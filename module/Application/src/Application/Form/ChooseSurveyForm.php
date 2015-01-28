<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Form\FormInterface;

class ChooseSurveyForm extends Form {

    public function __construct ($entityManager, $name = null) {

        parent::__construct('chooseSurvey');

        $this->add(array(
            'name' => 'authentification',
            'type' => 'Text',
            'attributes' => array(
                'class' => 'formInput',
                'id' => 'authentication',
                'placeholder' => 'ID Number',
            ),
        ));

        $this->add(array(
            'name' => 'date',
            'type' => 'Date',
            'attributes' => array(
                'class' => 'formInput',
                'id' => 'date',
                'placeholder' => 'Day/Month/Year',
            ),
        ));

        $mSurveys = $entityManager->getRepository('Administration\Entity\SurveyODK')->findBy(array('active' => 1));

        $surveys = array();
        foreach ($mSurveys as $survey) {
            $surveys[$survey->getId()] = $survey->getName();
        }

        $this->add(array(
            'name' => 'survey',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'formSelect -custom',
                'id' => 'chooseSurvey',
            ),
            'options' => array(
                'value_options' => $surveys,
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

        if (property_exists($object, 'survey') && !is_null($object->getSurvey()))
            $object->setSurvey($object->getSurvey()->getId());

        $this->setObject($object);
        $this->extract();

        return $this;
    }
}
