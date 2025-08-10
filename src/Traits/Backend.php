<?php

namespace PHPSaaS\PHPSaaS\Traits;

trait Backend
{
    protected function setupBackend(): void
    {
        if ($this->fileSystem->isDirectory($this->path)) {
            delete_files($this->path, get_files_in_path($this->path));
            delete_directories($this->path, get_directories_in_path($this->path));
        }
        copy_directory(PHP_SAAS_SCRIPT_ROOT.'/stacks/'.$this->backend, $this->path);
        $this->fileSystem->copy($this->path.'/.env.example', $this->path.'/.env');
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
