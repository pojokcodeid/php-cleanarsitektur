<?php

namespace App\Validators;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class PersonalDataValidator {
    public function validate($data) {
        $errors = [];

        $userIdValidator = v::intVal()->notEmpty();
        $addressValidator = v::stringType()->notEmpty()->length(1, 255);
        $phoneValidator = v::phone()->notEmpty();

        try {
            $userIdValidator->assert($data['user_id']);
        } catch (NestedValidationException $exception) {
            $errors['user_id'] = $exception->getMessages();
        }

        try {
            $addressValidator->assert($data['address']);
        } catch (NestedValidationException $exception) {
            $errors['address'] = $exception->getMessages();
        }

        try {
            $phoneValidator->assert($data['phone']);
        } catch (NestedValidationException $exception) {
            $errors['phone'] = $exception->getMessages();
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
