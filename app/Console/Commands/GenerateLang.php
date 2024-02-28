<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class GenerateLang extends Command
{
    protected $signature = 'lang:translate {from} {to*} {--file=}';
    protected $description = 'Translate language files from one language to another using Google Translate';

    public function handle()
    {
        $from = $this->argument('from');
        $targets = $this->argument('to');
        $specificFile = $this->option('file');
        $sourcePath = "lang/{$from}";

        if (!File::isDirectory($sourcePath)) {
            $this->error("The source language directory does not exist: {$sourcePath}");
            return;
        }

        $filesToProcess = [];
        if ($specificFile) {
            $filePath = $sourcePath . '/' . $specificFile;
            if (!File::exists($filePath)) {
                $this->error("The specified file does not exist: {$filePath}");
                return;
            }
            $filesToProcess[] = ['path' => $filePath, 'relativePathname' => $specificFile];
        } else {
            foreach (File::allFiles($sourcePath) as $file) {
                $filesToProcess[] = ['path' => $file->getPathname(), 'relativePathname' => $file->getRelativePathname()];
            }
        }

        foreach ($targets as $to) {
            $this->info("Starting translation to '{$to}'...");
            $bar = $this->output->createProgressBar(count($filesToProcess));
            $bar->setFormat(" %current%/%max% [%bar%] %percent:3s%% -- %message%");
            $bar->setMessage('Initializing...');
            $bar->start();

            foreach ($filesToProcess as $fileInfo) {
                $filePath = $fileInfo['relativePathname'];
                $bar->setMessage("Processing {$to} :: {$filePath}");

                $translations = include $fileInfo['path'];
                $translated = $this->translateArray($translations, $from, $to);

                $targetPath = "lang/{$to}/" . dirname($filePath);
                if (!File::isDirectory($targetPath)) {
                    File::makeDirectory($targetPath, 0755, true, true);
                }

                $outputFile = "{$targetPath}/" . basename($filePath);
                $outputContent = "<?php\n\nreturn " . $this->arrayToString($translated) . ";\n";
                File::put($outputFile, $outputContent);

                $bar->setMessage("Completed {$to} :: {$filePath}");
                $bar->advance();
            }

            $bar->finish();
            $this->info("\n\n All files have been translated from {$from}.");
        }
    }

    protected function translateArray($content, $source, $target)
    {
        if (is_array($content)) {
            foreach ($content as $key => $value) {
                $content[$key] = $this->translateArray($value, $source, $target);
            }
            return $content;
        } else {
            return $this->translateUsingGoogleTranslate($content, $source, $target);
        }
    }

    public function translateUsingGoogleTranslate($content, string $source, string $target)
    {
        if (is_array($content)) {
            $translatedArray = [];
            foreach ($content as $key => $value) {
                $translatedArray[$key] = $this->translateUsingGoogleTranslate($value, $source, $target);
            }
            return $translatedArray;
        } else {
            $response = Http::retry(3)
                ->throw()
                ->get('https://translate.googleapis.com/translate_a/single?client=gtx&sl=' . $source . '&tl=' . $target . '&dt=t&q=' . urlencode($content));
            $response = json_decode($response->body());
            $translatedText = '';
            foreach ($response[0] as $translation) {
                $translatedText .= $translation[0];
            }
            return !empty($translatedText) ? $translatedText : $content;
        }
    }

    /**
     * Convert an array to a string representation using short array syntax.
     *
     * @param array $array The array to convert.
     * @param int $indentLevel The current indentation level (for formatting).
     * @return string The array as a string.
     */
    protected function arrayToString(array $array, $indentLevel = 1)
    {
        $indent = str_repeat('    ', $indentLevel); // 4 spaces for indentation
        $entries = [];

        foreach ($array as $key => $value) {
            $entryKey = is_string($key) ? "'$key'" : $key;
            if (is_array($value)) {
                $entryValue = $this->arrayToString($value, $indentLevel + 1);
                $entries[] = "$indent$entryKey => $entryValue";
            } else {
                $entryValue = is_string($value) ? "'$value'" : $value;
                $entries[] = "$indent$entryKey => $entryValue";
            }
        }

        $glue = ",\n";
        $body = implode($glue, $entries);
        if ($indentLevel > 1) {
            return "[\n$body,\n" . str_repeat('    ', $indentLevel - 1) . ']';
        } else {
            return "[\n$body\n$indent]";
        }
    }
}
