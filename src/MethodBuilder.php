<?php

namespace AUnhurian\LaravelTestGenerator;

use AUnhurian\LaravelTestGenerator\Concerns\MethodTemplate\PrepareMethod;
use AUnhurian\LaravelTestGenerator\Enums\FormatorTypes;
use AUnhurian\LaravelTestGenerator\Formators\FormatorFactory;

class MethodBuilder
{
    use PrepareMethod;

    public const METHOD_ACCESS_PROTECTED = 'protected';
    public const METHOD_ACCESS_PUBLIC = 'public';

    public const RETURN_TYPE_VOID = 'void';

    private string $methodTemplate = '';
    private string $methodBody = '';

    public function cleanUp(): MethodBuilder
    {
        $this->methodTemplate = '';
        $this->methodBody = '';

        return $this;
    }

    public function addInjectionParameter(
        \ReflectionParameter $reflectionParameter,
        FormatorTypes $typeTest = FormatorTypes::UNIT
    ): MethodBuilder {
        if ($reflectionParameter->getClass() === null) {
            $this->addCode(
                sprintf(
                    '$%s = \'\';',
                    $reflectionParameter->getName(),
                )
            );

            return $this;
        }

        $template = '$%s = $this->mock(%s::class);';
        if ($typeTest->value === FormatorTypes::FEATURE->value) {
            $template = '$%s = $this->app->make(%s::class);';
        }

        $this->addCode(
            sprintf(
                $template,
                $reflectionParameter->getName(),
                $reflectionParameter?->getClass()?->getShortName() ?? $reflectionParameter->getType()->getName()
            )
        );

        return $this;
    }

    public function addCode(string $code, bool $shouldAddEmptyLine = true): MethodBuilder
    {
        $this->methodBody .= $code;
        $shouldAddEmptyLine && $this->addLine();

        return $this;
    }

    public function addLine(int $withTabs = 0): MethodBuilder
    {
        $this->methodBody .= PHP_EOL . $this->getMethodIndentation();

        for ($i = 0; $i < $withTabs; $i++) {
            $this->addTab();
        }

        return $this;
    }

    public function addTab(): MethodBuilder
    {
        $this->methodBody .= '    ';

        return $this;
    }

    private function getMethodIndentation(): string
    {
        return '        ';
    }

    public function setParameters(
        array $parameters,
        FormatorTypes $typeTest = FormatorTypes::UNIT
    ): void {
        if (empty($parameters)) {
            return;
        }

        foreach ($parameters as $parameter) {
            $this->addInjectionParameter($parameter, $typeTest);
        }
    }
}
