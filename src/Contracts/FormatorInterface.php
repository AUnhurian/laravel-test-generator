<?php

namespace AUnhurian\LaravelTestGenerator\Contracts;

interface FormatorInterface
{
    public function getClassStubPath(): string;
    public function getFunctionStubPath(): string;
}
