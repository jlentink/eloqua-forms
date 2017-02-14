<?php
/**
 * Created by PhpStorm.
 * User: utopia
 * Date: 14/02/2017
 * Time: 17:37
 */

namespace EloquaForms\Data;


class FieldOption
{
    /**
     * @var string
     */
    private $_displayName = '';

    /**
     * @var string
     */
    private $_value = '';

    public function __construct(\stdClass $rawOption)
    {
        if(isset($rawOption->displayName))
            $this->_displayName = $rawOption->displayName;

        if(isset($rawOption->value))
            $this->_value = $rawOption->value;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->_displayName;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }


}