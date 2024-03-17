<?php

namespace AUnhurian\LaravelTestGenerator\Concerns\ClassTemplate;

trait CompleteFunctionsTrait
{
    public function setFunctions(): void
    {
        $functions = implode(PHP_EOL, $this->functions);

        if (!empty($this->properties)) {
            $functions = implode(PHP_EOL, $this->properties) . $functions;
        }

        $this->template = str_replace(
            '{{ functions }}',
            $functions,
            $this->template
        );
    }

    public function addFunction(string $methodName, string $preparedFunction): void
    {
        if (!empty($this->functions[$methodName])) {
            return;
        }

        $this->functions[$methodName] = $preparedFunction;
    }

    public function addProperty(
        string $propertyName,
        string $mode = 'private',
        string $propertyType = '',
        string $defaultValue = null
    ): void {
        if (!empty($this->properties[$propertyName])) {
            return;
        }

        $property = '    ' . $mode;

        if (!empty($propertyType)) {
            $property .= sprintf(' %s ', $propertyType);
        }

        if ($propertyType === 'const') {
            $property .= $propertyName;
        } else {
            $property .= sprintf('$%s', $propertyName);
        }

        if (!empty($defaultValue)) {
            $property .= sprintf(' = %s;', $defaultValue);
        }

        $this->properties[$propertyName] = $property;
    }
}
