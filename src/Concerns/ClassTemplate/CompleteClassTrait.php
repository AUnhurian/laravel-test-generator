<?php

namespace AUnhurian\LaravelTestGenerator\Concerns\ClassTemplate;

trait CompleteClassTrait
{
    public function setClassName(): void
    {
        $this->template = str_replace(
            '{{ class }}',
            $this->reflectionClass->getShortName(),
            $this->template
        );
    }
}
