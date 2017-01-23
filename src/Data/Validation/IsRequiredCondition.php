<?php

namespace EloquaForms\Data\Validation;


class IsRequiredCondition extends Validation
{

    protected function _validate($value)
    {
        if(null !== $value && $value != "")
            return true;

        return false;
    }
}