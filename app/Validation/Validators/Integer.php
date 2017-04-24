<?php

namespace ResponsiveMenu\Validation\Validators;

class Integer extends BaseValidator {

    public function validate() {
        $valid_integer = false;

        if(is_numeric($this->data))
            $valid_integer = true;

        if(!$valid_integer)
            $this->error = 'Invalid integer - must be a whole number';

        return $valid_integer;
    }

}