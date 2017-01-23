<?php

namespace EloquaForms\Data\Validation;


use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;

class IsEmailAddressCondition extends Validation
{
    protected function _validate($value)
    {

        $multipleValidations = new MultipleValidationWithAnd([
            new RFCValidation(),
            new DNSCheckValidation()
        ]);

        $validator = new EmailValidator();
        return $validator->isValid($value, $multipleValidations);
    }

}