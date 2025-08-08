<?php

namespace PHPSaaS\PHPSaaS\Traits;

use RuntimeException;
use Symfony\Component\Process\Process;

trait RunCommands
{
    protected function runCommands($commands, ?string $workingPath = null, array $env = []): Process
    {
        $process = Process::fromShellCommandline(implode(' && ', $commands), $workingPath, $env, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> '.$e->getMessage().PHP_EOL);
            }
        }

        $process->run(function ($type, $line) {
            $this->output->write('    '.$line);
        });

        return $process;
    }
}
