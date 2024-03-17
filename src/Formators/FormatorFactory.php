<?php

namespace AUnhurian\LaravelTestGenerator\Formators;

use AUnhurian\LaravelTestGenerator\Contracts\FormatorInterface;
use AUnhurian\LaravelTestGenerator\Exceptions\FormatorClassNotFoundException;
use AUnhurian\LaravelTestGenerator\Exceptions\InvalidFormatorClassException;
use AUnhurian\LaravelTestGenerator\Exceptions\WrongTestTypeException;

class FormatorFactory
{
    public const TEST_TYPE_FEATURE = 'feature';
    public const TEST_TYPE_UNIT = 'unit';

    public const TEST_TYPES = [
        self::TEST_TYPE_UNIT,
        self::TEST_TYPE_FEATURE,
    ];

    public function createFormator(string $type = self::TEST_TYPE_UNIT): FormatorInterface
    {
        if (! in_array($type, self::TEST_TYPES)) {
            throw new WrongTestTypeException($type);
        }

        $className = config("test-generator.formators.{$type}");

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
