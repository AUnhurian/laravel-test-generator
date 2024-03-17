<?php

namespace AUnhurian\LaravelTestGenerator\Concerns;

use AUnhurian\LaravelTestGenerator\Concerns\ClassTemplate\CompleteClassTrait;
use AUnhurian\LaravelTestGenerator\Concerns\ClassTemplate\CompleteFunctionsTrait;
use AUnhurian\LaravelTestGenerator\Concerns\ClassTemplate\CompleteNamespaceTrait;
use AUnhurian\LaravelTestGenerator\Concerns\ClassTemplate\CompleteUseTrait;
use AUnhurian\LaravelTestGenerator\Contracts\FormatorInterface;
use AUnhurian\LaravelTestGenerator\Enums\FormatorTypes;
use AUnhurian\LaravelTestGenerator\Helper;
use AUnhurian\LaravelTestGenerator\MethodBuilder;
use ReflectionClass;

abstract class Formator implements FormatorInterface
{
    use CompleteUseTrait;
    use CompleteNamespaceTrait;
    use CompleteClassTrait;
    use CompleteFunctionsTrait;

    private string $template = '';

    protected array $usingClasses = [];
    protected array $functions = [];
    protected array $properties = [];

    protected ReflectionClass $reflectionClass;

    public const STUB_CLASS_NAME = 'test-class.stub';
    public const STUB_FUNCTION_NAME = 'test-function.stub';
    protected string $classPath;

    protected MethodBuilder $methodBuilder;

    public function __construct(private FormatorTypes $type)
    {
        $this->methodBuilder = new MethodBuilder();
    }

    public function setTemplate(): void
    {
        $content = Helper::getStubClassContent();

        $this->template = $content;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function setClassReflection($classPath): void
    {
        $this->classPath = $classPath;
        $this->reflectionClass = new ReflectionClass($classPath);
    }

    public function prepareTestClassPath(): string
    {
        $testsPath = 'tests\\' . ucfirst($this->type->value) . '\\';
        $path = str_replace(['App\\', '\\App\\'], $testsPath, $this->classPath);

        $path = str_replace('\\', '/', $path);

        return base_path($path . 'Test.php');
    }
}
