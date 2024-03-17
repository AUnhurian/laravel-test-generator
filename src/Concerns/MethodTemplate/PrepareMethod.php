<?php

namespace AUnhurian\LaravelTestGenerator\Concerns\MethodTemplate;

use AUnhurian\LaravelTestGenerator\Helper;

trait PrepareMethod
{
    public function buildMethod(string $methodAccess, string $methodName, string $returnType): string
    {
        $this->methodTemplate = Helper::getStubFunctionContent();

        $this->setMethodAccess($methodAccess);
        $this->setMethodName($methodName);
        $this->setReturnType($returnType);
        $this->setBody();

        $methodTemplate = $this->methodTemplate;
        $this->cleanUp();

        return $methodTemplate;
    }

    private function setMethodAccess(string $methodAccess): void
    {
        $this->methodTemplate = str_replace(
            '{{ method_access }}',
            $methodAccess,
            $this->methodTemplate
        );
    }

    private function setMethodName(string $methodName): void
    {
        $this->methodTemplate = str_replace(
            '{{ name }}',
            $methodName,
            $this->methodTemplate
        );
    }

    private function setReturnType(string $returnType): void
    {
        $this->methodTemplate = str_replace(
            '{{ return_type }}',
            $this->prepareReturnType($returnType),
            $this->methodTemplate
        );
    }

    private function prepareReturnType(string $returnType): string
    {
        return sprintf(': %s', $returnType);
    }

    private function setBody(): void
    {
        if (!empty($this->methodBody)) {
            $this->addLine();
        }

        $this->addCode('$this->assertTrue(true);', false);

        $this->methodTemplate = str_replace(
            '{{ body }}',
            $this->methodBody,
            $this->methodTemplate
        );
    }
}
