<?php

namespace AUnhurian\LaravelTestGenerator\Formators;

use AUnhurian\LaravelTestGenerator\Concerns\Formator;
use AUnhurian\LaravelTestGenerator\Contracts\FormatorInterface;
use AUnhurian\LaravelTestGenerator\Enums\FormatorTypes;
use AUnhurian\LaravelTestGenerator\MethodBuilder;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class FeatureFormator extends Formator implements FormatorInterface
{
    public function buildSetUpMethod(): void
    {
    }

    public function buildMethods(): void
    {
        $methods = $this->reflectionClass->getMethods();

        foreach ($methods as $method) {
            if (in_array($method->getName(), config('test-generator.list_methods.exclude'))) {
                continue;
            }

            $route = $this->getRouteOfClass($method->getName());

            if ($route) {
                $this->buildHttpMethod($method, $route);

                continue;
            }

            $this->buildSimpleMethod($method);
        }
    }

    private function getRouteOfClass(string $method): bool|\Illuminate\Routing\Route
    {
        $controller = $this->reflectionClass->getName();
        $routes = Route::getRoutes();

        foreach ($routes as $route) {
            /** @var \Illuminate\Routing\Route $route */
            $action = $route->getAction();

            if (isset($action['controller']) && $action['controller'] === $controller . '@' . $method) {
                return $route;
            }
        }

        return false;
    }

    private function buildHttpMethod(\ReflectionMethod $method, \Illuminate\Routing\Route $route): void
    {
        $routeName = $route->getAction('as');
        $returnType = $method->getReturnType()?->getName();

        $httpMethod = $route->methods()[0];
        $additionalData = '';
        if (in_array(strtolower($httpMethod), ['post', 'put'])) {
            $this->methodBuilder->addCode('$data = [];')->addLine();
            $additionalData = ', $data';
        }

        $testHttpMethod = strtolower($httpMethod);
        if ($route->getPrefix() === 'api') {
            $testHttpMethod .= 'Json';
        }

        $routeParameters = '';
        foreach ($route->parameterNames() as $parameterName) {
            $routeParameters .= sprintf(" '%s' => '',", $parameterName);
        }

        $isJsonResponse = $returnType === \Illuminate\Http\JsonResponse::class;
        $this->methodBuilder->addCode(
            sprintf(
                '$url = route(\'%s\'%s);',
                $routeName,
                $routeParameters ? ', [' . $routeParameters . ']' : ''
            )
        );
        $this->methodBuilder->addLine();
        $this->methodBuilder->addCode(sprintf('$this->%s($url%s)', $testHttpMethod, $additionalData))
            ->addTab()
            ->addCode(
                sprintf('->assertStatus(SymphonyResponse::HTTP_OK)%s', $isJsonResponse ? '' : ';')
            );

        if ($isJsonResponse) {
            $this->methodBuilder->addTab()
                ->addCode('->assertJson([')
                ->addTab()
                ->addTab()
                ->addCode('//TODO: Add your expected response here')
                ->addTab()
                ->addCode(']);');
            ;
        }

        $this->addUse(Response::class, 'SymphonyResponse');

        $this->createTestMethod($method->getName());
    }

    private function buildSimpleMethod(\ReflectionMethod $method): void
    {
        $methodName = $method->getName();
        $parameters = $method->getParameters();

        $this->methodBuilder->setParameters(
            $this->reflectionClass->getConstructor()?->getParameters() ?? [],
                FormatorTypes::FEATURE
        );
        $this->methodBuilder->setParameters(
            $parameters,
            FormatorTypes::FEATURE
        );
        $this->createMockOfClass();
        $this->addUse($this->reflectionClass->getName());

        $this->methodBuilder->addLine();
        $this->initializeCallMethod($methodName);

        foreach ($parameters as $parameter) {
            if ($parameter->getClass() === null) {
                continue;
            }

            $this->addUse($parameter->getClass()->getName());
        }

        $this->createTestMethod($methodName);
    }

    private function initializeCallMethod(string $methodName): void
    {
        $parameters = $this->reflectionClass->getMethod($methodName)->getParameters();
        $parametersBody = [];

        foreach ($parameters as $parameter) {
            $parametersBody[] = sprintf('$%s', $parameter->getName());
        }

        $this->methodBuilder->addCode(
            sprintf('$%s->%s(%s);',
                $this->reflectionClass->getShortName(),
                $methodName,
                implode(', ', $parametersBody)
            )
        );
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

    private function createTestMethod(string $methodName): void
    {
        $methodCode = $this->methodBuilder->buildMethod(
            MethodBuilder::METHOD_ACCESS_PUBLIC,
            sprintf('test%s', ucfirst($methodName)),
            MethodBuilder::RETURN_TYPE_VOID
        );
        $this->addFunction($methodName, $methodCode);
    }
}
