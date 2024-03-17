<?php

namespace AUnhurian\LaravelTestGenerator\Formators;

use AUnhurian\LaravelTestGenerator\Concerns\Formator;
use AUnhurian\LaravelTestGenerator\Contracts\FormatorInterface;
use AUnhurian\LaravelTestGenerator\MethodBuilder;

class UnitFormator extends Formator implements FormatorInterface
{
    public function buildMethods(): void
    {
        $methods = $this->reflectionClass->getMethods();

        foreach ($methods as $method) {
            if (in_array($method->getName(), config('test-generator.list_methods.exclude'))) {
                continue;
            }

            $this->buildMethod($method);
        }
    }

    public function buildMethod(\ReflectionMethod $method): void
    {
        $methodName = $method->getName();
        $parameters = $method->getParameters();

        $this->methodBuilder->setParameters($this->reflectionClass->getConstructor()?->getParameters() ?? []);
        $this->methodBuilder->setParameters($parameters);
        $this->createMockOfClass();
        $this->addUse($this->reflectionClass->getName());

        $this->methodBuilder->addLine();
        $this->initializeCallMethod($methodName);

        $method = $this->methodBuilder->buildMethod(
            MethodBuilder::METHOD_ACCESS_PUBLIC,
            sprintf('test%s', ucfirst($methodName)),
            MethodBuilder::RETURN_TYPE_VOID
        );

        foreach ($parameters as $parameter) {
            if ($parameter->getClass() === null) {
                continue;
            }

            $this->addUse($parameter->getClass()->getName());
        }

        $this->addFunction($methodName, $method);
    }

    private function initializeCallMethod(string $methodName)
    {
        $hasReturnType = (bool) $this->reflectionClass->getMethod($methodName)->getReturnType();
        $parameters = $this->reflectionClass->getMethod($methodName)->getParameters();
        $parametersBody = [];

        foreach ($parameters as $parameter) {
            $parametersBody[] = sprintf('$%s', $parameter->getName());
        }

        $this->methodBuilder->addCode(
            sprintf('%s$%s->%s(%s);',
                $hasReturnType ? '$response = ' : '',
                $this->reflectionClass->getShortName(),
                $methodName,
                implode(', ', $parametersBody)
            )
        );

        if ($hasReturnType) {
            $this->methodBuilder->addCode(
                sprintf('$this->assertNotEmpty($response);')
            );
        }
    }

    private function createMockOfClass(): void
    {
        $parameters = $this->reflectionClass->getConstructor()?->getParameters() ?? [];
        $parametersBody = [];

        foreach ($parameters as $parameter) {
            $parametersBody[] = sprintf('$%s', $parameter->getName());

            if ($parameter->getClass() !== null) {
                $this->addUse($parameter->getClass()->getName());
            }
        }

        $this->methodBuilder->addCode(
            sprintf('$%s = new %s(%s);',
                $this->reflectionClass->getShortName(),
                $this->reflectionClass->getShortName(),
                implode(', ', $parametersBody)
            )
        );
    }
}
