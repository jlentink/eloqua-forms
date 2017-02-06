<?php

namespace EloquaForms\Data\Validation;

class TextLengthCondition extends Validation
{
    protected function _validate($value)
    {

        if($this->_completeObject->condition->minimum >= $value && $this->_completeObject->condition->maximum <= $value){
            return true;
        }
        return false;
    }

}
