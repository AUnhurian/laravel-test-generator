<?php

namespace AUnhurian\LaravelTestGenerator\Console;

use AUnhurian\LaravelTestGenerator\Contracts\FormatorInterface;
use AUnhurian\LaravelTestGenerator\Exceptions\ClassNotExistException;
use AUnhurian\LaravelTestGenerator\Exceptions\FormatorClassNotFoundException;
use AUnhurian\LaravelTestGenerator\Exceptions\InvalidFormatorClassException;
use AUnhurian\LaravelTestGenerator\Exceptions\WrongTestTypeException;
use AUnhurian\LaravelTestGenerator\Generator;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateTestCommand extends Command
{
    protected $signature = 'test:generate {class} {--feature} {--unit}';

    protected $description = 'This command for generate test';

    private const TEST_TYPE_FEATURE = 'feature';
    private const TEST_TYPE_UNIT = 'unit';
    private const TEST_TYPES = [
        self::TEST_TYPE_UNIT,
        self::TEST_TYPE_FEATURE,
    ];

    public function __construct(private readonly Filesystem $files)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $class = $this->argument('class');

        if (!class_exists($class)) {
            throw new ClassNotExistException($class);
        }

        if (! $this->option('unit') && ! $this->option('feature')) {
            $this->output->error('You should choose one of the test types and set as option.');

            return;
        }

        if ($this->option('unit')) {
            $this->generate(self::TEST_TYPE_FEATURE, $class);
        }

        if ($this->option('feature')) {
            $this->generate(self::TEST_TYPE_FEATURE, $class);
        }
    }

    private function generate(string $type, string $class): void
    {
        $formator = $this->getFormator($type);
        $generator = new Generator($this->files, $formator, $class);

        $testPath = $generator->generate();

        $this->output->success(
            "The {$type} test was generated for {$class}. Path: {$testPath}"
        );
    }

    private function getFormator(string $type = self::TEST_TYPE_UNIT): FormatorInterface
    {
        if (! in_array($type, self::TEST_TYPES)) {
            throw new WrongTestTypeException($type);
        }

        $className = config("test-generator.formators.{$type}");

        if (!class_exists($className)) {
            throw new FormatorClassNotFoundException($className);
        }

        $formatorInstance = app($className);

        if (! $formatorInstance instanceof FormatorInterface) {
            throw new InvalidFormatorClassException($formatorInstance);
        }

        return $formatorInstance;
    }
}
