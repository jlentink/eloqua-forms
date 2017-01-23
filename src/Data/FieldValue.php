<?php

namespace EloquaForms\Data;


class FieldValue
{
    public $type = 'FieldValue';

    public $id = 0;

    public $value = '';

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return FieldValue
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return FieldValue
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return FieldValue
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}