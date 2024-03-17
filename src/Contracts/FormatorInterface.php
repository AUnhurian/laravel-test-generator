<?php

namespace AUnhurian\LaravelTestGenerator\Contracts;

interface FormatorInterface
{
    public function setTemplate(): void;

    public function getTemplate(): string;

    public function setClassReflection($classPath): void;

    public function setNamespace(): void;

    public function setUseClassesInTemplate(): void;

    public function setFunctions(): void;

    public function setClassName(): void;

    public function addFunction(string $methodName, string $preparedFunction): void;

    public function prepareTestClassPath(): string;

    public function addProperty(
        string $propertyName,
        string $mode = 'private',
        string $propertyType = '',
        string $defaultValue = null
    ): void;

    public function buildSetUpMethod(): void;
    public function buildMethods(): void;
}
