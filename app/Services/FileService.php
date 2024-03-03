<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileService
{
    protected $allowedPaths;

    public function __construct(array $allowedPaths = null)
    {
        $this->allowedPaths = $allowedPaths ?? Config::get('filemanager.allowed_paths', [
            base_path('app'),
            base_path('resources'),
            base_path('config'),
        ]);
    }

    public function readFile(string $path)
    {
        $this->validatePath($path);

        if (!File::exists($path)) {
            Log::error("File does not exist: {$path}");
            throw new FileException("The file does not exist.");
        }

        return File::get($path);
    }

    public function writeFile(string $path, string $content)
    {
        $this->validatePath($path);

        if (!File::put($path, $content)) {
            Log::error("Unable to write to file: {$path}");
            throw new FileException("Unable to write to file.");
        }

        return true;
    }

    protected function validatePath(string &$path)
    {
        $realPath = realpath($path);
        if (!$realPath) {
            throw new \InvalidArgumentException("Invalid path provided.");
        }

        $isAllowed = array_reduce($this->allowedPaths, function ($carry, $allowedPath) use ($realPath) {
            return $carry || strpos($realPath, $allowedPath) === 0;
        }, false);

        if (!$isAllowed) {
            Log::warning("Attempt to access a path not allowed: {$path}");
            throw new \InvalidArgumentException("Access to this path is not allowed.");
        }

        $path = $realPath;
    }
}
