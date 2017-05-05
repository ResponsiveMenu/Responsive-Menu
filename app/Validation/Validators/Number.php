<?php

namespace ResponsiveMenu\Validation\Validators;

class Number extends BaseValidator {

    public function validate() {
        $valid_integer = false;

        if(is_numeric($this->data))
            $valid_integer = true;

        if(!$valid_integer)
            $this->error = 'Invalid number';

        return $valid_integer;
    }

}