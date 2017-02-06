<?php

namespace EloquaForms\Data\Validation;


use Egulias\EmailValidator\EmailValidator;


class IsEmailAddressCondition extends Validation
{
    protected function _validate($value)
    {
        $validator = new EmailValidator();
        return $validator->isValid($value);
    }

}
