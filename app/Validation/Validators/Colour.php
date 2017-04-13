<?php

namespace ResponsiveMenu\Validation\Validators;

class Colour extends BaseValidator {

    public function validate() {
        if(!preg_match('/(#(?:[0-9a-f]{2}){2,4}|#[0-9a-f]{3}|(?:rgba?|hsla?)\((?:\d+%?(?:deg|rad|grad|turn)?(?:,|\s)+){2,3}[\s\/]*[\d\.]+%?\))/i', $this->data)):
            $this->error = 'Invalid colour value';
            return false;
        endif;

        return true;
    }

}