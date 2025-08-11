<?php

namespace PHPSaaS\PHPSaaS;

use Illuminate\Filesystem\Filesystem;
use PHPSaaS\PHPSaaS\Traits\Backend;
use PHPSaaS\PHPSaaS\Traits\Billing;
use PHPSaaS\PHPSaaS\Traits\CollectInputs;
use PHPSaaS\PHPSaaS\Traits\Frontend;
use PHPSaaS\PHPSaaS\Traits\InteractWithBlocks;
use PHPSaaS\PHPSaaS\Traits\Projects;
use PHPSaaS\PHPSaaS\Traits\RunCommands;
use PHPSaaS\PHPSaaS\Traits\Tests;
use PHPSaaS\PHPSaaS\Traits\Tokens;
use Spatie\Watcher\Watch;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DevCommand extends Command
{
    use Backend;
    use Billing;
    use CollectInputs;
    use Frontend;
    use InteractWithBlocks;
    use Projects;
    use RunCommands;
    use Tests;
    use Tokens;

    protected OutputInterface $output;

    protected InputInterface $input;

    protected Filesystem $fileSystem;

    protected array $backendStacks = [
        'laravel',
    ];

    protected array $frontendStacks = [
        'react',
        'vue',
    ];

    protected array $billingStacks = [
        'paddle',
        'stripe',
        'none',
    ];

    protected array $testStacks = [
        'phpunit',
        'pest',
    ];

    protected array $projectsName = [
        'projects',
        'teams',
        'organizations',
        'custom',
        'none',
    ];

    protected array $yesNoOptions = [
        'yes',
        'no',
    ];

    protected string $backend = 'laravel';

    protected string $frontend = '';

    protected string $billing = '';

    protected string $test = '';

    protected string $projects = '';

    protected string $tokens = 'yes';

    protected string $npm = 'no';

    protected string $path = 'dist';

    protected function configure(): void
    {
        $this
            ->setName('dev')
            ->setDescription('PHPSaaS Development')
            ->addOption('backend', null, InputArgument::OPTIONAL, 'The backend stack to use. Options (' . formatted_options($this->backendStacks) . ')', 'laravel')
            ->addOption('frontend', null, InputArgument::OPTIONAL, 'The frontend stack to use. Options (' . formatted_options($this->frontendStacks) . ')', '', suggestedValues: $this->frontendStacks)
            ->addOption('billing', null, InputArgument::OPTIONAL, 'The billing stack to use. Options (' . formatted_options($this->billingStacks) . ')', '', suggestedValues: $this->billingStacks)
            ->addOption('test', null, InputArgument::OPTIONAL, 'The testing framework to use. Options (' . formatted_options($this->testStacks) . ')', '', suggestedValues: $this->testStacks)
            ->addOption('projects', null, InputArgument::OPTIONAL, 'The projects stack to use. Options (' . formatted_options($this->projectsName) . ')', '', suggestedValues: $this->projectsName)
            ->addOption('tokens', null, InputArgument::OPTIONAL, 'Include API tokens. Options (' . formatted_options($this->yesNoOptions) . ')', 'yes', suggestedValues: $this->yesNoOptions)
            ->addOption('npm', null, InputArgument::OPTIONAL, 'Run npm install after setup. Options (' . formatted_options($this->yesNoOptions) . ')', 'no', suggestedValues: $this->yesNoOptions);
    }

    /**
     * @throws FileNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;
        $this->fileSystem = new Filesystem;

        $this->collectInputs();

        $this->setup();

        $this->watch();

        return 0;
    }

    protected function setup(): void
    {
        $this->fileSystem->deleteDirectory($this->path);
        $this->setupBackend();
        $this->setupFrontend();
        $this->setupBilling();
        $this->setupTests();
        $this->setupProjects();
        $this->setupTokens();
        $this->boot();
    }

    protected function watch(): void
    {
        $this->output->writeln('<info>Watching for changes in stacks...</info>');

        Watch::path(PHP_SAAS_SCRIPT_ROOT.'/stacks')
            ->onAnyChange(function (string $type, string $path) {
                if ($type === 'fileUpdated' || $type === 'fileCreated') {
                    if (str($path)->contains('stacks/'.$this->backend)) {
                        $this->fileSystem->copy($path, $this->path.'/'.str($path)->after('stacks/'.$this->backend.'/'));
                    }

                    if (str($path)->contains('stacks/'.$this->frontend)) {
                        $this->fileSystem->copy($path, $this->path.'/resources/js/'.str($path)->after('stacks/'.$this->frontend.'/src'));
                    }
                }

                if ($type === 'fileDeleted') {
                    if (str($path)->contains('stacks/'.$this->backend)) {
                        $this->fileSystem->delete($this->path.'/'.str($path)->after('stacks/'.$this->backend.'/'));
                    }

                    if (str($path)->contains('stacks/'.$this->frontend)) {
                        $this->fileSystem->delete($this->path.'/resources/js/'.str($path)->after('stacks/'.$this->frontend.'/src'));
                    }
                }

                if ($type === 'directoryCreated') {
                    if (str($path)->contains('stacks/'.$this->backend)) {
                        copy_directory($path, $this->path.'/'.str($path)->after('stacks/'.$this->backend.'/'));
                    }

                    if (str($path)->contains('stacks/'.$this->frontend)) {
                        copy_directory($path, $this->path.'/resources/js/'.str($path)->after('stacks/'.$this->frontend.'/src'));
                    }
                }

                if ($type === 'directoryDeleted') {
                    if (str($path)->contains('stacks/'.$this->backend)) {
                        $this->fileSystem->deleteDirectory($this->path.'/'.str($path)->after('stacks/'.$this->backend.'/'));
                    }

                    if (str($path)->contains('stacks/'.$this->frontend)) {
                        $this->fileSystem->deleteDirectory($this->path.'/resources/js/'.str($path)->after('stacks/'.$this->frontend.'/src'));
                    }
                }

                echo $type.' '.$path.PHP_EOL;
            })
            ->start();
    }
}
