<?php

namespace AUnhurian\LaravelTestGenerator\Exceptions;

use Exception;

class ClassNotExistException extends Exception
{
    public function __construct(string $class)
    {
        parent::__construct("The class '{$class}' is not exist.");
    }
}
