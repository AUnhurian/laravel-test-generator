<?php

namespace AUnhurian\LaravelTestGenerator\Exceptions;

use Exception;

class WrongClassNamespaceException extends Exception
{
    public function __construct(string $class)
    {
        parent::__construct("Wrong class '{$class}' namespace.");
    }
}
