<?php

namespace AUnhurian\LaravelTestGenerator\Exceptions;

use Exception;

class TestClassExistException extends Exception
{
    public function __construct(string $class)
    {
        parent::__construct("The test class with '{$class}' path already exist.");
    }
}
