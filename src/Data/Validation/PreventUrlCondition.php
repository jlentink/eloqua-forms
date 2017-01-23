<?php

namespace EloquaForms\Data\Validation;


class PreventUrlCondition extends Validation
{
    protected function _validate($value)
    {
        if(null !== $value && $value != '')
            return true;

        return false;
    }

}