<?php

namespace EloquaForms\Data\Validation;


abstract class Validation implements ValidationInterface
{
    /**
     * @var string
     */
    protected $_name = '';

    /**
     * @var string
     */
    protected $_description = '';

    /**
     * @var bool
     */
    protected $_enabled = false;

    /**
     * @var string
     */
    protected $_message = '';

    /**
     * @var \stdClass
     */
    protected $_completeObject;

    public function __construct($eloquaValidationObject)
    {
        $this->_name = $eloquaValidationObject->condition->type;
        $this->_description = $eloquaValidationObject->description;
        $this->_enabled = $eloquaValidationObject->isEnabled;
        $this->_message = $eloquaValidationObject->message;
        $this->_completeObject = $eloquaValidationObject;
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