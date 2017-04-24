<?php

namespace ResponsiveMenu\Validation\Validators;

class Integer extends BaseValidator {

    public function validate() {
        $valid_integer = false;

        // First check valid Hex
        if(is_numeric($this->data))
            $valid_integer = true;

        if(!$valid_integer)
            $this->error = 'Invalid integer';

        return $valid_integer;
    }

}