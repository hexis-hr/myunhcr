<?php

namespace Application\Form;

use Zend\Form\Form;

class BookAnAppointmentForm extends Form {

    public function __construct ($entityManager, $name = null) {

        parent::__construct('bookAnAppointment');

        $this->add(array(
            'name' => 'appointmentType',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'formSelect customSelect_select -custom',
                'id' => 'appointmentType',
            ),
            'options' => array(
                'value_options' => array(
                    'Registration' => 'Registration',
                ),
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
