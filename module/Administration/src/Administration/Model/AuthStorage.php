<?php

namespace Administration\Model;

use Zend\Authentication\Storage;

class AuthStorage extends Storage\Session {

    public function setRememberMe ($time = 2592000) {
        $this->session->getManager()->rememberMe($time);
    }

    public function forgetMe () {
        $this->session->getManager()->forgetMe();
    }
}
