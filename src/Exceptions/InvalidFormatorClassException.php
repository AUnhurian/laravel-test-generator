<?php

namespace AUnhurian\LaravelTestGenerator\Exceptions;

use Exception;

class InvalidFormatorClassException extends Exception
{
    public function __construct(string $class)
    {
        parent::__construct("The formator '{$class}' is not implements the FormatorInterface.");
    }
}
