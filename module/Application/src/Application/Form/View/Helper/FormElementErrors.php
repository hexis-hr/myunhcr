<?php

namespace Application\Form\View\Helper;

use Zend\Form\View\Helper\FormElementErrors as OriginalFormElementErrors;

class FormElementErrors extends OriginalFormElementErrors
{
    protected $messageCloseString     = '</div>';
    protected $messageOpenFormat      = '<div class="form_error">';
    protected $messageSeparatorString = '<br>';
}
