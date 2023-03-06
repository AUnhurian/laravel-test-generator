<?php

namespace AUnhurian\LaravelTestGenerator;

use AUnhurian\LaravelTestGenerator\Contracts\FormatorInterface;
use Illuminate\Filesystem\Filesystem;

class Generator
{
    public function __construct(
        private readonly Filesystem $files,
        private FormatorInterface $formator,
        private readonly string $className
    ) {
    }

    public function generate(): string
    {
        return '';
    }
}
