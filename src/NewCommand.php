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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NewCommand extends Command
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

    protected string $npm = 'yes';

    protected string $path = '';

    protected function configure(): void
    {
        $this
            ->setName('new')
            ->setDescription('Create a new Laravel application')
            ->addArgument('name', InputArgument::REQUIRED)
            ->addOption('backend', null, InputArgument::OPTIONAL, 'The backend stack to use. Options (' . formatted_options($this->backendStacks) . ')', 'laravel')
            ->addOption('frontend', null, InputArgument::OPTIONAL, 'The frontend stack to use. Options (' . formatted_options($this->frontendStacks) . ')', '', suggestedValues: $this->frontendStacks)
            ->addOption('billing', null, InputArgument::OPTIONAL, 'The billing stack to use. Options (' . formatted_options($this->billingStacks) . ')', '', suggestedValues: $this->billingStacks)
            ->addOption('test', null, InputArgument::OPTIONAL, 'The testing framework to use. Options (' . formatted_options($this->testStacks) . ')', '', suggestedValues: $this->testStacks)
            ->addOption('projects', null, InputArgument::OPTIONAL, 'The projects stack to use. Options (' . formatted_options($this->projectsName) . ')', '', suggestedValues: $this->projectsName)
            ->addOption('tokens', null, InputArgument::OPTIONAL, 'Include API tokens. Options (' . formatted_options($this->yesNoOptions) . ')', 'yes', suggestedValues: $this->yesNoOptions)
            ->addOption('npm', null, InputArgument::OPTIONAL, 'Run npm install after setup. Options (' . formatted_options($this->yesNoOptions) . ')', 'yes', suggestedValues: $this->yesNoOptions);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        $this->path = getcwd().'/'.$input->getArgument('name');

        $output->write('<fg=bright-magenta>
  ██████╗ ██╗  ██╗██████╗       ███████╗ █████╗  █████╗ ███████╗
  ██╔══██╗██║  ██║██╔══██╗      ██╔════╝██╔══██╗██╔══██╗██╔════╝
  ██████╔╝███████║██████╔╝█████╗███████╗███████║███████║███████╗
  ██╔═══╝ ██╔══██║██╔═══╝ ╚════╝╚════██║██╔══██║██╔══██║╚════██║
  ██║     ██║  ██║██║           ███████║██║  ██║██║  ██║███████║
  ╚═╝     ╚═╝  ╚═╝╚═╝           ╚══════╝╚═╝  ╚═╝╚═╝  ╚═╝╚══════╝
        </>'.PHP_EOL);

        $this->collectInputs();

        $this->fileSystem = new Filesystem;

        if ($this->fileSystem->isDirectory($this->path)) {
            $output->writeln('<error>Directory already exists: '.$this->path.'</error>');

            return 1;
        }

        $this->setup();

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
        $this->cleanup();
        $this->boot();
    }

    protected function cleanup(): void
    {
        $this->removeBlockTags($this->path, 'billing');
        $this->removeBlockTags($this->path, 'projects');
        if ($this->projects !== 'none') {
            $this->removeBlockTags($this->path, $this->projects);
        }
        $this->removeBlockTags($this->path, 'tokens');
        $this->removeBlockTags($this->path, 'vite');
        $this->fileSystem->delete($this->path.'/info.json');
    }
}
