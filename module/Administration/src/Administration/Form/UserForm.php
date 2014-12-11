<?php

namespace Administration\Form;

use Zend\Form\Form;
use Zend\Form\Exception\InvalidArgumentException;
use Zend\Stdlib\ArrayUtils;

class UserForm extends Form {

    public function __construct ($name = null) {

        parent::__construct('user');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $this->add(array(
            'name' => 'email',
            'type' => 'Email',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'oldPassword',
            'type' => 'Password',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'confirmPassword',
            'type' => 'Password',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
        ));

        $this->add(array(
            'name' => 'type',
            'type' => 'Select',
            'attributes' => array(
                'class' => 'col-md-12 form-control',
            ),
            'options' => array(
                'value_options' => array(
                    'user' => 'user',
                    'admin' => 'admin',
                ),
            ),
        ));
    }

    public function setData ($data) {

        if ($data instanceof \Traversable) {
            $data = ArrayUtils::iteratorToArray($data);
        }

        if (!is_array($data)) {
            throw new InvalidArgumentException(sprintf(
                '%s expects an array or Traversable argument; received "%s"',
                __METHOD__,
                (is_object($data) ? get_class($data) : gettype($data))
            ));
        }

        // overriding set data for password
        if ($data['password'] == "") {
            unset($data['password']);
            unset($data['confirmPassword']);

            if ($data['oldPassword'])
                unset($data['oldPassword']);
        }

        $this->hasValidated = false;
        $this->data = $data;
        $this->populateValues($data);

        return $this;
    }

}
