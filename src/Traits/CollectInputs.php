<?php

namespace PHPSaaS\PHPSaaS\Traits;

use Symfony\Component\Console\Helper\Helper;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

trait CollectInputs
{
    protected function collectInputs(): void
    {
        $isWindows = windows_os();
        $helper = $isWindows ? $this->getHelper('question') : null;

        $this->backend = $this->input->getOption('backend') ?? 'laravel';

        $this->frontend = $this->getOptionOrPrompt(
            'frontend',
            'Which frontend stack would you like to use?',
            ['react' => 'React', 'vue' => 'Vue'],
            'react',
            $isWindows,
            $helper,
            hint: 'The frontend stacks are integrated with Inertia.js'
        );

        $this->test = $this->getOptionOrPrompt(
            'test',
            'Which testing framework would you like to use?',
            ['phpunit' => 'PHPUnit', 'pest' => 'Pest'],
            'phpunit',
            $isWindows,
            $helper
        );

        $this->projects = $this->getOptionOrPrompt(
            'projects',
            'Which projects stack would you like to use?',
            [
                'projects' => 'Projects',
                'organizations' => 'Organizations',
                'teams' => 'Teams',
                'custom' => 'I name it myself!',
                'none' => 'None',
            ],
            'projects',
            $isWindows,
            $helper
        );
        if ($this->projects === 'custom') {
            $this->projects = $isWindows
                ? $this->askTextWindows('What do you want to call it? (One word, lowercase and plural like folks, friends, ...): ', $helper)
                : text('What do you want to call it? (One word, lowercase and plural like folks, friends, ...)');
        }

        $this->billing = $this->getOptionOrPrompt(
            'billing',
            'Which billing stack would you like to use?',
            [
                'paddle' => 'Cashier Paddle',
                'stripe' => 'Cashier Stripe (coming soon)',
                'none' => 'None',
            ],
            'paddle',
            $isWindows,
            $helper
        );

        $this->tokens = $this->getOptionOrPrompt(
            'tokens',
            'Do you want to include API tokens?',
            ['yes' => 'Yes', 'no' => 'No'],
            'yes',
            $isWindows,
            $helper
        );

        $this->npm = $this->getOptionOrPrompt(
            'npm',
            'Do you want to run npm install?',
            ['yes' => 'Yes', 'no' => 'No'],
            'yes',
            $isWindows,
            $helper
        );
    }

    private function getOptionOrPrompt(
        string  $option,
        string  $question,
        array   $choices,
        string  $default,
        bool    $isWindows,
        ?Helper $helper,
        string  $hint = null
    )
    {
        $value = $this->input->getOption($option);
        if ($value) {
            return $value;
        }

        if ($isWindows) {
            $choiceQuestion = new ChoiceQuestion($question, $choices, $default);
            $choiceQuestion->setErrorMessage("$option %s is invalid.");
            return $helper->ask($this->input, $this->output, $choiceQuestion);
        }

        return select($question, $choices, default: $default, hint: $hint);
    }

    private
    function askTextWindows(string $question, $helper)
    {
        $textQuestion = new Question($question);
        return $helper->ask($this->input, $this->output, $textQuestion);
    }
}