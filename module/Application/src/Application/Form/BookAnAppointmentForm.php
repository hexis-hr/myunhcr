<?php

namespace Application\Form;

use Zend\Form\Form;

class BookAnAppointmentForm extends Form {

    public function __construct ($entityManager, $name = null) {

        parent::__construct('appointmentForm');

        $appointmentCategories = $entityManager->getRepository('Administration\Entity\AppointmentCategory')->findAll();

        $categories = array();
        foreach ($appointmentCategories as $category)
            $categories[$category->getId()] = $category->getCategory();

        asort($categories);

        $this->add(array(
            'name' => 'appointmentType',
            'type' => 'Select',
            'attributes' => array(
                'id' => 'appointmentType',
                'class' => 'formSelect customSelect_select -custom',
            ),
            'options' => array(
                'value_options' => $categories,
                'empty_option' => 'Choose type',
            ),
        ));

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
            'name' => 'appointmentDate',
            'type' => 'Date',
            'attributes' => array(
                'class' => 'formInput picker__input',
                'id' => 'appointmentDate',
                'placeholder' => 'Day/Month/Year',
            ),
        ));

        $this->add(array(
            'name' => 'appointmentTime',
            'type' => 'Time',
            'attributes' => array(
                'class' => 'formInput picker__input',
                'id' => 'appointmentTime',
                'placeholder' => 'Hour:Minutes',
            ),
        ));
    }
}
