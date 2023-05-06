<?php

namespace LaraDumps\GlobalLaraDumps\Traits;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

trait PHPIni
{
    protected function findPhpIniPath(InputInterface $input, OutputInterface $output): mixed
    {
        $iniPaths = array_filter($this->getLoadedIniFiles(), fn ($file) => str_contains($file, 'php.ini'));

        if (count($iniPaths) === 1) {
            return reset($iniPaths);
        }

        $question = new ChoiceQuestion('   Several PHP ini files were found. Which would you like to update?', $iniPaths);

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        return $helper->ask($input, $output, $question);
    }

    protected function updateIniAutoPretendFile(string $iniFilePath, string $autoPrependFile = null): bool
    {
        $iniContents = strval(file_get_contents($iniFilePath));

        $iniContents = preg_replace('/^auto_prepend_file\s*=.*$/m', "auto_prepend_file = $autoPrependFile", $iniContents);

        return file_put_contents($iniFilePath, $iniContents) !== false;
    }

    private function getLoadedIniFiles(): array
    {
        $paths = [];

        $phpIniLoadedFile = php_ini_loaded_file();

        if ($phpIniLoadedFile !== false) {
            $paths[] = $phpIniLoadedFile;
        }

        $cfgFilePath = get_cfg_var('cfg_file_path');

        if ($cfgFilePath !== false) {
            $paths[] = $cfgFilePath;
        }

        $scannedFiles = php_ini_scanned_files();

        if (!empty($scannedFiles)) {
            $paths = array_merge($paths, explode(',', $scannedFiles));
        }

        return array_unique(array_filter($paths));
    }
}
