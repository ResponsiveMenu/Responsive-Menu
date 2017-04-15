<?php

namespace ResponsiveMenu\Validation\Validators;

class Colour extends BaseValidator {

    public function validate() {
        $valid_colour = false;

        // First check valid Hex
        if(preg_match('/^#(?:[0-9a-f]{3}){1,2}$/i', $this->data))
            $valid_colour = true;

        // Next check valid rgba()
        if(preg_match('/rgba\((\d{1,3}),\s*(\d{1,3}),\s*(\d{1,3}),\s*(\d*(?:\.\d+)?)\)/i', $this->data))
            $valid_colour = true;

        if(!$valid_colour)
            $this->error = 'Invalid colour value';

        return $valid_colour;
    }

}