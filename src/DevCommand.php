<?php

namespace PHPSaaS\PHPSaaS;

use Illuminate\Filesystem\Filesystem;
use InvalidArgumentException;
use PHPSaaS\PHPSaaS\Traits\Backend;
use PHPSaaS\PHPSaaS\Traits\Billing;
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

    protected string $backend = '';

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
            ->addArgument('backend', InputArgument::REQUIRED)
            ->addArgument('frontend', InputArgument::REQUIRED)
            ->addArgument('billing', InputArgument::REQUIRED)
            ->addArgument('test', InputArgument::REQUIRED)
            ->addArgument('projects', InputArgument::OPTIONAL, 'Projects stack (projects, teams, organizations, none)', 'projects')
            ->addArgument('tokens', InputArgument::OPTIONAL, 'Enable tokens (yes, no)', 'yes');
    }

    /**
     * @throws FileNotFoundException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->validateInputs($input);

        $this->fileSystem = new Filesystem;

        $this->setup();

        $this->watch();

        return 0;
    }

    protected function setup(): void
    {
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

    protected function validateInputs(InputInterface $input): void
    {
        $this->backend = $input->getArgument('backend');
        $this->frontend = $input->getArgument('frontend');
        $this->test = $input->getArgument('test');
        $this->billing = $input->getArgument('billing');
        $this->projects = $input->getArgument('projects');
        $this->tokens = $input->getArgument('tokens') ?? 'yes';

        if (! in_array($this->backend, $this->backendStacks)) {
            throw new InvalidArgumentException("Invalid backend stack: {$this->backend}");
        }

        if (! in_array($this->frontend, $this->frontendStacks)) {
            throw new InvalidArgumentException("Invalid frontend stack: {$this->frontend}");
        }

        if (! in_array($this->billing, $this->billingStacks)) {
            throw new InvalidArgumentException("Invalid billing stack: {$this->billing}");
        }

        if (! in_array($this->test, $this->testStacks)) {
            throw new InvalidArgumentException("Invalid test stack: {$this->test}");
        }

        if (! in_array($this->projects, $this->projectsName)) {
            throw new InvalidArgumentException("Invalid projects stack: {$this->projects}");
        }
    }
}
