<?php

namespace ResponsiveMenu\Validation\Validators;

abstract class BaseValidator {

    protected $error;
    protected $data;

    abstract public function validate();

    public function __construct($data) {
        $this->data = $data;
    }

    public function getErrorMessage() {
        return $this->error;
    }
}