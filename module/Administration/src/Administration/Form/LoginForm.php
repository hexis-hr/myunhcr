<?php

namespace Administration\Form;

use Zend\Form\Form;

class LoginForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('login');
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'Email',
                'class' => 'form-control input-block-level',
                'placeholder' => 'Email address',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'type'  => 'Password',
            'attributes' => array(
                'class'  => 'form-control input-block-level',
                'placeholder'  => 'Password',
            ),
        ));

        //checkbox type must have first letter uppercase
        $this->add(array(
            'name' => 'rememberMe',
            'type'  => 'Checkbox',
        ));

        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'id' => 'loginSubmit',
            'attributes' => array(
                'value' => 'Log In',
                'class' => 'btn btn-large btn-primary',
            ),
        ));
    }
}
