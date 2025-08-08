<?php

namespace PHPSaaS\PHPSaaS\Traits;

use Illuminate\Contracts\Filesystem\FileNotFoundException;

trait Tokens
{
    protected array $tokensFilesToDelete = [
        'app/Http/Controllers/TokenController.php',
        'app/Http/Resources/TokenResource.php',
        'tests/Feature/TokenTest.php',
        'database/migrations/2025_07_10_214649_create_personal_access_tokens_table.php',
        'app/Models/PersonalAccessToken.php',
        'app/Policies/PersonalAccessTokenPolicy.php',
        'resources/js/types/token.d.ts',
    ];

    protected array $tokensDirectoriesToDelete = [
        'resources/js/pages/tokens',
    ];

    /**
     * @throws FileNotFoundException
     */
    protected function setupTokens(): void
    {
        if ($this->tokens === 'yes') {
            return;
        }

        foreach ($this->tokensFilesToDelete as $file) {
            $this->fileSystem->delete($this->path.'/'.$file);
        }
        foreach ($this->tokensDirectoriesToDelete as $directory) {
            if (! $this->fileSystem->isDirectory($directory)) {
                continue;
            }
            $this->fileSystem->deleteDirectory($this->path.'/'.$directory);
        }
        $this->removeBlocks($this->path, 'tokens');

        // manual removals
        $userModel = $this->fileSystem->get($this->path.'/app/Models/User.php');
        $userModel = str_replace('@property Collection<int, PersonalAccessToken> $tokens', '', $userModel);
        $this->fileSystem->put($this->path.'/app/Models/User.php', $userModel);

        $this->runCommands([
            'composer remove laravel/sanctum --no-update',
        ], $this->path);
    }
}
