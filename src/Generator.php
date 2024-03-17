<?php

namespace AUnhurian\LaravelTestGenerator;

use AUnhurian\LaravelTestGenerator\Contracts\FormatorInterface;
use AUnhurian\LaravelTestGenerator\Exceptions\TestClassExistException;
use AUnhurian\LaravelTestGenerator\Exceptions\WrongClassNamespaceException;
use Illuminate\Filesystem\Filesystem;

class Generator
{
    public function __construct(
        private readonly Filesystem $files,
        private FormatorInterface $formator,
        private readonly string $classPath,
        private readonly bool $canOverride
    ) {
    }

    public function generate(): string
    {
        $this->validateClass();

        $this->formator->setTemplate();
        $this->formator->setClassReflection($this->classPath);

        $this->formator->buildSetUpMethod();
        $this->formator->buildMethods();

        $this->completeTestTemplate();
        $path = $this->saveTemplate();

        return $path;
    }

    private function validateClass()
    {
        $containsAppNamespace = strpos($this->classPath, 'App\\') !== false;

        if (! $containsAppNamespace) {
            throw new WrongClassNamespaceException($this->classPath);
        }
    }

    private function completeTestTemplate(): void
    {
        $this->formator->setNamespace();
        $this->formator->setUseClassesInTemplate();
        $this->formator->setClassName();
        $this->formator->setFunctions();
    }

    private function saveTemplate(): string
    {
        $template = $this->formator->getTemplate();
        $path = $this->formator->prepareTestClassPath();

        if ($this->files->exists($path) && !$this->canOverride) {
            throw new TestClassExistException($path);
        }

        $directoryPath = dirname($path);
        if (! is_dir($directoryPath)) {
            $this->files->makeDirectory($directoryPath, recursive: true);
        }

        $this->files->put($path, $template);

        return $path;
    }
}
