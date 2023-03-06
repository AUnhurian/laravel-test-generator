<?php

namespace AUnhurian\LaravelTestGenerator\Exceptions;

use Exception;

class WrongTestTypeException extends Exception
{
    public function __construct(string $type)
    {
        parent::__construct("Wrong test type. The test type that was provided: {$type}.");
    }
}
