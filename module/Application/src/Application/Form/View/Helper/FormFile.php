<?php

namespace Application\Form\View\Helper;

use Zend\Form\Exception;

class FormFile extends \Zend\Form\View\Helper\FormFile
{

    /**
     * Custom prepareAttributes for file since we do not filter out any keys
     *
     * @param  array $attributes
     * @return array
     */
    public function prepareAttributes(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $attribute = strtolower($key);

            // Normalize attribute key, if needed
            if ($attribute != $key) {
                unset($attributes[$key]);
                $attributes[$attribute] = $value;
            }

            // Normalize boolean attribute values
            if (isset($this->booleanAttributes[$attribute])) {
                $attributes[$attribute] = $this->prepareBooleanAttributeValue($attribute, $value);
            }
        }

        return $attributes;
    }

}
