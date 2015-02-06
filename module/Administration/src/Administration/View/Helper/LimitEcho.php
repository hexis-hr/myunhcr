<?php

namespace Administration\View\Helper;

use Zend\View\Helper\AbstractHelper;

class LimitEcho extends AbstractHelper {

    public function __invoke ($text, $length = 200) {

        if (strlen($text) <= $length)
            return $text;
        else
            return substr($text, 0, $length) . '...';
    }
}
