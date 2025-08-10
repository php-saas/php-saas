<?php

namespace PHPSaaS\PHPSaaS\Traits;

trait Tests
{
    protected function setupTests(): void
    {
        $this->fileSystem->moveDirectory($this->path.'/tests-'.$this->test, $this->path.'/tests');

        if ($this->test === 'pest') {
            $this->fileSystem->deleteDirectory($this->path.'/tests-phpunit');
            $this->runCommands([
                composer_binary().' remove phpunit/phpunit --no-update --no-scripts --no-interaction',
            ], $this->path);
        }

        if ($this->test === 'phpunit') {
            $this->fileSystem->deleteDirectory($this->path.'/tests-pest');
            $this->runCommands([
                composer_binary().' remove pestphp/pest --no-update --no-scripts --no-interaction',
            ], $this->path);
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
