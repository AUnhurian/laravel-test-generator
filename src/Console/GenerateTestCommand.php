<?php

namespace AUnhurian\LaravelTestGenerator\Console;

use AUnhurian\LaravelTestGenerator\Enums\FormatorTypes;
use AUnhurian\LaravelTestGenerator\Exceptions\ClassNotExistException;
use AUnhurian\LaravelTestGenerator\Formators\FormatorFactory;
use AUnhurian\LaravelTestGenerator\Generator;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateTestCommand extends Command
{
    protected $signature = 'test:generate {class} {--feature} {--unit} {--override}';

    protected $description = 'This command for generate test';

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
            $this->generate(FormatorTypes::UNIT, $class);
        }

        if ($this->option('feature')) {
            $this->generate(FormatorTypes::FEATURE, $class);
        }
    }

    private function generate(FormatorTypes $type, string $class): void
    {
        $formatorFactory = new FormatorFactory();
        $formator =  $formatorFactory->createFormator($type);

        $generator = new Generator($this->files, $formator, $class, $this->option('override'));

        $testPath = $generator->generate();

        $this->output->success(
            "The {$type->value} test was generated for {$class}. Path: {$testPath}"
        );
    }
}
