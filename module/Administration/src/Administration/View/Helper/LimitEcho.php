<?php

namespace Administration\View\Helper;

use Zend\View\Helper\AbstractHelper;

class LimitEcho extends AbstractHelper {

    public function __invoke ($text) {

        if (strlen($text) <= 200)
            return $text;
        else
            return substr($text, 0, 200) . '...';
    }
}
