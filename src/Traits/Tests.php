<?php

namespace PHPSaaS\PHPSaaS\Traits;

trait Tests
{
    protected function setupTests(): void
    {
        $this->fileSystem->deleteDirectory($this->path.'/tests');
        $this->fileSystem->moveDirectory($this->path.'/tests-'.$this->test, $this->path.'/tests');
        foreach ($this->testStacks as $testStack) {
            if ($testStack !== $this->test) {
                $this->fileSystem->deleteDirectory($this->path.'/tests-'.$testStack);
            }
        }

        if ($this->billing === 'paddle') {
            $this->fileSystem->deleteDirectory($this->path.'/tests/Feature/BillingStripe');
            $this->fileSystem->moveDirectory($this->path.'/tests/Feature/BillingPaddle', $this->path.'/tests/Feature/Billing');
        }

        if ($this->billing === 'stripe') {
            $this->fileSystem->deleteDirectory($this->path.'/tests/Feature/BillingPaddle');
            $this->fileSystem->moveDirectory($this->path.'/tests/Feature/BillingStripe', $this->path.'/tests/Feature/Billing');
        }

        if ($this->billing === 'none') {
            $this->fileSystem->deleteDirectory($this->path.'/tests/Feature/BillingPaddle');
            $this->fileSystem->deleteDirectory($this->path.'/tests/Feature/BillingStripe');
        }
    }
}
