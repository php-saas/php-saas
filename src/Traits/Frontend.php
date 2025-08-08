<?php

namespace PHPSaaS\PHPSaaS\Traits;

trait Frontend
{
    protected function setupFrontend(): void
    {
        $info = $this->fileSystem->get($this->path.'/info.json');
        $info = json_decode($info, true);

        if (! isset($info['frontend']) || $info['frontend'] !== $this->frontend) {
            $this->fileSystem->deleteDirectory($this->path.'/resources/js');
            $this->fileSystem->deleteDirectory($this->path.'/node_modules');
            $this->fileSystem->delete($this->path.'/components.json');
            $this->fileSystem->delete($this->path.'/vite.config.ts');
            $this->fileSystem->delete($this->path.'/eslint.config.js');
            $this->fileSystem->delete($this->path.'/package.json');
            $this->fileSystem->delete($this->path.'/tsconfig.json');
            $info['npm_build'] = false;
        }

        copy_directory(SCRIPT_ROOT.'/stacks/'.$this->frontend.'/src', $this->path.'/resources/js');
        $this->fileSystem->copy('stacks/'.$this->frontend.'/components.json', $this->path.'/components.json');
        $this->fileSystem->copy('stacks/'.$this->frontend.'/vite.config.ts', $this->path.'/vite.config.ts');
        $this->fileSystem->copy('stacks/'.$this->frontend.'/eslint.config.js', $this->path.'/eslint.config.js');
        $this->fileSystem->copy('stacks/'.$this->frontend.'/package.json', $this->path.'/package.json');
        $this->fileSystem->copy('stacks/'.$this->frontend.'/tsconfig.json', $this->path.'/tsconfig.json');

        $block = '@vite([\'resources/js/app.ts\', "resources/js/pages/{$page[\'component\']}.%s"])';
        $this->replaceBlocks(
            $this->path,
            'vite',
            sprintf($block, $this->frontend === 'react' ? 'tsx' : 'vue'),
        );

        if ($this->npm === 'yes') {
            $this->runCommands([
                'npm install',
            ], $this->path);
            $info['npm_install'] = true;

            if (! isset($info['npm_build'])) {
                $this->runCommands([
                    'npm run build',
                ], $this->path);
                $info['npm_build'] = true;
            }
        }

        $info['frontend'] = $this->frontend;
        $this->fileSystem->put($this->path.'/info.json', json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
