<?php

namespace AUnhurian\LaravelTestGenerator\Formators;

use AUnhurian\LaravelTestGenerator\Contracts\FormatorInterface;
use AUnhurian\LaravelTestGenerator\Enums\FormatorTypes;
use AUnhurian\LaravelTestGenerator\Exceptions\FormatorClassNotFoundException;
use AUnhurian\LaravelTestGenerator\Exceptions\InvalidFormatorClassException;

class FormatorFactory
{
    public function createFormator(FormatorTypes $type = FormatorTypes::UNIT): FormatorInterface
    {
        $className = config("test-generator.formators.{$type->value}");

        if (!class_exists($className)) {
            throw new FormatorClassNotFoundException($className);
        }

        $formatorInstance = new $className($type);

        if (! $formatorInstance instanceof FormatorInterface) {
            throw new InvalidFormatorClassException($formatorInstance);
        }

        return $formatorInstance;
    }
}
