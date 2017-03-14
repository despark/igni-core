<?php

namespace Despark\Cms\Fields;

/**
 * Class Password.
 */
class Password extends Text
{

    /**
     * Password constructor.
     * @param string $fieldName
     * @param array  $options
     * @param null   $value
     */
    public function __construct($fieldName, array $options, $value = null)
    {
        if (! isset($options['attributes'], $options['attributes']['autocomplete'])) {
            $options['attributes']['autocomplete'] = 'off';
        }
        parent::__construct($fieldName, $options, $value);
    }

}
