<?php

namespace App\Validators;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class UserValidator {
    public function validate($data) {
        $errors = [];

        $nameValidator = v::stringType()->notEmpty()->length(1, 50);
        $emailValidator = v::email();

        try {
            $nameValidator->assert($data['name']);
        } catch (NestedValidationException $exception) {
            $errors['name'] = $exception->getMessages();
        }

        try {
            $emailValidator->assert($data['email']);
        } catch (NestedValidationException $exception) {
            $errors['email'] = $exception->getMessages();
        }

        return new ValidationResult(!empty($errors), $errors);
    }
}

class ValidationResult {
    private $failed;
    private $errors;

    public function __construct($failed, $errors) {
        $this->failed = $failed;
        $this->errors = $errors;
    }

    public function failed() {
        return $this->failed;
    }

    public function errors() {
        return $this->errors;
    }
}
