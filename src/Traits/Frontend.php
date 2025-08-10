<?php

namespace PHPSaaS\PHPSaaS\Traits;

trait Frontend
{
    protected function setupFrontend(): void
    {
        $this->fileSystem->deleteDirectory($this->path.'/resources/js');
        $this->fileSystem->deleteDirectory($this->path.'/node_modules');
        $this->fileSystem->delete($this->path.'/components.json');
        $this->fileSystem->delete($this->path.'/vite.config.ts');
        $this->fileSystem->delete($this->path.'/eslint.config.js');
        $this->fileSystem->delete($this->path.'/package.json');
        $this->fileSystem->delete($this->path.'/tsconfig.json');

        copy_directory(PHP_SAAS_SCRIPT_ROOT.'/stacks/'.$this->frontend.'/src', $this->path.'/resources/js');
        $this->fileSystem->copy(PHP_SAAS_SCRIPT_ROOT.'/stacks/'.$this->frontend.'/components.json', $this->path.'/components.json');
        $this->fileSystem->copy(PHP_SAAS_SCRIPT_ROOT.'/stacks/'.$this->frontend.'/vite.config.ts', $this->path.'/vite.config.ts');
        $this->fileSystem->copy(PHP_SAAS_SCRIPT_ROOT.'/stacks/'.$this->frontend.'/eslint.config.js', $this->path.'/eslint.config.js');
        $this->fileSystem->copy(PHP_SAAS_SCRIPT_ROOT.'/stacks/'.$this->frontend.'/package.json', $this->path.'/package.json');
        $this->fileSystem->copy(PHP_SAAS_SCRIPT_ROOT.'/stacks/'.$this->frontend.'/tsconfig.json', $this->path.'/tsconfig.json');

        $block = '@vite([\'resources/js/app.ts\', "resources/js/pages/{$page[\'component\']}.%s"])';
        $this->replaceBlocks(
            $this->path,
            'vite',
            sprintf($block, $this->frontend === 'react' ? 'tsx' : 'vue'),
        );

        if ($this->npm === 'yes') {
            $this->runCommands([
                'npm install',
                'npm run build',
            ], $this->path);
        }
    }
}
