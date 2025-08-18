<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\ProcessUtils;
use Symfony\Component\Process\PhpExecutableFinder;

function delete_files(string $path, array $files): void
{
    foreach ($files as $file) {
        @unlink($path.'/'.$file);
    }
}

function delete_directories(string $path, array $directories): void
{
    $fileSystem = new Filesystem;
    foreach ($directories as $directory) {
        $fileSystem->deleteDirectory($path.'/'.$directory);
    }
}

function composer_binary(): string
{
    $composer = new Composer(new Filesystem);

    return implode(' ', $composer->findComposer());
}

function copy_directory(string $src, string $dst): void
{
    try {
        $dir = opendir($src);

        @mkdir($dst);

        foreach (scandir($src) as $file) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src.'/'.$file)) {
                    copy_directory($src.'/'.$file, $dst.'/'.$file);
                } else {
                    copy($src.'/'.$file, $dst.'/'.$file);
                }
            }
        }

        closedir($dir);
    } catch (Throwable) {
        //
    }
}

function php_binary(): string
{
    $phpBinary = function_exists('Illuminate\Support\php_binary')
        ? \Illuminate\Support\php_binary()
        : (new PhpExecutableFinder)->find(false);

    return $phpBinary !== false
        ? ProcessUtils::escapeArgument($phpBinary)
        : 'php';
}

function insert_after_match(string $filePath, string $lookup, string $insertion): bool
{
    $contents = file_get_contents($filePath);

    // If already contains the line, do nothing
    if (str_contains($contents, $insertion)) {
        return false;
    }

    // Find position of the lookup string
    $pos = strpos($contents, $lookup);
    if ($pos === false) {
        return false;
    }

    // Find end of line after lookup
    $newlinePos = strpos($contents, "\n", $pos);
    if ($newlinePos === false) {
        return false;
    }

    // Inject the insertion string after that line
    $insertionWithNewline = $insertion."\n";
    $contents = substr_replace($contents, $insertionWithNewline, $newlinePos + 1, 0);

    file_put_contents($filePath, $contents);

    return true;
}

function get_files_in_path(string $path, array $excludeFiles = []): array
{
    $files = [];
    $iterator = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->isFile()
            && ! str_contains($file->getPathname(), 'vendor')
            && ! str_contains($file->getPathname(), 'node_modules')
            && ! in_array($file->getFilename(), $excludeFiles, true)) {

            $files[] = str_replace($path.'/', '', $file->getPathname());
        }
    }

    return $files;
}

function get_directories_in_path(string $path): array
{
    $dirs = [];
    $iterator = new \RecursiveIteratorIterator(
        new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
        \RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($iterator as $file) {
        if ($file->isDir()
            && ! str_contains($file->getPathname(), 'vendor')
            && ! str_contains($file->getPathname(), 'node_modules')) {
            $dirs[] = str_replace($path.'/', '', $file->getPathname());
        }
    }

    return $dirs;
}

function rename_files_with_name(string $basePath, array $relativePaths, string $oldName, string $newName): void
{
    foreach ($relativePaths as $relativePath) {
        $fullPath = rtrim($basePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$relativePath;

        if (is_file($fullPath) && strpos(basename($fullPath), $oldName) !== false) {
            $newRelativePath = dirname($relativePath).DIRECTORY_SEPARATOR.
                               str_replace($oldName, $newName, basename($relativePath));
            $newFullPath = rtrim($basePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$newRelativePath;

            if (! rename($fullPath, $newFullPath)) {
                throw new RuntimeException("Failed to rename file {$fullPath} to {$newFullPath}");
            }
        }
    }
}

function rename_directories_with_name(string $basePath, array $relativePaths, string $oldName, string $newName): void
{
    // Sort by depth (deepest first) to avoid renaming parent dirs before children
    usort($relativePaths, function ($a, $b) {
        return substr_count($b, DIRECTORY_SEPARATOR) <=> substr_count($a, DIRECTORY_SEPARATOR);
    });

    foreach ($relativePaths as $relativePath) {
        $fullPath = rtrim($basePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$relativePath;

        if (is_dir($fullPath) && strpos(basename($fullPath), $oldName) !== false) {
            $newRelativePath = dirname($relativePath).DIRECTORY_SEPARATOR.
                               str_replace($oldName, $newName, basename($relativePath));
            $newFullPath = rtrim($basePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$newRelativePath;

            if (! rename($fullPath, $newFullPath)) {
                throw new RuntimeException("Failed to rename directory {$fullPath} to {$newFullPath}");
            }
        }
    }
}

function replace_in_file_contents(string $basePath, array $relativePaths, string $oldName, string $newName): void
{
    foreach ($relativePaths as $relativePath) {
        $fullPath = rtrim($basePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$relativePath;

        if (! is_file($fullPath)) {
            // Skip if it’s not a regular file
            continue;
        }

        $contents = file_get_contents($fullPath);

        if ($contents === false) {
            throw new RuntimeException("Failed to read file: {$fullPath}");
        }

        // Only replace if the old name is found (case-sensitive)
        if (strpos($contents, $oldName) !== false) {
            $newContents = str_replace($oldName, $newName, $contents);

            if (file_put_contents($fullPath, $newContents) === false) {
                throw new RuntimeException("Failed to write file: {$fullPath}");
            }
        }
    }
}

function formatted_options(array $options): string
{
    $green = "\033[32m";
    $reset = "\033[0m";
    $formatted = [];
    foreach ($options as $key => $value) {
        if (is_array($value)) {
            $formatted[] = sprintf(
                '%s%s%s',
                $green, implode(', ', $value), $reset
            );
        } else {
            $formatted[] = sprintf(
                '%s%s%s',
                $green, $value, $reset
            );
        }
    }

    return implode(', ', $formatted);
}
