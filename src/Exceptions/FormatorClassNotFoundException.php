<?php

namespace AUnhurian\LaravelTestGenerator\Exceptions;

use Exception;

class FormatorClassNotFoundException extends Exception
{
    public function __construct($className)
    {
        if (empty($message)) {
            $message = "Formator class '{$className}' was not found. Please check configuration file.";
        }

        parent::__construct($message);
    }
}
