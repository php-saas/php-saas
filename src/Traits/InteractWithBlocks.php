<?php

namespace PHPSaaS\PHPSaaS\Traits;

trait InteractWithBlocks
{
    protected function removeBlocks(string $path, string $block): int
    {
        $removed = 0;

        $files = get_files_in_path($path);

        foreach ($files as $file) {
            $filePath = $this->path.'/'.$file;

            if (! file_exists($filePath)) {
                continue;
            }

            $content = $this->fileSystem->get($filePath);

            $patterns = [
                // PHP comments
                "/\/\/ <php-saas:{$block}>.*?\/\/ <\/php-saas:{$block}>\n?/s",
                // JSX/TSX comments
                "/\{\s*\/\*<php-saas:{$block}>\*\/\s*\}.*?\{\s*\/\*<\/php-saas:{$block}>\*\/\s*\}\n?/s",
                // HTML comments
                "/<!--<php-saas:{$block}>-->.*?<!--<\/php-saas:{$block}>-->\n?/s",
                // .env comments
                "/#<php-saas:{$block}>.*?#<\/php-saas:{$block}>\n?/s",
            ];

            $newContent = $content;

            foreach ($patterns as $pattern) {
                $newContent = preg_replace($pattern, '', $newContent, -1, $count);
                $removed += $count;
            }

            if ($newContent !== $content) {
                $this->fileSystem->put($filePath, $newContent);
            }
        }

        return $removed;
    }

    protected function removeBlockTags(string $path, string $block): int
    {
        $tagsRemoved = 0;

        $files = get_files_in_path($path);

        foreach ($files as $file) {
            $filePath = $this->path.'/'.$file;

            if (! file_exists($filePath)) {
                continue;
            }

            $content = $this->fileSystem->get($filePath);

            $patterns = [
                // PHP comments
                "/^\s*\/\/ <\/?php-saas:{$block}>\s*[\r\n]?/m",
                // JSX/TSX comments
                "/^\s*\{\s*\/\*<\\/?php-saas:{$block}>\*\/\s*\}\s*[\r\n]?/m",
                // HTML comments
                "/^\s*<!--<\\/?php-saas:{$block}>-->\s*[\r\n]?/m",
                // .env comments
                "/^\s*#<\/?php-saas:{$block}>\s*[\r\n]?/m",
            ];

            $newContent = $content;

            foreach ($patterns as $pattern) {
                $newContent = preg_replace($pattern, '', $newContent, -1, $count);
                $tagsRemoved += $count;
            }

            if ($newContent !== $content) {
                $this->fileSystem->put($filePath, $newContent);
            }
        }

        return $tagsRemoved;
    }

    protected function replaceBlocks(string $path, string $block, string $replacement): void
    {
        $files = get_files_in_path($path);

        foreach ($files as $file) {
            $filePath = $this->path.'/'.$file;

            if (! file_exists($filePath)) {
                continue;
            }

            $content = $this->fileSystem->get($filePath);

            $patterns = [
                // PHP comments
                "/\/\/ <php-saas:{$block}>.*?\/\/ <\/php-saas:{$block}>\n?/s",
                // JSX/TSX comments
                "/\{\s*\/\*<php-saas:{$block}>\*\/\s*\}.*?\{\s*\/\*<\/php-saas:{$block}>\*\/\s*\}\n?/s",
                // HTML comments
                "/<!--<php-saas:{$block}>-->.*?<!--<\/php-saas:{$block}>-->\n?/s",
                // .env comments
                "/#<php-saas:{$block}>.*?#<\/php-saas:{$block}>\n?/s",
            ];

            $newContent = preg_replace($patterns, $replacement, $content);

            if ($newContent !== $content) {
                $this->fileSystem->put($filePath, $newContent);
            }
        }
    }
}
