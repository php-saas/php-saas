<?php

namespace PHPSaaS\PHPSaaS\Traits;

trait Backend
{
    protected function setupBackend(): void
    {
        $info = [];
        if ($this->fileSystem->isDirectory($this->path)) {
            $info = $this->fileSystem->get($this->path.'/info.json');
            $info = json_decode($info, true);
            if (! isset($info['backend']) || $info['backend'] !== $this->backend) {
                $this->fileSystem->deleteDirectory($this->path);
            }
        }

        copy_directory(SCRIPT_ROOT.'/stacks/'.$this->backend, $this->path);
        $info['backend'] = $this->backend;

        $this->fileSystem->copy($this->path.'/.env.example', $this->path.'/.env');

        $this->fileSystem->put($this->path.'/info.json', json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    }

    protected function boot(): void
    {
        $this->fileSystem->delete($this->path.'/database/database.sqlite');
        $this->runCommands([
            composer_binary().' install',
            php_binary().' artisan migrate --force',
            php_binary().' artisan migrate:refresh --seed --force',
            php_binary().' artisan key:generate',
            './vendor/bin/pint --parallel',
        ], $this->path);
    }
}
