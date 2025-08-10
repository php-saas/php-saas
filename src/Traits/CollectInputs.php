<?php

namespace PHPSaaS\PHPSaaS\Traits;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

trait CollectInputs
{
    protected function collectInputs(): void
    {
        $this->backend = $this->input->getOption('backend') ?? 'laravel';

        $this->frontend = $this->input->getOption('frontend');
        if (! $this->frontend) {
            $this->frontend = select('Which frontend stack would you like to use?', [
                'react' => 'React',
                'vue' => 'Vue',
            ], hint: 'The frontend stacks are integrated with Inertia.js');
        }

        $this->test = $this->input->getOption('test');
        if (! $this->test) {
            $this->test = select('Which testing framework would you like to use?', [
                'phpunit' => 'PHPUnit',
                'pest' => 'Pest',
            ]);
        }

        $this->projects = $this->input->getOption('projects');
        if (! $this->projects) {
            $this->projects = select('Which projects stack would you like to use?', [
                'projects' => 'Projects',
                'organizations' => 'Organizations',
                'teams' => 'Teams',
                'custom' => 'I name it myself!',
                'none' => 'None',
            ], default: 'projects');
            if ($this->projects === 'custom') {
                $this->projects = text('What do you want to call it? (One word, lowercase and plural like folks, friends, ...)');
            }
        }

        $this->billing = $this->input->getOption('billing');
        if (! $this->billing) {
            $this->billing = select('Which billing stack would you like to use?', [
                'paddle' => 'Cashier Paddle',
                'stripe' => 'Cashier Stripe (coming soon)',
                'none' => 'None',
            ], default: 'paddle');
        }

        $this->test = $this->input->getOption('test');
        if (! in_array($this->test, $this->testStacks)) {
            $this->tokens = select('Do you want to include API tokens?', [
                'yes' => 'Yes',
                'no' => 'No',
            ], default: 'yes');
        }

        $this->npm = $this->input->getOption('npm');
        if (! $this->npm) {
            $this->npm = select('Do you want to run npm install?', [
                'yes' => 'Yes',
                'no' => 'No',
            ], default: 'yes');
        }
    }
}
