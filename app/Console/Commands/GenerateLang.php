<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Stichoza\GoogleTranslate\GoogleTranslate;

class GenerateLang extends Command
{
    protected $signature = 'superduper:lang-translate {from} {to*} {--file=} {--json}';
    protected $description = 'Translate language files from one language to another using Google Translate';

    public function handle()
    {
        $from = $this->argument('from');
        $targets = $this->argument('to');
        $specificFile = $this->option('file');
        $onlyJson = $this->option('json');
        $sourcePath = "lang/{$from}";

        if (!$onlyJson && !File::isDirectory($sourcePath)) {
            $this->error("The source language directory does not exist: {$sourcePath}");
            return;
        }

        if ($onlyJson) {
            $sourcePath = "lang/{$from}.json";
            if (!File::isFile($sourcePath)) {
                $this->error("The source language json file does not exist: {$sourcePath}");
                return;
            }
        }

        if ($onlyJson) {
            $this->processJsonFile($sourcePath, $from, $targets);
        } else {
            $this->processDirectory($sourcePath, $from, $targets, $specificFile);
        }

        $this->info("\n\n All files have been translated. \n");
    }

    protected function processJsonFile(string $sourceFile, string $from, array|string $targets): void
    {
        foreach ($targets as $to) {
            $this->info("\n\n 🔔 Translate to '{$to}'");

            $translations = json_decode(File::get($sourceFile), true, 512, JSON_THROW_ON_ERROR);

            $bar = $this->output->createProgressBar(count($translations));
            $bar->setFormat(" %current%/%max% [%bar%] %percent:3s%% -- %message%");
            $bar->setMessage('Initializing...');
            $bar->start();

            $bar->setMessage("🔄 Processing: {$sourceFile}");
            $bar->display();

            $translated = $this->translateArray($translations, $from, $to, $bar);

            $targetPath = "lang/{$to}.json";

            $outputContent = json_encode($translated, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            File::put($targetPath, $outputContent);

            $bar->setMessage("✅");
        }

        $bar->finish();
    }

    protected function processDirectory(string $sourcePath, string $from, array|string $targets, bool|array|string|null $specificFile): void
    {
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
            $this->info("\n\n 🔔 Translate to '{$to}'");

            $bar = $this->output->createProgressBar(count($filesToProcess));
            $bar->setFormat(" %current%/%max% [%bar%] %percent:3s%% -- %message%");
            $bar->setMessage('Initializing...');
            $bar->start();

            foreach ($filesToProcess as $fileInfo) {
                $filePath = $fileInfo['relativePathname'];

                $bar->setMessage("🔄 Processing: {$filePath}");
                $bar->display();

                $translations = include $fileInfo['path'];
                $translated = $this->translateArray($translations, $from, $to);

                $targetPath = "lang/{$to}/" . dirname($filePath);
                if (!File::isDirectory($targetPath)) {
                    File::makeDirectory($targetPath, 0755, true, true);
                }

                $outputFile = "{$targetPath}/" . basename($filePath);
                $outputContent = "<?php\n\nreturn " . $this->arrayToString($translated) . ";\n";
                File::put($outputFile, $outputContent);

                $bar->advance();

                $bar->setMessage("✅");
            }

            $bar->finish();
        }
    }

    protected function translateArray($content, $source, $target, $bar = null)
    {
        if (is_array($content)) {
            foreach ($content as $key => $value) {
                $content[$key] = $this->translateArray($value, $source, $target);
                $bar?->advance();
            }
            return $content;
        } else if ($content === '' || $content === null) {
            $this->error("Translation value missing, make sure all translation values are not empty, in the source file!");
            exit();
        } else {
            return $this->translateUsingGoogleTranslate($content, $source, $target);
        }
    }

    public function translateUsingGoogleTranslate($content, string $source, string $target)
    {
        try {
            // Use Stichoza\GoogleTranslate\GoogleTranslate for translation
            $tr = new GoogleTranslate();
            $tr->setSource($source);
            $tr->setTarget($target);
            return $tr->translate($content);
        } catch (\Exception $e) {
            $this->error("Failed to translate text: " . $e->getMessage());
            return $content; // Return original text if translation fails
        }
    }

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
                $entryValue = is_string($value) ? "'" . addcslashes($value, "'") . "'" : $value;
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
