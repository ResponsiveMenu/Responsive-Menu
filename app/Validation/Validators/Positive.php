<?php

namespace ResponsiveMenu\Validation\Validators;

class Positive extends BaseValidator {

    public function validate() {
        $is_positive = false;

        if($this->data >= 0)
            $is_positive = true;

        if(!$is_positive)
            $this->error = 'Number must be 0 or positive';

        return $is_positive;
    }

}