<?php

namespace EloquaForms\Data\Validation;


abstract class Validation implements ValidationInterface
{
    /**
     * @var string
     */
    private $_name = '';

    /**
     * @var string
     */
    private $_description = '';

    /**
     * @var bool
     */
    private $_enabled = false;

    /**
     * @var string
     */
    private $_message = '';

    public function __construct($eloquaValidationObject)
    {
        $this->_name = $eloquaValidationObject->name;
        $this->_description = $eloquaValidationObject->description;
        $this->_enabled = $eloquaValidationObject->isEnabled;
        $this->_message = $eloquaValidationObject->message;
    }

    /**
     * Internal validation function
     *
     * @param mixed $value
     * @return boolean
     */
    abstract protected function _validate($value);

    /**
     * Validate the value for the field.
     *
     * @param mixed $value
     * @return bool
     */
    final public function validate($value){
        if(false === $this->_enabled)
            return true;

        return $this->_validate($value);
    }

    /**
     * Get the message.
     *
     * @return string
    */
    public function getMessage() {
        return $this->_message;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * @return string
     */
    public function getDescription() {
        return $this->_description;
    }

    /**
     * @return boolean
     */
    public function isEnabled() {
        return $this->_enabled;
    }

}