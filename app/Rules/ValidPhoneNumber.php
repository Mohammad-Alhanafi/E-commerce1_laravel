<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use libphonenumber\PhoneNumberUtil;

class ValidPhoneNumber implements ValidationRule
{

    public function __construct(
        private $country = null
    ) {}


    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $phoneUtil = PhoneNumberUtil::getInstance();

        try {

            $number = $phoneUtil->parse(
                $value,
                strtoupper($this->country)
            );


            if (!$phoneUtil->isValidNumber($number)) {
                $fail('رقم الهاتف غير صحيح.');
            }


        } catch (\Exception $e) {

            $fail('رقم الهاتف غير صالح.');

        }

    }
}