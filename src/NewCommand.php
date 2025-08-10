<?php

namespace PHPSaaS\PHPSaaS;

use Illuminate\Filesystem\Filesystem;
use PHPSaaS\PHPSaaS\Traits\Backend;
use PHPSaaS\PHPSaaS\Traits\Billing;
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

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class NewCommand extends Command
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

    protected array $testStacks = [
        'phpunit',
        'pest',
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
            ->addOption('backend', null, InputArgument::OPTIONAL, 'The backend stack to use', 'laravel')
            ->addOption('frontend', null, InputArgument::OPTIONAL, 'The frontend stack to use', '')
            ->addOption('billing', null, InputArgument::OPTIONAL, 'The billing stack to use', '')
            ->addOption('test', null, InputArgument::OPTIONAL, 'The testing framework to use', '')
            ->addOption('projects', null, InputArgument::OPTIONAL, 'The projects stack to use', '')
            ->addOption('tokens', null, InputArgument::OPTIONAL, 'Include API tokens', '')
            ->addOption('npm', null, InputArgument::OPTIONAL, 'Run npm install after setup', '');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->path = getcwd().'/'.$input->getArgument('name');

        $output->write('<fg=bright-magenta>
  ██████╗ ██╗  ██╗██████╗       ███████╗ █████╗  █████╗ ███████╗
  ██╔══██╗██║  ██║██╔══██╗      ██╔════╝██╔══██╗██╔══██╗██╔════╝
  ██████╔╝███████║██████╔╝█████╗███████╗███████║███████║███████╗
  ██╔═══╝ ██╔══██║██╔═══╝ ╚════╝╚════██║██╔══██║██╔══██║╚════██║
  ██║     ██║  ██║██║           ███████║██║  ██║██║  ██║███████║
  ╚═╝     ╚═╝  ╚═╝╚═╝           ╚══════╝╚═╝  ╚═╝╚═╝  ╚═╝╚══════╝
        </>'.PHP_EOL);

        $this->collectInputs($input);

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

    protected function collectInputs(InputInterface $input): void
    {
        $this->backend = $input->getOption('backend') ?? 'laravel';

        $this->frontend = $input->getOption('frontend');
        if (! $this->frontend) {
            $this->frontend = select('Which frontend stack would you like to use?', [
                'react' => 'React',
                'vue' => 'Vue',
            ], hint: 'The frontend stacks are integrated with Inertia.js');
        }

        $this->test = $input->getOption('test');
        if (! $this->test) {
            $this->test = select('Which testing framework would you like to use?', [
                'phpunit' => 'PHPUnit',
                'pest' => 'Pest',
            ]);
        }

        $this->projects = $input->getOption('projects');
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

        $this->billing = $input->getOption('billing');
        if (! $this->billing) {
            $this->billing = select('Which billing stack would you like to use?', [
                'paddle' => 'Cashier Paddle',
                'stripe' => 'Cashier Stripe (coming soon)',
                'none' => 'None',
            ], default: 'paddle');
        }

        $this->test = $input->getOption('test');
        if (! in_array($this->test, $this->testStacks)) {
            $this->tokens = select('Do you want to include API tokens?', [
                'yes' => 'Yes',
                'no' => 'No',
            ], default: 'yes');
        }

        $this->npm = $input->getOption('npm');
        if (! $this->npm) {
            $this->npm = select('Do you want to run npm install?', [
                'yes' => 'Yes',
                'no' => 'No',
            ], default: 'yes');
        }
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
