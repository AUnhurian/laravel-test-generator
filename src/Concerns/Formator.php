<?php

namespace AUnhurian\LaravelTestGenerator\Concerns;

use AUnhurian\LaravelTestGenerator\Contracts\FormatorInterface;

abstract class Formator implements FormatorInterface
{
    private const STUB_CLASS_NAME = 'test-class.stub';
    private const STUB_FUNCTION_NAME = 'test-function.stub';

    public function getClassStubPath(): string
    {
        return $this->getStubPath(self::STUB_CLASS_NAME);
    }

    public function getFunctionStubPath(): string
    {
        return $this->getStubPath(self::STUB_FUNCTION_NAME);
    }

    private function getStubPath(string $stubFileName): string
    {
        $stubPath = $this->getStubDirPath() . '/' . $stubFileName;

        if (file_exists($stubPath)) {
            return $stubPath;
        }

        return __DIR__ . '/../../stubs/' . $stubFileName;
    }

    private function getStubDirPath(): string
    {
        $stubsBasePath = base_path('stubs');

        if (is_dir($stubsBasePath)) {
            return $stubsBasePath;
        }

        return __DIR__ . '/../../stubs';
    }
}
