<?php

namespace Administration\Form;

use Zend\Form\Form;

class UserCategoryForm extends Form {

    public function     __construct ($name = null) {

        parent::__construct('userCategoryForm');

        $this->add(array(
            'name' => 'userCategory',
            'type' => 'Text',
            'attributes' => array(
                'id' => 'userCategory',
                'class' => 'col-md-12 form-control',
            ),
        ));
    }
}
