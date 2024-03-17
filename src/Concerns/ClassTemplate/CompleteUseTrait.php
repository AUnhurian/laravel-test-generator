<?php

namespace AUnhurian\LaravelTestGenerator\Concerns\ClassTemplate;

trait CompleteUseTrait
{
    protected function addUse(string $use, string $as = null): void
    {
        $usage = $use;
        if ($as) {
            $usage .= ' as ' . $as;
        }

        if (in_array($usage, $this->usingClasses)) {
            return;
        }

        $this->usingClasses[] = $usage;
    }

    private function generateUseClasses(): string
    {
        if (empty($this->usingClasses)) {
            return '';
        }

        $completeUsing = '';
        foreach ($this->usingClasses as $usingClass) {
            $completeUsing .= PHP_EOL . sprintf('use %s;', $usingClass);
        }

        return $completeUsing;
    }

    public function setUseClassesInTemplate(): void
    {
        $usingClasses = $this->generateUseClasses();

        $this->template = str_replace('{{ use_class }}', $usingClasses, $this->template);
    }
}
