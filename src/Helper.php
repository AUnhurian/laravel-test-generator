<?php

namespace AUnhurian\LaravelTestGenerator;

use AUnhurian\LaravelTestGenerator\Concerns\Formator;

class Helper
{
    public static function getStubClassContent(): string
    {
        return file_get_contents(self::getClassStubPath());
    }

    public static function getStubFunctionContent(): string
    {
        return file_get_contents(self::getFunctionStubPath());
    }

    private static function getClassStubPath(): string
    {
        return self::getStubPath(Formator::STUB_CLASS_NAME);
    }

    private static function getFunctionStubPath(): string
    {
        return self::getStubPath(Formator::STUB_FUNCTION_NAME);
    }

    private static function getStubPath(string $stubFileName): string
    {
        $stubPath = self::getStubDirPath() . '/' . $stubFileName;

        if (file_exists($stubPath)) {
            return $stubPath;
        }

        return __DIR__ . '/../stubs/' . $stubFileName;
    }

    private static function getStubDirPath(): string
    {
        $stubsBasePath = base_path('stubs');

        if (is_dir($stubsBasePath)) {
            return $stubsBasePath;
        }

        return __DIR__ . '/../../stubs';
    }
}
