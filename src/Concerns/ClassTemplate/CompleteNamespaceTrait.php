<?php

namespace AUnhurian\LaravelTestGenerator\Concerns\ClassTemplate;

trait CompleteNamespaceTrait
{
    public function setNamespace(): void
    {
        $this->template = str_replace('{{ namespace }}', $this->generateTestNamespace(), $this->template);
    }

    private function generateTestNamespace(): string
    {
        $testsPath = 'Tests\\' . ucfirst($this->type->value) . '\\';
        $path = str_replace(['App\\', '\\App\\'], $testsPath, $this->classPath);

        $classShortName = $this->reflectionClass->getShortName();

        return rtrim(ltrim($path, '\\'), '\\' . $classShortName);
    }
}
